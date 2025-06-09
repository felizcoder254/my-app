{{-- resources/views/partials/chatbot.blade.php --}}

@php
  $route = request()->route()->getName();
  if ($route === 'dashboard') {
    $room = 'paper_overview';
  } elseif ($route === 'doc.view') {
    $room = request()->route('section') ?? request()->route('file');
  } else {
    $room = 'global';
  }
  $myId = auth()->id();
@endphp

<style>
  :root {
    --nck-blue: #2563eb;
    --nck-green: #10b981;
    --nck-bg: #f3f8fc;
    --nck-glass: rgba(243, 248, 252, 0.92);
    --nck-panel: #fff;
    --nck-border: #e0e7ef;
    --nck-txt: #1c263c;
    --nck-muted: #6b7280;
    --nck-radius: 21px;
    --nck-shadow: 0 12px 32px rgba(56, 189, 248, 0.09), 0 2px 6px #bae6fd19;
    --nck-font: 'Inter', 'Segoe UI', Arial, sans-serif;
    --nck-glass-blur: blur(14px);
    --bubble-shadow: 0 6px 18px #60a5fa12, 0 1px 6px #bae6fd11;
  }
  #peerChat {
    position: fixed;
    bottom: 110px;
    right: 92px;
    width: 350px;
    max-width: 97vw;
    max-height: 540px;
    background: var(--nck-glass);
    border: 2px solid var(--nck-border);
    border-radius: var(--nck-radius);
    box-shadow: var(--nck-shadow);
    display: flex;
    flex-direction: column;
    font-family: var(--nck-font);
    color: var(--nck-txt);
    z-index: 9999;
    backdrop-filter: var(--nck-glass-blur);
    overflow: hidden;
  }
  #peerChat.closed { display: none !important; }
  @keyframes peerFadeIn { 0% { opacity: 0; transform: translateY(16px); } 100% { opacity: 1; transform: translateY(0); } }
  #peerChat header {
    background: linear-gradient(93deg, var(--nck-blue) 68%, var(--nck-green) 100%);
    color: #fff;
    padding: 1em 1.2em;
    font-size: 1.1em;
    font-weight: 800;
    display: flex;
    align-items: center;
    justify-content: space-between;
    border-top-left-radius: var(--nck-radius);
    border-top-right-radius: var(--nck-radius);
    box-shadow: 0 2px 10px rgba(56, 189, 248, 0.06);
    animation: peerFadeIn .3s;
  }
  .header-actions button {
    background: none;
    border: none;
    color: #fff;
    cursor: pointer;
    font-size: 1.2em;
    opacity: .9;
  }
  #peerChat .messages {
    flex: 1;
    overflow-y: auto;
    padding: 1em;
    background: var(--nck-bg);
    display: flex;
    flex-direction: column;
    gap: .5em;
  }
  .date-divider {
    text-align: center;
    font-size: .8em;
    color: var(--nck-muted);
    margin: 1em 0;
    position: relative;
  }
  .date-divider::before,
  .date-divider::after {
    content: '';
    position: absolute;
    top: 50%;
    width: 25%;
    height: 1px;
    background: var(--nck-border);
  }
  .date-divider::before { left: 0; }
  .date-divider::after { right: 0; }
  .bubble {
    position: relative;
    max-width: 75%;
    padding: .75em 1em;
    border-radius: 1.2em 1.8em 1.7em 1.2em;
    box-shadow: var(--bubble-shadow);
    animation: bubblePop .13s;
    word-break: break-word;
  }
  .bubble.you {
    align-self: flex-end;
    background: linear-gradient(121deg, var(--nck-blue) 90%, var(--nck-green) 108%);
    color: #fff;
    font-weight: 600;
  }
  .bubble.peer {
    align-self: flex-start;
    background: #fff;
    color: var(--nck-text-dark);
  }
  @keyframes bubblePop { 0% { transform: scale(.94); opacity: .4; } 100% { transform: scale(1); opacity: 1; } }
  .bubble + .bubble { margin-top: .5em; }
  .bubble .meta {
    display: block;
    font-size: .75em;
    color: var(--nck-muted);
    margin-top: .25em;
    text-align: right;
  }
  #peerChat .input-wrap {
    display: flex;
    padding: .75em;
    background: var(--nck-bg);
    border-top: 1px solid var(--nck-border);
  }
  #peerChat input {
    flex: 1;
    padding: .5em .75em;
    border: 1px solid var(--nck-border);
    border-radius: .75em;
    font-size: 1em;
    outline: none;
  }
  #peerChat button.sendBtn {
    margin-left: .5em;
    padding: .5em .75em;
    background: linear-gradient(95deg, var(--nck-green) 68%, var(--nck-blue) 100%);
    color: #fff;
    border: none;
    border-radius: .75em;
    font-weight: 700;
    cursor: pointer;
  }
  #chatFab {
    position: fixed;
    bottom: 32px;
    right: 92px;
    background: linear-gradient(90deg, var(--nck-blue) 65%, var(--nck-green) 100%);
    color: #fff;
    border: none;
    border-radius: 999px;
    padding: .5em 1em;
    font-size: 1.2em;
    cursor: pointer;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    z-index: 10000;
  }
  @media (max-width: 600px) {
    #peerChat { width: 95vw; height: 60vh; right: 2.5vw; bottom: 80px; }
    #chatFab { right: 2.5vw; }
  }
</style>

<div id="peerChat" class="closed">
  <header>
    <span>🤝 Peer Chat ({{ $room }})</span>
    <span class="header-actions">
      <button class="broomBtn" id="clearPeerChat" title="Clear chat">
        <span class="material-icons">cleaning_services</span>
      </button>
      <button class="closeBtn" id="closePeerChat" title="Close chat">&times;</button>
    </span>
  </header>

  <div class="messages" id="msgs">
    <div class="date-divider">Today</div>
  </div>

  <div class="input-wrap">
    <input id="chatIn" placeholder="Type a message…" autocomplete="off" />
    <button class="sendBtn" id="chatSend">Send</button>
  </div>
</div>

<button id="chatFab">
  <span class="material-icons">chat_bubble</span>
</button>

<script>
  const ROOM     = "{{ $room }}",
        fetchU   = `/chat/${ROOM}/messages`,
        sendU    = `/chat/${ROOM}/message`,
        clearU   = `/chat/${ROOM}/clear`,
        msgsEl   = document.getElementById('msgs'),
        inEl     = document.getElementById('chatIn'),
        sendBtn  = document.getElementById('chatSend'),
        myId     = {{ (int)($myId ?? 0) }},
        peerChat = document.getElementById('peerChat'),
        fab      = document.getElementById('chatFab'),
        closeBtn = document.getElementById('closePeerChat'),
        broomBtn = document.getElementById('clearPeerChat');

  // Load and render messages without resetting scroll if user scrolled up
  async function loadMsgs() {
    try {
      const atBottom = msgsEl.scrollHeight - msgsEl.scrollTop <= msgsEl.clientHeight + 10;
      const res = await fetch(fetchU);
      const data = await res.json();
      const today = new Date().toLocaleDateString([], { month: 'short', day: 'numeric' });
      let html = `<div class=\"date-divider\">${today}</div>`;
      data.forEach(m => {
        const you = m.user?.id === myId;
        const time = m.created_at ? new Date(m.created_at)
          .toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }) : '';
        html += `
          <div class=\"bubble ${you ? 'you' : 'peer'}\">
            <strong>${m.user?.name || 'User'}</strong>
            <div>${m.message}</div>
            <span class=\"meta\">${time}</span>
          </div>`;
      });
      msgsEl.innerHTML = html;
      if (atBottom) {
        msgsEl.scrollTop = msgsEl.scrollHeight;
      }
    } catch (e) {
      console.error('Chat load error', e);
    }
  }

  // Optimistic send
  async function sendMessage() {
    const msg = inEl.value.trim(); if (!msg) return;
    const time = new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
    msgsEl.innerHTML += `
      <div class=\"bubble you\">
        <strong>You</strong>
        <div>${msg}</div>
        <span class=\"meta\">${time}</span>
      </div>`;
    msgsEl.scrollTop = msgsEl.scrollHeight;
    inEl.value = '';
    try {
      await fetch(sendU, { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }, body: JSON.stringify({ message: msg }) });
    } catch (e) {
      console.error('Chat send error', e);
    }
  }

  inEl.addEventListener('keydown', e => { if (e.key === 'Enter') sendMessage(); });
  sendBtn.addEventListener('click', sendMessage);

  setInterval(loadMsgs, 1000);
  document.addEventListener('DOMContentLoaded', loadMsgs);

  closeBtn.addEventListener('click', () => { peerChat.classList.add('closed'); fab.style.display = 'flex'; });
  fab.addEventListener('click', () => { peerChat.classList.remove('closed'); fab.style.display = 'none'; setTimeout(() => inEl.focus(), 100); });
  broomBtn.addEventListener('click', async () => { if (!confirm('Clear all chat messages?')) return; await fetch(clearU, { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' } }); loadMsgs(); });
</script>

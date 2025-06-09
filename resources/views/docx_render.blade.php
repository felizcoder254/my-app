<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>📘 {{ $filename }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
        }
        body {
            background: #0f172a;
            color: #f1f5f9;
            font-family: 'Segoe UI', sans-serif;
            padding: 2rem;
            min-height: 100vh;
            box-sizing: border-box;
            transition: background 0.2s, color 0.2s;
        }
        h1 {
            color: #facc15;
            margin-bottom: 2rem;
            font-size: 2rem;
            word-break: break-word;
        }
        .back {
            color: #38bdf8;
            text-decoration: none;
            margin-bottom: 2rem;
            display: inline-block;
            font-size: 1.12rem;
        }
        .toolbar {
            display: flex;
            flex-wrap: wrap;
            gap: 0.7rem;
            align-items: center;
            margin-bottom: 1.3rem;
        }
        .speak-btn, .pause-btn {
            background: #14b8a6;
            color: white;
            border: none;
            border-radius: 8px;
            padding: 0.55rem 1.2rem;
            font-weight: bold;
            font-size: 1rem;
            cursor: pointer;
            transition: background 0.14s, transform 0.1s;
        }
        .pause-btn {
            background: #6366f1;
        }
        .speak-btn:active, .pause-btn:active {
            transform: scale(0.95);
        }
        select {
            padding: 0.42rem 0.8rem;
            border-radius: 4px;
            border: 1px solid #374151;
            font-size: 1rem;
            background: #18181b;
            color: #f1f5f9;
            min-width: 120px;
            flex: 1 1 120px;
        }
        .question-block {
            background: #1e293b;
            border-left: 5px solid #14b8a6;
            padding: 1.1rem 1.1rem 1.1rem 1.25rem;
            margin-bottom: 2rem;
            border-radius: 13px;
            box-shadow: 0 2px 8px rgba(16, 24, 39, 0.11);
            word-break: break-word;
        }
        .question-header {
            background: #2563eb;
            color: white;
            padding: 0.85rem 1.1rem;
            border-radius: 7px 7px 0 0;
            font-weight: bold;
            font-size: 1.08rem;
            margin: -1.1rem -1.1rem 1rem -1.25rem;
            display: flex;
            flex-wrap: wrap;
            gap: 0.45rem;
            align-items: center;
        }
        .question-body {
            padding-top: 0.18rem;
        }
        .option {
            margin-bottom: 0.53rem;
            padding-left: 1.28rem;
            position: relative;
            font-size: 1.02rem;
        }
        .option::before {
            content: attr(data-label);
            position: absolute;
            left: 0;
            top: 0;
            font-weight: bold;
            color: #facc15;
        }
        .correct {
            color: #22c55e;
            font-weight: bold;
        }
        .toggle-expl, .section-toggle {
            margin-top: 1rem;
            background: #0ea5e9;
            color: white;
            border: none;
            border-radius: 6px;
            padding: 0.46rem 1rem;
            font-weight: bold;
            cursor: pointer;
            box-shadow: 0 2px 8px rgba(14, 165, 233, 0.09);
            margin-bottom: 0.2rem;
        }
        .explanation-wrapper {
            margin-top: 1rem;
        }
        .explanation {
            margin-top: 0.8rem;
            padding: 0.8rem 1.1rem;
            background: #1e3a8a;
            color: #e0f2fe;
            border-radius: 7px;
            display: none;
            font-size: 0.98rem;
        }
        /* Tablet break */
        @media (max-width: 850px) {
            body { padding: 1rem; }
            h1 { font-size: 1.2rem; margin-bottom: 1.15rem; }
            .toolbar { gap: 0.45rem; }
            .speak-btn, .pause-btn { font-size: 0.97rem; padding: 0.46rem 0.7rem;}
            .question-block { padding: 0.9rem 0.6rem 0.85rem 0.95rem; }
            .question-header { margin: -0.9rem -0.6rem 0.7rem -0.95rem; font-size: 1rem; padding: 0.7rem 0.7rem;}
            .option { font-size: 0.97rem; }
        }
        /* Mobile phone */
        @media (max-width: 600px) {
            body { padding: 0.3rem; }
            h1 { font-size: 1.01rem; margin-bottom: 0.85rem;}
            .back { font-size: 0.94rem; margin-bottom: 0.8rem;}
            .toolbar {
                flex-direction: column;
                align-items: stretch;
                gap: 0.5rem;
            }
            .speak-btn, .pause-btn { width: 100%; font-size: 1.01rem; padding: 0.63rem 0.35rem;}
            select { margin: 0; width: 100%; margin-top: 0.25rem;}
            .question-block { padding: 0.7rem 0.1rem 0.7rem 0.47rem; margin-bottom: 0.97rem;}
            .question-header { margin: -0.7rem -0.1rem 0.53rem -0.47rem; font-size: 0.95rem; padding: 0.53rem 0.3rem;}
            .option { font-size: 0.95rem; padding-left: 1.05rem;}
            .explanation { font-size: 0.92rem; }
        }
        /* XS devices */
        @media (max-width: 400px) {
            body { padding: 0.13rem; }
            .toolbar { gap: 0.3rem;}
            h1 { font-size: 0.93rem;}
            .question-block { padding: 0.41rem 0.02rem 0.55rem 0.19rem; }
            .question-header { margin: -0.41rem -0.02rem 0.32rem -0.19rem; font-size: 0.87rem;}
        }
    </style>
</head>
<body>
    <a href="{{ route('dashboard') }}" class="back">← Back to Dashboard</a>
    <h1><strong style="color: gold;">{{ $filename }}</strong></h1>
    <div class="toolbar">
        <button class="speak-btn" onclick="readAllQuestions()">🔊 Read All</button>
        <button id="pauseBtn" class="pause-btn" onclick="toggleSpeech()" style="display:none;">⏸ Pause</button>
        <select id="voiceSelect">
            <option value="">Auto Voice</option>
        </select>
    </div>
    <div id="docx-container">
        {!! str_replace(['&nbsp;', 'PHPWord'], ['', ''], $html) !!}
    </div>

<script>
let selectedVoice = null;
let isPaused = false;

function extractVisibleText(node) {
    if (!node || node.nodeType === 8) return '';
    if (node.nodeType === 3) return node.textContent.trim();
    if (node.nodeType === 1) {
        if (['BUTTON', 'SVG', 'IMG', 'SELECT'].includes(node.tagName)) return '';
        if (window.getComputedStyle(node).display === "none") return '';
        let out = '';
        for (let child of node.childNodes) out += extractVisibleText(child) + ' ';
        return out.trim();
    }
    return '';
}

window.speechSynthesis.onvoiceschanged = () => {
    const voices = speechSynthesis.getVoices();
    const voiceSelect = document.getElementById('voiceSelect');
    voiceSelect.innerHTML = `<option value="">Auto Voice</option>`;
    voices.forEach((voice, i) => {
        const option = document.createElement('option');
        option.value = i;
        option.textContent = `${voice.name} (${voice.lang})${voice.default ? ' — Default' : ''}`;
        voiceSelect.appendChild(option);
    });
    voiceSelect.addEventListener('change', () => {
        selectedVoice = voices[voiceSelect.value] || null;
    });
};
window.speechSynthesis.onvoiceschanged();

function speakOptionsSequentially(options, done) {
    let idx = 0;
    function next() {
        if (idx >= options.length) { if (done) done(); return; }
        const opt = options[idx];
        const label = opt.getAttribute('data-label') || String.fromCharCode(65+idx) + ':';
        const txt = opt.innerText.replace(/^[A-Z]:/, '').trim();
        const utter = new SpeechSynthesisUtterance(`Option ${label}. ${txt}`);
        if (selectedVoice) utter.voice = selectedVoice;
        utter.rate = 0.97; utter.pitch = 1;
        utter.onend = () => setTimeout(() => { idx++; next(); }, 260);
        speechSynthesis.speak(utter);
    }
    next();
}

function speakQuiz(button) {
    const block = button.closest('.question-block');
    const allBlocks = Array.from(document.querySelectorAll('.question-block'));
    const idx = allBlocks.indexOf(block);
    let text = `Question ${idx + 1}. `;

    const header = block.querySelector('.question-header');
    if (header) text += extractVisibleText(header) + ' ';

    const qbody = block.querySelector('.question-body');
    if (qbody) {
        for (let child of qbody.childNodes) {
            if (
                child.nodeType === 1 &&
                (
                    child.classList.contains('option') ||
                    child.classList.contains('explanation') ||
                    child.classList.contains('section-toggle')
                )
            ) continue;
            text += extractVisibleText(child) + ' ';
        }
    }
    const utter = new SpeechSynthesisUtterance(text.trim());
    if (selectedVoice) utter.voice = selectedVoice;
    utter.rate = 0.97; utter.pitch = 1;
    utter.onend = () => {
        const options = Array.from(block.querySelectorAll('.option')).filter(
            el => window.getComputedStyle(el).display !== 'none'
        );
        speakOptionsSequentially(options, () => {
            const explanations = block.querySelectorAll('.explanation');
            Array.from(explanations).forEach(expl => {
                if (expl.style.display !== "none") {
                    const expUtter = new SpeechSynthesisUtterance(expl.innerText);
                    if (selectedVoice) expUtter.voice = selectedVoice;
                    expUtter.rate = 0.97; expUtter.pitch = 1;
                    speechSynthesis.speak(expUtter);
                }
            });
        });
    };
    speechSynthesis.cancel();
    speechSynthesis.speak(utter);
    document.getElementById('pauseBtn').style.display = 'inline-block';
}

function readAllQuestions() {
    const blocks = document.querySelectorAll('.question-block');
    let index = 0;
    function readBlock() {
        if (index >= blocks.length) {
            document.getElementById('pauseBtn').style.display = 'none';
            return;
        }
        let block = blocks[index];
        let text = `Question ${index + 1}. `;
        const header = block.querySelector('.question-header');
        if (header) text += extractVisibleText(header) + ' ';
        const qbody = block.querySelector('.question-body');
        if (qbody) {
            for (let child of qbody.childNodes) {
                if (
                    child.nodeType === 1 &&
                    (
                        child.classList.contains('option') ||
                        child.classList.contains('explanation') ||
                        child.classList.contains('section-toggle')
                    )
                ) continue;
                text += extractVisibleText(child) + ' ';
            }
        }
        const utter = new SpeechSynthesisUtterance(text.trim());
        if (selectedVoice) utter.voice = selectedVoice;
        utter.rate = 0.97; utter.pitch = 1;
        utter.onend = () => {
            const options = Array.from(block.querySelectorAll('.option')).filter(
                el => window.getComputedStyle(el).display !== 'none'
            );
            speakOptionsSequentially(options, () => {
                const explanations = block.querySelectorAll('.explanation');
                let expls = Array.from(explanations).filter(
                    el => el.style.display !== "none"
                );
                let ei = 0;
                function speakExpls() {
                    if (ei >= expls.length) { index++; readBlock(); return; }
                    const expUtter = new SpeechSynthesisUtterance(expls[ei].innerText);
                    if (selectedVoice) expUtter.voice = selectedVoice;
                    expUtter.rate = 0.97; expUtter.pitch = 1;
                    expUtter.onend = () => { ei++; speakExpls(); };
                    speechSynthesis.speak(expUtter);
                }
                speakExpls();
            });
        };
        speechSynthesis.cancel();
        speechSynthesis.speak(utter);
        document.getElementById('pauseBtn').style.display = 'inline-block';
    }
    speechSynthesis.cancel();
    readBlock();
}

function toggleSpeech() {
    if (!speechSynthesis.speaking) return;
    if (speechSynthesis.paused) {
        speechSynthesis.resume();
        isPaused = false;
        document.getElementById('pauseBtn').innerText = '⏸ Pause';
    } else {
        speechSynthesis.pause();
        isPaused = true;
        document.getElementById('pauseBtn').innerText = '▶ Resume';
    }
}
</script>
<script>
function toggleExplanation(btn) {
    const section = btn.nextElementSibling;
    section.style.display = (section.style.display === "block") ? "none" : "block";
    btn.innerText = section.style.display === "block" ? "Hide Explanation ↑" : "Show Explanation ↓";
}

function toggleSub(button) {
    const sub = button.nextElementSibling;
    sub.style.display = (sub.style.display === "block") ? "none" : "block";
    button.innerText = sub.style.display === "block"
        ? button.innerText.replace("↓", "↑")
        : button.innerText.replace("↑", "↓");
}
</script>

 @include('partials.chatbot')
</body>
</html>

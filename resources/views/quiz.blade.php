{{-- resources/views/quiz.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Quiz: {{ $filename }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    {{-- Material Icons for the 🔊 icon etc. --}}
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet"/>

    <style>
        /* ─────────── Root & Variables ─────────── */
        :root {
            --bg-dark:             #0f172a;
            --bg-darker:           #0c1320;
            --bg-card:             #1e293b;
            --bg-card-hover:       #16212e;
            --accent-primary:      #14b8a6;  /* Teal */
            --accent-secondary:    #0ea5e9;  /* Blue */
            --accent-highlight:    #facc15;  /* Gold */
            --text-light:          #f1f5f9;
            --text-lighter:        #e2e8f0;
            --text-muted:          #94a3b8;
            --radius-lg:           12px;
            --radius-md:           8px;
            --radius-sm:           4px;
            --shadow-light:        0 4px 14px rgba(0,0,0,0.25);
            --shadow-strong:       0 6px 24px rgba(0,0,0,0.35);
            --transition-short:    0.15s ease-out;
            --transition-medium:   0.3s ease-in-out;
        }
        *, *::before, *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        html, body {
            height: 100%;
            font-family: 'Inter', sans-serif;
            background: var(--bg-dark);
            color: var(--text-light);
        }
        body {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px 16px;
        }

        /* ─────────── Container & Header ─────────── */
        .quiz-container {
            width: 100%;
            max-width: 800px;
            background: var(--bg-darker);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-strong);
            padding: 30px 24px 40px;
            margin-bottom: 40px;
            transition: background var(--transition-medium), box-shadow var(--transition-medium);
        }
        .quiz-container:hover {
            box-shadow: 0 8px 32px rgba(0,0,0,0.45);
        }
        a.back {
            color: var(--accent-highlight);
            text-decoration: none;
            font-size: 1rem;
            display: inline-block;
            margin-bottom: 24px;
            transition: color var(--transition-short);
        }
        a.back:hover {
            color: var(--accent-secondary);
        }
        h1.quiz-title {
            font-size: 1.8rem;
            font-weight: 700;
            text-align: center;
            margin-bottom: 24px;
            color: var(--accent-highlight);
        }

        /* ─────────── TTS Toolbar ─────────── */
        .tts-toolbar {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 12px;
            margin-bottom: 32px;
        }
        .tts-toolbar button,
        .tts-toolbar select {
            font-size: 0.95rem;
            font-weight: 600;
            border: none;
            border-radius: var(--radius-md);
            padding: 10px 16px;
            cursor: pointer;
            transition: background var(--transition-short), transform var(--transition-short);
        }
        .tts-toolbar button {
            background: var(--accent-primary);
            color: #fff;
            box-shadow: var(--shadow-light);
        }
        .tts-toolbar button:hover {
            background: var(--accent-secondary);
            transform: translateY(-2px);
        }
        .tts-toolbar button:active {
            transform: scale(0.97);
        }
        .tts-toolbar .pause-btn {
            background: var(--bg-card);
            color: var(--accent-primary);
            border: 2px solid var(--accent-primary);
        }
        .tts-toolbar .pause-btn:hover {
            background: var(--accent-primary);
            color: #fff;
        }
        .tts-toolbar select {
            background: #18181b;
            color: var(--text-light);
            border: 1px solid #374151;
            min-width: 180px;
        }

        /* ─────────── Question Block ─────────── */
        .question-block {
            background: var(--bg-card);
            border-left: 5px solid var(--accent-primary);
            border-radius: var(--radius-md);
            margin-bottom: 28px;
            padding: 22px 24px 24px 24px;
            box-shadow: var(--shadow-light);
            transition: background var(--transition-medium), box-shadow var(--transition-medium);
        }
        .question-block:hover {
            background: var(--bg-card-hover);
            box-shadow: 0 6px 20px rgba(0,0,0,0.30);
        }
        .question-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 16px;
        }
        .question-header h2 {
            font-size: 1.05rem;
            font-weight: 600;
            color: var(--text-light);
            flex: 1;
            margin-right: 12px;
        }
        .question-header h2::first-letter {
            text-transform: uppercase;
        }
        .speak-button {
            background: none;
            border: none;
            color: var(--accent-primary);
            font-size: 1.4rem;
            cursor: pointer;
            transition: color var(--transition-short), transform var(--transition-short);
        }
        .speak-button:hover {
            color: var(--accent-secondary);
            transform: scale(1.2);
        }

        /* ─────────── Options List ─────────── */
        .option-list {
            list-style: none;
            padding-left: 0;
            margin: 0;
        }
        .option-list li {
            display: flex;
            align-items: center;
            background: #111827;
            border-radius: var(--radius-sm);
            padding: 12px 16px;
            margin-bottom: 12px;
            transition: background var(--transition-short);
            cursor: pointer;
        }
        .option-list li:hover {
            background: #141c2c;
        }
        .option-list li input[type="radio"] {
            margin-right: 14px;
            accent-color: var(--accent-primary);
            width: 20px;
            height: 20px;
        }
        .option-list li label {
            font-size: 1rem;
            color: var(--text-light);
            flex: 1;
            user-select: none;
        }

        /* ─────────── Submit Button ─────────── */
        .submit-container {
            text-align: center;
            margin-top: 36px;
        }
        .submit-container button {
            font-size: 1rem;
            font-weight: 600;
            background: var(--accent-primary);
            color: #fff;
            border: none;
            border-radius: var(--radius-md);
            padding: 12px 32px;
            cursor: pointer;
            box-shadow: var(--shadow-light);
            transition: background var(--transition-short), transform var(--transition-short);
        }
        .submit-container button:hover {
            background: var(--accent-secondary);
            transform: translateY(-2px);
        }
        .submit-container button:active {
            transform: scale(0.97);
        }

        /* ─────────── Responsive Tweaks ─────────── */
        @media (max-width: 768px) {
            .quiz-container {
                padding: 24px 20px 32px;
            }
            .tts-toolbar {
                flex-direction: column;
                align-items: stretch;
            }
            .tts-toolbar button,
            .tts-toolbar select {
                width: 100%;
            }
            .question-header h2 {
                font-size: 1rem;
            }
            .option-list li {
                padding: 10px 14px;
            }
            .submit-container button {
                width: 100%;
                padding: 14px 0;
            }
        }
        @media (max-width: 480px) {
            h1.quiz-title {
                font-size: 1.4rem;
            }
            .tts-toolbar button,
            .tts-toolbar select {
                font-size: 0.9rem;
                padding: 10px 12px;
            }
            .question-header h2 {
                font-size: 0.95rem;
            }
            .speak-button {
                font-size: 1.2rem;
            }
            .option-list li {
                padding: 8px 12px;
                font-size: 0.95rem;
            }
            .submit-container button {
                font-size: 0.95rem;
                padding: 12px 0;
            }
        }
    </style>
</head>

<body>
    {{-- ← Back to Dashboard --}}
    <a href="{{ route('dashboard') }}" class="back">← Back to Dashboard</a>

    <div class="quiz-container">
        <h1 class="quiz-title">Quiz: {{ $filename }}</h1>

        {{-- ─────── TTS Toolbar ─────── --}}
        <div class="tts-toolbar">
            <button id="readAllBtn">🔊 Read All Questions</button>
            <button id="pauseBtn" class="pause-btn" style="display:none;">⏸ Pause</button>
            <select id="voiceSelect">
                <option value="">Auto Voice</option>
            </select>
        </div>

        {{-- ─────── Quiz Form ─────── --}}
        <form method="POST" action="{{ route('quiz') }}">
            @csrf
            <input type="hidden" name="file"     value="{{ $filename }}">
            <input type="hidden" name="category" value="{{ $category }}">
            <input type="hidden" name="section"  value="{{ $section }}">

            @foreach ($questions as $index => $q)
                <div class="question-block" id="qblock-{{ $index }}">
                    <div class="question-header">
                        <h2>Q{{ $index + 1 }}: {{ $q['prompt'] }}</h2>
                        <button
                            type="button"
                            class="speak-button"
                            data-qindex="{{ $index }}"
                            title="Read this question aloud">
                            🔊
                        </button>
                    </div>

                    <ul class="option-list">
                        @foreach ($q['options'] as $letter => $optText)
                            <li>
                                <input
                                    type="radio"
                                    name="answers[{{ $index }}]"
                                    id="q{{ $index }}_{{ $letter }}"
                                    value="{{ $letter }}">
                                <label for="q{{ $index }}_{{ $letter }}">
                                    {{ $letter }}. {{ $optText }}
                                </label>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endforeach

            <div class="submit-container">
                <button type="submit">Submit Answers</button>
            </div>
        </form>
    </div> {{-- .quiz-container --}}

    {{-- ─────── JavaScript: Web Speech API ─────── --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // ─── 1) Cache elements ───
            const readAllBtn  = document.getElementById('readAllBtn');
            const pauseBtn    = document.getElementById('pauseBtn');
            const voiceSelect = document.getElementById('voiceSelect');
            const questionEls = document.querySelectorAll('.question-block');

            let selectedVoice = null;
            let isPaused      = false;

            // ─── 2) Populate voice dropdown ───
            function populateVoices() {
                const voices = window.speechSynthesis.getVoices();
                voiceSelect.innerHTML = '<option value="">Auto Voice</option>';
                voices.forEach((voice, idx) => {
                    const opt = document.createElement('option');
                    opt.value = idx;
                    opt.textContent = `${voice.name} (${voice.lang})${voice.default ? ' — Default' : ''}`;
                    voiceSelect.appendChild(opt);
                });
            }
            // Initial call
            populateVoices();
            // Re-populate when voices list changes
            window.speechSynthesis.onvoiceschanged = populateVoices;

            // When user selects a voice:
            voiceSelect.addEventListener('change', () => {
                const voices = window.speechSynthesis.getVoices();
                selectedVoice = voices[ voiceSelect.value ] || null;
            });

            // ─── 3) Extract text from a question block ───
            function extractTextFromQBlock(qBlock) {
                let text = '';
                // Grab header text
                const headerEl = qBlock.querySelector('h2');
                if (headerEl) {
                    text += headerEl.innerText.trim() + '. ';
                }
                // Grab each option: “A. …” → “Option A. …”
                const options = qBlock.querySelectorAll('.option-list li label');
                options.forEach((lbl) => {
                    const labelText = lbl.innerText.trim();
                    text += labelText.replace(/^([A-D])\.\s*/, 'Option $1. ') + '. ';
                });
                return text.trim();
            }

            // ─── 4) Speak a single question by index ───
            function speakQuestion(index) {
                const qBlock = document.getElementById('qblock-' + index);
                if (!qBlock) return;

                const toSpeak = extractTextFromQBlock(qBlock);
                if (!toSpeak) return;

                window.speechSynthesis.cancel();
                const utt = new SpeechSynthesisUtterance(toSpeak);
                if (selectedVoice) utt.voice = selectedVoice;
                utt.rate  = 0.95;
                utt.pitch = 1.0;
                window.speechSynthesis.speak(utt);

                // Show pause button
                pauseBtn.style.display = 'inline-block';
                pauseBtn.innerText = '⏸ Pause';
                isPaused = false;
            }

            // ─── 5) Read ALL questions in sequence ───
            function readAllQuestions() {
                let idx = 0;
                function readNext() {
                    if (idx >= questionEls.length) {
                        pauseBtn.style.display = 'none';
                        return;
                    }
                    const block = questionEls[idx];
                    const text = extractTextFromQBlock(block);
                    const utt  = new SpeechSynthesisUtterance(text);
                    if (selectedVoice) utt.voice = selectedVoice;
                    utt.rate  = 0.95;
                    utt.pitch = 1.0;
                    utt.onend = () => {
                        setTimeout(() => {
                            idx++;
                            readNext();
                        }, 300); // 300ms pause between questions
                    };
                    window.speechSynthesis.speak(utt);
                    pauseBtn.style.display = 'inline-block';
                    pauseBtn.innerText = '⏸ Pause';
                    isPaused = false;
                }

                window.speechSynthesis.cancel();
                readNext();
            }

            // ─── 6) Toggle Pause/Resume ───
            function togglePauseResume() {
                if (!window.speechSynthesis.speaking) return;
                if (window.speechSynthesis.paused) {
                    window.speechSynthesis.resume();
                    isPaused = false;
                    pauseBtn.innerText = '⏸ Pause';
                } else {
                    window.speechSynthesis.pause();
                    isPaused = true;
                    pauseBtn.innerText = '▶ Resume';
                }
            }

            // ─── 7) Attach event listeners ───
            readAllBtn.addEventListener('click', readAllQuestions);
            pauseBtn.addEventListener('click', togglePauseResume);

            document.querySelectorAll('.question-block .speak-button').forEach(btn => {
                btn.addEventListener('click', () => {
                    const idx = btn.getAttribute('data-qindex');
                    speakQuestion(idx);
                });
            });

            // ─── 8) Cleanup on unload ───
            window.addEventListener('beforeunload', () => {
                window.speechSynthesis.cancel();
            });
        });
    </script>
    @include('partials.chatbot')
</body>
</html>

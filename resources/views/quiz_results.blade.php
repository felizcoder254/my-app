{{-- resources/views/quiz_results.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Quiz Results: {{ $filename }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Google Fonts, FontAwesome & Libraries -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/particles.js@2.0.0/particles.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.5.1/dist/confetti.browser.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

    <style>
        /*──────────────── Variables & Resets ────────────────*/
        :root {
            --bg-dark: #0f172a;
            --bg-light: #1e293b;
            --text-light: #f1f5f9;
            --accent-red: #f43f5e;
            --accent-orange: #f97316;
            --accent-yellow: #facc15;
            --accent-green: #10b981;
            --transition: 0.4s ease;
        }
        [data-theme="light"] {
            --bg-dark: #f3f4f6;
            --bg-light: #ffffff;
            --text-light: #1f2937;
        }
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            background: var(--bg-dark);
            color: var(--text-light);
            font-family: 'Inter', sans-serif;
            overflow-x: hidden;
            position: relative;
            line-height: 1.6;
        }
        /*──────────────── Navigation Bar ────────────────*/
        nav {
            position: sticky; top: 0; width: 100%;
            display: flex; justify-content: space-between; align-items: center;
            padding: 1rem 2rem;
            background: var(--bg-light);
            box-shadow: 0 2px 6px rgba(0,0,0,0.2);
            z-index: 10;
        }
        nav .controls button { background: none; border: none; color: var(--text-light); font-size: 1.25rem; margin-left: 1rem; cursor: pointer; transition: transform var(--transition); }
        nav .controls button:hover { transform: scale(1.2); }
        a.back { color: var(--accent-yellow); text-decoration: none; font-weight: 600; font-size: 1rem; }
        /*──────────────── Background ────────────────*/
        #particles-js, #mountains, #confetti-canvas { position: fixed; top: 0; left: 0; width: 100%; height: 100%; pointer-events: none; }
        #particles-js { z-index: 1; }
        #mountains { bottom: 0; height: 30vh; background: var(--bg-light); clip-path: polygon(0 100%,10% 60%,20% 80%,30% 50%,40% 75%,50% 40%,60% 70%,70% 45%,80% 65%,90% 50%,100% 80%,100% 100%); opacity: 0.25; z-index: 2; animation: mountainShift 60s infinite linear; }
        @keyframes mountainShift { 0%{transform:translateX(0);} 100%{transform:translateX(-50%);} }
        #confetti-canvas { z-index: 20; }
        /*──────────────── Headline ────────────────*/
        h1 { text-align: center; font-size: 2.6rem; font-weight: 700; margin: 3rem 1rem 1.5rem; z-index: 5; position: relative; }
        /*──────────────── Grade Banner ────────────────*/
        .grade-banner {
            max-width: 700px; margin: 1rem auto 2rem; padding: 1.5rem 2rem;
            display: flex; align-items: center; gap: 1rem;
            background: var(--bg-light); border-left: 6px solid; border-radius: 12px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.4);
            opacity: 0; animation: slideDown 0.8s forwards 0.4s;
            z-index: 5; position: relative;
        }
        .grade-banner.poor           { border-color: var(--accent-red);    }
        .grade-banner.below-average  { border-color: var(--accent-orange); }
        .grade-banner.average        { border-color: var(--accent-yellow); }
        .grade-banner.above-average  { border-color: var(--accent-green);  }
        .grade-banner.excellent      { border-color: var(--accent-green);  }
        .grade-banner h2 { font-size: 1.75rem; margin:0; display:flex;align-items:center;gap:0.5rem; }
        .grade-banner p  { margin:0; opacity:0.9; }
        .trophy          { font-size:2rem; color:var(--accent-green); animation:bounce 1s infinite; }
        @keyframes bounce { 0%,100%{transform:translateY(0);}50%{transform:translateY(-8px);} }
        /*──────────────── Gauge Chart ────────────────*/
        .gauge-container { position:relative; width:220px; height:220px; margin:2rem auto; z-index:5; }
        .gauge-svg       { transform:rotate(-90deg); }
        .gauge-bg, .gauge-fill { fill:none; stroke-width:20; }
        .gauge-bg        { stroke:var(--bg-light); }
        .gauge-fill      { stroke:var(--accent-green); stroke-linecap:round; stroke-dasharray:565; stroke-dashoffset:565; transition:stroke-dashoffset 1.8s ease-out; }
        .gauge-text      { position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);font-size:2.4rem;font-weight:700;z-index:5; }
        /*──────────────── Score Summary ────────────────*/
        .score-container { max-width:450px; margin:2rem auto; background:var(--bg-light); padding:1.2rem; text-align:center; border-radius:8px; box-shadow:0 4px 16px rgba(0,0,0,0.3); opacity:0; animation:fadeIn 1s forwards 1s; z-index:5; position:relative; }
        .score-container h2 { font-size:2rem; color:var(--accent-green); margin:0; }
        .score-container p  { margin:0.4rem 0 0; opacity:0.8; }
        /*──────────────── Detailed Feedback ────────────────*/
        .feedback-block           { max-width:800px; margin:1.5rem auto; padding:1.2rem 1.6rem; background:var(--bg-light); border-left:8px solid var(--accent-green); border-radius:8px; box-shadow:0 4px 14px rgba(0,0,0,0.3); opacity:0; transform:translateY(20px); animation:fadeInUp 0.8s forwards; z-index:5; position:relative; }
        .feedback-block.incorrect { border-left-color:var(--accent-red);    }
        .feedback-block.below-average { border-left-color:var(--accent-orange); }
        .feedback-block.average   { border-left-color:var(--accent-yellow); }
        .feedback-block.above-average { border-left-color:var(--accent-green);  }
        .question-prompt { font-weight:600; margin-bottom:0.8rem; }
        .feedback-block ul { list-style:none; padding:0; margin:0; }
        .feedback-block ul li { padding:0.8rem 1rem; margin-bottom:0.6rem; border-radius:6px; transition:background var(--transition); }
        .correct-option { background:#065f46; color:#d1fae5; }
        .user-wrong    { background:#7f1d1d; color:#fee2e2; }
        .neutral       { background:#334155; color:#cbd5e1; }
        /*──────────────── Animations ────────────────*/
        @keyframes slideDown {0%{opacity:0;transform:translateY(-40px);}100%{opacity:1;transform:translateY(0);} }
        @keyframes fadeIn    {0%{opacity:0;}100%{opacity:1;}            }
        @keyframes fadeInUp  {0%{opacity:0;transform:translateY(30px);}100%{opacity:1;transform:translateY(0);} }
    </style>
</head>
<body data-theme="dark">
    <nav>
        <div><a href="{{ route('dashboard') }}" class="back"><i class="fas fa-arrow-left"></i> Dashboard</a></div>
        <div class="controls">
            <button id="retake" title="Retake Quiz"><i class="fas fa-redo"></i></button>
            <button id="download" title="Download Results"><i class="fas fa-file-download"></i></button>
            <button id="share" title="Share on Twitter"><i class="fas fa-share-alt"></i></button>
            <button id="theme-toggle" title="Toggle Theme"><i class="fas fa-sun"></i></button>
        </div>
    </nav>

    <div id="particles-js"></div>
    <div id="mountains"></div>
    <canvas id="confetti-canvas"></canvas>
    <audio id="applause" src="/sounds/applause.mp3" preload="auto"></audio>
    <audio id="ambient" src="/sounds/ambient.mp3" preload="auto" loop></audio>

    <h1>Quiz Results: {{ $filename }}</h1>

    @php
        // Determine grade class and message
        if (isset($scorePercent)) {
            if ($scorePercent < 50) {
                $gradeClass = 'poor';
                $message = 'Needs Improvement';
            } elseif ($scorePercent < 60) {
                $gradeClass = 'below-average';
                $message = 'Below Average';
            } elseif ($scorePercent < 75) {
                $gradeClass = 'average';
                $message = 'Average Performance';
            } elseif ($scorePercent < 90) {
                $gradeClass = 'above-average';
                $message = 'Above Average';
            } else {
                $gradeClass = 'excellent';
                $message = 'Outstanding!';
            }
        } else {
            $gradeClass = 'average';
            $message = 'Average Performance';
        }
    @endphp

    <div class="grade-banner {{ $gradeClass }}">
        @if($gradeClass === 'excellent')<i class="fas fa-trophy trophy"></i>@endif
        <h2>{{ $message }}</h2>
        <p>You scored <strong>{{ $scorePercent }}%</strong>.</p>
    </div>

    <div class="gauge-container">
        <svg class="gauge-svg" viewBox="0 0 200 200">
            <circle class="gauge-bg" cx="100" cy="100" r="90" />
            <circle class="gauge-fill" cx="100" cy="100" r="90" />
        </svg>
        <div class="gauge-text">{{ $scorePercent }}%</div>
    </div>

    <div class="score-container">
        <h2>{{ $correctCount }} / {{ $total }}</h2>
        <p>Overall Correct Answers</p>
    </div>

    @foreach ($feedback as $idx => $fb)
        @php
            $blockClass = $fb['isCorrect'] ? '' : ($fb['isCorrect']===false ? 'incorrect': '');
            $delayMs    = 0.8 + ($idx * 0.1);
        @endphp
        <div class="feedback-block {{ $blockClass }}" style="animation-delay: {{ $delayMs }}s;">
            <div class="question-prompt">Q{{ $idx + 1 }}: {{ $fb['prompt'] }}</div>
            <ul>
                @foreach (['A','B','C','D'] as $opt)
                    @php
                        $text    = $fb['options'][$opt] ?? '';
                        $correct = ($opt === $fb['correctKey']);
                        $chosen  = ($opt === $fb['userKey']);
                        $liClass = $correct ? 'correct-option' : ($chosen ? 'user-wrong' : 'neutral');
                    @endphp
                    <li class="{{ $liClass }}">
                        <strong>{{ $opt }}.</strong> {{ $text }}
                        @if($correct) <em>(Correct)</em>
                        @elseif($chosen) <em>(Your choice)</em>
                        @endif
                    </li>
                @endforeach
            </ul>
        </div>
    @endforeach

    @include('partials.chatbot')

    <script>
        // Theme Toggle
        document.getElementById('theme-toggle').addEventListener('click', () => {
            const b = document.body, icon = document.querySelector('#theme-toggle i');
            if (b.dataset.theme === 'dark') { b.dataset.theme = 'light'; icon.className='fas fa-moon'; }
            else { b.dataset.theme = 'dark'; icon.className='fas fa-sun'; }
        });
        // Retake
        document.getElementById('retake').addEventListener('click', () => location.reload());
        // Download PDF
        document.getElementById('download').addEventListener('click', () => {
            const { jsPDF } = window.jspdf; const doc=new jsPDF();
            doc.setFontSize(16);
            doc.text(`Quiz: {{ $filename }}`, 10,20);
            doc.setFontSize(14);
            doc.text(`Score: {{ $scorePercent }}%`,10,30);
            doc.save(`{{ $filename }}_Results.pdf`);
        });
        // Share to Twitter
        document.getElementById('share').addEventListener('click', () => {
            const url=encodeURIComponent(location.href);
            window.open(`https://twitter.com/intent/tweet?url=${url}&text=${encodeURIComponent(`I scored {{ $scorePercent }}% on {{ $filename }}!`)}`, '_blank');
        });
        // Particles
        particlesJS.load('particles-js','/particlesjs-config.json');
                // Gauge & Celebration
        document.addEventListener('DOMContentLoaded', () => {
            // Animate gauge
            document.querySelector('.gauge-fill').style.strokeDashoffset = 565 * (1 - ({{ $scorePercent }} / 100));

            // Create confetti instance
            const conf = confetti.create(document.getElementById('confetti-canvas'), { resize: true });
            // Celebration based on performance
            if ('{{ $gradeClass }}' === 'excellent') {
                // Grand celebration
                conf({ particleCount: 500, spread: 160, origin: { y: 0.6 } });
                document.getElementById('applause').play();
            } else if ('{{ $gradeClass }}' === 'above-average') {
                // Moderate celebration
                conf({ particleCount: 250, spread: 120, origin: { y: 0.6 } });
            } else if ('{{ $gradeClass }}' === 'average') {
                // Light celebration
                conf({ particleCount: 100, spread: 90, origin: { y: 0.7 } });
            }

            // Ambient Sound
            const amb = document.getElementById('ambient');
            amb.volume = 0.15;
            amb.play();
        });
    </script>
</body>
</html>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $filename }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            background: #0f172a;
            color: #f1f5f9;
            font-family: 'Segoe UI', sans-serif;
            padding: 2rem;
        }
        .question-block {
            background: #1e293b;
            border-radius: 8px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }
        .question-header {
            font-size: 1.1rem;
            font-weight: bold;
            margin-bottom: 1rem;
        }
        .options {
            display: flex;
            flex-direction: column;
        }
        .option {
            background: #334155;
            padding: 0.8rem;
            margin-bottom: 0.5rem;
            border-radius: 6px;
            cursor: pointer;
            transition: background 0.3s;
        }
        .option:hover {
            background: #475569;
        }
        .correct {
            background: #16a34a !important;
            color: white;
        }
        .wrong {
            background: #dc2626 !important;
            color: white;
        }
        .explanation {
            margin-top: 1rem;
            font-style: italic;
            color: #facc15;
            display: none;
        }
        .reveal-button {
            background: #facc15;
            color: #1e293b;
            border: none;
            border-radius: 6px;
            padding: 0.5rem 1rem;
            cursor: pointer;
            font-weight: bold;
            margin-top: 1rem;
        }
        .nav {
            margin-bottom: 2rem;
        }
        .nav a {
            color: #38bdf8;
            text-decoration: none;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="nav">
        <a href="/dashboard">← Back to Dashboard</a>
    </div>

    <h2 style="margin-bottom: 1rem;">📘 {{ $filename }}</h2>
    <div id="contentArea">
        {!! $html !!}
    </div>

    <script>
        document.querySelectorAll('.question-block').forEach((block) => {
            const options = block.querySelectorAll('.option');
            const explanation = block.querySelector('.explanation');

            options.forEach(option => {
                option.addEventListener('click', () => {
                    const isCorrect = option.dataset.correct === 'true';
                    options.forEach(opt => opt.classList.remove('correct', 'wrong'));
                    option.classList.add(isCorrect ? 'correct' : 'wrong');
                    explanation.style.display = 'block';
                });
            });
        });
    </script>
</body>
</html>

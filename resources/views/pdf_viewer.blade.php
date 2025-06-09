<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>{{ $filename }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">

    <style>
        :root {
            --bg-primary: #0f172a;
            --bg-secondary: rgba(30, 41, 59, 0.85);
            --text-light: #f1f5f9;
            --accent: #38bdf8;
            --header-height: 4rem;
        }
        *, *::before, *::after {
            box-sizing: border-box;
        }
        html, body {
            margin: 0; padding: 0;
            height: 100%; background: var(--bg-primary);
            font-family: 'Inter', sans-serif;
            color: var(--text-light);
            overflow: hidden;
        }

        /* Header */
        header {
            position: sticky;
            top: 0; left: 0; right: 0;
            height: var(--header-height);
            background: var(--bg-secondary);
            backdrop-filter: blur(8px);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 1rem;
            z-index: 100;
            box-shadow: 0 2px 8px rgba(0,0,0,0.3);
            transition: background 0.3s;
        }
        header strong {
            font-size: 1.125rem;
            font-weight: 600;
        }
        header a {
            display: inline-flex;
            align-items: center;
            padding: 0.5rem 0.75rem;
            background: var(--accent);
            color: var(--bg-primary);
            border-radius: 0.375rem;
            text-decoration: none;
            font-size: 0.9rem;
            transition: background 0.2s, transform 0.2s;
        }
        header a:hover {
            background: lighten(var(--accent), 10%);
            transform: translateY(-1px);
        }

        /* Fullscreen toggle button */
        #fs-toggle {
            margin-left: 0.5rem;
            cursor: pointer;
            font-size: 1.2rem;
            color: var(--accent);
            transition: color 0.2s;
        }
        #fs-toggle:hover {
            color: lighten(var(--accent), 10%);
        }

        /* PDF iframe container */
        #viewer-container {
            position: relative;
            width: 100%;
            height: calc(100% - var(--header-height));
        }
        iframe {
            width: 100%;
            height: 100%;
            border: none;
            opacity: 0;
            transition: opacity 0.4s ease-in;
        }

        /* Loading spinner */
        #spinner {
            position: absolute;
            top: 50%; left: 50%;
            transform: translate(-50%, -50%);
            border: 4px solid rgba(255,255,255,0.2);
            border-top: 4px solid var(--accent);
            border-radius: 50%;
            width: 3rem; height: 3rem;
            animation: spin 1s linear infinite;
            z-index: 50;
        }
        @keyframes spin {
            to { transform: translate(-50%, -50%) rotate(360deg); }
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        ::-webkit-scrollbar-thumb {
            background: var(--accent);
            border-radius: 4px;
        }
        ::-webkit-scrollbar-track {
            background: rgba(255,255,255,0.05);
        }
    </style>
</head>
<body>
    <header>
        <strong>{{ $filename }}</strong>
        <div>
            <a href="/dashboard">⬅ Back</a>
            <span id="fs-toggle" title="Toggle Fullscreen">⤢</span>
        </div>
    </header>

    <div id="viewer-container">
        <div id="spinner"></div>
        <iframe id="pdf-frame" src="/pdfjs/web/viewer.html?file={{ $url }}"></iframe>
    </div>

    @include('partials.chatbot')

    <script>
        const iframe = document.getElementById('pdf-frame');
        const spinner = document.getElementById('spinner');
        const fsToggle = document.getElementById('fs-toggle');

        // Show iframe once loaded
        iframe.addEventListener('load', () => {
            spinner.style.display = 'none';
            iframe.style.opacity = '1';
        });

        // Fullscreen toggle
        fsToggle.addEventListener('click', () => {
            const el = document.documentElement;
            if (!document.fullscreenElement) {
                el.requestFullscreen?.();
            } else {
                document.exitFullscreen?.();
            }
        });
    </script>
</body>
</html>

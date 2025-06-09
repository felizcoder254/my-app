<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $filename }}</title>
    <style>
        body { margin: 0; background: #0f172a; color: white; font-family: sans-serif; }
        iframe { width: 100%; height: 100vh; border: none; }
        header {
            background: #1e293b; color: #f1f5f9;
            padding: 1rem; display: flex; justify-content: space-between;
        }
        a { color: #38bdf8; text-decoration: none; }
        .fallback { text-align:center; padding:5rem; font-size:1.5rem; color:#fca5a5; }
    </style>
</head>
<body>
    <header>
        <strong>{{ $filename }}</strong>
        <a href="/dashboard">⬅ Back to Dashboard</a>
    </header>

    @if(!empty($fallback))
        <div class="fallback">No preview available.</div>
    @else
        <iframe src="https://view.officeapps.live.com/op/view.aspx?src={{ urlencode($url) }}"></iframe>
    @endif
</body>
</html>

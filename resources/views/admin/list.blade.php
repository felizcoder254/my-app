{{-- resources/views/admin/list.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin File Management</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #06b6d4;
            --secondary: #6366f1;
            --danger: #ef4444;
            --success: #10b981;
            --light-bg: #f9fafb;
            --dark-bg: #0f172a;
            --card-bg: #1e293b;
            --radius: 14px;
            --shadow: 0 6px 24px rgba(0,0,0,0.11);
        }
        body {
            background: var(--dark-bg);
            color: #fff;
            font-family:'Inter', sans-serif;
            margin: 0;
            padding: 0;
            min-height: 100vh;
        }
        body.light-mode {
            background: var(--light-bg);
            color: #1e293b;
        }
        /* Navbar */
        nav {
            background: var(--card-bg);
            padding: 1.2rem 2.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: var(--shadow);
        }
        body.light-mode nav {
            background: #fff;
            box-shadow: 0 3px 18px rgba(24,63,205,0.07);
        }
        .brand {
            font-weight: 800;
            font-size: 1.7rem;
            color: var(--primary);
            display: flex;
            align-items: center;
            gap: 0.8rem;
            letter-spacing: 0.04em;
        }
        /* Status and Actions */
        .top-actions { display: flex; flex-direction: column; align-items: flex-end; }
        .top-actions .status { font-size:1rem; margin-bottom:0.2rem;}
        .top-actions .status span { font-weight: 700;}
        .top-actions .status .sub { color: var(--success);}
        .top-actions .status .notsub { color: var(--danger);}
        .actions {
            display: flex; gap: 0.8rem;
        }
        .actions a, .actions button {
            font-size: 1rem; font-weight: 600;
            border: none;
            border-radius: var(--radius);
            padding: 0.45rem 1.25rem;
            cursor: pointer;
            text-decoration: none;
            color: #fff;
            transition: background 0.18s, transform 0.18s;
            box-shadow: 0 2px 8px rgba(6,182,212,0.06);
        }
        .actions a {
            background: var(--success);
        }
        .actions button {
            background: var(--danger);
        }
        .actions a:hover {
            background: var(--primary);
            transform: scale(1.07);
        }
        .actions button:hover {
            background: #be123c;
        }

        /* Toggle switch */
        .toggle-wrap { text-align: right; padding: 1.3rem 2.5rem 0 0; }
        .switch {
            position: relative; display: inline-block; width: 52px; height: 28px;
        }
        .switch input { opacity: 0; width: 0; height: 0; }
        .slider {
            position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0;
            background: #64748b;
            border-radius: 18px;
            transition: background 0.22s;
        }
        .slider:before {
            position: absolute; content: "";
            height: 22px; width: 22px; left: 4px; bottom: 3px;
            background: #fff;
            border-radius: 50%;
            transition: transform 0.22s;
            box-shadow: 0 2px 6px rgba(0,0,0,0.18);
        }
        input:checked + .slider {
            background: var(--success);
        }
        input:checked + .slider:before {
            transform: translateX(22px);
        }

        /* Header */
        h1 {
            text-align:center;
            color: var(--primary);
            margin: 2.5rem 0 1.5rem 0;
            font-size: 2.1rem;
            letter-spacing: 0.02em;
        }

        /* Flash messages */
        .alert-success { background:var(--success); color: #fff; padding:1rem; border-radius:9px; max-width:800px; margin:1rem auto; text-align:center; }
        .alert-error   { background:var(--danger);  color: #fff; padding:1rem; border-radius:9px; max-width:800px; margin:1rem auto; text-align:center; }

        /* Sections */
        .sections-wrap { max-width:1100px; margin:2rem auto 2.5rem; }
        .section {
            margin-bottom:2.8rem;
            background:var(--card-bg);
            border-radius:18px;
            padding:2rem 2rem 1.5rem;
            box-shadow: var(--shadow);
        }
        body.light-mode .section {
            background:#fff;
        }
        .section h2 {
            color:var(--secondary);
            font-size:1.25rem;
            text-transform:uppercase;
            margin-bottom:1rem;
            letter-spacing: 0.02em;
            font-weight: 800;
        }
        .category-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 2rem;
        }
        .category {
            background:#111827;
            border-radius:12px;
            box-shadow: 0 2px 10px rgba(6,182,212,0.11);
            padding:1.2rem 1rem 1.5rem;
            margin-bottom:1rem;
            display: flex; flex-direction: column; gap: 1.2rem;
        }
        body.light-mode .category {
            background: #f3f4f6;
        }
        .category-controls {
            display:flex; justify-content:space-between; align-items:center;
        }
        .category-controls h3 {
            color:var(--primary);
            font-size:1.13rem;
            margin:0 0 0.25rem 0;
            letter-spacing: 0.01em;
            font-weight: 700;
        }
        .category-controls form button {
            background: var(--danger);
            color: #fff;
            padding: 0.38rem 0.75rem;
            border-radius:7px;
            border:none;
            cursor:pointer;
            font-size:0.94rem;
            font-weight:600;
            transition: background 0.18s;
        }
        .category-controls form button:hover {
            background: #be123c;
        }

        .upload-form label { font-size:0.96rem; color:var(--secondary); }
        .upload-form input[type="file"] {
            margin: 0.4rem 0;
            padding: 5px;
            border-radius: 7px;
            background: #0f172a;
            color: #fff;
            border: 1px solid #334155;
        }
        .upload-form button {
            background: var(--success);
            color: #fff;
            border: none;
            padding: 6px 16px;
            border-radius: 8px;
            font-weight: 600;
            margin-top: 0.3rem;
            cursor: pointer;
            transition: background 0.16s;
        }
        .upload-form button:hover {
            background: #059669;
        }
        /* File list styling */
        .file-list ul { list-style:none; padding:0; margin:0.7rem 0 0 0; }
        .file-list li {
            background:rgba(2,132,199,0.04);
            display:flex; justify-content:space-between; align-items:center;
            border-radius:7px; padding:0.48rem 0.8rem; margin-bottom:0.7rem;
        }
        body.light-mode .file-list li {
            background: #e0e7ef;
            color: #1e293b;
        }
        .filename { flex:1; font-size:1.07rem; }
        .file-buttons a.view-link {
            color: var(--secondary); font-weight:600; text-decoration:none; margin-right:0.8rem;
        }
        .file-buttons a.view-link:hover { color: var(--primary);}
        .file-buttons button {
            background: var(--danger);
            border:none;
            color:#fff;
            padding:0.28rem 0.82rem;
            border-radius:7px;
            cursor:pointer;
            font-size:1.05rem;
        }
        .file-buttons button:hover { background:#be123c; }
        /* Responsive */
        @media (max-width: 700px) {
            .sections-wrap, .section { padding:1rem !important;}
            .category-grid { grid-template-columns:1fr; }
        }

        .file-buttons {
    display: flex;
    gap: 0.7rem;
    align-items: center;
}

.file-buttons a.view-link {
    background: #6366f1;
    color: #fff;
    font-weight: 600;
    padding: 0.34rem 1.1rem 0.34rem 0.7rem;
    border-radius: 100px;
    text-decoration: none;
    box-shadow: 0 2px 6px rgba(99,102,241,0.08);
    display: flex;
    align-items: center;
    transition: background 0.15s, box-shadow 0.18s;
    font-size: 1.03rem;
}
.file-buttons a.view-link:hover {
    background: #3b82f6;
    box-shadow: 0 4px 18px rgba(59,130,246,0.13);
}

.file-buttons a.view-link::before {
    content: '▶';
    font-size: 1.04em;
    margin-right: 0.36em;
    color: #fff;
    opacity: 0.88;
}

.file-buttons button {
    background: #ef4444;
    border: none;
    color: #fff;
    width: 2.3rem;
    height: 2.3rem;
    border-radius: 50%;
    font-size: 1.15rem;
    cursor: pointer;
    font-weight: 800;
    transition: background 0.13s, box-shadow 0.15s, transform 0.12s;
    display: flex; align-items: center; justify-content: center;
    box-shadow: 0 2px 7px rgba(239,68,68,0.12);
}
.file-buttons button:hover {
    background: #be123c;
    box-shadow: 0 3px 16px rgba(239,68,68,0.20);
    transform: scale(1.07);
}

    </style>
</head>
<body>

<nav>
    <div class="brand">🧠 NCK Helper</div>
    <div class="top-actions">
        <div class="status">
            Status:
            @if(Auth::check() && Auth::user()->is_subscribed)
                <span class="sub">📌 Subscribed</span>
            @else
                <span class="notsub">❌ Not Subscribed</span>
            @endif
        </div>
        <div class="actions">
            <a href="{{ route('dashboard') }}">🏠 Back to Dashboard</a>
            <form method="POST" action="{{ route('logout') }}" style="display:inline;">
                @csrf
                <button type="submit">Sign Out</button>
            </form>
        </div>
    </div>
</nav>

<div class="toggle-wrap">
    <label class="switch">
        <input type="checkbox" onchange="toggleMode()"/>
        <span class="slider"></span>
    </label>
</div>

<h1>📁 Admin File Management</h1>

{{-- Flash messages --}}
@if(session('success'))
    <div class="alert-success">✅ {{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert-error">⚠️ {{ session('error') }}</div>
@endif

<div class="sections-wrap">
@php
    $sections = [
        'Paper 1'     => 'nck/paper_1',
        'Paper 2'     => 'nck/paper_2',
        'Past Papers' => 'nck/pastpapers',
        'Predictions' => 'nck/predictions',
    ];
@endphp

@foreach($sections as $sectionLabel => $relativeDiskFolder)
    <div class="section">
        <h2>{{ $sectionLabel }}</h2>
        @php
            $subfolders = Storage::disk('public')->directories($relativeDiskFolder);
        @endphp
        <div class="category-grid">
        @forelse($subfolders as $subfolderPath)
            @php
                $categoryName    = basename($subfolderPath);
                $filesInCategory = Storage::disk('public')->files($subfolderPath);
                if ($sectionLabel === 'Paper 1')      $paperKey = '1';
                elseif ($sectionLabel === 'Paper 2')  $paperKey = '2';
                elseif ($sectionLabel === 'Past Papers') $paperKey = 'past-papers';
                elseif ($sectionLabel === 'Predictions') $paperKey = 'prediction';
                else $paperKey = '';
            @endphp

            <div class="category">
                <div class="category-controls">
                    <h3>{{ $categoryName }}</h3>
                    <form method="POST" action="{{ route('admin.delete') }}">
                        @csrf
                        <input type="hidden" name="path" value="{{ $subfolderPath }}">
                        <button type="submit"
                            onclick="return confirm('Delete entire category “{{ $categoryName }}” and its files?')"
                        >🗑 Delete Category</button>
                    </form>
                </div>

                <form action="{{ route('admin.upload') }}" method="POST" enctype="multipart/form-data" class="upload-form">
                    @csrf
                    <input type="hidden" name="paper"    value="{{ $paperKey }}">
                    <input type="hidden" name="category" value="{{ $categoryName }}">
                    <label>
                        Upload files to <strong>{{ $categoryName }}</strong>
                    </label><br>
                    <input type="file" name="files[]" multiple required><br>
                    <button type="submit">Upload Selected Files</button>
                </form>

                <div class="file-list">
                    <ul>
                        @forelse($filesInCategory as $relativeFilePath)
                            @php
                                $filename  = basename($relativeFilePath);
                                $publicUrl = Storage::url($relativeFilePath);
                            @endphp
                            <li>
                                <span class="filename">{{ $filename }}</span>
                                <div class="file-buttons">
                                    <a href="{{ $publicUrl }}" class="view-link" target="_blank">
                                        ▶ View
                                    </a>
                                    @if(Auth::check() && Auth::user()->is_admin)
                                        <form method="POST" action="{{ route('admin.delete') }}" style="display:inline;">
                                            @csrf
                                            <input type="hidden" name="path" value="{{ $relativeFilePath }}">
                                            <button
                                                type="submit"
                                                onclick="return confirm('Delete {{ $filename }}?')"
                                            >✖</button>
                                        </form>
                                    @endif
                                </div>
                            </li>
                        @empty
                            <li>No files uploaded in {{ $categoryName }}.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        @empty
            <p style="color:#94a3b8; margin-left:1rem;">
                No categories found in {{ $sectionLabel }}.
            </p>
        @endforelse
        </div>
    </div>
@endforeach
</div>

<script>
function toggleMode() {
    document.body.classList.toggle('light-mode');
}
</script>
</body>
</html>

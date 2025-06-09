{{-- resources/views/admin/list.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin File Management</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body { background:#0f172a; color:white; font-family:'Inter', sans-serif; padding:2rem; }
        h1 { text-align:center; color:#38bdf8; margin-bottom:2rem; }
        .alert-success { background:#16a34a; padding:1rem; margin:1rem auto; border-radius:8px; max-width:800px; text-align:center; }
        .alert-error   { background:#dc2626; padding:1rem; margin:1rem auto; border-radius:8px; max-width:800px; text-align:center; }

        .section { margin-bottom:2rem; background:#1e293b; padding:1rem; border-radius:10px; }
        .section h2 { color:#facc15; margin-bottom:0.5rem; text-transform: uppercase; }
        .category { margin-left:1rem; background:#111827; border-radius:8px; padding:1rem; margin-bottom:1rem; }
        .category h3 { color:#60a5fa; margin:0 0 0.5rem; }
        ul { list-style:none; padding:0; margin-top:0.5rem; }
        li { display:flex; justify-content:space-between; align-items:center; padding:0.4rem 0; }
        .filename { flex:1; }
        .file-buttons a, .file-buttons form { display:inline-block; margin-left:1rem; }
        .file-buttons a { color:#38bdf8; text-decoration:none; }
        .file-buttons button { background:#dc2626; border:none; color:white; padding:0.3rem 0.7rem; border-radius:5px; cursor:pointer; }
        .upload-form { margin-top:0.5rem; margin-bottom:1rem; }
        .upload-form input[type="file"] { background:#0f172a; border:1px solid #334155; padding:5px; border-radius:6px; color:white; margin-top:0.5rem; }
        .upload-form button { background:#10b981; color:white; border:none; padding:5px 10px; border-radius:6px; cursor:pointer; margin-top:0.5rem; }
        .category-controls { display:flex; justify-content:space-between; align-items:center; }
        .category-controls form button { background:#ef4444; border:none; color:white; padding:4px 10px; border-radius:6px; font-size:0.8rem; cursor:pointer; }
        .actions a { background:#2563eb; color:white; padding:0.4rem 0.8rem; border-radius:5px; text-decoration:none; font-weight:bold; }
        .actions a:hover { background:#1e40af; }
        .actions button { background:#ef4444; }

        .toggle-wrap { text-align: right; margin: 1rem 0; }
        .light-mode { background: #f9fafb; color: #1e293b; }
        .light-mode li { background: #e2e8f0; }
    </style>
</head>
<body>

    <nav>
        <div class="brand">🧠 NCK Helper</div>
        <div style="display:flex; flex-direction:column; align-items:flex-end;">
            <div style="font-size:0.9rem; margin-bottom:0.3rem;">
                Status:
                @if(Auth::user()->is_subscribed)
                    <span style="color:#10b981; font-weight:bold;">📌 Subscribed</span>
                @else
                    <span style="color:#f87171; font-weight:bold;">❌ Not Subscribed</span>
                @endif
            </div>
            <div class="actions" style="margin-top:0.3rem;">
                <a href="{{ route('dashboard') }}" style="background:#10b981;">🏠 Back to Dashboard</a>
                <form method="POST" action="{{ route('logout') }}" style="display:inline;">
                    @csrf
                    <button type="submit">Sign Out</button>
                </form>
            </div>
        </div>
    </nav>

    <div class="toggle-wrap">
        <button onclick="toggleMode()" style="padding:0.5rem 1rem; background:#facc15; border:none; border-radius:5px; font-weight:bold;">
            Toggle Dark/Light
        </button>
    </div>

    <h1>📁 Admin File Management</h1>

    {{-- Flash messages --}}
    @if(session('success'))
        <div class="alert-success">✅ {{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert-error">⚠️ {{ session('error') }}</div>
    @endif

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
                // Get all category folders under storage/app/public/{ $relativeDiskFolder }
                $subfolders = Storage::disk('public')->directories($relativeDiskFolder);
            @endphp

            @forelse($subfolders as $subfolderPath)
                @php
                    $categoryName    = basename($subfolderPath);
                    // Get all files under this category folder
                    $filesInCategory = Storage::disk('public')->files($subfolderPath);

                    // Determine paperKey for upload
                    if ($sectionLabel === 'Paper 1') {
                        $paperKey = '1';
                    } elseif ($sectionLabel === 'Paper 2') {
                        $paperKey = '2';
                    } elseif ($sectionLabel === 'Past Papers') {
                        $paperKey = 'past-papers';
                    } elseif ($sectionLabel === 'Predictions') {
                        $paperKey = 'prediction';
                    } else {
                        $paperKey = '';
                    }
                @endphp

                <div class="category">
                    <div class="category-controls">
                        <h3>{{ $categoryName }}</h3>
                        <form method="POST" action="{{ route('admin.delete') }}">
                            @csrf
                            {{-- Delete entire category folder (storage/app/public/{{ $subfolderPath }}) --}}
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

                        <label style="font-size:0.8rem;">
                            Upload one or more files to <strong>{{ $categoryName }}</strong>
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

                                        @if(Auth::user()->is_admin)
                                            <form method="POST" action="{{ route('admin.delete') }}" style="display:inline;">
                                                @csrf
                                                {{-- Send exactly the disk‐relative path under storage/app/public --}}
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
    @endforeach

    <script>
    function toggleMode() {
        document.body.classList.toggle('light-mode');
    }
    </script>
</body>
</html>

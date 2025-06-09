{{-- resources/views/dashboard.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>NURSESmed Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet"/>

    <style>
        :root {
            --bg-gradient-dark:      linear-gradient(135deg, #0f172a 0%, #111827 100%);
            --bg-gradient-light:     linear-gradient(135deg, #f0fdfa 0%, #f9fafb 100%);
            --text-light:            #1e2023;
            --text-dark:             #f1f5f9;
            --card-bg-light:         #ffffff;
            --card-bg-dark:          rgba(22, 28, 36, 0.85);
            --card-hover-light:      #f3f4f6;
            --card-hover-dark:       rgba(55, 65, 81, 0.75);
            --primary-accent:        #3b82f6;
            --secondary-accent:      #10b981;
            --danger-accent:         #f43f5e;
            --title-gradient-light:  linear-gradient(90deg, #3b82f6, #9333ea);
            --title-gradient-dark:   linear-gradient(90deg, #60a5fa, #d8b4fe);
            --shadow-dark:           0 8px 24px rgba(0, 0, 0, 0.6);
            --shadow-light:          0 4px 12px rgba(0, 0, 0, 0.1);
            --radius-lg:             1rem;
            --radius-md:             0.5rem;
            --radius-sm:             0.25rem;
            --transition-fast:       0.2s ease-out;
            --transition-medium:     0.3s ease-in-out;
        }
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0;}
        html, body { height: 100%; }
        body {
            position: relative;
            background: var(--bg-gradient-dark);
            color: var(--text-dark);
            font-family: 'Inter', sans-serif;
            line-height: 1.5;
            transition: background var(--transition-medium), color var(--transition-medium);
        }
        body.light-mode {
            background: var(--bg-gradient-light);
            color: var(--text-light);
        }
        body::before {
            content: "";
            position: absolute; top: 0; left: 0; width: 100%; height: 100%;
            background: url("{{ asset('images/medical-bg.svg') }}") repeat;
            opacity: 0.05; pointer-events: none; z-index: 0;
        }
        nav, .toggle-wrap, .header-row, .sections-wrapper, .section, .card-grid, .card, .file-list {
            position: relative; z-index: 1;
        }
        nav {
            background: var(--bg-gradient-dark);
            padding: 0.75rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom-left-radius: var(--radius-lg);
            border-bottom-right-radius: var(--radius-lg);
            box-shadow: var(--shadow-dark);
            transition: background var(--transition-medium), box-shadow var(--transition-medium);
        }
        body.light-mode nav { background: var(--bg-gradient-light); box-shadow: var(--shadow-light);}
        nav .brand { font-weight: 800; font-size: 1.6rem; color: #fff; display: flex; align-items: center; gap: 0.75rem;}
        nav .brand .material-icons { font-size: 2rem; color: var(--secondary-accent);}
        nav .actions { display: flex; gap: 1rem; }
        nav .actions a, nav .actions button {
            font-size: 0.95rem; font-weight: 600; border: none; border-radius: var(--radius-md);
            padding: 0.5rem 1.25rem; cursor: pointer; color: #fff; background: var(--secondary-accent);
            box-shadow: var(--shadow-light);
            transition: background var(--transition-fast), transform var(--transition-fast);
        }
        nav .actions a.manage:hover, nav .actions button.signout:hover {
            background: var(--danger-accent); transform: scale(1.05);
        }
        .toggle-wrap { text-align: right; padding: 1rem 2rem;}
        .switch { position: relative; display: inline-block; width: 52px; height: 28px;}
        .switch input { opacity: 0; width: 0; height: 0;}
        .slider {
            position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0;
            background-color: #4b5563; border-radius: 14px; transition: background-color var(--transition-fast);
        }
        .slider::before {
            position: absolute; content: ""; height: 24px; width: 24px; left: 2px; bottom: 2px;
            background-color: #fff; border-radius: 50%; transition: transform var(--transition-fast);
            transform: translateX(var(--toggle-translate, 0));
        }
        input:checked + .slider { background-color: var(--secondary-accent);}
        input:checked + .slider::before { --toggle-translate: 24px;}
        .switch:hover .slider::before { transform: scale(1.1) translateX(var(--toggle-translate, 0)); }

        .header-row {
            padding: 1.5rem 2rem;
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            align-items: center;
            gap: 1rem;
        }
        .header-row h2 {
            font-size: 2.25rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            background: var(--title-gradient-dark);
            -webkit-background-clip: text;
            color: transparent;
            transition: background var(--transition-medium);
        }
        .header-row .material-icons {
            font-size: 2.5rem;
            color: #3b82f6;
            transition: color var(--transition-medium);
        }
        body.light-mode .header-row h2 { background: var(--title-gradient-light);}
        body.light-mode .header-row .material-icons { color: #3b82f6 !important;}
        .header-row input[type="text"] {
            flex: 1 1 280px; max-width: 420px;
            background: #1f2937; border: 1px solid #374151;
            border-radius: var(--radius-sm); padding: 0.75rem 1rem; color: #f1f5f9;
            box-shadow: inset 0 2px 6px rgba(0,0,0,0.4);
            transition: background var(--transition-medium), border var(--transition-medium), box-shadow var(--transition-medium);
        }
        body.light-mode .header-row input[type="text"] {
            background: #f3f4f6; border: 1px solid #d1d5db; color: #1f2937; box-shadow: inset 0 2px 6px rgba(0,0,0,0.1);
        }

        .sections-wrapper { padding: 1rem 2rem 3rem;}
        .section { margin-bottom: 3rem; }
        .section-title {
            font-size: 1.6rem; font-weight: 700; margin-bottom: 1rem; text-transform: uppercase;
            display: flex; align-items: center; gap: 0.5rem; position: relative;
        }
        .section-title:after {
            content: ""; position: absolute; bottom: -6px; left: 0; width: 80px; height: 4px;
            background: var(--primary-accent); border-radius: 2px;
            animation: pulseUnderline 2.5s ease-in-out infinite;
            transition: background var(--transition-medium);
        }
        body.light-mode .section-title:after { background: var(--secondary-accent);}
        .section-title svg { width: 28px; height: 28px; fill: var(--primary-accent); transition: fill var(--transition-medium);}
        body.light-mode .section-title svg { fill: var(--secondary-accent);}
        @keyframes pulseUnderline { 0% { opacity: 0.4; } 50% { opacity: 1; } 100% { opacity: 0.4; }}

        .card-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
            gap: 1.75rem;
        }
        .card {
            background: var(--card-bg-dark); border-radius: var(--radius-md); padding: 2rem 1.5rem;
            text-align: center; color: var(--text-dark); transition: transform var(--transition-fast), box-shadow var(--transition-fast), background var(--transition-medium), border var(--transition-medium);
            cursor: pointer; position: relative; overflow: hidden; display: flex; flex-direction: column; align-items: center;
            min-height: 260px; border: 2px solid transparent; box-shadow: inset 0 2px 6px rgba(0,0,0,0.4);

        }
        .card:hover {
            transform: translateY(-8px); box-shadow: var(--shadow-dark);
            background: var(--card-hover-dark); border-color: var(--secondary-accent);
        }
        body.light-mode .card { background: var(--card-bg-light); color: var(--text-light); box-shadow: var(--shadow-light);}
        body.light-mode .card:hover { background: var(--card-hover-light); border-color: var(--primary-accent);}
        .card-icon {
            font-family: 'Material Icons';
            font-size: 4rem;
            line-height: 1;
            color: var(--primary-accent);
            margin-bottom: 1.25rem;
            transition: color var(--transition-medium), transform var(--transition-fast), text-shadow var(--transition-medium);
            text-shadow: 0 2px 6px rgba(0,0,0,0.3);
        }
        .card:hover .card-icon {
            color: var(--secondary-accent);
            transform: scale(1.15);
            text-shadow: 0 0 10px rgba(16,185,129,0.6),
                         0 0 14px rgba(16,185,129,0.4);
        }
        body.light-mode .card-icon { text-shadow: 0 1px 2px rgba(0,0,0,0.1);}
        body.light-mode .card:hover .card-icon { color: var(--secondary-accent); text-shadow: 0 0 10px rgba(16,185,129,0.6), 0 0 14px rgba(16,185,129,0.4);}
        .card-label { font-size: 1.3rem; font-weight: 700; margin-bottom: 0.85rem;}
        .card-sub { font-size: 1rem; opacity: 0.75; margin-bottom: 1.5rem;}
        body.light-mode .card-sub { opacity: 0.85;}

        .file-list {
            background: rgba(255, 255, 255, 0.12);
            border-radius: var(--radius-sm);
            margin-top: 1.5rem;
            padding: 1rem 1.25rem;
            display: none;
            width: 100%;
            box-shadow: inset 0 2px 6px rgba(0,0,0,0.4);
        }
        body.light-mode .file-list {
            background: rgba(243, 244, 246, 0.9);
            box-shadow: inset 0 2px 6px rgba(0,0,0,0.1);
        }
        .file-list ul {
            list-style: none;
            padding-left: 0;
            max-height: 180px;
            overflow-y: auto;
        }
        .file-list li {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.5rem 0;
            border-bottom: 1px solid rgba(255,255,255,0.15);
        }
        body.light-mode .file-list li {
            border-bottom: 1px solid rgba(0,0,0,0.1);
        }
        .file-list .filename {
            flex: 1;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            margin-right: 0.75rem;
            font-size: 0.95rem;
            color: inherit;
        }
        .file-buttons { display: flex; gap: 0.6rem;}
        .file-buttons a.view-link, .file-buttons button.delete-link, .file-buttons a.test-link {
            font-size: 0.85rem; font-weight: 600; border: none; border-radius: var(--radius-sm);
            padding: 0.45rem 0.85rem; cursor: pointer; text-decoration: none;
            transition: background var(--transition-fast), transform var(--transition-fast); color: #fff;
            box-shadow: var(--shadow-light);
        }
        .file-buttons a.view-link { background: var(--primary-accent);}
        .file-buttons a.view-link:hover { background: var(--secondary-accent); transform: scale(1.05);}
        .file-buttons button.delete-link { background: var(--danger-accent);}
        .file-buttons button.delete-link:hover { background: #e11d48; transform: scale(1.05);}
        .file-buttons a.test-link {
            background: #14b8a6;
            color: #fff;
            font-weight: 700;
        }
        .file-buttons a.test-link:hover {
            background: #0ea5e9;
            color: #fff;
            transform: scale(1.08);
        }
        body.light-mode .file-buttons a.view-link { background: var(--primary-accent);}
        body.light-mode .file-buttons a.view-link:hover { background: var(--secondary-accent);}
        body.light-mode .file-buttons button.delete-link { background: var(--danger-accent);}
        body.light-mode .file-buttons button.delete-link:hover { background: #e11d48;}
        body.light-mode .file-buttons a.test-link { background: #14b8a6;}
        body.light-mode .file-buttons a.test-link:hover { background: #0ea5e9;}
        .file-list ul::-webkit-scrollbar { width: 6px;}
        .file-list ul::-webkit-scrollbar-track { background: transparent;}
        .file-list ul::-webkit-scrollbar-thumb { background: rgba(0,0,0,0.25); border-radius: 3px;}
        body.light-mode .file-list ul::-webkit-scrollbar-thumb { background: rgba(0,0,0,0.2);}

        /* --- MOBILE RESPONSIVE --- */
        @media (max-width: 900px) {
            .header-row, .sections-wrapper, nav, .toggle-wrap {
                padding-left: 1rem !important;
                padding-right: 1rem !important;
            }
            .header-row {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.7rem;
            }
            nav {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.5rem;
                padding-top: 1rem;
                padding-bottom: 1rem;
            }
            nav .actions {
                width: 100%;
                justify-content: flex-start;
                gap: 0.5rem;
            }
            .header-row h2 {
                font-size: 1.35rem;
                gap: 0.4rem;
            }
            .header-row input[type="text"] {
                max-width: 100%;
                width: 100%;
                margin-top: 0.5rem;
            }
            .card-grid {
                grid-template-columns: 1fr 1fr;
                gap: 1rem;
            }
            .card {
                padding: 1rem 0.6rem;
                min-height: 170px;
                font-size: 0.97rem;
            }
            .card-icon {
                font-size: 2.3rem !important;
                margin-bottom: 0.7rem !important;
            }
            .file-list {
                padding: 0.7rem 0.3rem;
            }
        }
        @media (max-width: 600px) {
            nav {
                position: sticky;
                top: 0;
                left: 0;
                right: 0;
                z-index: 999;
                padding: 0.7rem 0.4rem !important;
                font-size: 0.97rem;
            }
            .card-grid {
                grid-template-columns: 1fr;
                gap: 0.7rem;
            }
            .section-title, .section-title svg {
                font-size: 1.08rem !important;
                min-width: 1.3rem;
                gap: 0.25rem;
            }
            .card-label {
                font-size: 1rem !important;
            }
            .file-list .filename {
                font-size: 0.92rem !important;
                margin-right: 0.4rem;
            }
            .file-buttons a.view-link, .file-buttons button.delete-link, .file-buttons a.test-link {
                padding: 0.27rem 0.5rem !important;
                font-size: 0.91rem !important;
            }
        }

        /* Optional: back‐to‐top floating button on small screens */
        #back-to-top {
            display: none;
            position: fixed;
            bottom: 30px; right: 24px;
            z-index: 999;
            background: var(--primary-accent);
            color: #fff;
            border: none;
            border-radius: 50%;
            width: 50px; height: 50px;
            font-size: 2rem;
            box-shadow: 0 4px 20px rgba(0,0,0,0.16);
            cursor: pointer;
        }
        @media (max-width: 600px) {
            #back-to-top { display: block; }
        }
    </style>
</head>
<body class="dark-mode">

    {{-- ========================= NAVBAR ========================= --}}
    <nav>
        <div class="brand">
            <span class="material-icons">memory</span>
            NURSESmed
        </div>
        <div class="actions">
            @if(Auth::user()->is_admin)
                <a href="{{ route('admin.files') }}" class="manage">
                    <span class="material-icons" style="vertical-align: middle;">folder_open</span>
                    Manage Uploads
                </a>
            @endif
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="signout">
                    <span class="material-icons" style="vertical-align: middle;">logout</span>
                    Sign Out
                </button>
            </form>
        </div>
    </nav>

    {{-- ====================== DARK/LIGHT TOGGLE ====================== --}}
    <div class="toggle-wrap">
        <label class="switch">
            <input type="checkbox" onchange="toggleMode()" />
            <span class="slider"></span>
        </label>
    </div>

    {{-- ============ WELCOME & SEARCH ============ --}}
    <div class="header-row">
        <h2>
            <span class="material-icons" style="color:#3b82f6;">auto_stories</span>
            Welcome, {{ $user->name }}
        </h2>
        <input type="text" id="searchInput" placeholder="Search categories..." oninput="filterCards(this.value)">
    </div>

    {{-- ============ SECTIONS ============ --}}
    <div class="sections-wrapper">
        @php
            use Illuminate\Support\Str;
            $iconMap = [
                // ==== PAPER 1 ====
                "Adult Nursing"                                  => '<span class="material-icons card-icon">accessible</span>',
                "Communication & Counselling"                    => '<span class="material-icons card-icon">forum</span>',
                "Contraception & Gynecology"                     => '<span class="material-icons card-icon">pregnant_woman</span>',
                "Critical Care & Theatre Nursing"                => '<span class="material-icons card-icon">monitor_heart</span>',
                "Intro to Reproductive Health"                   => '<span class="material-icons card-icon">female</span>',
                "Paediatric Nursing"                             => '<span class="material-icons card-icon">child_care</span>',
                "Pregnancy, Labour & Puerperal Care"             => '<span class="material-icons card-icon">baby_changing_station</span>',
                "Professionalism & Trends in Nursing"            => '<span class="material-icons card-icon">school</span>',
                "STIs & HIV"                                     => '<span class="material-icons card-icon">coronavirus</span>',

                // ==== PAPER 2 ====
                "Community Health & Disease Control"             => '<span class="material-icons card-icon">groups</span>',
                "Environmental & Communicable Diseases"          => '<span class="material-icons card-icon">eco</span>',
                "Home-Based Care"                                => '<span class="material-icons card-icon">home</span>',
                "Leadership, Management & Education Methodologies"=> '<span class="material-icons card-icon">supervisor_account</span>',
                "Mental Health"                                  => '<span class="material-icons card-icon">psychology</span>',
                "Primary Healthcare"                             => '<span class="material-icons card-icon">medical_services</span>',
                "Research in Nursing"                            => '<span class="material-icons card-icon">search</span>',
                "Sociology & Anthropology"                       => '<span class="material-icons card-icon">diversity_3</span>',
                "Special Health Issues"                          => '<span class="material-icons card-icon">health_and_safety</span>',

                // ==== PAST PAPERS ====
                "Past Paper 1"                                   => '<span class="material-icons card-icon">description</span>',
                "Past Paper 2"                                   => '<span class="material-icons card-icon">find_in_page</span>',

                // ==== PREDICTIONS ==== (you can expand this as needed)
                "Predictive Template A"                          => '<span class="material-icons card-icon">article</span>',
                "Predictive Template B"                          => '<span class="material-icons card-icon">assignment</span>',
            ];
            $defaultIcon = '<span class="material-icons card-icon">medical_services</span>';
        @endphp

        {{-- ================= PAPER 1 ================= --}}
        <div class="section">
            <h3 class="section-title">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                  <path d="M19 3H5c-1.103 0-2 .897-2 2v14c0 1.103.897 2 2 2h14c1.103 0 2-.897 2-2V5c0-1.103-.897-2-2-2zM5 5h14v4H5V5zm0 14V11h14v8H5z"/>
                </svg>
                PAPER 1
            </h3>
            <div class="card-grid">
                @foreach($paper1 as $categoryName => $files)
                    @php
                        $listId   = 'list-p1-' . Str::slug($categoryName, '-');
                        $iconHtml = $iconMap[$categoryName] ?? $defaultIcon;
                    @endphp

                    <div class="card" data-list-id="{{ $listId }}" data-category="{{ strtolower($categoryName) }}">
                        {!! $iconHtml !!}
                        <div class="card-label">{{ $categoryName }}</div>
                        <div class="card-sub">Click to expand</div>

                        <div class="file-list" id="{{ $listId }}">
                            @if(count($files) === 0)
                                <p style="color: #9ca3af; padding: 0.5rem; font-style: italic;">
                                    No files uploaded.
                                </p>
                            @else
                                <ul>
                                    @foreach($files as $file)
                                        @php $filename = $file['name']; @endphp
                                        <li style="display: flex; align-items:center; justify-content: space-between;">
                                            <span class="filename">{{ $filename }}</span>
                                            <div class="file-buttons">
                                                {{-- ▶ View --}}
                                                <a href="{{ $file['link'] }}"
                                                   class="view-link"
                                                   target="_blank">
                                                    ▶ View
                                                </a>

                                                {{-- ✖ Delete (if admin) --}}
                                                @if(Auth::user()->is_admin)
                                                    <form method="POST"
                                                          action="{{ route('admin.delete') }}"
                                                          style="display:inline-block; margin-left:0.5rem;">
                                                        @csrf
                                                        <input type="hidden"
                                                               name="path"
                                                               value="{{ 'nck/paper_1/' . $categoryName . '/' . $filename }}">
                                                        <button type="submit"
                                                                class="delete-link"
                                                                onclick="return confirm('Delete &quot;{{ $filename }}&quot;?')">
                                                            ✖
                                                        </button>
                                                    </form>
                                                @endif

                                                {{-- 📝 Test Yourself (note: ‘section’ => 'paper_1') --}}
                                                <a href="{{ route('quiz', [
                                                              'file'     => $filename,
                                                              'category' => $categoryName,
                                                              'section'  => 'paper_1'
                                                          ]) }}"
                                                   class="test-link"
                                                   style="
                                                     background: #14b8a6;
                                                     color: white;
                                                     border-radius: 6px;
                                                     padding: 0.45rem 0.8rem;
                                                     margin-left: 0.5rem;
                                                     font-size: 0.92rem;
                                                     text-decoration: none;
                                                   ">
                                                    📝Test
                                                </a>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>


        {{-- ================= PAPER 2 ================= --}}
        <div class="section">
            <h3 class="section-title">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                  <path d="M21 4H3c-1.103 0-2 .897-2 2v12c0 1.103.897 2 2 2h18c1.103 0 2-.897 2-2V6c0-1.103-.897-2-2-2zM3 6h18v10H3V6zm2 2v6l5-3-5-3z"/>
                </svg>
                PAPER 2
            </h3>
            <div class="card-grid">
                @foreach($paper2 as $categoryName => $files)
                    @php
                        $listId   = 'list-p2-' . Str::slug($categoryName, '-');
                        $iconHtml = $iconMap[$categoryName] ?? $defaultIcon;
                    @endphp

                    <div class="card" data-list-id="{{ $listId }}" data-category="{{ strtolower($categoryName) }}">
                        {!! $iconHtml !!}
                        <div class="card-label">{{ $categoryName }}</div>
                        <div class="card-sub">Click to expand</div>

                        <div class="file-list" id="{{ $listId }}">
                            @if(count($files) === 0)
                                <p style="color: #9ca3af; padding: 0.5rem; font-style: italic;">
                                    No files uploaded.
                                </p>
                            @else
                                <ul>
                                    @foreach($files as $file)
                                        @php $filename = $file['name']; @endphp
                                        <li style="display: flex; align-items:center; justify-content: space-between;">
                                            <span class="filename">{{ $filename }}</span>
                                            <div class="file-buttons">
                                                {{-- ▶ View --}}
                                                <a href="{{ $file['link'] }}"
                                                   class="view-link"
                                                   target="_blank">
                                                    ▶ View
                                                </a>

                                                {{-- ✖ Delete (if admin) --}}
                                                @if(Auth::user()->is_admin)
                                                    <form method="POST"
                                                          action="{{ route('admin.delete') }}"
                                                          style="display:inline-block; margin-left:0.5rem;">
                                                        @csrf
                                                        <input type="hidden"
                                                               name="path"
                                                               value="{{ 'nck/paper_2/' . $categoryName . '/' . $filename }}">
                                                        <button type="submit"
                                                                class="delete-link"
                                                                onclick="return confirm('Delete &quot;{{ $filename }}&quot;?')">
                                                            ✖
                                                        </button>
                                                    </form>
                                                @endif

                                                {{-- 📝 Test Yourself (note: ‘section’ => 'paper_2') --}}
                                                <a href="{{ route('quiz', [
                                                              'file'     => $filename,
                                                              'category' => $categoryName,
                                                              'section'  => 'paper_2'
                                                          ]) }}"
                                                   class="test-link"
                                                   style="
                                                     background: #14b8a6;
                                                     color: white;
                                                     border-radius: 6px;
                                                     padding: 0.45rem 0.8rem;
                                                     margin-left: 0.5rem;
                                                     font-size: 0.92rem;
                                                     text-decoration: none;
                                                   ">
                                                    📝 Test Yourself
                                                </a>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>


        {{-- ================= PAST PAPERS ================= --}}
        <div class="section">
            <h3 class="section-title">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                  <path d="M4 4h16v2H4V4zm0 4h16v2H4V8zm0 4h16v2H4v-2zm0 4h16v2H4v-2z"/>
                </svg>
                PAST PAPERS
            </h3>
            <div class="card-grid">
                @foreach($pastpapers as $categoryName => $files)
                    @php
                        $listId   = 'list-pp-' . Str::slug($categoryName, '-');
                        $iconHtml = $iconMap[$categoryName] ?? $defaultIcon;
                    @endphp

                    <div class="card" data-list-id="{{ $listId }}" data-category="{{ strtolower($categoryName) }}">
                        {!! $iconHtml !!}
                        <div class="card-label">{{ $categoryName }}</div>
                        <div class="card-sub">Click to expand</div>

                        <div class="file-list" id="{{ $listId }}">
                            @if(count($files) === 0)
                                <p style="color: #9ca3af; padding: 0.5rem; font-style: italic;">
                                    No files uploaded.
                                </p>
                            @else
                                <ul>
                                    @foreach($files as $file)
                                        @php $filename = $file['name']; @endphp
                                        <li style="display: flex; align-items:center; justify-content: space-between;">
                                            <span class="filename">{{ $filename }}</span>
                                            <div class="file-buttons">
                                                {{-- ▶ View --}}
                                                <a href="{{ $file['link'] }}"
                                                   class="view-link"
                                                   target="_blank">
                                                    ▶ View
                                                </a>

                                                {{-- ✖ Delete (if admin) --}}
                                                @if(Auth::user()->is_admin)
                                                    <form method="POST"
                                                          action="{{ route('admin.delete') }}"
                                                          style="display:inline-block; margin-left:0.5rem;">
                                                        @csrf
                                                        <input type="hidden"
                                                               name="path"
                                                               value="{{ 'nck/pastpapers/' . $categoryName . '/' . $filename }}">
                                                        <button type="submit"
                                                                class="delete-link"
                                                                onclick="return confirm('Delete &quot;{{ $filename }}&quot;?')">
                                                            ✖
                                                        </button>
                                                    </form>
                                                @endif

                                                {{-- 📝 Test Yourself (note: ‘section’ => 'pastpapers') --}}
                                                <a href="{{ route('quiz', [
                                                              'file'     => $filename,
                                                              'category' => $categoryName,
                                                              'section'  => 'pastpapers'
                                                          ]) }}"
                                                   class="test-link"
                                                   style="
                                                     background: #14b8a6;
                                                     color: white;
                                                     border-radius: 6px;
                                                     padding: 0.45rem 0.8rem;
                                                     margin-left: 0.5rem;
                                                     font-size: 0.92rem;
                                                     text-decoration: none;
                                                   ">
                                                    📝 Test Yourself
                                                </a>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>


        {{-- ================= PREDICTIONS ================= --}}
        <div class="section">
            <h3 class="section-title">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                  <path d="M21 3H3c-1.103 0-2 .897-2 2v14a2 2 0 0 0 2 2h18c1.103 0 2-.897 2-2V5c0-1.103-.897-2-2-2zm-2 12H5V7h14v8z"/>
                </svg>
                PREDICTIONS
            </h3>
            <div class="card-grid">
                @foreach($predictions as $categoryName => $files)
                    @php
                        $listId   = 'list-pred-' . Str::slug($categoryName, '-');
                        $iconHtml = $iconMap[$categoryName] ?? $defaultIcon;
                    @endphp

                    <div class="card" data-list-id="{{ $listId }}" data-category="{{ strtolower($categoryName) }}">
                        {!! $iconHtml !!}
                        <div class="card-label">{{ $categoryName }}</div>
                        <div class="card-sub">Click to expand</div>

                        <div class="file-list" id="{{ $listId }}">
                            @if(count($files) === 0)
                                <p style="color: #9ca3af; padding: 0.5rem; font-style: italic;">
                                    No files uploaded.
                                </p>
                            @else
                                <ul>
                                    @foreach($files as $file)
                                        @php $filename = $file['name']; @endphp
                                        <li style="display: flex; align-items:center; justify-content: space-between;">
                                            <span class="filename">{{ $filename }}</span>
                                            <div class="file-buttons">
                                                {{-- ▶ View --}}
                                                <a href="{{ $file['link'] }}"
                                                   class="view-link"
                                                   target="_blank">
                                                    ▶ View
                                                </a>

                                                {{-- ✖ Delete (if admin) --}}
                                                @if(Auth::user()->is_admin)
                                                    <form method="POST"
                                                          action="{{ route('admin.delete') }}"
                                                          style="display:inline-block; margin-left:0.5rem;">
                                                        @csrf
                                                        <input type="hidden"
                                                               name="path"
                                                               value="{{ 'nck/predictions/' . $categoryName . '/' . $filename }}">
                                                        <button type="submit"
                                                                class="delete-link"
                                                                onclick="return confirm('Delete &quot;{{ $filename }}&quot;?')">
                                                            ✖
                                                        </button>
                                                    </form>
                                                @endif

                                                {{-- 📝 Test Yourself (note: ‘section’ => 'predictions') --}}
                                                <a href="{{ route('quiz', [
                                                              'file'     => $filename,
                                                              'category' => $categoryName,
                                                              'section'  => 'predictions'
                                                          ]) }}"
                                                   class="test-link"
                                                   style="
                                                     background: #14b8a6;
                                                     color: white;
                                                     border-radius: 6px;
                                                     padding: 0.45rem 0.8rem;
                                                     margin-left: 0.5rem;
                                                     font-size: 0.92rem;
                                                     text-decoration: none;
                                                   ">
                                                    📝 Test Yourself
                                                </a>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

    </div> {{-- END .sections-wrapper --}}

    {{-- Back‐to‐top button (optional) --}}
    <button id="back-to-top" onclick="window.scrollTo({top:0,behavior:'smooth'});" title="Back to top">↑</button>

    {{-- ===================== SCRIPTS ===================== --}}
    <script>
        // Toggle Light/Dark mode (keeps “auto_stories” icon blue)
        function toggleMode() {
            document.body.classList.toggle('light-mode');
            document.querySelector('.header-row .material-icons').style.color = '#3b82f6';
        }

        // Expand/collapse file lists inside each card
        document.querySelectorAll('.card').forEach(card => {
            card.addEventListener('click', function(e) {
                if (
                    e.target.tagName === 'A' ||
                    e.target.tagName === 'BUTTON' ||
                    e.target.closest('form')
                ) return;

                const listId = card.getAttribute('data-list-id');
                if (!listId) return;
                const fileList = document.getElementById(listId);
                if (!fileList) return;
                fileList.style.display = (fileList.style.display === 'block') ? 'none' : 'block';
            });
        });

        // Live search filter on card labels
        function filterCards(query) {
            query = query.toLowerCase().trim();
            document.querySelectorAll('.card').forEach(card => {
                const label = card.querySelector('.card-label').innerText.toLowerCase();
                card.style.display = label.includes(query) ? 'flex' : 'none';
            });
        }

        // Show/hide “back-to-top” button on scroll
        window.addEventListener('scroll', () => {
            document.getElementById('back-to-top').style.display = (window.scrollY > 300) ? 'block' : 'none';
        });
    </script>

    @include('partials.chatbot')
    @include('partials.peer-chat')
</body>
</html>

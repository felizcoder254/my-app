<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About | NURSESmed</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --navy: #0f172a;
            --blue: #1e40af;
            --accent: #10b981;
            --white: #f1f5f9;
            --gray: #94a3b8;
            --radius: 10px;
            --shadow: 0 6px 18px rgba(0,0,0,0.10);
            --transition: .24s cubic-bezier(.5,.2,.37,1.3);
        }
        body {
            font-family: 'Inter', sans-serif;
            background: var(--navy);
            color: var(--white);
            margin: 0;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        header {
            background: var(--blue);
            padding: 1rem 2.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: var(--shadow);
            position: sticky;
            top: 0;
            z-index: 10;
        }
        .site-title {
            font-size: 1.65rem;
            font-weight: 800;
            letter-spacing: .5px;
        }
        nav {
            display: flex;
            gap: 1.2rem;
        }
        nav a {
            color: var(--white);
            text-decoration: none;
            font-weight: 700;
            padding: .2rem .7rem;
            border-radius: 6px;
            transition: background var(--transition), color var(--transition);
        }
        nav a:hover, nav a.active {
            background: var(--accent);
            color: #fff;
        }
        main {
            flex: 1;
            max-width: 680px;
            margin: 2.5rem auto 0 auto;
            background: rgba(255,255,255,0.02);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            padding: 2.5rem 2.5rem 1.8rem 2.5rem;
        }
        main h2 {
            font-size: 2rem;
            color: var(--accent);
            margin-bottom: 1.1rem;
            font-weight: 800;
        }
        main p {
            color: #dbeafe;
            font-size: 1.12rem;
            line-height: 1.65;
            margin-bottom: 1.3rem;
        }
        footer {
            text-align: center;
            padding: 1.3rem 0 .9rem 0;
            background: var(--navy);
            color: var(--gray);
            font-size: 1rem;
            letter-spacing: .04rem;
        }
        /* Mobile styles */
        @media (max-width: 600px) {
            header {
                flex-direction: column;
                align-items: flex-start;
                padding: .9rem 1rem .7rem 1rem;
                gap: .6rem;
            }
            .site-title {
                font-size: 1.25rem;
            }
            nav {
                gap: .6rem;
            }
            main {
                max-width: 98vw;
                padding: 1.1rem 0.7rem;
                margin: 1.2rem .3rem 0 .3rem;
            }
            main h2 {
                font-size: 1.19rem;
                margin-bottom: .75rem;
            }
            footer {
                font-size: .92rem;
                padding: .9rem 0 .7rem 0;
            }
        }
    </style>
</head>
<body>
    <header>
        <div class="site-title">NURSESmed</div>
        <nav>
            <a href="/" >Home</a>
            <a href="/about" class="active">About</a>
            <a href="/contact">Contact</a>
        </nav>
    </header>
    <main>
        <h2>About NURSESmed</h2>
        <p>
            <strong>NURSESmed</strong> is a digital assistant designed to support Kenyan nursing students and professionals in preparing for the Nursing Council of Kenya (NCK) exams.
        </p>
        <p>
            Our mission is to make exam preparation seamless and accessible, with up-to-date study resources, practice questions, and smart revision tools—all in one platform.
        </p>
        <p>
            Whether you're a student or a practicing nurse, NURSESmed is here to help you excel with confidence.
        </p>
    </main>
    <footer>
        &copy; {{ date('Y') }} NURSESmed — All rights reserved.
    </footer>
</body>
</html>

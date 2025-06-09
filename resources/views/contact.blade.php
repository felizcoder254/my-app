<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact | NCK Helper</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style>
        :root {
            --navy: #0f172a;
            --blue: #1e40af;
            --accent: #10b981;
            --white: #f1f5f9;
            --gray: #94a3b8;
            --radius: 10px;
            --shadow: 0 6px 18px rgba(0,0,0,0.10);
            --transition: .22s cubic-bezier(.5,.2,.37,1.3);
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
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: var(--shadow);
            position: sticky;
            top: 0;
            z-index: 10;
        }
        .site-title {
            font-size: 1.55rem;
            font-weight: 800;
            letter-spacing: .5px;
        }
        nav {
            display: flex;
            gap: 1.1rem;
        }
        nav a {
            color: var(--white);
            text-decoration: none;
            font-weight: 700;
            padding: .25rem .7rem;
            border-radius: 6px;
            transition: background var(--transition), color var(--transition);
        }
        nav a:hover, nav a.active {
            background: var(--accent);
            color: #fff;
        }
        main {
            flex: 1;
            max-width: 480px;
            margin: 2.2rem auto 0 auto;
            background: rgba(255,255,255,0.02);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            padding: 2.5rem 2.5rem 1.7rem 2.5rem;
        }
        main h2 {
            font-size: 1.5rem;
            color: var(--accent);
            margin-bottom: 1.15rem;
            font-weight: 800;
        }
        .contact-list {
            margin-top: 1.5rem;
        }
        .contact-list p {
            font-size: 1.13rem;
            color: #dbeafe;
            margin-bottom: .9rem;
            line-height: 1.8;
            display: flex;
            align-items: center;
            gap: .5rem;
        }
        .contact-list .material-icons {
            color: var(--accent);
            font-size: 1.45rem;
        }
        .contact-list a {
            color: var(--accent);
            text-decoration: underline;
            font-weight: 600;
            word-break: break-all;
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
                padding: .95rem 1rem .7rem 1rem;
                gap: .4rem;
            }
            .site-title {
                font-size: 1.18rem;
            }
            nav {
                gap: .65rem;
            }
            main {
                max-width: 98vw;
                padding: 1.1rem 0.6rem;
                margin: 1.1rem .3rem 0 .3rem;
            }
            main h2 {
                font-size: 1.09rem;
                margin-bottom: .67rem;
            }
            .contact-list p {
                font-size: .98rem;
            }
            footer {
                font-size: .91rem;
                padding: .8rem 0 .6rem 0;
            }
        }
    </style>
</head>
<body>
    <header>
        <div class="site-title">NCK Helper</div>
        <nav>
            <a href="/">Home</a>
            <a href="/about">About</a>
            <a href="/contact" class="active">Contact</a>
        </nav>
    </header>
    <main>
        <h2>Contact Us</h2>
        <div class="contact-list">
            <p>
                <span class="material-icons">email</span>
                Email: <a href="mailto:support@nckhelper.co.ke">support@nckhelper.co.ke</a>
            </p>
            <p>
                <span class="material-icons">phone</span>
                Phone: <a href="tel:+254700000000">+254 700 000000</a>
            </p>
        </div>
    </main>
    <footer>
        &copy; {{ date('Y') }} NCK Helper — All rights reserved.
    </footer>
</body>
</html>

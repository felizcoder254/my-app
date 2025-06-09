{{-- resources/views/auth/login.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login | NCK Helper</title>
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet" />
  <style>
    /* ===================== VARIABLES & BASE STYLES ===================== */
    :root {
      --bg-gradient-dark:      linear-gradient(135deg, #0f172a 0%, #111827 100%);
      --bg-gradient-light:     linear-gradient(135deg, #f0fdfa 0%, #f9fafb 100%);

      --text-light:            #1e293b;
      --text-dark:             #f1f5f9;

      --primary-accent:        #3b82f6;   /* Electric Blue */
      --secondary-accent:      #10b981;   /* Vivid Green */

      --shadow-dark:           0 8px 24px rgba(0, 0, 0, 0.45);
      --shadow-light:          0 4px 12px rgba(0, 0, 0, 0.1);
    }

    *, *::before, *::after {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }
    html, body {
      height: 100%;
      width: 100%;
    }
    body {
      font-family: 'Inter', sans-serif;
      display: flex;
      overflow: hidden;
      background: var(--bg-gradient-dark);
      color: var(--text-dark);
    }
    body.light-mode {
      background: var(--bg-gradient-light);
      color: var(--text-light);
    }

    /* ===================== BACKGROUND PATTERN ===================== */
    body::before {
      content: "";
      position: absolute;
      top: 0; left: 0;
      width: 100%; height: 100%;
      background: url("{{ asset('images/medical-bg.svg') }}") repeat;
      opacity: 0.04;
      pointer-events: none;
      z-index: 0;
    }

    /* ===================== CONTAINER LAYOUT ===================== */
    .container {
      display: flex;
      flex: 1;
      z-index: 1;
    }
    .left, .right {
      flex: 1;
      display: flex;
      align-items: center;
      justify-content: center;
      position: relative;
    }
    .left {
      background: url('{{ asset('images/update.png') }}') no-repeat center center;
      background-size: cover;
    }
    .left::after {
      content: "";
      position: absolute;
      top: 0; left: 0;
      width: 100%; height: 100%;
      background: rgba(0, 0, 0, 0.25);
      z-index: 0;
    }
    .right {
      background: rgba(255, 255, 255, 0.95);
      flex-direction: column;
      padding: 2rem 1.5rem;
    }
    body.light-mode .right {
      background: rgba(15, 23, 42, 0.85);
    }

    /* ===================== FORM CARD ===================== */
    .card {
      background: var(--bg-gradient-light);
      border-radius: 12px;
      box-shadow: var(--shadow-light);
      width: 100%;
      max-width: 400px;
      padding: 2rem;
      position: relative;
      overflow: hidden;
      transition: background 0.3s, box-shadow 0.3s;
      z-index: 1;
    }
    body.light-mode .card {
      background: var(--bg-gradient-dark);
      box-shadow: var(--shadow-dark);
    }

    h2 {
      font-size: 2rem;
      font-weight: 800;
      color: var(--text-light);
      margin-bottom: 1.5rem;
      text-align: center;
    }
    body.light-mode h2 {
      color: var(--text-dark);
    }

    form {
      display: flex;
      flex-direction: column;
      gap: 1rem;
      position: relative;
      z-index: 1;
    }
    form label {
      font-size: 0.875rem;
      font-weight: 600;
      margin-bottom: 0.25rem;
      color: var(--text-light);
    }
    body.light-mode form label {
      color: var(--text-dark);
    }

    form input {
      width: 100%;
      padding: 0.75rem 1rem;
      border: none;
      border-radius: 8px;
      background: rgba(243, 244, 246, 0.8);
      font-size: 1rem;
      transition: background 0.3s, box-shadow 0.3s;
      box-shadow: inset 0 2px 6px rgba(0, 0, 0, 0.1);
      color: var(--text-light);
    }
    body.light-mode form input {
      background: rgba(31, 41, 55, 0.8);
      color: var(--text-dark);
      box-shadow: inset 0 2px 6px rgba(0, 0, 0, 0.3);
    }
    form input:focus {
      outline: none;
      box-shadow: 0 0 0 3px var(--secondary-accent);
      background: #fff;
    }
    body.light-mode form input:focus {
      background: #1f2937;
      box-shadow: 0 0 0 3px var(--primary-accent);
    }

    .btn-submit {
      background-color: var(--secondary-accent);
      color: #fff;
      font-weight: 700;
      padding: 0.75rem;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      font-size: 1rem;
      transition: background 0.3s, transform 0.3s, box-shadow 0.3s;
      box-shadow: 0 4px 12px rgba(16, 185, 129, 0.4);
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 0.5rem;
    }
    .btn-submit:hover {
      background-color: #14c784;
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(20, 199, 132, 0.5);
    }
    body.light-mode .btn-submit {
      background-color: var(--primary-accent);
      box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4);
    }
    body.light-mode .btn-submit:hover {
      background-color: #2563eb;
      box-shadow: 0 6px 20px rgba(37, 99, 235, 0.5);
    }

    .toggle-wrap {
      position: absolute;
      top: 1rem;
      right: 1rem;
      z-index: 2;
    }
    .switch {
      position: relative;
      display: inline-block;
      width: 50px;
      height: 28px;
    }
    .switch input {
      opacity: 0;
      width: 0;
      height: 0;
    }
    .slider {
      position: absolute;
      cursor: pointer;
      top: 0; left: 0; right: 0; bottom: 0;
      background-color: #4b5563;
      border-radius: 14px;
      transition: background-color 0.3s;
    }
    .slider:before {
      position: absolute;
      content: "";
      height: 22px;
      width: 22px;
      left: 3px;
      bottom: 3px;
      background-color: #fff;
      border-radius: 50%;
      transition: transform 0.3s;
      transform: translateX(var(--toggle-translate, 0));
    }
    input:checked + .slider {
      background-color: var(--secondary-accent);
    }
    input:checked + .slider:before {
      --toggle-translate: 22px;
    }
    .switch:hover .slider:before {
      transform: scale(1.1) translateX(var(--toggle-translate, 0));
    }

    /* ===================== RESPONSIVE TWEAKS ===================== */
    @media (max-width: 768px) {
      .container {
        flex-direction: column;
      }
      .left, .right {
        flex: none;
        height: 50%;
      }
      .left { display: none; }
      .right {
        height: 100%;
        padding: 1.5rem;
      }
    }

    /* ===================== “Don’t have an account?” TEXT ===================== */
    .register-redirect {
      margin-top: 1rem;
      text-align: center;
      font-size: 0.9rem;
      color: var(--text-light);
    }
    body.light-mode .register-redirect {
      color: var(--text-dark);
    }
    .register-redirect a {
      color: var(--secondary-accent);
      text-decoration: none;
      font-weight: 600;
      transition: color 0.3s;
    }
    .register-redirect a:hover {
      color: var(--primary-accent);
    }
  </style>
</head>
<body>
  <div class="toggle-wrap">
    <label class="switch">
      <input type="checkbox" onchange="toggleMode()" />
      <span class="slider"></span>
    </label>
  </div>

  <div class="container">
    <div class="left"></div>

    <div class="right">
      <div class="card">
        <h2>Sign In</h2>
        <form method="POST" action="{{ route('login') }}">
          @csrf

          <label for="email">Email</label>
          <input id="email" type="email" name="email" placeholder="Enter your email" required autofocus>

          <label for="password">Password</label>
          <input id="password" type="password" name="password" placeholder="Enter your password" required>

          <button type="submit" class="btn-submit">
            <span class="material-icons" style="vertical-align: middle; font-size: 20px;">login</span>
            Log In
          </button>
        </form>

        <!-- ===== Added “Don’t have an account?” link ===== -->
        <div class="register-redirect">
          Don’t have an account?
          <a href="{{ route('register') }}">Register here</a>
        </div>
      </div>
    </div>
  </div>

  <script>
    function toggleMode() {
      document.body.classList.toggle('light-mode');
    }
  </script>
</body>
</html>

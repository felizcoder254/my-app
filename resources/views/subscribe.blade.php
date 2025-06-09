{{-- resources/views/subscribe.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Subscribe – NCK Helper</title>

  <!-- Inter font & Material Icons -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet"/>

  <style>
    /* ─────────── Reset & Base ─────────── */
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }
    html {
      scroll-behavior: smooth;
    }
    body {
      font-family: 'Inter', sans-serif;
      background: linear-gradient(135deg, #e0f2f1, #ffffff);
      min-height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      padding: 1rem;
      color: #334155;
    }

    /* ─────────── Card Container ─────────── */
    .subscribe-card {
      background: #ffffff;
      border-radius: 16px;
      box-shadow: 0 12px 24px rgba(0,0,0,0.08);
      max-width: 480px;
      width: 100%;
      padding: 2.5rem 2rem;
      position: relative;
      overflow: hidden;
    }
    /* Decorative gradient shape */
    .subscribe-card::before {
      content: "";
      position: absolute;
      top: -60px;
      right: -60px;
      width: 180px;
      height: 180px;
      background: radial-gradient(circle at center, rgba(14,165,233,0.4), rgba(14,165,233,0) 70%);
      border-radius: 50%;
      z-index: 0;
    }

    h2 {
      font-size: 1.75rem;
      font-weight: 800;
      text-align: center;
      color: #0f172a;
      margin-bottom: 1.5rem;
      position: relative;
      z-index: 1;
    }
    h2::before {
      content: "📘";
      margin-right: 0.5rem;
      font-size: 1.5rem;
      vertical-align: middle;
    }

    form {
      display: flex;
      flex-direction: column;
      gap: 1.25rem;
      position: relative;
      z-index: 1;
    }

    label {
      font-weight: 600;
      font-size: 0.95rem;
      color: #475569;
    }

    select,
    input[type="text"] {
      padding: 0.75rem 1rem;
      border-radius: 8px;
      border: 1px solid #cbd5e1;
      font-size: 1rem;
      background: #f8fafc;
      color: #1e293b;
      transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }
    select:focus,
    input[type="text"]:focus {
      border-color: #0ea5e9;
      box-shadow: 0 0 0 3px rgba(14,165,233,0.2);
      outline: none;
    }

    /* Phone input icon */
    .input-with-icon {
      position: relative;
    }
    .input-with-icon .material-icons {
      position: absolute;
      top: 50%;
      left: 12px;
      transform: translateY(-50%);
      color: #94a3b8;
    }
    .input-with-icon input {
      padding-left: 2.75rem;
    }

    button {
      padding: 0.75rem 1.25rem;
      background: #0ea5e9;
      color: #ffffff;
      border: none;
      border-radius: 8px;
      font-size: 1rem;
      font-weight: 600;
      cursor: pointer;
      box-shadow: 0 6px 16px rgba(14,165,233,0.3);
      transition: background 0.3s ease, transform 0.2s ease, box-shadow 0.3s ease;
    }
    button:hover {
      background: #0284c7;
      transform: translateY(-2px);
      box-shadow: 0 8px 24px rgba(14,165,233,0.4);
    }
    button:active {
      transform: scale(0.97);
      box-shadow: 0 4px 12px rgba(14,165,233,0.3);
    }

    .note {
      font-size: 0.85rem;
      color: #64748b;
      text-align: center;
      margin-top: 0.5rem;
      position: relative;
      z-index: 1;
    }

    /* ─────────── Responsive ─────────── */
    @media (max-width: 600px) {
      .subscribe-card {
        padding: 2rem 1.5rem;
      }
      h2 {
        font-size: 1.5rem;
      }
      .input-with-icon .material-icons {
        left: 8px;
      }
      .input-with-icon input {
        padding-left: 2.5rem;
      }
      select,
      input[type="text"],
      button {
        font-size: 0.95rem;
      }
    }
  </style>
</head>

<body>
  <div class="subscribe-card">
    <h2>Choose Your Subscription</h2>

    <form method="POST" action="/subscribe">
      @csrf

      <label for="plan">Select a Plan</label>
      <select name="plan" id="plan" required>
        <option value="" disabled selected>— Choose Plan —</option>
        <option value="trial">Free Trial (7 Days)</option>
        <option value="month_1">1 Month – KES 100</option>
        <option value="month_2">2 Months – KES 180</option>
      </select>

      <label for="phone">M-Pesa Phone Number</label>
      <div class="input-with-icon">
        <span class="material-icons">phone_android</span>
        <input type="text"
               name="phone"
               id="phone"
               placeholder="e.g. 0700 123 456"
               required>
      </div>

      <button type="submit">Activate Subscription</button>
      <p class="note">You’ll receive a prompt to confirm payment on your phone.</p>
    </form>
  </div>
</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Confirming Payment</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Inter', sans-serif;
      background: #0f172a;
      color: #f9fafb;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
    }
    h2 {
      font-size: 1.5rem;
      font-weight: 700;
      margin-bottom: 1rem;
    }
    .spinner {
      width: 40px;
      height: 40px;
      border: 5px solid #1e40af;
      border-top-color: transparent;
      border-radius: 50%;
      animation: spin 1s linear infinite;
    }
    @keyframes spin {
      to { transform: rotate(360deg); }
    }
  </style>
</head>
<body>
  <h2>Confirming your M-Pesa payment...</h2>
  <div class="spinner"></div>

      <script>
setInterval(() => {
    fetch('/check-subscription')
        .then(res => res.json())
        .then(data => {
            if (data.subscribed) {
                window.location.href = "/dashboard";
            }
        });
}, 5000); // check every 5 seconds
</script>

</body>
</html>

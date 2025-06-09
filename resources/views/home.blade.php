{{-- resources/views/home.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Home | {{ config('app.name', 'NURSESmed') }}</title>

  <!-- Google Fonts & Material Icons -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

  <style>
    :root{
      --navy:#002B55; --off-white:#F1F5F9; --white:#fff; --black:#000;
      --dark-gray:#4A5568; --accent-blue:#3b82f6; --accent-green:#10b981;
      --accent-purple:#9333ea; --fs-h1:clamp(2.5rem,4vw,3.5rem); --fs-body:1rem;
      --radius:12px; --shadow-heavy:0 12px 32px rgba(0,0,0,.11),0 2px 4px rgba(0,0,0,0.08);
      --shadow-light:0 4px 12px rgba(0,0,0,.10); --transition:.32s cubic-bezier(.48,.04,.56,1.38);
    }
    *,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
    html,body{height:100%;width:100%;}
    body{
      display:flex;flex-direction:column;min-height:100vh;overflow-x:hidden;
      font-family:'Inter',sans-serif;background:var(--navy);color:var(--white);
      transition:background var(--transition),color var(--transition)
    }
    body.light-mode{background:var(--off-white);color:var(--black)}
    body::before{
      content:"";position:absolute;inset:0;
      background:url("{{ asset('images/medical-bg.svg') }}") repeat;
      opacity:.04;pointer-events:none;z-index:0
    }
    /* HEADER (Sticky, glass on mobile) */
    header{
      height:4rem;padding:0 2rem;position:sticky;top:0;z-index:10;
      display:flex;justify-content:space-between;align-items:center;
      background:rgba(15,23,42,.85);backdrop-filter:blur(6px);
      box-shadow:var(--shadow-heavy);transition:background var(--transition)
    }
    body.light-mode header{background:rgba(241,245,249,.92);box-shadow:var(--shadow-light)}
    .site-title{display:flex;align-items:center;gap:.5rem;font-weight:800;font-size:1.6rem;letter-spacing:.5px;color:var(--white);text-transform:uppercase}
    .site-title .material-icons{font-size:2rem;color:var(--accent-green)}
    body.light-mode .site-title{color:var(--black)}
    nav{display:flex;gap:1.5rem;align-items:center}
    nav a{
      position:relative;font-weight:600;text-decoration:none;
      color:var(--white);padding-bottom:2px;transition:color var(--transition)
    }
    nav a::after{
      content:"";position:absolute;left:0;bottom:-2px;width:0;height:2px;
      background:var(--accent-green);transition:width var(--transition)
    }
    nav a:hover{color:var(--accent-green)}
    nav a:hover::after{width:100%}
    body.light-mode nav a{color:var(--black)}
    .toggle-btn{
      background:none;border:none;cursor:pointer;font-size:1.6rem;line-height:1;color:var(--white);
      transition:color var(--transition)
    }
    body.light-mode .toggle-btn{color:var(--black)}

    /* HERO SECTION */
    main{
      flex:1;display:flex;align-items:center;justify-content:center;
      padding:3.5rem 1rem 1rem 1rem;position:relative;
      min-height:60vh;
    }
    .hero-wrapper{
      position:relative;display:flex;flex-wrap:wrap-reverse;gap:2.5rem;
      align-items:center;justify-content:center;max-width:1100px;width:100%;padding:2rem;
      background:rgba(255,255,255,0.03);border-radius:var(--radius);
      box-shadow:var(--shadow-heavy);
    }
    .hero-wrapper::before{
      content:"";position:absolute;top:-15%;right:-20%;width:440px;height:430px;
      background:linear-gradient(45deg,var(--accent-blue),var(--accent-purple));
      filter:blur(100px);opacity:.45;border-radius:50%;z-index:-1;
      animation:float 7s ease-in-out infinite alternate;
    }
    @keyframes float{from{transform:translate(0,0)}to{transform:translate(-20px,-18px)}}

    .hero-image{
      flex:1 1 340px;min-width:170px;max-width:430px;max-height:400px;
      overflow:hidden;border-radius:var(--radius);
      background:rgba(0,0,0,.15);display:flex;align-items:center;justify-content:center;
      box-shadow:var(--shadow-heavy);transition:transform var(--transition),box-shadow var(--transition)
    }
    .hero-image img{width:100%;height:100%;object-fit:cover;border-radius:var(--radius);transition:transform var(--transition)}
    .hero-image:hover{transform:translateY(-6px) scale(1.02);box-shadow:0 18px 44px rgba(0,0,0,.21)}
    .hero-image:hover img{transform:scale(1.07)}

    .hero-text{flex:1 1 370px;max-width:500px;animation:fadeUp .85s both;text-align:left;}
    @keyframes fadeUp{0%{opacity:0;transform:translateY(22px)}100%{opacity:1;transform:translateY(0)}}

    .hero-text h1{font-size:var(--fs-h1);font-weight:800;margin-bottom:1rem;color:var(--white);transition:color var(--transition)}
    body.light-mode .hero-text h1{color:var(--accent-blue)}
    .hero-text p{font-size:var(--fs-body);margin-bottom:1.5rem;line-height:1.65;color:var(--white)}
    body.light-mode .hero-text p{color:var(--dark-gray)}
    .buttons{display:flex;gap:1rem;flex-wrap:wrap}
    .btn{
      display:inline-flex;align-items:center;gap:.5rem;padding:.82rem 1.5rem;
      border-radius:var(--radius);font-weight:700;color:var(--white);border:none;cursor:pointer;
      text-transform:uppercase;box-shadow:var(--shadow-heavy);position:relative;overflow:hidden;
      letter-spacing:.3px;font-size:1.03rem;
      transition:background var(--transition),transform var(--transition),box-shadow var(--transition)
    }
    .btn::before{content:"";position:absolute;inset:0;background:rgba(255,255,255,.08);opacity:0;transition:opacity var(--transition)}
    .btn:hover::before{opacity:1}
    .btn-primary{background:var(--accent-green)}
    .btn-primary:hover{background:#14c784;transform:translateY(-2px) scale(1.03);box-shadow:0 8px 32px rgba(20,199,132,.13)}
    .btn-secondary{background:var(--accent-blue)}
    .btn-secondary:hover{background:#2563eb;transform:translateY(-2px) scale(1.03);box-shadow:0 8px 32px rgba(37,99,235,.11)}

    /* STATS STRIP */
    .stats-strip{
      width:100%;max-width:1100px;margin:2.8rem auto 0;padding:1.7rem 1.1rem;
      display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:1.25rem;
      border-radius:var(--radius);background:rgba(255,255,255,.09);backdrop-filter:blur(4px)
    }
    body.light-mode .stats-strip{background:rgba(0,0,0,.06)}
    .stat{display:flex;flex-direction:column;align-items:center;text-align:center}
    .stat .material-icons{
      font-size:2.4rem;margin-bottom:.5rem;padding:.65rem;border-radius:50%;
      border:2px dashed var(--accent-blue);color:var(--accent-blue);background:rgba(255,255,255,.06)
    }
    body.light-mode .stat .material-icons{border-color:var(--accent-green);color:var(--accent-green)}
    .stat h3{font-size:2rem;font-weight:800;margin-bottom:.19rem;letter-spacing:.5px;}
    .stat p{font-size:.96rem;color:var(--white)}
    body.light-mode .stat p{color:var(--dark-gray)}

    /* FOOTER */
    footer{
      margin-top:auto;padding:1.2rem;text-align:center;font-size:.93rem;
      background:var(--navy);color:var(--white);border-top:1px solid rgba(255,255,255,.09);
      transition:background var(--transition),color var(--transition)
    }
    body.light-mode footer{background:var(--off-white);color:var(--black)}
    footer a{color:var(--accent-green);text-decoration:none;transition:color var(--transition)}
    footer a:hover{color:#14c784}

    /* ====== RESPONSIVE ====== */
    @media(max-width:1024px){
      .hero-wrapper{padding:1.2rem;}
      .stats-strip{padding:1.3rem 0.5rem;}
    }
    @media(max-width:768px){
      header{padding:0 1rem;height:3.6rem}
      .hero-wrapper{flex-direction:column;padding:0.7rem 0.2rem;}
      .hero-text h1{font-size:2.1rem}.hero-text p{font-size:.995rem}
      .btn{width:100%;justify-content:center;}
      .hero-image{max-width:95vw;max-height:320px;}
      .stats-strip{padding:0.9rem 0.2rem;}
      .stat h3{font-size:1.4rem;}
      .stat .material-icons{font-size:2rem;padding:.4rem;}
    }
    @media(max-width:480px){
      header{padding:0 0.25rem;height:3.1rem;}
      .site-title{font-size:1.08rem;}
      nav{gap:0.7rem;}
      .hero-wrapper{gap:1rem;}
      .hero-image{min-width:0;max-width:100vw;max-height:180px;}
      .hero-text{max-width:98vw;}
      .hero-text h1{font-size:1.2rem;}
      .hero-text p{font-size:.9rem;}
      .btn{font-size:.97rem;padding:.66rem 1rem;}
      .stats-strip{grid-template-columns:1fr;padding:0.6rem 0.05rem;}
      .stat .material-icons{padding:.25rem;}
      .stat p{font-size:.88rem;}
      footer{font-size:.84rem;}
    }
  </style>
</head>

<body>
  <!-- ===== HEADER ===== -->
  <header>
    <div class="site-title"><span class="material-icons">medical_services</span>NURSESmed</div>
    <nav>
      <a href="{{ route('home') }}">Home</a>
      <a href="{{ url('/about') }}">About</a>
      <a href="{{ url('/contact') }}">Contact</a>
      <button class="toggle-btn" data-toggle="mode"><span class="material-icons">light_mode</span></button>
    </nav>
  </header>

  <!-- ===== HERO ===== -->
  <main>
    <div class="hero-wrapper">
      <div class="hero-image"><img src="{{ asset('images/update.png') }}" alt="Nurse studying"></div>
      <div class="hero-text">
        <h1>Welcome to the NURSESmed</h1>
        <p>This is your go-to platform for nursing licensing exam materials, revision tools and mentorship support. We’re here to help you pass with confidence.</p>
        <div class="buttons">
          <a href="{{ route('register') }}" class="btn btn-primary"><span class="material-icons">person_add</span>Register</a>
          <a href="{{ route('login') }}"    class="btn btn-secondary"><span class="material-icons">login</span>Sign&nbsp;In</a>
        </div>
      </div>
    </div>
  </main>

  <!-- ===== SOCIAL-PROOF STATS ===== -->
  @php
    $stats = [
      ['icon'=>'star_rate','value'=>'4.8','label'=>'2 400+ Ratings'],
      ['icon'=>'quiz','value'=>'12 750','label'=>'Practice Questions'],
      ['icon'=>'sticky_note_2','value'=>'580','label'=>'Study Notes'],
      ['icon'=>'group','value'=>'13 000','label'=>'Active Students'],
    ];
  @endphp

  <section class="stats-strip">
    @foreach($stats as $s)
      <div class="stat">
        <span class="material-icons">{{ $s['icon'] }}</span>
        <h3 class="counter" data-target="{{ preg_replace('/[^0-9]/','',$s['value']) }}">{{ $s['value'] }}</h3>
        <p>{{ $s['label'] }}</p>
      </div>
    @endforeach
  </section>

  <!-- ===== FOOTER ===== -->
  <footer>&copy; {{ date('Y') }} {{ config('app.name','NURSESmed') }} — All rights reserved.</footer>

  <!-- ===== SCRIPTS ===== -->
  <script>
    /* theme toggle */
    document.querySelector('[data-toggle="mode"]').addEventListener('click',()=>document.body.classList.toggle('light-mode'));

    /* animated counters */
    document.querySelectorAll('.counter').forEach(el=>{
      const target=+el.dataset.target,step=Math.max(1,target/120);let current=0;
      const tick=()=>{current+=step;if(current<target){el.textContent=new Intl.NumberFormat().format(Math.floor(current));requestAnimationFrame(tick);}else{el.textContent=el.dataset.target.replace(/\B(?=(\d{3})+(?!\d))/g," ");}}
      tick();
    });
  </script>
</body>
</html>

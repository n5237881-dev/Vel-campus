<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 — Page Not Found · VelCampus</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        body { display:flex; flex-direction:column; min-height:100vh; }
        .error-wrap {
            flex:1; display:flex; align-items:center; justify-content:center;
            flex-direction:column; text-align:center; padding:60px 20px;
        }
        .error-code {
            font-family:var(--font-head); font-size:clamp(6rem,15vw,11rem); font-weight:800;
            line-height:1; color:transparent;
            background: linear-gradient(135deg, var(--accent), var(--accent2));
            -webkit-background-clip:text; background-clip:text;
            letter-spacing:-0.05em; margin-bottom:16px;
        }
        .error-msg { font-family:var(--font-head); font-size:1.4rem; font-weight:700; color:var(--text); margin-bottom:10px; }
        .error-sub { color:var(--text-muted); font-size:0.95rem; max-width:380px; margin:0 auto 32px; }
        .back-btn  { display:inline-flex; align-items:center; gap:8px; padding:10px 24px;
                     background:var(--accent); color:#fff; border-radius:var(--radius-sm);
                     font-family:var(--font-head); font-weight:700; font-size:0.9rem;
                     transition:all .2s; }
        .back-btn:hover { background:#3a7aee; color:#fff; transform:translateY(-2px); }
    </style>
</head>
<body>
<nav class="navbar">
    <div class="nav-brand">
        <div class="logo-icon">🎓</div>
        <h1>Vel<span>Campus</span></h1>
    </div>
</nav>
<div class="error-wrap">
    <div class="error-code">404</div>
    <div class="error-msg">Page Not Found</div>
    <p class="error-sub">The page you're looking for doesn't exist or has been moved.</p>
    <a href="index.php" class="back-btn">← Back to Events</a>
</div>
<footer><span>VelCampus</span> · Vel Tech R&D Institute</footer>
</body>
</html>

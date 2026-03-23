<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — VelCampus</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:wght@300;400;500&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    <style>
        /* ============================================
           VelCampus Login Page
           Aesthetic: Split-screen academic dark
           Left: Animated campus identity panel
           Right: Clean login/signup form
        ============================================ */

        :root {
            --bg:        #080a0f;
            --panel:     #0d1017;
            --card:      #111520;
            --border:    #1a2035;
            --glow:      #1e2d50;
            --accent:    #4f8cff;
            --accent2:   #00e5c0;
            --accent3:   #ff6b6b;
            --text:      #e8eaf2;
            --muted:     #6b7590;
            --dim:       #2a3050;
            --success:   #00e5c0;
            --error:     #ff6b6b;
            --font-h:    'Syne', sans-serif;
            --font-b:    'DM Sans', sans-serif;
            --font-m:    'JetBrains Mono', monospace;
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        html, body {
            height: 100%;
            font-family: var(--font-b);
            background: var(--bg);
            color: var(--text);
            overflow: hidden;
        }

        /* ---- Layout ---- */
        .login-wrap {
            display: grid;
            grid-template-columns: 1fr 1fr;
            height: 100vh;
        }

        /* ============================================
           LEFT PANEL — Identity
        ============================================ */
        .left-panel {
            position: relative;
            background: var(--panel);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 48px;
            overflow: hidden;
            border-right: 1px solid var(--border);
        }

        /* Animated grid background */
        .left-panel::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image:
                linear-gradient(var(--border) 1px, transparent 1px),
                linear-gradient(90deg, var(--border) 1px, transparent 1px);
            background-size: 48px 48px;
            opacity: 0.4;
            animation: gridDrift 20s linear infinite;
        }

        @keyframes gridDrift {
            from { background-position: 0 0; }
            to   { background-position: 48px 48px; }
        }

        /* Radial glow */
        .left-panel::after {
            content: '';
            position: absolute;
            top: 20%;
            left: 20%;
            width: 500px;
            height: 500px;
            background: radial-gradient(ellipse, rgba(79,140,255,0.12) 0%, transparent 70%);
            pointer-events: none;
            animation: glowPulse 6s ease-in-out infinite;
        }

        @keyframes glowPulse {
            0%, 100% { opacity: 0.6; transform: scale(1); }
            50%       { opacity: 1;   transform: scale(1.15); }
        }

        /* Brand */
        .brand {
            position: relative;
            z-index: 2;
            animation: fadeUp 0.8s ease both;
        }

        .brand-logo {
            width: 52px;
            height: 52px;
            background: linear-gradient(135deg, var(--accent), var(--accent2));
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 26px;
            margin-bottom: 18px;
            box-shadow: 0 8px 32px rgba(79,140,255,0.3);
        }

        .brand h1 {
            font-family: var(--font-h);
            font-size: 2rem;
            font-weight: 800;
            letter-spacing: -0.04em;
            color: var(--text);
            line-height: 1;
            margin-bottom: 6px;
        }

        .brand h1 span { color: var(--accent); }

        .brand p {
            font-size: 0.8rem;
            color: var(--muted);
            font-family: var(--font-m);
            letter-spacing: 0.04em;
        }

        /* Center hero text */
        .hero-text {
            position: relative;
            z-index: 2;
            animation: fadeUp 0.8s 0.2s ease both;
        }

        .hero-text .eyebrow {
            font-family: var(--font-m);
            font-size: 0.7rem;
            color: var(--accent2);
            text-transform: uppercase;
            letter-spacing: 0.12em;
            margin-bottom: 14px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .hero-text .eyebrow::before {
            content: '';
            width: 24px;
            height: 1px;
            background: var(--accent2);
        }

        .hero-text h2 {
            font-family: var(--font-h);
            font-size: clamp(2rem, 3.5vw, 2.8rem);
            font-weight: 800;
            line-height: 1.1;
            letter-spacing: -0.03em;
            color: var(--text);
            margin-bottom: 18px;
        }

        .hero-text h2 .grad {
            background: linear-gradient(135deg, var(--accent), var(--accent2));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .hero-text p {
            font-size: 0.9rem;
            color: var(--muted);
            line-height: 1.7;
            max-width: 320px;
        }

        /* Feature pills */
        .feature-pills {
            display: flex;
            flex-direction: column;
            gap: 10px;
            margin-top: 28px;
        }

        .pill {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 0.82rem;
            color: var(--muted);
        }

        .pill-icon {
            width: 28px;
            height: 28px;
            border-radius: 8px;
            background: var(--card);
            border: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
            flex-shrink: 0;
        }

        /* Bottom footer on left */
        .left-footer {
            position: relative;
            z-index: 2;
            font-family: var(--font-m);
            font-size: 0.7rem;
            color: var(--dim);
            animation: fadeUp 0.8s 0.4s ease both;
        }

        /* Floating cards decoration */
        .float-cards {
            position: absolute;
            bottom: 120px;
            right: -20px;
            z-index: 2;
            display: flex;
            flex-direction: column;
            gap: 10px;
            animation: floatCards 0.8s 0.3s ease both;
        }

        @keyframes floatCards {
            from { opacity: 0; transform: translateX(30px); }
            to   { opacity: 1; transform: translateX(0); }
        }

        .float-card {
            background: var(--card);
            border: 1px solid var(--glow);
            border-radius: 10px;
            padding: 10px 14px;
            font-size: 0.75rem;
            color: var(--muted);
            white-space: nowrap;
            box-shadow: 0 4px 20px rgba(0,0,0,0.3);
        }

        .float-card strong { color: var(--text); }
        .float-card .badge {
            display: inline-block;
            padding: 2px 7px;
            border-radius: 100px;
            font-size: 0.65rem;
            font-family: var(--font-m);
            margin-left: 6px;
        }

        .badge-hack  { background: rgba(255,140,0,0.15); color:#ff8c00; }
        .badge-ws    { background: rgba(255,209,102,0.12); color:var(--warning,#ffd166); }
        .badge-up    { background: rgba(0,229,192,0.1); color:var(--accent2); }

        /* ============================================
           RIGHT PANEL — Form
        ============================================ */
        .right-panel {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 48px 40px;
            background: var(--bg);
            overflow-y: auto;
        }

        .form-box {
            width: 100%;
            max-width: 380px;
            animation: fadeUp 0.7s 0.1s ease both;
        }

        .form-head {
            margin-bottom: 28px;
        }

        .form-head h3 {
            font-family: var(--font-h);
            font-size: 1.7rem;
            font-weight: 800;
            letter-spacing: -0.03em;
            color: var(--text);
            margin-bottom: 6px;
        }

        .form-head p {
            font-size: 0.85rem;
            color: var(--muted);
        }

        .form-head p a {
            color: var(--accent);
            text-decoration: none;
            font-weight: 500;
        }

        /* Tabs */
        .tabs {
            display: flex;
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 4px;
            margin-bottom: 24px;
            gap: 4px;
        }

        .tab {
            flex: 1;
            padding: 8px;
            border: none;
            border-radius: 7px;
            background: transparent;
            color: var(--muted);
            font-family: var(--font-h);
            font-size: 0.85rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }

        .tab.active {
            background: var(--accent);
            color: #fff;
            box-shadow: 0 2px 12px rgba(79,140,255,0.4);
        }

        /* Fields */
        .field {
            margin-bottom: 14px;
        }

        .field label {
            display: block;
            font-size: 0.72rem;
            font-weight: 600;
            color: var(--muted);
            text-transform: uppercase;
            letter-spacing: 0.08em;
            margin-bottom: 6px;
        }

        .field input,
        .field select {
            width: 100%;
            padding: 10px 14px;
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 8px;
            color: var(--text);
            font-family: var(--font-b);
            font-size: 0.9rem;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .field input:focus,
        .field select:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(79,140,255,0.12);
        }

        .field input::placeholder { color: var(--dim); }
        .field select option { background: var(--card); }

        .field-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }

        /* Password toggle */
        .pass-wrap {
            position: relative;
        }

        .pass-wrap input { padding-right: 40px; }

        .pass-toggle {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: var(--muted);
            cursor: pointer;
            font-size: 0.85rem;
            padding: 2px;
        }

        /* Submit button */
        .btn-login {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, var(--accent) 0%, #3a7aee 100%);
            color: #fff;
            border: none;
            border-radius: 8px;
            font-family: var(--font-h);
            font-size: 1rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s;
            margin-top: 6px;
            position: relative;
            overflow: hidden;
        }

        .btn-login::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(255,255,255,0.1), transparent);
            opacity: 0;
            transition: opacity 0.2s;
        }

        .btn-login:hover { transform: translateY(-2px); box-shadow: 0 6px 24px rgba(79,140,255,0.45); }
        .btn-login:hover::after { opacity: 1; }
        .btn-login:disabled { opacity: 0.5; cursor: not-allowed; transform: none; box-shadow: none; }

        /* Alert */
        .alert {
            padding: 10px 13px;
            border-radius: 8px;
            font-size: 0.83rem;
            margin-bottom: 14px;
            display: flex;
            align-items: center;
            gap: 7px;
        }

        .alert-success { background: rgba(0,229,192,0.1); border:1px solid rgba(0,229,192,0.3); color: var(--success); }
        .alert-error   { background: rgba(255,107,107,0.1); border:1px solid rgba(255,107,107,0.3); color: var(--error); }

        /* Divider */
        .divider {
            display: flex;
            align-items: center;
            gap: 10px;
            margin: 16px 0;
            color: var(--dim);
            font-size: 0.75rem;
        }

        .divider::before,
        .divider::after { content: ''; flex: 1; height: 1px; background: var(--border); }

        /* Back link */
        .back-link {
            display: flex;
            align-items: center;
            gap: 6px;
            color: var(--muted);
            font-size: 0.8rem;
            text-decoration: none;
            margin-top: 20px;
            transition: color 0.2s;
        }

        .back-link:hover { color: var(--accent); }

        /* Hint chip */
        .hint-chip {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 5px 11px;
            border-radius: 100px;
            background: rgba(79,140,255,0.08);
            border: 1px solid rgba(79,140,255,0.2);
            font-size: 0.72rem;
            color: var(--accent);
            font-family: var(--font-m);
            margin-bottom: 16px;
        }

        /* Animations */
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(18px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* Responsive — stack on mobile */
        @media (max-width: 820px) {
            html, body { overflow: auto; }
            .login-wrap { grid-template-columns: 1fr; height: auto; min-height: 100vh; }
            .left-panel { display: none; }
            .right-panel { padding: 40px 24px; min-height: 100vh; }
            .float-cards { display: none; }
        }
    </style>
</head>
<body>

<div class="login-wrap">

    <!-- ========== LEFT PANEL ========== -->
    <div class="left-panel">

        <div class="brand">
            <div class="brand-logo">🎓</div>
            <h1>Vel<span>Campus</span></h1>
            <p>Vel Tech R&D Institute · Student Portal</p>
        </div>

        <div class="hero-text">
            <div class="eyebrow">Academic Events Portal</div>
            <h2>Your campus,<br><span class="grad">your events.</span></h2>
            <p>Register for hackathons, workshops, cultural fests, seminars and more — all in one place.</p>

            <div class="feature-pills">
                <div class="pill"><div class="pill-icon">💻</div> Hackathons & Tech Competitions</div>
                <div class="pill"><div class="pill-icon">🎭</div> Cultural Fests & Performances</div>
                <div class="pill"><div class="pill-icon">🔧</div> Workshops & Skill Sessions</div>
                <div class="pill"><div class="pill-icon">📢</div> Seminars & Industry Talks</div>
            </div>
        </div>

        <!-- Floating event preview cards -->
        <div class="float-cards">
            <div class="float-card">
                <strong>HackVelTech 2025</strong>
                <span class="badge badge-hack">Hackathon</span><br>
                <span>Apr 10 · 200 seats · Upcoming</span>
            </div>
            <div class="float-card">
                <strong>Full Stack Bootcamp</strong>
                <span class="badge badge-ws">Workshop</span><br>
                <span>Apr 15 · 60 seats · Open</span>
            </div>
            <div class="float-card" style="display:flex;align-items:center;gap:8px">
                <span style="color:var(--accent2);font-family:var(--font-m);font-size:.65rem">✦ 6 events live</span>
                <span class="badge badge-up">Active</span>
            </div>
        </div>

        <div class="left-footer">
            © 2025 VelCampus · Vel Tech Rangarajan Dr. Sagunthala R&D Institute
        </div>

    </div>

    <!-- ========== RIGHT PANEL ========== -->
    <div class="right-panel">
        <div class="form-box">

            <div class="form-head">
                <h3 id="formTitle">Welcome back</h3>
                <p id="formSubtitle">Sign in to your student account</p>
            </div>

            <!-- Tabs -->
            <div class="tabs">
                <button class="tab active" id="tabLogin" onclick="switchTab('login')">Login</button>
                <button class="tab" id="tabSignup" onclick="switchTab('signup')">Sign Up</button>
            </div>

            <!-- Alert -->
            <div id="alertBox"></div>

            <!-- ===== LOGIN FORM ===== -->
            <div id="loginSection">
                <div class="hint-chip">
                    💡 Default password = your Roll Number
                </div>

                <div class="field">
                    <label>Roll Number</label>
                    <input type="text" id="loginRoll" placeholder="e.g. VT22CS001" autocomplete="username"
                           style="text-transform:uppercase" oninput="this.value=this.value.toUpperCase()">
                </div>

                <div class="field">
                    <label>Password</label>
                    <div class="pass-wrap">
                        <input type="password" id="loginPass" placeholder="Enter your password" autocomplete="current-password">
                        <button class="pass-toggle" onclick="togglePass('loginPass', this)" type="button">👁</button>
                    </div>
                </div>

                <button class="btn-login" id="loginBtn" onclick="doLogin()">
                    Sign In →
                </button>

                <div class="divider">or</div>

                <p style="text-align:center;font-size:.82rem;color:var(--muted)">
                    Don't have an account? <a href="#" onclick="switchTab('signup');return false" style="color:var(--accent);font-weight:600">Create one</a>
                </p>
            </div>

            <!-- ===== SIGNUP FORM ===== -->
            <div id="signupSection" style="display:none">

                <div class="field-row">
                    <div class="field">
                        <label>Full Name *</label>
                        <input type="text" id="suName" placeholder="Your name">
                    </div>
                    <div class="field">
                        <label>Roll Number *</label>
                        <input type="text" id="suRoll" placeholder="VT22CS001"
                               oninput="this.value=this.value.toUpperCase()">
                    </div>
                </div>

                <div class="field">
                    <label>Email Address *</label>
                    <input type="email" id="suEmail" placeholder="name@veltech.edu.in">
                </div>

                <div class="field-row">
                    <div class="field">
                        <label>Department *</label>
                        <select id="suDept">
                            <option value="">— Select —</option>
                            <option>Computer Science</option>
                            <option>Electronics & Communication</option>
                            <option>Electrical Engineering</option>
                            <option>Mechanical Engineering</option>
                            <option>Civil Engineering</option>
                            <option>Information Technology</option>
                            <option>Biotechnology</option>
                        </select>
                    </div>
                    <div class="field">
                        <label>Year *</label>
                        <select id="suYear">
                            <option value="">— Year —</option>
                            <option>1st Year</option>
                            <option>2nd Year</option>
                            <option>3rd Year</option>
                            <option>4th Year</option>
                        </select>
                    </div>
                </div>

                <div class="field">
                    <label>Phone Number *</label>
                    <input type="tel" id="suPhone" placeholder="10-digit mobile" maxlength="10">
                </div>

                <div class="field">
                    <label>Password * (min 6 characters)</label>
                    <div class="pass-wrap">
                        <input type="password" id="suPass" placeholder="Create a strong password">
                        <button class="pass-toggle" onclick="togglePass('suPass', this)" type="button">👁</button>
                    </div>
                </div>

                <button class="btn-login" id="signupBtn" onclick="doSignup()">
                    Create Account →
                </button>

                <p style="text-align:center;font-size:.82rem;color:var(--muted);margin-top:12px">
                    Already registered? <a href="#" onclick="switchTab('login');return false" style="color:var(--accent);font-weight:600">Sign in</a>
                </p>
            </div>

            <a href="index.php" class="back-link">
                ← Back to Events Portal
            </a>

        </div>
    </div>

</div>

<script>
// ============================================
// Tab switching
// ============================================
function switchTab(tab) {
    const isLogin = tab === 'login';
    document.getElementById('loginSection').style.display  = isLogin ? 'block' : 'none';
    document.getElementById('signupSection').style.display = isLogin ? 'none'  : 'block';
    document.getElementById('tabLogin').classList.toggle('active', isLogin);
    document.getElementById('tabSignup').classList.toggle('active', !isLogin);
    document.getElementById('alertBox').innerHTML = '';
    document.getElementById('formTitle').textContent    = isLogin ? 'Welcome back'            : 'Create account';
    document.getElementById('formSubtitle').textContent = isLogin ? 'Sign in to your student account' : 'Join VelCampus today';
}

// ============================================
// Password toggle
// ============================================
function togglePass(inputId, btn) {
    const input = document.getElementById(inputId);
    const show  = input.type === 'password';
    input.type  = show ? 'text' : 'password';
    btn.textContent = show ? '🙈' : '👁';
}

// ============================================
// Alert helper
// ============================================
function showAlert(msg, type) {
    document.getElementById('alertBox').innerHTML =
        `<div class="alert alert-${type}">${type === 'success' ? '🎉' : '⚠️'} ${msg}</div>`;
}

// ============================================
// Login
// ============================================
async function doLogin() {
    const roll = document.getElementById('loginRoll').value.trim().toUpperCase();
    const pass = document.getElementById('loginPass').value.trim();

    if (!roll || !pass) { showAlert('Enter roll number and password.', 'error'); return; }

    const btn = document.getElementById('loginBtn');
    btn.disabled = true;
    btn.textContent = 'Signing in...';

    try {
        const res  = await fetch('php/login.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ roll_number: roll, password: pass })
        });
        const data = await res.json();

        if (data.success) {
            showAlert('Login successful! Redirecting...', 'success');
            if (data.is_admin) {
                localStorage.setItem('velcampus_admin', 'true');
                localStorage.removeItem('velcampus_student');
            } else {
                localStorage.setItem('velcampus_student', JSON.stringify(data.student));
                localStorage.removeItem('velcampus_admin');
            }
            setTimeout(() => window.location.href = data.redirect, 1000);
        } else {
            showAlert(data.message, 'error');
            btn.disabled = false;
            btn.textContent = 'Sign In →';
        }
    } catch (e) {
        showAlert('Network error. Is XAMPP running?', 'error');
        btn.disabled = false;
        btn.textContent = 'Sign In →';
    }
}

// ============================================
// Sign Up
// ============================================
async function doSignup() {
    const payload = {
        name:          document.getElementById('suName').value.trim(),
        roll_number:   document.getElementById('suRoll').value.trim().toUpperCase(),
        email:         document.getElementById('suEmail').value.trim(),
        department:    document.getElementById('suDept').value,
        year_of_study: document.getElementById('suYear').value,
        phone:         document.getElementById('suPhone').value.trim(),
        password:      document.getElementById('suPass').value.trim()
    };

    for (const [k, v] of Object.entries(payload)) {
        if (!v) { showAlert(`Please fill in: ${k.replace(/_/g, ' ')}`, 'error'); return; }
    }

    const btn = document.getElementById('signupBtn');
    btn.disabled = true;
    btn.textContent = 'Creating account...';

    try {
        const res  = await fetch('php/signup.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(payload)
        });
        const data = await res.json();

        if (data.success) {
            showAlert(data.message + ' Switching to login...', 'success');
            setTimeout(() => { switchTab('login'); document.getElementById('loginRoll').value = payload.roll_number; }, 1800);
        } else {
            showAlert(data.message, 'error');
        }
    } catch (e) {
        showAlert('Network error. Is XAMPP running?', 'error');
    } finally {
        btn.disabled = false;
        btn.textContent = 'Create Account →';
    }
}

// ============================================
// Enter key support
// ============================================
document.addEventListener('keydown', (e) => {
    if (e.key !== 'Enter') return;
    const isLogin = document.getElementById('tabLogin').classList.contains('active');
    if (isLogin) doLogin(); else doSignup();
});

// ============================================
// Auto-fill roll if already logged in
// ============================================
const saved = JSON.parse(localStorage.getItem('velcampus_student') || 'null');
if (saved) {
    document.getElementById('loginRoll').value = saved.roll_number;
}
</script>

</body>
</html>

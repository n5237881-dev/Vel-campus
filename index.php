<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Campus Events Portal — Vel Tech</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        /* ---- Auth Modal ---- */
        .auth-tabs { display:flex; border-bottom:1px solid var(--border); margin-bottom:20px; }
        .auth-tab  { flex:1; padding:10px; background:transparent; border:none; color:var(--text-muted);
                     font-family:var(--font-head); font-size:.9rem; font-weight:600; cursor:pointer;
                     border-bottom:2px solid transparent; transition:all .2s; }
        .auth-tab.active { color:var(--accent); border-bottom-color:var(--accent); }

        .user-chip { display:flex; align-items:center; gap:8px; padding:5px 12px;
                     border-radius:100px; background:var(--accent-dim); border:1px solid rgba(79,140,255,.3);
                     color:var(--accent); font-size:.82rem; font-weight:600; cursor:pointer; }
        .user-chip .dot { width:7px;height:7px;border-radius:50%;background:var(--success);box-shadow:0 0 5px var(--success); }

        .logout-btn { padding:5px 12px; border-radius:100px; background:transparent;
                      border:1px solid var(--border); color:var(--text-muted); font-size:.82rem;
                      cursor:pointer; transition:all .2s; }
        .logout-btn:hover { border-color:var(--error); color:var(--error); }
    </style>
</head>
<body>

<!-- ============ NAVBAR ============ -->
<nav class="navbar">
    <div class="nav-brand">
        <div class="logo-icon">🎓</div>
        <h1>Vel<span>Campus</span></h1>
    </div>
    <div class="nav-links" id="navLinks">
        <a href="index.php" class="active">Events</a>
        <a href="registrations.php" class="hide-mobile">My Registrations</a>
        <a href="admin.php" class="btn-primary">Admin Panel</a>
        <!-- Auth buttons injected by JS -->
    </div>
</nav>

<!-- ============ HERO ============ -->
<section class="hero">
    <div class="hero-glow"></div>
    <div class="hero-tag">
        <svg width="10" height="10" viewBox="0 0 10 10" fill="currentColor"><circle cx="5" cy="5" r="5"/></svg>
        Vel Tech Rangarajan Dr. Sagunthala R&D Institute
    </div>
    <h2>Academic <span class="gradient-text">Campus Events</span><br>&amp; Registration Portal</h2>
    <p>Discover workshops, hackathons, cultural fests and more. Register in one click.</p>
</section>

<!-- ============ STATS ============ -->
<div class="stats-bar">
    <div class="stat-item">
        <div class="stat-num" id="statsTotal">0</div>
        <div class="stat-label">Total Events</div>
    </div>
    <div class="stat-item">
        <div class="stat-num" id="statsUpcoming">0</div>
        <div class="stat-label">Upcoming</div>
    </div>
    <div class="stat-item">
        <div class="stat-num" id="statsRegs">0</div>
        <div class="stat-label">Registrations</div>
    </div>
</div>

<!-- ============ FILTER BAR ============ -->
<div class="filter-bar">
    <div class="filter-tabs">
        <button class="filter-tab active" data-category="All">All</button>
        <button class="filter-tab" data-category="Hackathon">💻 Hackathon</button>
        <button class="filter-tab" data-category="Workshop">🔧 Workshop</button>
        <button class="filter-tab" data-category="Seminar">📢 Seminar</button>
        <button class="filter-tab" data-category="Cultural">🎭 Cultural</button>
        <button class="filter-tab" data-category="Sports">🏆 Sports</button>
        <button class="filter-tab" data-category="Technical">⚡ Technical</button>
    </div>
    <div class="search-box">
        <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
        </svg>
        <input type="text" id="searchInput" placeholder="Search events...">
    </div>
</div>

<!-- ============ EVENTS GRID ============ -->
<section class="events-section">
    <div class="events-grid" id="eventsGrid"></div>
</section>

<!-- ============ EVENT / REGISTER MODAL ============ -->
<div class="modal-overlay" id="modalOverlay">
    <div class="modal">
        <div class="modal-header">
            <h3 id="modalTitle">Event Details</h3>
            <button class="modal-close" id="modalClose">✕</button>
        </div>
        <div class="modal-body" id="modalBody"></div>
    </div>
</div>

<!-- ============ AUTH MODAL ============ -->
<div class="modal-overlay" id="authOverlay">
    <div class="modal" style="max-width:440px">
        <div class="modal-header">
            <h3 id="authModalTitle">Student Login</h3>
            <button class="modal-close" id="authModalClose">✕</button>
        </div>
        <div class="modal-body">
            <div class="auth-tabs">
                <button class="auth-tab active" id="tabLogin" onclick="switchAuthTab('login')">Login</button>
                <button class="auth-tab" id="tabSignup" onclick="switchAuthTab('signup')">Sign Up</button>
            </div>
            <div id="authAlert"></div>

            <!-- LOGIN FORM -->
            <div id="loginForm">
                <div class="form-group" style="margin-bottom:14px">
                    <label>Roll Number</label>
                    <input type="text" id="loginRoll" placeholder="e.g. VT22CS001" style="padding:9px 12px;background:var(--bg);border:1px solid var(--border);border-radius:8px;color:var(--text);font-family:var(--font-body);font-size:.9rem;outline:none;width:100%;text-transform:uppercase">
                </div>
                <div class="form-group" style="margin-bottom:20px">
                    <label>Password</label>
                    <input type="password" id="loginPass" placeholder="Your password">
                </div>
                <button class="btn-submit" onclick="doLogin()">Login →</button>
                <p style="text-align:center;margin-top:12px;font-size:.8rem;color:var(--text-muted)">
                    Default password for sample accounts = Roll Number
                </p>
            </div>

            <!-- SIGNUP FORM -->
            <div id="signupForm" style="display:none">
                <div class="form-grid">
                    <div class="form-group">
                        <label>Full Name *</label>
                        <input type="text" id="suName" placeholder="Your full name">
                    </div>
                    <div class="form-group">
                        <label>Roll Number *</label>
                        <input type="text" id="suRoll" placeholder="e.g. VT22CS001" style="text-transform:uppercase">
                    </div>
                    <div class="form-group full-width">
                        <label>Email *</label>
                        <input type="email" id="suEmail" placeholder="name@veltech.edu.in">
                    </div>
                    <div class="form-group">
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
                    <div class="form-group">
                        <label>Year *</label>
                        <select id="suYear">
                            <option value="">— Select —</option>
                            <option>1st Year</option><option>2nd Year</option>
                            <option>3rd Year</option><option>4th Year</option>
                        </select>
                    </div>
                    <div class="form-group full-width">
                        <label>Phone *</label>
                        <input type="tel" id="suPhone" placeholder="10-digit number" maxlength="10">
                    </div>
                    <div class="form-group full-width">
                        <label>Password * (min 6 chars)</label>
                        <input type="password" id="suPass" placeholder="Create a password">
                    </div>
                </div>
                <button class="btn-submit" style="margin-top:14px" onclick="doSignup()">Create Account →</button>
            </div>
        </div>
    </div>
</div>

<!-- ============ FOOTER ============ -->
<footer>
    <span>VelCampus</span> · Academic Campus Event &amp; Registration Portal · Vel Tech R&D Institute
</footer>

<script src="js/app.js"></script>
<script>
// ============================================
// Auth System
// ============================================
let currentStudent = JSON.parse(localStorage.getItem('velcampus_student') || 'null');

function updateNavAuth() {
    const nav = document.getElementById('navLinks');
    // Remove old auth buttons
    document.querySelectorAll('.nav-auth').forEach(el => el.remove());

    if (currentStudent) {
        nav.insertAdjacentHTML('beforeend', `
            <span class="user-chip nav-auth" onclick="showMyRegistrations()">
                <span class="dot"></span> ${currentStudent.name.split(' ')[0]}
            </span>
            <button class="logout-btn nav-auth" onclick="doLogout()">Logout</button>
        `);
    } else {
        nav.insertAdjacentHTML('beforeend', `
            <button class="btn-register nav-auth" onclick="openAuthModal()" style="padding:6px 16px;font-size:.85rem">Login / Sign Up</button>
        `);
    }
}

function openAuthModal() {
    document.getElementById('authOverlay').classList.add('active');
    document.body.style.overflow = 'hidden';
    document.getElementById('authAlert').innerHTML = '';
}

function closeAuthModal() {
    document.getElementById('authOverlay').classList.remove('active');
    document.body.style.overflow = '';
}

function switchAuthTab(tab) {
    document.getElementById('loginForm').style.display  = tab === 'login'  ? 'block' : 'none';
    document.getElementById('signupForm').style.display = tab === 'signup' ? 'block' : 'none';
    document.getElementById('tabLogin').classList.toggle('active', tab === 'login');
    document.getElementById('tabSignup').classList.toggle('active', tab === 'signup');
    document.getElementById('authAlert').innerHTML = '';
    document.getElementById('authModalTitle').textContent = tab === 'login' ? 'Student Login' : 'Create Account';
}

async function doLogin() {
    const roll = document.getElementById('loginRoll').value.trim().toUpperCase();
    const pass = document.getElementById('loginPass').value.trim();
    const alert = document.getElementById('authAlert');

    if (!roll || !pass) { alert.innerHTML = `<div class="alert alert-error">⚠️ Enter roll number and password.</div>`; return; }

    try {
        const res = await fetch('php/login.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({roll_number: roll, password: pass})
        });
        const data = await res.json();
        if (data.success) {
            currentStudent = data.student;
            localStorage.setItem('velcampus_student', JSON.stringify(currentStudent));
            closeAuthModal();
            updateNavAuth();
            alert.innerHTML = '';
        } else {
            alert.innerHTML = `<div class="alert alert-error">⚠️ ${data.message}</div>`;
        }
    } catch(e) {
        alert.innerHTML = `<div class="alert alert-error">⚠️ Network error.</div>`;
    }
}

async function doSignup() {
    const alertEl = document.getElementById('authAlert');
    const payload = {
        name:         document.getElementById('suName').value.trim(),
        roll_number:  document.getElementById('suRoll').value.trim().toUpperCase(),
        email:        document.getElementById('suEmail').value.trim(),
        department:   document.getElementById('suDept').value,
        year_of_study:document.getElementById('suYear').value,
        phone:        document.getElementById('suPhone').value.trim(),
        password:     document.getElementById('suPass').value.trim()
    };

    for (const [k,v] of Object.entries(payload)) {
        if (!v) { alertEl.innerHTML = `<div class="alert alert-error">⚠️ Missing: ${k.replace(/_/g,' ')}</div>`; return; }
    }

    try {
        const res = await fetch('php/signup.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify(payload)
        });
        const data = await res.json();
        if (data.success) {
            alertEl.innerHTML = `<div class="alert alert-success">🎉 ${data.message}</div>`;
            setTimeout(() => switchAuthTab('login'), 1500);
        } else {
            alertEl.innerHTML = `<div class="alert alert-error">⚠️ ${data.message}</div>`;
        }
    } catch(e) {
        alertEl.innerHTML = `<div class="alert alert-error">⚠️ Network error.</div>`;
    }
}

async function doLogout() {
    await fetch('php/logout.php');
    currentStudent = null;
    localStorage.removeItem('velcampus_student');
    updateNavAuth();
}

function showMyRegistrations() {
    window.location.href = 'registrations.php';
}

// Pre-fill register form with logged-in student data
window.addEventListener('openRegisterPrefill', (e) => {
    if (!currentStudent) return;
    setTimeout(() => {
        const fields = {
            student_name:  currentStudent.name,
            student_email: currentStudent.email,
            roll_number:   currentStudent.roll_number,
            department:    currentStudent.department,
            year_of_study: currentStudent.year_of_study,
            phone:         currentStudent.phone
        };
        for (const [name, val] of Object.entries(fields)) {
            const el = document.querySelector(`[name="${name}"]`);
            if (el) {
                el.value = val;
                if (el.tagName === 'SELECT') {
                    [...el.options].forEach(o => { if (o.value === val) o.selected = true; });
                }
            }
        }
    }, 100);
});

// Override openRegisterModal to require login
const _origOpenRegister = window.openRegisterModal;
window.openRegisterModal = function(eventId) {
    if (!currentStudent) {
        openAuthModal();
        return;
    }
    _origOpenRegister(eventId);
    window.dispatchEvent(new CustomEvent('openRegisterPrefill'));
};

// Close auth modal
document.getElementById('authModalClose').addEventListener('click', closeAuthModal);
document.getElementById('authOverlay').addEventListener('click', (e) => {
    if (e.target.id === 'authOverlay') closeAuthModal();
});
document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') closeAuthModal();
});

// Enter key on login
document.addEventListener('keydown', (e) => {
    if (e.key === 'Enter' && document.getElementById('authOverlay').classList.contains('active')) {
        const tab = document.getElementById('tabLogin').classList.contains('active') ? 'login' : 'signup';
        if (tab === 'login') doLogin();
    }
});

// Init
updateNavAuth();
</script>
</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard — VelCampus</title>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:wght@300;400;500&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg:      #080a0f;
            --panel:   #0d1017;
            --card:    #111520;
            --border:  #1a2035;
            --glow:    #1e2d50;
            --accent:  #4f8cff;
            --accent2: #00e5c0;
            --error:   #ff6b6b;
            --warning: #ffd166;
            --success: #00e5c0;
            --text:    #e8eaf2;
            --muted:   #6b7590;
            --dim:     #2a3050;
            --font-h:  'Syne', sans-serif;
            --font-b:  'DM Sans', sans-serif;
            --font-m:  'JetBrains Mono', monospace;
            --radius:  12px;
            --radius-s:8px;
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: var(--font-b);
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
        }

        /* ---- NAVBAR ---- */
        .navbar {
            position: sticky; top: 0; z-index: 100;
            background: rgba(8,10,15,0.9);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--border);
            height: 60px;
            display: flex; align-items: center;
            justify-content: space-between;
            padding: 0 28px;
        }

        .nav-brand {
            display: flex; align-items: center; gap: 10px;
        }

        .nav-logo {
            width: 34px; height: 34px;
            background: linear-gradient(135deg, var(--accent), var(--accent2));
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            font-size: 17px;
        }

        .nav-brand h1 {
            font-family: var(--font-h); font-size: 1rem; font-weight: 800;
            letter-spacing: -0.02em;
        }

        .nav-brand h1 span { color: var(--accent); }

        .admin-badge {
            font-family: var(--font-m); font-size: 0.65rem;
            padding: 2px 8px; border-radius: 100px;
            background: rgba(0,229,192,0.1);
            border: 1px solid rgba(0,229,192,0.3);
            color: var(--accent2);
            text-transform: uppercase; letter-spacing: 0.1em;
        }

        .nav-right { display: flex; align-items: center; gap: 10px; }

        .nav-btn {
            padding: 6px 14px; border-radius: var(--radius-s);
            border: 1px solid var(--border); background: transparent;
            color: var(--muted); font-family: var(--font-b);
            font-size: 0.82rem; cursor: pointer; transition: all .2s;
            text-decoration: none; display: inline-block;
        }

        .nav-btn:hover { border-color: var(--accent); color: var(--accent); }

        .nav-btn.danger:hover { border-color: var(--error); color: var(--error); }

        /* ---- LAYOUT ---- */
        .layout {
            display: grid;
            grid-template-columns: 220px 1fr;
            min-height: calc(100vh - 60px);
        }

        /* ---- SIDEBAR ---- */
        .sidebar {
            background: var(--panel);
            border-right: 1px solid var(--border);
            padding: 24px 16px;
            position: sticky;
            top: 60px;
            height: calc(100vh - 60px);
            overflow-y: auto;
        }

        .sidebar-label {
            font-family: var(--font-m); font-size: 0.65rem;
            color: var(--dim); text-transform: uppercase;
            letter-spacing: 0.12em; margin-bottom: 10px;
            padding-left: 8px;
        }

        .sidebar-nav { display: flex; flex-direction: column; gap: 4px; margin-bottom: 28px; }

        .sidebar-link {
            display: flex; align-items: center; gap: 10px;
            padding: 9px 12px; border-radius: var(--radius-s);
            color: var(--muted); font-size: 0.875rem; font-weight: 500;
            cursor: pointer; transition: all .2s; border: none;
            background: transparent; width: 100%; text-align: left;
        }

        .sidebar-link:hover { background: var(--card); color: var(--text); }
        .sidebar-link.active { background: rgba(79,140,255,0.12); color: var(--accent); font-weight: 600; }
        .sidebar-link .icon { font-size: 15px; width: 18px; text-align: center; }

        /* ---- MAIN ---- */
        .main { padding: 28px; overflow-y: auto; }

        .page { display: none; }
        .page.active { display: block; }

        /* ---- PAGE HEADER ---- */
        .page-header {
            display: flex; align-items: center;
            justify-content: space-between;
            margin-bottom: 24px; flex-wrap: wrap; gap: 12px;
        }

        .page-header h2 {
            font-family: var(--font-h); font-size: 1.5rem;
            font-weight: 800; letter-spacing: -0.02em;
        }

        /* ---- STAT CARDS ---- */
        .stats-row { display: grid; grid-template-columns: repeat(4, 1fr); gap: 14px; margin-bottom: 24px; }

        .stat-card {
            background: var(--card); border: 1px solid var(--border);
            border-radius: var(--radius); padding: 18px 20px;
        }

        .stat-card .stat-num {
            font-family: var(--font-h); font-size: 2rem; font-weight: 800;
            color: var(--accent); line-height: 1;
        }

        .stat-card .stat-label {
            font-size: 0.75rem; color: var(--muted);
            text-transform: uppercase; letter-spacing: 0.08em;
            margin-top: 4px;
        }

        /* ---- TABLE ---- */
        .table-card {
            background: var(--card); border: 1px solid var(--border);
            border-radius: var(--radius); overflow: hidden;
        }

        .table-card-header {
            padding: 16px 20px; border-bottom: 1px solid var(--border);
            display: flex; align-items: center; justify-content: space-between;
            flex-wrap: wrap; gap: 10px;
        }

        .table-card-header h3 {
            font-family: var(--font-h); font-size: 1rem; font-weight: 700;
        }

        .search-input {
            padding: 7px 12px; background: var(--bg);
            border: 1px solid var(--border); border-radius: var(--radius-s);
            color: var(--text); font-family: var(--font-b); font-size: 0.85rem;
            outline: none; width: 200px; transition: border-color .2s;
        }

        .search-input:focus { border-color: var(--accent); }
        .search-input::placeholder { color: var(--dim); }

        table { width: 100%; border-collapse: collapse; }

        thead { background: rgba(255,255,255,0.02); }

        th {
            padding: 11px 16px; text-align: left;
            font-size: 0.72rem; font-weight: 600;
            color: var(--muted); text-transform: uppercase;
            letter-spacing: 0.07em; border-bottom: 1px solid var(--border);
        }

        td {
            padding: 12px 16px; font-size: 0.875rem;
            border-bottom: 1px solid rgba(255,255,255,0.03);
            color: var(--text);
        }

        tr:last-child td { border-bottom: none; }
        tr:hover td { background: rgba(255,255,255,0.015); }

        .roll-tag {
            font-family: var(--font-m); font-size: 0.75rem;
            padding: 2px 8px; border-radius: 4px;
            background: rgba(79,140,255,0.1); color: var(--accent);
        }

        /* Category badges */
        .cat-badge {
            padding: 3px 9px; border-radius: 100px;
            font-size: 0.68rem; font-family: var(--font-m);
            font-weight: 500; text-transform: uppercase; letter-spacing: 0.05em;
        }

        .cat-Technical  { background:rgba(79,140,255,.12); color:#4f8cff; border:1px solid rgba(79,140,255,.25); }
        .cat-Cultural   { background:rgba(255,107,107,.12); color:#ff6b6b; border:1px solid rgba(255,107,107,.25); }
        .cat-Sports     { background:rgba(0,229,192,.1); color:#00e5c0; border:1px solid rgba(0,229,192,.25); }
        .cat-Workshop   { background:rgba(255,209,102,.1); color:#ffd166; border:1px solid rgba(255,209,102,.25); }
        .cat-Seminar    { background:rgba(162,89,255,.1); color:#a259ff; border:1px solid rgba(162,89,255,.25); }
        .cat-Hackathon  { background:rgba(255,140,0,.1); color:#ff8c00; border:1px solid rgba(255,140,0,.25); }

        /* Status badge */
        .status-badge {
            font-size: 0.72rem; padding: 3px 9px; border-radius: 4px;
        }

        .status-Upcoming  { background:rgba(0,229,192,.1);color:var(--success); }
        .status-Completed { background:rgba(255,255,255,.05);color:var(--muted); }
        .status-Cancelled { background:rgba(255,107,107,.1);color:var(--error); }
        .status-Ongoing   { background:rgba(255,209,102,.1);color:var(--warning); }

        /* Action buttons */
        .act-btn {
            padding: 4px 10px; border-radius: 6px;
            border: 1px solid var(--border); background: transparent;
            color: var(--muted); font-size: 0.72rem;
            cursor: pointer; transition: all .2s;
        }

        .act-btn:hover { border-color: var(--accent); color: var(--accent); }
        .act-btn.danger:hover { border-color: var(--error); color: var(--error); }
        .act-btn.success:hover { border-color: var(--success); color: var(--success); }

        .act-cell { display: flex; gap: 6px; flex-wrap: wrap; }

        /* ---- FORM CARD ---- */
        .form-card {
            background: var(--card); border: 1px solid var(--border);
            border-radius: var(--radius); padding: 28px; max-width: 700px;
        }

        .form-card h3 {
            font-family: var(--font-h); font-size: 1.1rem; font-weight: 700;
            margin-bottom: 6px;
        }

        .form-card .subtitle {
            font-size: 0.82rem; color: var(--muted); margin-bottom: 24px;
            padding-bottom: 18px; border-bottom: 1px solid var(--border);
        }

        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px; }
        .form-row.single { grid-template-columns: 1fr; }
        .form-row.triple { grid-template-columns: 1fr 1fr 1fr; }

        .field { display: flex; flex-direction: column; gap: 5px; }

        .field label {
            font-size: 0.72rem; font-weight: 600; color: var(--muted);
            text-transform: uppercase; letter-spacing: 0.07em;
        }

        .field input, .field select, .field textarea {
            padding: 9px 12px; background: var(--bg);
            border: 1px solid var(--border); border-radius: var(--radius-s);
            color: var(--text); font-family: var(--font-b); font-size: 0.9rem;
            outline: none; transition: border-color .2s;
        }

        .field input:focus, .field select:focus, .field textarea:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(79,140,255,0.1);
        }

        .field textarea { resize: vertical; min-height: 80px; }
        .field select option { background: var(--card); }

        .btn-primary {
            padding: 10px 24px; background: var(--accent); color: #fff;
            border: none; border-radius: var(--radius-s);
            font-family: var(--font-h); font-size: 0.95rem; font-weight: 700;
            cursor: pointer; transition: all .2s;
        }

        .btn-primary:hover { background: #3a7aee; transform: translateY(-1px); box-shadow: 0 4px 16px rgba(79,140,255,.4); }
        .btn-primary:disabled { opacity: .5; cursor: not-allowed; transform: none; box-shadow: none; }

        /* ---- ALERT ---- */
        .alert {
            padding: 10px 14px; border-radius: var(--radius-s);
            font-size: 0.83rem; margin-bottom: 16px;
            display: flex; align-items: center; gap: 7px;
        }

        .alert-success { background:rgba(0,229,192,.1); border:1px solid rgba(0,229,192,.3); color:var(--success); }
        .alert-error   { background:rgba(255,107,107,.1); border:1px solid rgba(255,107,107,.3); color:var(--error); }

        /* ---- LOADING / EMPTY ---- */
        .loading-state, .empty-state {
            display: flex; flex-direction: column;
            align-items: center; justify-content: center;
            padding: 60px 20px; gap: 12px;
            color: var(--muted); text-align: center;
        }

        .spinner {
            width: 36px; height: 36px;
            border: 3px solid var(--border);
            border-top-color: var(--accent);
            border-radius: 50%;
            animation: spin .7s linear infinite;
        }

        @keyframes spin { to { transform: rotate(360deg); } }

        .empty-icon { font-size: 2.5rem; }

        /* ---- MODAL ---- */
        .modal-overlay {
            position: fixed; inset: 0;
            background: rgba(0,0,0,0.75);
            backdrop-filter: blur(8px);
            z-index: 200;
            display: flex; align-items: center; justify-content: center;
            padding: 20px;
            opacity: 0; pointer-events: none;
            transition: opacity .25s;
        }

        .modal-overlay.active { opacity: 1; pointer-events: all; }

        .modal {
            background: var(--card); border: 1px solid var(--glow);
            border-radius: 16px; width: 100%; max-width: 500px;
            max-height: 90vh; overflow-y: auto;
            transform: translateY(20px) scale(.97);
            transition: transform .25s;
            box-shadow: 0 8px 40px rgba(0,0,0,.5);
        }

        .modal-overlay.active .modal { transform: translateY(0) scale(1); }

        .modal-head {
            padding: 20px 24px;
            border-bottom: 1px solid var(--border);
            display: flex; align-items: center; justify-content: space-between;
        }

        .modal-head h3 { font-family: var(--font-h); font-size: 1.05rem; font-weight: 700; }

        .modal-close {
            width: 30px; height: 30px; border-radius: 8px;
            background: rgba(255,255,255,.05); border: 1px solid var(--border);
            color: var(--muted); cursor: pointer; font-size: 1rem;
            display: flex; align-items: center; justify-content: center;
            transition: all .2s;
        }

        .modal-close:hover { background: var(--error); border-color: var(--error); color: #fff; }

        .modal-body { padding: 20px 24px 24px; }

        /* ---- CSV button ---- */
        .csv-btn {
            padding: 4px 10px; border-radius: 6px;
            border: 1px solid rgba(255,209,102,.35);
            background: transparent; color: var(--warning);
            font-size: 0.72rem; cursor: pointer;
            text-decoration: none; display: inline-block;
            transition: all .2s;
        }

        .csv-btn:hover { background: rgba(255,209,102,.1); color: var(--warning); }

        @media(max-width:900px) {
            .layout { grid-template-columns: 1fr; }
            .sidebar { display: none; }
            .stats-row { grid-template-columns: 1fr 1fr; }
        }

        @media(max-width:480px) {
            .stats-row { grid-template-columns: 1fr; }
            .form-row, .form-row.triple { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar">
    <div class="nav-brand">
        <div class="nav-logo">🎓</div>
        <h1>Vel<span>Campus</span></h1>
        <span class="admin-badge">Admin</span>
    </div>
    <div class="nav-right">
        <a href="index.php" class="nav-btn">← Portal</a>
        <button class="nav-btn danger" onclick="adminLogout()">Logout</button>
    </div>
</nav>

<div class="layout">

    <!-- SIDEBAR -->
    <aside class="sidebar">
        <div class="sidebar-label">Dashboard</div>
        <div class="sidebar-nav">
            <button class="sidebar-link active" onclick="showPage('overview')">
                <span class="icon">📊</span> Overview
            </button>
            <button class="sidebar-link" onclick="showPage('events')">
                <span class="icon">📋</span> All Events
            </button>
            <button class="sidebar-link" onclick="showPage('add')">
                <span class="icon">➕</span> Add Event
            </button>
            <button class="sidebar-link" onclick="showPage('registrations')">
                <span class="icon">👥</span> Registrations
            </button>
            <button class="sidebar-link" onclick="showPage('students')">
                <span class="icon">🎓</span> Students
            </button>
        </div>
    </aside>

    <!-- MAIN CONTENT -->
    <main class="main">

        <!-- ======== OVERVIEW PAGE ======== -->
        <div class="page active" id="page-overview">
            <div class="page-header">
                <h2>Dashboard Overview</h2>
                <span style="font-family:var(--font-m);font-size:.75rem;color:var(--muted)" id="currentDate"></span>
            </div>

            <div class="stats-row" id="statsRow">
                <div class="stat-card"><div class="stat-num" id="st-total">—</div><div class="stat-label">Total Events</div></div>
                <div class="stat-card"><div class="stat-num" id="st-upcoming">—</div><div class="stat-label">Upcoming</div></div>
                <div class="stat-card"><div class="stat-num" id="st-regs">—</div><div class="stat-label">Registrations</div></div>
                <div class="stat-card"><div class="stat-num" id="st-students">—</div><div class="stat-label">Students</div></div>
            </div>

            <div class="table-card">
                <div class="table-card-header">
                    <h3>Recent Events</h3>
                    <button class="act-btn" onclick="showPage('events')">View All →</button>
                </div>
                <div id="overviewTable"><div class="loading-state"><div class="spinner"></div></div></div>
            </div>
        </div>

        <!-- ======== ALL EVENTS PAGE ======== -->
        <div class="page" id="page-events">
            <div class="page-header">
                <h2>All Events</h2>
                <button class="btn-primary" onclick="showPage('add')" style="font-size:.85rem;padding:8px 18px">+ Add Event</button>
            </div>

            <div class="table-card">
                <div class="table-card-header">
                    <h3>Events List</h3>
                    <input type="text" class="search-input" placeholder="Search events..." id="eventSearch" oninput="filterEvents()">
                </div>
                <div id="eventsTableBody"><div class="loading-state"><div class="spinner"></div></div></div>
            </div>
        </div>

        <!-- ======== ADD EVENT PAGE ======== -->
        <div class="page" id="page-add">
            <div class="page-header"><h2>Add New Event</h2></div>

            <div class="form-card">
                <h3>Event Details</h3>
                <p class="subtitle">Fill in all fields to create a new campus event.</p>
                <div id="addAlert"></div>

                <div class="form-row">
                    <div class="field"><label>Event Title *</label><input type="text" id="ef_title" placeholder="e.g. HackVelTech 2026"></div>
                    <div class="field"><label>Category *</label>
                        <select id="ef_category">
                            <option value="">— Select Category —</option>
                            <option>Technical</option><option>Cultural</option><option>Sports</option>
                            <option>Workshop</option><option>Seminar</option><option>Hackathon</option>
                        </select>
                    </div>
                </div>
                <div class="form-row single">
                    <div class="field"><label>Description *</label>
                        <textarea id="ef_description" placeholder="Describe the event..."></textarea>
                    </div>
                </div>
                <div class="form-row single">
                    <div class="field"><label>Venue *</label><input type="text" id="ef_venue" placeholder="e.g. Seminar Hall Block-A"></div>
                </div>
                <div class="form-row triple">
                    <div class="field"><label>Event Date *</label><input type="date" id="ef_event_date"></div>
                    <div class="field"><label>Event Time *</label><input type="time" id="ef_event_time"></div>
                    <div class="field"><label>Max Participants *</label><input type="number" id="ef_max" placeholder="100" min="1"></div>
                </div>
                <div class="form-row">
                    <div class="field"><label>Registration Deadline *</label><input type="date" id="ef_deadline"></div>
                    <div class="field"><label>Organizer *</label><input type="text" id="ef_organizer" placeholder="e.g. Dr. K Jayanthi"></div>
                </div>
                <div class="form-row single">
                    <div class="field"><label>Department *</label>
                        <select id="ef_department">
                            <option value="">— Select —</option>
                            <option>Computer Science</option><option>Electronics & Communication</option>
                            <option>Electrical Engineering</option><option>Mechanical Engineering</option>
                            <option>Civil Engineering</option><option>Information Technology</option>
                            <option>Biotechnology</option><option>All Departments</option>
                            <option>Sports Committee</option><option>Student Council</option>
                        </select>
                    </div>
                </div>
                <button class="btn-primary" onclick="submitAddEvent()" id="addBtn">Create Event →</button>
            </div>
        </div>

        <!-- ======== REGISTRATIONS PAGE ======== -->
        <div class="page" id="page-registrations">
            <div class="page-header"><h2>Registrations</h2></div>

            <div class="form-card" style="margin-bottom:20px;padding:18px 24px">
                <div style="display:flex;gap:12px;align-items:flex-end;flex-wrap:wrap">
                    <div class="field" style="flex:1;min-width:180px">
                        <label>Select Event</label>
                        <select id="regEventSelect" onchange="loadRegistrations()">
                            <option value="">— Choose an event —</option>
                        </select>
                    </div>
                    <a id="csvDownloadBtn" href="#" class="csv-btn" style="padding:9px 16px;font-size:.82rem;display:none">⬇ Export CSV</a>
                </div>
            </div>

            <div class="table-card">
                <div class="table-card-header">
                    <h3>Registration List</h3>
                    <span id="regCount" style="font-family:var(--font-m);font-size:.75rem;color:var(--muted)"></span>
                </div>
                <div id="regsTableBody">
                    <div class="empty-state"><div class="empty-icon">👆</div><p>Select an event above</p></div>
                </div>
            </div>
        </div>

        <!-- ======== STUDENTS PAGE ======== -->
        <div class="page" id="page-students">
            <div class="page-header"><h2>Students</h2></div>
            <div style="display:grid;grid-template-columns:340px 1fr;gap:20px;align-items:start;flex-wrap:wrap">

                <!-- Create Student Form -->
                <div class="form-card" style="max-width:100%">
                    <h3>Add New Student</h3>
                    <p class="subtitle">Create a student account manually.</p>
                    <div id="createStudentAlert"></div>

                    <div class="field" style="margin-bottom:12px">
                        <label>Full Name *</label>
                        <input type="text" id="cs_name" placeholder="e.g. Arjun Prabhu">
                    </div>
                    <div class="field" style="margin-bottom:12px">
                        <label>Roll Number *</label>
                        <input type="text" id="cs_roll" placeholder="e.g. VT22CS001" oninput="this.value=this.value.toUpperCase()">
                    </div>
                    <div class="field" style="margin-bottom:12px">
                        <label>Email *</label>
                        <input type="email" id="cs_email" placeholder="name@veltech.edu.in">
                    </div>
                    <div class="field" style="margin-bottom:12px">
                        <label>Department *</label>
                        <select id="cs_dept">
                            <option value="">Select</option>
                            <option>Computer Science</option>
                            <option>Electronics & Communication</option>
                            <option>Electrical Engineering</option>
                            <option>Mechanical Engineering</option>
                            <option>Civil Engineering</option>
                            <option>Information Technology</option>
                            <option>Biotechnology</option>
                        </select>
                    </div>
                    <div class="field" style="margin-bottom:12px">
                        <label>Year *</label>
                        <select id="cs_year">
                            <option value="">Select</option>
                            <option>1st Year</option><option>2nd Year</option>
                            <option>3rd Year</option><option>4th Year</option>
                        </select>
                    </div>
                    <div class="field" style="margin-bottom:12px">
                        <label>Phone *</label>
                        <input type="tel" id="cs_phone" placeholder="10-digit number" maxlength="10">
                    </div>
                    <div class="field" style="margin-bottom:18px">
                        <label>Password</label>
                        <input type="text" id="cs_pass" placeholder="Leave blank = Roll Number">
                        <span style="font-size:.7rem;color:var(--muted);margin-top:3px">Default password will be the Roll Number</span>
                    </div>
                    <button class="btn-primary" style="width:100%" onclick="createStudent()" id="createStudentBtn">Create Student</button>
                </div>

                <!-- Students Table -->
                <div class="table-card">
                    <div class="table-card-header">
                        <h3>All Students</h3>
                        <input type="text" class="search-input" placeholder="Search..." id="studentSearch" oninput="filterStudents()">
                    </div>
                    <div id="studentsTableBody"><div class="loading-state"><div class="spinner"></div></div></div>
                </div>
            </div>
        </div>

    </main>
</div>

<!-- ===== EDIT EVENT MODAL ===== -->
<div class="modal-overlay" id="editModal">
    <div class="modal">
        <div class="modal-head">
            <h3>Edit Event</h3>
            <button class="modal-close" onclick="closeModal('editModal')">✕</button>
        </div>
        <div class="modal-body">
            <div id="editAlert"></div>
            <input type="hidden" id="edit_id">
            <div class="form-row single">
                <div class="field"><label>Title</label><input type="text" id="edit_title"></div>
            </div>
            <div class="form-row">
                <div class="field"><label>Event Date</label><input type="date" id="edit_event_date"></div>
                <div class="field"><label>Event Time</label><input type="time" id="edit_event_time"></div>
            </div>
            <div class="form-row">
                <div class="field">
                    <label>Registration Deadline</label>
                    <input type="date" id="edit_deadline">
                    <span style="font-size:.72rem;color:var(--accent2);margin-top:3px">← Extend this to open registration</span>
                </div>
                <div class="field"><label>Max Participants</label><input type="number" id="edit_max"></div>
            </div>
            <div class="form-row single">
                <div class="field"><label>Venue</label><input type="text" id="edit_venue"></div>
            </div>
            <div class="form-row">
                <div class="field"><label>Status</label>
                    <select id="edit_status">
                        <option>Upcoming</option><option>Ongoing</option>
                        <option>Completed</option><option>Cancelled</option>
                    </select>
                </div>
                <div class="field"><label>Organizer</label><input type="text" id="edit_organizer"></div>
            </div>
            <button class="btn-primary" onclick="submitEditEvent()" id="editBtn" style="width:100%;margin-top:6px">Save Changes →</button>
        </div>
    </div>
</div>

<!-- ===== VIEW REGISTRATIONS MODAL ===== -->
<div class="modal-overlay" id="viewRegsModal">
    <div class="modal" style="max-width:700px">
        <div class="modal-head">
            <h3 id="viewRegsTitle">Registrations</h3>
            <button class="modal-close" onclick="closeModal('viewRegsModal')">✕</button>
        </div>
        <div class="modal-body" id="viewRegsBody"></div>
    </div>
</div>

<script>
const esc  = s => String(s??'').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
const fmt  = d => new Date(d+'T00:00:00').toLocaleDateString('en-IN',{day:'2-digit',month:'short',year:'numeric'});
const fmtT = t => { const [h,m]=t.split(':'); const hr=parseInt(h); return `${(hr%12)||12}:${m} ${hr>=12?'PM':'AM'}`; };

// ---- Auth guard ----
if (!localStorage.getItem('velcampus_admin')) {
    window.location.href = 'login.php';
}

document.getElementById('currentDate').textContent = new Date().toLocaleDateString('en-IN',{weekday:'long',day:'numeric',month:'long',year:'numeric'});

// ---- Page switching ----
function showPage(name) {
    document.querySelectorAll('.page').forEach(p => p.classList.remove('active'));
    document.querySelectorAll('.sidebar-link').forEach(l => l.classList.remove('active'));
    document.getElementById('page-' + name).classList.add('active');
    document.querySelector(`[onclick="showPage('${name}')"]`)?.classList.add('active');

    if (name === 'overview')       loadOverview();
    if (name === 'events')         loadEventsTable();
    if (name === 'registrations')  populateEventSelect();
    if (name === 'students')       loadStudents();
}

// ---- Logout ----
function adminLogout() {
    localStorage.removeItem('velcampus_admin');
    window.location.href = 'login.php';
}

// ---- Modal helpers ----
function openModal(id)  { document.getElementById(id).classList.add('active'); document.body.style.overflow='hidden'; }
function closeModal(id) { document.getElementById(id).classList.remove('active'); document.body.style.overflow=''; }
document.querySelectorAll('.modal-overlay').forEach(m => {
    m.addEventListener('click', e => { if (e.target === m) closeModal(m.id); });
});
document.addEventListener('keydown', e => { if(e.key==='Escape') document.querySelectorAll('.modal-overlay.active').forEach(m=>closeModal(m.id)); });

// ============================================
// OVERVIEW
// ============================================
let allEvents = [], allStudents = [];

async function loadOverview() {
    try {
        const [evRes, stRes] = await Promise.all([
            fetch('php/get_events.php').then(r=>r.json()),
            fetch('php/get_students.php').then(r=>r.json())
        ]);

        allEvents   = evRes.data  || [];
        allStudents = stRes.data  || [];

        const totalRegs = allEvents.reduce((s,e) => s + parseInt(e.registered_count||0), 0);
        const upcoming  = allEvents.filter(e => e.status === 'Upcoming').length;

        animNum('st-total',    allEvents.length);
        animNum('st-upcoming', upcoming);
        animNum('st-regs',     totalRegs);
        animNum('st-students', allStudents.length);

        // Recent 5 events table
        const rows = allEvents.slice(0,5).map(ev => `
            <tr>
                <td><strong>${esc(ev.title)}</strong></td>
                <td><span class="cat-badge cat-${esc(ev.category)}">${esc(ev.category)}</span></td>
                <td>${fmt(ev.event_date)}</td>
                <td><span class="roll-tag">${ev.registered_count}/${ev.max_participants}</span></td>
                <td><span class="status-badge status-${esc(ev.status)}">${esc(ev.status)}</span></td>
            </tr>`).join('');

        document.getElementById('overviewTable').innerHTML = `
            <table>
                <thead><tr><th>Event</th><th>Category</th><th>Date</th><th>Registrations</th><th>Status</th></tr></thead>
                <tbody>${rows}</tbody>
            </table>`;

    } catch(e) {
        document.getElementById('overviewTable').innerHTML = `<div class="empty-state"><p>Failed to load: ${esc(e.message)}</p></div>`;
    }
}

function animNum(id, target) {
    const el = document.getElementById(id);
    if (!el) return;
    let cur = 0;
    const step = Math.ceil(target/30);
    const t = setInterval(() => { cur = Math.min(cur+step, target); el.textContent = cur; if(cur>=target) clearInterval(t); }, 30);
}

// ============================================
// EVENTS TABLE
// ============================================
async function loadEventsTable() {
    const body = document.getElementById('eventsTableBody');
    body.innerHTML = '<div class="loading-state"><div class="spinner"></div></div>';

    try {
        const res = await fetch('php/get_events.php').then(r=>r.json());
        allEvents = res.data || [];
        renderEventsTable(allEvents);
    } catch(e) {
        body.innerHTML = `<div class="empty-state"><p>Error: ${esc(e.message)}</p></div>`;
    }
}

function renderEventsTable(events) {
    if (!events.length) {
        document.getElementById('eventsTableBody').innerHTML = '<div class="empty-state"><div class="empty-icon">📋</div><p>No events found</p></div>';
        return;
    }

    const rows = events.map(ev => `
        <tr>
            <td><strong>${esc(ev.title)}</strong><br><span style="font-size:.75rem;color:var(--muted)">${esc(ev.department)}</span></td>
            <td><span class="cat-badge cat-${esc(ev.category)}">${esc(ev.category)}</span></td>
            <td>${fmt(ev.event_date)}</td>
            <td style="font-size:.8rem;color:var(--muted)">${fmt(ev.registration_deadline)}</td>
            <td><span class="roll-tag">${ev.registered_count}/${ev.max_participants}</span></td>
            <td><span class="status-badge status-${esc(ev.status)}">${esc(ev.status)}</span></td>
            <td>
                <div class="act-cell">
                    <button class="act-btn" onclick="openEditModal(${ev.id})">✏ Edit</button>
                    <button class="act-btn" onclick="quickViewRegs(${ev.id},'${esc(ev.title).replace(/'/g,"\\'")}')">👁 Regs</button>
                    <a href="php/export_csv.php?event_id=${ev.id}" class="csv-btn">⬇ CSV</a>
                    ${ev.status==='Upcoming'?`
                    <button class="act-btn success" onclick="updateStatus(${ev.id},'Completed')">✔</button>
                    <button class="act-btn danger"   onclick="updateStatus(${ev.id},'Cancelled')">✖</button>`:``}
                </div>
            </td>
        </tr>`).join('');

    document.getElementById('eventsTableBody').innerHTML = `
        <table>
            <thead><tr><th>Event</th><th>Category</th><th>Event Date</th><th>Deadline</th><th>Regs</th><th>Status</th><th>Actions</th></tr></thead>
            <tbody>${rows}</tbody>
        </table>`;
}

function filterEvents() {
    const q = document.getElementById('eventSearch').value.toLowerCase();
    renderEventsTable(allEvents.filter(e =>
        e.title.toLowerCase().includes(q) || e.category.toLowerCase().includes(q) || e.department.toLowerCase().includes(q)
    ));
}

// ============================================
// EDIT EVENT MODAL
// ============================================
async function openEditModal(id) {
    try {
        const res = await fetch(`php/get_event.php?id=${id}`).then(r=>r.json());
        if (!res.success) throw new Error(res.message);
        const ev = res.data;

        document.getElementById('edit_id').value          = ev.id;
        document.getElementById('edit_title').value       = ev.title;
        document.getElementById('edit_event_date').value  = ev.event_date;
        document.getElementById('edit_event_time').value  = ev.event_time;
        document.getElementById('edit_deadline').value    = ev.registration_deadline;
        document.getElementById('edit_max').value         = ev.max_participants;
        document.getElementById('edit_venue').value       = ev.venue;
        document.getElementById('edit_organizer').value   = ev.organizer;
        document.getElementById('edit_status').value      = ev.status;
        document.getElementById('editAlert').innerHTML    = '';

        openModal('editModal');
    } catch(e) {
        alert('Failed to load event: ' + e.message);
    }
}

async function submitEditEvent() {
    const btn = document.getElementById('editBtn');
    const alertEl = document.getElementById('editAlert');
    btn.disabled = true; btn.textContent = 'Saving...';

    const payload = {
        id:                       parseInt(document.getElementById('edit_id').value),
        title:                    document.getElementById('edit_title').value.trim(),
        event_date:               document.getElementById('edit_event_date').value,
        event_time:               document.getElementById('edit_event_time').value,
        registration_deadline:    document.getElementById('edit_deadline').value,
        max_participants:         document.getElementById('edit_max').value,
        venue:                    document.getElementById('edit_venue').value.trim(),
        organizer:                document.getElementById('edit_organizer').value.trim(),
        status:                   document.getElementById('edit_status').value,
    };

    try {
        const res = await fetch('php/update_event.php', {
            method: 'POST', headers: {'Content-Type':'application/json'},
            body: JSON.stringify(payload)
        }).then(r=>r.json());

        if (res.success) {
            alertEl.innerHTML = `<div class="alert alert-success">✅ ${esc(res.message)}</div>`;
            loadEventsTable();
            setTimeout(() => closeModal('editModal'), 1200);
        } else {
            alertEl.innerHTML = `<div class="alert alert-error">⚠️ ${esc(res.message)}</div>`;
        }
    } catch(e) {
        alertEl.innerHTML = `<div class="alert alert-error">⚠️ Network error</div>`;
    } finally {
        btn.disabled = false; btn.textContent = 'Save Changes →';
    }
}

// ============================================
// STATUS UPDATE
// ============================================
async function updateStatus(id, status) {
    if (!confirm(`Mark this event as ${status}?`)) return;
    try {
        const res = await fetch('php/update_event_status.php', {
            method: 'POST', headers:{'Content-Type':'application/json'},
            body: JSON.stringify({id, status})
        }).then(r=>r.json());
        if (res.success) loadEventsTable();
        else alert(res.message);
    } catch(e) { alert(e.message); }
}

// ============================================
// ADD EVENT
// ============================================
async function submitAddEvent() {
    const alertEl = document.getElementById('addAlert');
    const fields = {
        title:                 document.getElementById('ef_title').value.trim(),
        category:              document.getElementById('ef_category').value,
        description:           document.getElementById('ef_description').value.trim(),
        venue:                 document.getElementById('ef_venue').value.trim(),
        event_date:            document.getElementById('ef_event_date').value,
        event_time:            document.getElementById('ef_event_time').value,
        max_participants:      document.getElementById('ef_max').value,
        registration_deadline: document.getElementById('ef_deadline').value,
        organizer:             document.getElementById('ef_organizer').value.trim(),
        department:            document.getElementById('ef_department').value,
    };

    for (const [k,v] of Object.entries(fields)) {
        if (!v) { alertEl.innerHTML=`<div class="alert alert-error">⚠️ Missing: ${k.replace(/_/g,' ')}</div>`; return; }
    }

    const btn = document.getElementById('addBtn');
    btn.disabled=true; btn.textContent='Creating...'; alertEl.innerHTML='';

    try {
        const res = await fetch('php/add_event.php', {
            method:'POST', headers:{'Content-Type':'application/json'},
            body: JSON.stringify(fields)
        }).then(r=>r.json());

        if (res.success) {
            alertEl.innerHTML = `<div class="alert alert-success">🎉 ${esc(res.message)} (ID: #${res.id})</div>`;
            ['ef_title','ef_description','ef_venue','ef_max','ef_organizer'].forEach(id => document.getElementById(id).value='');
            ['ef_category','ef_department'].forEach(id => document.getElementById(id).selectedIndex=0);
            ['ef_event_date','ef_event_time','ef_deadline'].forEach(id => document.getElementById(id).value='');
        } else {
            alertEl.innerHTML=`<div class="alert alert-error">⚠️ ${esc(res.message)}</div>`;
        }
    } catch(e) {
        alertEl.innerHTML=`<div class="alert alert-error">⚠️ Network error</div>`;
    } finally {
        btn.disabled=false; btn.textContent='Create Event →';
    }
}

// ============================================
// REGISTRATIONS
// ============================================
async function populateEventSelect() {
    try {
        const res = await fetch('php/get_events.php').then(r=>r.json());
        const sel = document.getElementById('regEventSelect');
        const existing = sel.innerHTML;
        sel.innerHTML = '<option value="">— Choose an event —</option>';
        (res.data||[]).forEach(ev => {
            sel.innerHTML += `<option value="${ev.id}">${esc(ev.title)} (${ev.registered_count} regs)</option>`;
        });
    } catch(e) {}
}

async function loadRegistrations() {
    const id = document.getElementById('regEventSelect').value;
    const csvBtn = document.getElementById('csvDownloadBtn');
    const body = document.getElementById('regsTableBody');
    const countEl = document.getElementById('regCount');

    if (!id) {
        body.innerHTML='<div class="empty-state"><div class="empty-icon">👆</div><p>Select an event above</p></div>';
        csvBtn.style.display='none'; countEl.textContent='';
        return;
    }

    csvBtn.href = `php/export_csv.php?event_id=${id}`;
    csvBtn.style.display = 'inline-block';
    body.innerHTML='<div class="loading-state"><div class="spinner"></div></div>';

    try {
        const res = await fetch(`php/get_registrations.php?event_id=${id}`).then(r=>r.json());
        if (!res.success) throw new Error(res.message);

        countEl.textContent = `${res.count} registrations`;

        if (!res.count) {
            body.innerHTML='<div class="empty-state"><div class="empty-icon">📋</div><p>No registrations yet</p></div>';
            return;
        }

        const rows = res.data.map((r,i) => `
            <tr>
                <td style="color:var(--muted)">${i+1}</td>
                <td><strong>${esc(r.student_name)}</strong></td>
                <td><span class="roll-tag">${esc(r.roll_number)}</span></td>
                <td style="font-size:.8rem;color:var(--muted)">${esc(r.student_email)}</td>
                <td style="font-size:.82rem">${esc(r.department)}</td>
                <td style="font-size:.82rem">${esc(r.year_of_study)}</td>
                <td style="font-family:var(--font-m);font-size:.78rem">${esc(r.phone)}</td>
            </tr>`).join('');

        body.innerHTML=`<table>
            <thead><tr><th>#</th><th>Name</th><th>Roll</th><th>Email</th><th>Dept</th><th>Year</th><th>Phone</th></tr></thead>
            <tbody>${rows}</tbody></table>`;
    } catch(e) {
        body.innerHTML=`<div class="empty-state"><p>Error: ${esc(e.message)}</p></div>`;
    }
}

// Quick view regs from events table
async function quickViewRegs(id, title) {
    document.getElementById('viewRegsTitle').textContent = `Regs — ${title}`;
    document.getElementById('viewRegsBody').innerHTML='<div class="loading-state"><div class="spinner"></div></div>';
    openModal('viewRegsModal');

    try {
        const res = await fetch(`php/get_registrations.php?event_id=${id}`).then(r=>r.json());
        if (!res.success) throw new Error(res.message);
        if (!res.count) { document.getElementById('viewRegsBody').innerHTML='<div class="empty-state"><div class="empty-icon">📋</div><p>No registrations yet</p></div>'; return; }

        const rows = res.data.map((r,i) => `
            <tr>
                <td style="color:var(--muted)">${i+1}</td>
                <td><strong>${esc(r.student_name)}</strong></td>
                <td><span class="roll-tag">${esc(r.roll_number)}</span></td>
                <td style="font-size:.8rem;color:var(--muted)">${esc(r.student_email)}</td>
                <td style="font-size:.82rem">${esc(r.department)}</td>
            </tr>`).join('');

        document.getElementById('viewRegsBody').innerHTML=`
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:14px">
                <span style="color:var(--muted);font-size:.83rem"><strong style="color:var(--accent)">${res.count}</strong> registrations</span>
                <a href="php/export_csv.php?event_id=${id}" class="csv-btn" style="padding:6px 14px">⬇ CSV</a>
            </div>
            <div style="overflow-x:auto"><table style="min-width:440px">
                <thead><tr><th>#</th><th>Name</th><th>Roll</th><th>Email</th><th>Dept</th></tr></thead>
                <tbody>${rows}</tbody></table></div>`;
    } catch(e) {
        document.getElementById('viewRegsBody').innerHTML=`<div class="empty-state"><p>${esc(e.message)}</p></div>`;
    }
}

// ============================================
// STUDENTS
// ============================================
async function loadStudents() {
    const body = document.getElementById('studentsTableBody');
    body.innerHTML='<div class="loading-state"><div class="spinner"></div></div>';

    try {
        const res = await fetch('php/get_students.php').then(r=>r.json());
        allStudents = res.data || [];
        renderStudents(allStudents);
    } catch(e) {
        body.innerHTML=`<div class="empty-state"><p>Error: ${esc(e.message)}</p></div>`;
    }
}

function renderStudents(students) {
    if (!students.length) {
        document.getElementById('studentsTableBody').innerHTML='<div class="empty-state"><div class="empty-icon">🎓</div><p>No students yet</p></div>';
        return;
    }

    const rows = students.map((s,i) => `
        <tr>
            <td style="color:var(--muted)">${i+1}</td>
            <td><strong>${esc(s.name)}</strong></td>
            <td><span class="roll-tag">${esc(s.roll_number)}</span></td>
            <td style="font-size:.82rem;color:var(--muted)">${esc(s.email)}</td>
            <td style="font-size:.82rem">${esc(s.department)}</td>
            <td style="font-size:.82rem">${esc(s.year_of_study)}</td>
            <td style="font-family:var(--font-m);font-size:.78rem">${esc(s.phone)}</td>
            <td style="color:var(--muted);font-size:.75rem">${new Date(s.created_at).toLocaleDateString('en-IN')}</td>
        </tr>`).join('');

    document.getElementById('studentsTableBody').innerHTML=`
        <table>
            <thead><tr><th>#</th><th>Name</th><th>Roll</th><th>Email</th><th>Dept</th><th>Year</th><th>Phone</th><th>Joined</th></tr></thead>
            <tbody>${rows}</tbody>
        </table>`;
}


// ============================================
// CREATE STUDENT (Admin)
// ============================================
async function createStudent() {
    const alertEl = document.getElementById('createStudentAlert');
    const roll = document.getElementById('cs_roll').value.trim().toUpperCase();
    const payload = {
        name:          document.getElementById('cs_name').value.trim(),
        roll_number:   roll,
        email:         document.getElementById('cs_email').value.trim(),
        department:    document.getElementById('cs_dept').value,
        year_of_study: document.getElementById('cs_year').value,
        phone:         document.getElementById('cs_phone').value.trim(),
        password:      document.getElementById('cs_pass').value.trim() || roll
    };

    for (const [k, v] of Object.entries(payload)) {
        if (!v) { alertEl.innerHTML=`<div class="alert alert-error">Missing: ${k.replace(/_/g,' ')}</div>`; return; }
    }

    const btn = document.getElementById('createStudentBtn');
    btn.disabled = true; btn.textContent = 'Creating...';
    alertEl.innerHTML = '';

    try {
        const res = await fetch('php/admin_create_student.php', {
            method: 'POST',
            headers: {'Content-Type':'application/json'},
            body: JSON.stringify(payload)
        }).then(r => r.json());

        if (res.success) {
            alertEl.innerHTML = `<div class="alert alert-success">Student created! Password: <strong>${esc(payload.password)}</strong></div>`;
            ['cs_name','cs_roll','cs_email','cs_phone','cs_pass'].forEach(id => document.getElementById(id).value = '');
            ['cs_dept','cs_year'].forEach(id => document.getElementById(id).selectedIndex = 0);
            loadStudents();
        } else {
            alertEl.innerHTML = `<div class="alert alert-error">${esc(res.message)}</div>`;
        }
    } catch(e) {
        alertEl.innerHTML = `<div class="alert alert-error">Network error</div>`;
    } finally {
        btn.disabled = false; btn.textContent = 'Create Student';
    }
}

function filterStudents() {
    const q = document.getElementById('studentSearch').value.toLowerCase();
    renderStudents(allStudents.filter(s =>
        s.name.toLowerCase().includes(q) || s.roll_number.toLowerCase().includes(q) || s.department.toLowerCase().includes(q)
    ));
}

// Init
loadOverview();
</script>
</body>
</html>

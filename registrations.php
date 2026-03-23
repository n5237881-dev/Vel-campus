<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Registrations — VelCampus</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<nav class="navbar">
    <div class="nav-brand">
        <div class="logo-icon">🎓</div>
        <h1>Vel<span>Campus</span></h1>
    </div>
    <div class="nav-links">
        <a href="index.php">Events</a>
        <a href="registrations.php" class="active">My Registrations</a>
        <a href="admin.php" class="btn-primary">Admin Panel</a>
    </div>
</nav>

<div class="page-section">
    <h2 class="page-title">🔍 Check Your Registration</h2>

    <!-- Lookup Form -->
    <div style="background:var(--bg-card);border:1px solid var(--border);border-radius:var(--radius);padding:24px;max-width:480px;margin-bottom:32px;">
        <div class="form-group" style="margin-bottom:14px">
            <label>Roll Number</label>
            <input type="text" id="rollInput" placeholder="e.g. VT22CS001" style="padding:9px 12px;background:var(--bg);border:1px solid var(--border);border-radius:8px;color:var(--text);font-family:var(--font-body);font-size:0.9rem;outline:none;width:100%;text-transform:uppercase">
        </div>
        <button class="btn-submit" onclick="lookupRegistrations()" style="width:auto;padding:9px 24px;">
            Search Registrations →
        </button>
    </div>

    <div id="resultsArea"></div>
</div>

<footer>
    <span>VelCampus</span> · Academic Campus Event & Registration Portal
</footer>

<script>
const formatDate = (d) => new Date(d + 'T00:00:00').toLocaleDateString('en-IN', {day:'2-digit',month:'short',year:'numeric'});
const esc = (s) => String(s).replace(/</g,'&lt;').replace(/>/g,'&gt;');

async function lookupRegistrations() {
    const roll = document.getElementById('rollInput').value.trim().toUpperCase();
    const area = document.getElementById('resultsArea');
    if (!roll) { area.innerHTML = `<div class="alert alert-error">⚠️ Enter your roll number.</div>`; return; }

    area.innerHTML = `<div class="loading-state"><div class="spinner"></div><p>Searching...</p></div>`;

    try {
        const res = await fetch(`php/my_registrations.php?roll=${encodeURIComponent(roll)}`);
        const data = await res.json();

        if (!data.success) throw new Error(data.message);
        if (data.data.length === 0) {
            area.innerHTML = `<div class="empty-state"><div class="icon">📋</div><h3>No registrations found</h3><p>No events registered under <strong>${esc(roll)}</strong></p></div>`;
            return;
        }

        const rows = data.data.map(r => `
            <tr>
                <td>${esc(r.event_title)}</td>
                <td><span class="category-badge badge-${esc(r.category)}">${esc(r.category)}</span></td>
                <td>${formatDate(r.event_date)}</td>
                <td>${esc(r.venue)}</td>
                <td><span class="roll-badge">${esc(r.roll_number)}</span></td>
                <td style="color:var(--text-muted);font-size:0.78rem">${new Date(r.registered_at).toLocaleDateString('en-IN')}</td>
            </tr>`).join('');

        area.innerHTML = `
            <p style="color:var(--text-muted);font-size:0.85rem;margin-bottom:14px">${data.data.length} registration(s) found for <strong style="color:var(--accent)">${esc(roll)}</strong></p>
            <div class="table-wrap">
                <table>
                    <thead><tr><th>Event</th><th>Category</th><th>Date</th><th>Venue</th><th>Roll No.</th><th>Registered On</th></tr></thead>
                    <tbody>${rows}</tbody>
                </table>
            </div>`;
    } catch(err) {
        area.innerHTML = `<div class="alert alert-error">⚠️ ${esc(err.message)}</div>`;
    }
}

document.getElementById('rollInput').addEventListener('keydown', (e) => {
    if (e.key === 'Enter') lookupRegistrations();
});
</script>
</body>
</html>

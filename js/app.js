// ============================================
// Campus Event Portal — ES6 JavaScript
// ============================================

'use strict';

// ---- Config ----
const API_BASE = 'php/';

// ---- State ----
const state = {
    events: [],
    currentFilter: 'All',
    searchQuery: '',
    loading: false
};

// ---- DOM Refs ----
const dom = {
    eventsGrid:     () => document.getElementById('eventsGrid'),
    statsTotal:     () => document.getElementById('statsTotal'),
    statsUpcoming:  () => document.getElementById('statsUpcoming'),
    statsRegs:      () => document.getElementById('statsRegs'),
    filterTabs:     () => document.querySelectorAll('.filter-tab'),
    searchInput:    () => document.getElementById('searchInput'),
    modalOverlay:   () => document.getElementById('modalOverlay'),
    modalTitle:     () => document.getElementById('modalTitle'),
    modalBody:      () => document.getElementById('modalBody'),
    modalClose:     () => document.getElementById('modalClose')
};

// ---- Utils ----
const formatDate = (dateStr) => {
    const d = new Date(dateStr + 'T00:00:00');
    return d.toLocaleDateString('en-IN', { day: '2-digit', month: 'short', year: 'numeric' });
};

const formatTime = (timeStr) => {
    const [h, m] = timeStr.split(':');
    const hour = parseInt(h);
    const ampm = hour >= 12 ? 'PM' : 'AM';
    const h12 = ((hour % 12) || 12);
    return `${h12}:${m} ${ampm}`;
};

const pct = (filled, max) => Math.min(100, Math.round((filled / max) * 100));

const escHtml = (str) =>
    String(str).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');

// ---- API ----
const api = {
    async get(endpoint) {
        const res = await fetch(API_BASE + endpoint);
        if (!res.ok) throw new Error(`HTTP ${res.status}`);
        return res.json();
    },
    async post(endpoint, data) {
        const res = await fetch(API_BASE + endpoint, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data)
        });
        if (!res.ok) throw new Error(`HTTP ${res.status}`);
        return res.json();
    }
};

// ---- Render Events ----
const getCategoryIcon = (cat) => {
    const icons = {
        Technical: '⚡', Cultural: '🎭', Sports: '🏆',
        Workshop: '🔧', Seminar: '📢', Hackathon: '💻'
    };
    return icons[cat] || '📅';
};

const renderEventCard = (event) => {
    const filled = event.registered_count;
    const max    = event.max_participants;
    const spots  = event.spots_left;
    const fillPct = pct(filled, max);
    const isOpen  = event.status === 'Upcoming' && spots > 0;
    const deadline = new Date(event.registration_deadline + 'T00:00:00');
    const today    = new Date();
    const deadlinePassed = today > deadline;

    return `
        <article class="event-card" data-id="${event.id}" onclick="openEventDetail(${event.id})">
            <div class="card-header">
                <span class="category-badge badge-${escHtml(event.category)}">${getCategoryIcon(event.category)} ${escHtml(event.category)}</span>
                <div class="status-dot ${escHtml(event.status)}" title="${escHtml(event.status)}"></div>
            </div>
            <div class="card-body">
                <h3 class="card-title">${escHtml(event.title)}</h3>
                <p class="card-desc">${escHtml(event.description)}</p>
                <div class="card-meta">
                    <div class="meta-item">
                        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
                        ${formatDate(event.event_date)} &nbsp;·&nbsp; ${formatTime(event.event_time)}
                    </div>
                    <div class="meta-item">
                        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7z"/><circle cx="12" cy="9" r="2.5"/></svg>
                        ${escHtml(event.venue)}
                    </div>
                    <div class="meta-item">
                        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                        ${escHtml(event.department)}
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <div class="spots-bar-wrap">
                    <div class="spots-label">${filled}/${max} registered ${spots > 0 ? `· ${spots} spots left` : '· Full'}</div>
                    <div class="spots-bar"><div class="spots-fill" style="width:${fillPct}%"></div></div>
                </div>
                <button class="btn-register" 
                    onclick="event.stopPropagation(); openRegisterModal(${event.id})"
                    ${(!isOpen || deadlinePassed) ? 'disabled' : ''}>
                    ${event.status === 'Completed' ? 'Ended' : event.status === 'Cancelled' ? 'Cancelled' : deadlinePassed ? 'Closed' : spots === 0 ? 'Full' : 'Register'}
                </button>
            </div>
        </article>`;
};

const renderEvents = () => {
    const grid = dom.eventsGrid();
    if (!grid) return;

    const filtered = state.events.filter(ev => {
        const matchCat  = state.currentFilter === 'All' || ev.category === state.currentFilter;
        const q = state.searchQuery.toLowerCase();
        const matchSearch = !q || ev.title.toLowerCase().includes(q) || ev.description.toLowerCase().includes(q) || ev.department.toLowerCase().includes(q);
        return matchCat && matchSearch;
    });

    if (filtered.length === 0) {
        grid.innerHTML = `
            <div class="empty-state" style="grid-column:1/-1">
                <div class="icon">🔍</div>
                <h3>No events found</h3>
                <p>Try a different category or search term</p>
            </div>`;
        return;
    }

    grid.innerHTML = filtered.map(renderEventCard).join('');
};

// ---- Load Events ----
const loadEvents = async () => {
    const grid = dom.eventsGrid();
    if (!grid) return;

    grid.innerHTML = `<div class="loading-state" style="grid-column:1/-1"><div class="spinner"></div><p>Loading events...</p></div>`;

    try {
        const res = await api.get('get_events.php');
        if (!res.success) throw new Error(res.message);

        state.events = res.data;
        renderEvents();
        updateStats();
    } catch (err) {
        grid.innerHTML = `<div class="empty-state" style="grid-column:1/-1"><div class="icon">⚠️</div><h3>Failed to load events</h3><p>${escHtml(err.message)}</p></div>`;
    }
};

// ---- Stats ----
const updateStats = () => {
    const total    = state.events.length;
    const upcoming = state.events.filter(e => e.status === 'Upcoming').length;
    const regs     = state.events.reduce((s, e) => s + parseInt(e.registered_count || 0), 0);

    const animateNum = (el, target) => {
        if (!el) return;
        let current = 0;
        const step = Math.ceil(target / 30);
        const timer = setInterval(() => {
            current = Math.min(current + step, target);
            el.textContent = current;
            if (current >= target) clearInterval(timer);
        }, 30);
    };

    animateNum(dom.statsTotal(), total);
    animateNum(dom.statsUpcoming(), upcoming);
    animateNum(dom.statsRegs(), regs);
};

// ---- Filters ----
const initFilters = () => {
    dom.filterTabs().forEach(tab => {
        tab.addEventListener('click', () => {
            dom.filterTabs().forEach(t => t.classList.remove('active'));
            tab.classList.add('active');
            state.currentFilter = tab.dataset.category;
            renderEvents();
        });
    });

    const search = dom.searchInput();
    if (search) {
        let debounceTimer;
        search.addEventListener('input', () => {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => {
                state.searchQuery = search.value.trim();
                renderEvents();
            }, 250);
        });
    }
};

// ---- Modal Helpers ----
const openModal = (title, html) => {
    dom.modalTitle().textContent = title;
    dom.modalBody().innerHTML = html;
    dom.modalOverlay().classList.add('active');
    document.body.style.overflow = 'hidden';
};

const closeModal = () => {
    dom.modalOverlay().classList.remove('active');
    document.body.style.overflow = '';
};

// ---- Event Detail Modal ----
window.openEventDetail = async (id) => {
    openModal('Event Details', `<div class="loading-state"><div class="spinner"></div></div>`);

    try {
        const res = await api.get(`get_event.php?id=${id}`);
        if (!res.success) throw new Error(res.message);
        const ev = res.data;
        const spots  = ev.spots_left;
        const isOpen = ev.status === 'Upcoming' && spots > 0 && new Date() <= new Date(ev.registration_deadline + 'T23:59:59');

        dom.modalTitle().textContent = ev.title;
        dom.modalBody().innerHTML = `
            <div style="display:flex;gap:8px;flex-wrap:wrap;margin-bottom:14px">
                <span class="category-badge badge-${escHtml(ev.category)}">${getCategoryIcon(ev.category)} ${escHtml(ev.category)}</span>
                <span class="category-badge" style="background:rgba(255,255,255,0.05);color:var(--text-muted);border:1px solid var(--border)">${escHtml(ev.status)}</span>
            </div>
            <p class="event-description">${escHtml(ev.description)}</p>
            <div class="event-detail-info">
                <div class="detail-chip">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
                    <strong>${formatDate(ev.event_date)}</strong>
                </div>
                <div class="detail-chip">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
                    <strong>${formatTime(ev.event_time)}</strong>
                </div>
                <div class="detail-chip">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7z"/><circle cx="12" cy="9" r="2.5"/></svg>
                    <strong>${escHtml(ev.venue)}</strong>
                </div>
                <div class="detail-chip">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
                    <strong>${ev.registered_count}/${ev.max_participants}</strong> registered
                </div>
                <div class="detail-chip">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
                    Deadline: <strong>${formatDate(ev.registration_deadline)}</strong>
                </div>
            </div>
            <div class="organizer-row">
                🎓 Organized by <strong>${escHtml(ev.organizer)}</strong> &nbsp;·&nbsp; ${escHtml(ev.department)}
            </div>
            ${isOpen ? `<button class="btn-submit" onclick="openRegisterModal(${ev.id})">Register for this Event →</button>` : `<button class="btn-submit" disabled style="opacity:0.4">Registration ${ev.status !== 'Upcoming' ? ev.status : 'Closed'}</button>`}
        `;
    } catch (err) {
        dom.modalBody().innerHTML = `<div class="alert alert-error">⚠️ ${escHtml(err.message)}</div>`;
    }
};

// ---- Registration Modal ----
window.openRegisterModal = async (eventId) => {
    const ev = state.events.find(e => e.id == eventId);
    const title = ev ? ev.title : 'Register';

    openModal(`Register — ${title}`, `
        <div id="regAlert"></div>
        <form id="regForm" autocomplete="off">
            <div class="form-grid">
                <div class="form-group">
                    <label>Full Name *</label>
                    <input type="text" name="student_name" placeholder="e.g. Arjun Prabhu" required>
                </div>
                <div class="form-group">
                    <label>Roll Number *</label>
                    <input type="text" name="roll_number" placeholder="e.g. VT22CS001" required style="text-transform:uppercase">
                </div>
                <div class="form-group full-width">
                    <label>Email Address *</label>
                    <input type="email" name="student_email" placeholder="yourname@veltech.edu.in" required>
                </div>
                <div class="form-group">
                    <label>Department *</label>
                    <select name="department" required>
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
                    <label>Year of Study *</label>
                    <select name="year_of_study" required>
                        <option value="">— Select —</option>
                        <option>1st Year</option>
                        <option>2nd Year</option>
                        <option>3rd Year</option>
                        <option>4th Year</option>
                    </select>
                </div>
                <div class="form-group full-width">
                    <label>Phone Number *</label>
                    <input type="tel" name="phone" placeholder="10-digit mobile number" maxlength="10" required>
                </div>
            </div>
            <button type="submit" class="btn-submit" style="margin-top:16px" id="submitBtn">
                Confirm Registration →
            </button>
        </form>
    `);

    // Handle form submit
    document.getElementById('regForm').addEventListener('submit', async (e) => {
        e.preventDefault();
        const btn = document.getElementById('submitBtn');
        const alertEl = document.getElementById('regAlert');
        const formData = new FormData(e.target);

        const payload = {
            event_id:      eventId,
            student_name:  formData.get('student_name').trim(),
            student_email: formData.get('student_email').trim(),
            roll_number:   formData.get('roll_number').trim().toUpperCase(),
            department:    formData.get('department'),
            year_of_study: formData.get('year_of_study'),
            phone:         formData.get('phone').trim()
        };

        btn.disabled = true;
        btn.textContent = 'Registering...';
        alertEl.innerHTML = '';

        try {
            const res = await api.post('register.php', payload);
            if (res.success) {
                alertEl.innerHTML = `<div class="alert alert-success">🎉 ${escHtml(res.message)}<br><small>Registration ID: #${res.reg_id}</small></div>`;
                document.getElementById('regForm').style.display = 'none';
                // Reload events to update counts
                setTimeout(loadEvents, 800);
            } else {
                alertEl.innerHTML = `<div class="alert alert-error">⚠️ ${escHtml(res.message)}</div>`;
                btn.disabled = false;
                btn.textContent = 'Confirm Registration →';
            }
        } catch (err) {
            alertEl.innerHTML = `<div class="alert alert-error">⚠️ Network error. Please try again.</div>`;
            btn.disabled = false;
            btn.textContent = 'Confirm Registration →';
        }
    });
};

// ---- Modal Close ----
const initModal = () => {
    dom.modalClose()?.addEventListener('click', closeModal);
    dom.modalOverlay()?.addEventListener('click', (e) => {
        if (e.target === dom.modalOverlay()) closeModal();
    });
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') closeModal();
    });
};

// ---- Init ----
document.addEventListener('DOMContentLoaded', () => {
    initFilters();
    initModal();
    loadEvents();
});

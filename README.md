# Academic Campus Event & Registration Portal
### Vel Tech Rangarajan Dr. Sagunthala R&D Institute

---

## Tech Stack
- **Frontend**: HTML5, CSS3, ES6 JavaScript (Vanilla)
- **Backend**: PHP (MySQLi)
- **Database**: MySQL
- **Server**: XAMPP (Apache + MySQL)

---

## Setup Instructions

### Step 1 — XAMPP Setup
1. Start **Apache** and **MySQL** in XAMPP Control Panel
2. Make sure MySQL is running on port **3306**

### Step 2 — Copy Project Files
Copy the entire `campus_portal/` folder into:
```
C:\xampp\htdocs\campus_portal\
```

### Step 3 — Create Database
1. Open your browser → go to `http://localhost/phpmyadmin`
2. Click **Import** tab
3. Choose the file: `campus_portal/database.sql`
4. Click **Go** — this creates the `campus_events` database with sample data

### Step 4 — Configure DB (if needed)
Open `includes/db.php` and update if your MySQL has a password:
```php
define('DB_PASS', '');   // Set your MySQL password here
```

### Step 5 — Run the Portal
Open browser and go to:
```
http://localhost/campus_portal/
```

---

## File Structure
```
campus_portal/
│
├── index.php               ← Main events listing page
├── registrations.php       ← Student registration lookup page
├── admin.php               ← Admin panel (view all registrations)
├── database.sql            ← DB schema + sample data
│
├── css/
│   └── style.css           ← All styles
│
├── js/
│   └── app.js              ← ES6 frontend logic
│
├── php/
│   ├── get_events.php      ← API: fetch all events
│   ├── get_event.php       ← API: fetch single event
│   ├── register.php        ← API: POST registration
│   ├── get_registrations.php  ← API: admin view registrations
│   └── my_registrations.php   ← API: lookup by roll number
│
└── includes/
    └── db.php              ← MySQL connection config
```

---

## Features
- Browse all campus events with category filters & search
- View event details (venue, date, time, organizer, seats left)
- Register for events with form validation
- Duplicate registration prevention (roll number + event)
- Registration deadline & capacity enforcement
- Student registration lookup by roll number
- Admin panel showing all events + per-event registrations
- Animated stats counter
- Responsive design

---

## Database Tables
- **events** — stores all event info
- **registrations** — stores student registrations (FK to events)

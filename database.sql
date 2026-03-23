-- ============================================
-- Academic Campus Event & Registration Portal
-- ============================================

CREATE DATABASE IF NOT EXISTS campus_events;
USE campus_events;

-- Students Table (for login)
CREATE TABLE IF NOT EXISTS students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    roll_number VARCHAR(30) NOT NULL UNIQUE,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    department VARCHAR(100) NOT NULL,
    year_of_study ENUM('1st Year','2nd Year','3rd Year','4th Year') NOT NULL,
    phone VARCHAR(15) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Events Table
CREATE TABLE IF NOT EXISTS events (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    description TEXT NOT NULL,
    category ENUM('Technical','Cultural','Sports','Workshop','Seminar','Hackathon') NOT NULL,
    venue VARCHAR(200) NOT NULL,
    event_date DATE NOT NULL,
    event_time TIME NOT NULL,
    max_participants INT NOT NULL DEFAULT 100,
    registration_deadline DATE NOT NULL,
    organizer VARCHAR(100) NOT NULL,
    department VARCHAR(100) NOT NULL,
    image_url VARCHAR(255) DEFAULT NULL,
    status ENUM('Upcoming','Ongoing','Completed','Cancelled') DEFAULT 'Upcoming',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Registrations Table
CREATE TABLE IF NOT EXISTS registrations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    event_id INT NOT NULL,
    student_name VARCHAR(100) NOT NULL,
    student_email VARCHAR(150) NOT NULL,
    roll_number VARCHAR(30) NOT NULL,
    department VARCHAR(100) NOT NULL,
    year_of_study ENUM('1st Year','2nd Year','3rd Year','4th Year') NOT NULL,
    phone VARCHAR(15) NOT NULL,
    registered_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (event_id) REFERENCES events(id) ON DELETE CASCADE,
    UNIQUE KEY unique_registration (event_id, roll_number)
);

-- ============================================
-- Sample Students (password = roll number)
-- ============================================
INSERT INTO students (name, roll_number, email, password, department, year_of_study, phone) VALUES
('Arjun Prabhu',   'VT22CS001', 'arjun.prabhu@veltech.edu.in',  MD5('VT22CS001'), 'Computer Science', '3rd Year', '9876543210'),
('Kavya Sharma',   'VT22CS045', 'kavya.sharma@veltech.edu.in',  MD5('VT22CS045'), 'Computer Science', '3rd Year', '9123456789'),
('Rohit Menon',    'VT23CS012', 'rohit.menon@veltech.edu.in',   MD5('VT23CS012'), 'Computer Science', '2nd Year', '9988776655'),
('Priya Nair',     'VT22ME030', 'priya.nair@veltech.edu.in',    MD5('VT22ME030'), 'Mechanical Engineering', '3rd Year', '8877665544'),
('Siddharth Raj',  'VT21CS088', 'sid.raj@veltech.edu.in',       MD5('VT21CS088'), 'Computer Science', '4th Year', '7766554433');

-- ============================================
-- Sample Events
-- ============================================
INSERT INTO events (title, description, category, venue, event_date, event_time, max_participants, registration_deadline, organizer, department, status) VALUES
('HackVelTech 2025', 'A 24-hour hackathon challenging students to build innovative solutions for real-world problems. Top 3 teams win cash prizes and internship opportunities.', 'Hackathon', 'Vel Tech Seminar Hall Block-A', '2025-04-10', '09:00:00', 200, '2025-04-05', 'Dr. K Jayanthi', 'Computer Science', 'Upcoming'),
('Full Stack Bootcamp', 'An intensive 2-day workshop on modern full stack development covering React, Node.js, and MySQL. Hands-on projects and live deployment.', 'Workshop', 'CSE Lab 3, Block-B', '2025-04-15', '10:00:00', 60, '2025-04-12', 'Prof. R Suresh', 'Computer Science', 'Upcoming'),
('Vel Tech Cultural Fest - VELANZA', 'Annual cultural extravaganza featuring dance, music, drama, and art competitions. Open to all departments.', 'Cultural', 'Open Air Auditorium', '2025-04-20', '08:00:00', 500, '2025-04-18', 'Student Council', 'All Departments', 'Upcoming'),
('Data Science Seminar', 'A seminar on big data analytics, machine learning pipelines, and career paths in data science. Guest speaker from Amazon.', 'Seminar', 'Conference Hall, Block-C', '2025-04-08', '11:00:00', 150, '2025-04-06', 'Dr. K Jayanthi', 'Computer Science', 'Upcoming'),
('Inter-Department Cricket Tournament', 'Annual cricket tournament between all departments. 10-over format. Knockout rounds followed by finals.', 'Sports', 'Vel Tech Cricket Ground', '2025-04-25', '07:00:00', 110, '2025-04-20', 'Sports Director', 'Sports Committee', 'Upcoming'),
('AI & Ethics Symposium', 'Exploring the ethical dimensions of artificial intelligence. Panel discussion with industry experts and researchers.', 'Seminar', 'Seminar Hall Block-B', '2025-03-30', '10:00:00', 200, '2025-03-28', 'Prof. A Meenakshi', 'ECE Department', 'Completed');

-- Sample Registrations
INSERT INTO registrations (event_id, student_name, student_email, roll_number, department, year_of_study, phone) VALUES
(1, 'Arjun Prabhu',  'arjun.prabhu@veltech.edu.in', 'VT22CS001', 'Computer Science', '3rd Year', '9876543210'),
(1, 'Kavya Sharma',  'kavya.sharma@veltech.edu.in', 'VT22CS045', 'Computer Science', '3rd Year', '9123456789'),
(2, 'Rohit Menon',   'rohit.menon@veltech.edu.in',  'VT23CS012', 'Computer Science', '2nd Year', '9988776655'),
(3, 'Priya Nair',    'priya.nair@veltech.edu.in',   'VT22ME030', 'Mechanical Engineering', '3rd Year', '8877665544'),
(4, 'Siddharth Raj', 'sid.raj@veltech.edu.in',      'VT21CS088', 'Computer Science', '4th Year', '7766554433');

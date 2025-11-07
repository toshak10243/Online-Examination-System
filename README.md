# 🧠 Online Examination System (Core PHP + MySQL + AJAX)

A complete **Online Examination System** built from scratch using **Core PHP**, **MySQL**, **AJAX**, **jQuery**, **HTML**, **CSS**, and **Bootstrap 5**.

## 🚀 Key Highlights
- Admin Panel for Subject, Question & Exam Management  
- Student Dashboard for Exam Attempts & Results  
- Real-time Result Calculation  
- Leaderboard & Analytics with Chart.js  
- Secure Anti-Cheating System:
  - Auto Full Screen  
  - Tab Switch & Fullscreen Exit Detection  
  - Copy/Paste Disabled  
  - Instant Exam Termination + Auto Logout  

## 🧰 Tech Stack
**Frontend:** HTML, CSS, JavaScript, jQuery, Bootstrap 5  
**Backend:** Core PHP, AJAX  
**Database:** MySQL (using Prepared Statements)  

## 📊 Features Overview
✅ Dynamic Exam Creation  
✅ One-by-One Question Navigation  
✅ Timer + Auto Submit  
✅ SweetAlert2 Notifications  
✅ CSV & PDF Report Generation  
✅ Chart.js Analytics Dashboard  
✅ Secure Session Handling  

-- ==========================================
-- ONLINE EXAMINATION SYSTEM DATABASE STRUCTURE
-- ==========================================

-- Step 1: Create Database
CREATE DATABASE IF NOT EXISTS online_exam CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE online_exam;

-- ==========================================
-- USERS TABLE (Admin & Students)
-- ==========================================
CREATE TABLE IF NOT EXISTS users (
  user_id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(100) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  fullname VARCHAR(255),
  role ENUM('admin','student') NOT NULL DEFAULT 'student',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert Default Admin & Student (SHA2 Hashed)
INSERT IGNORE INTO users (username, password, fullname, role) VALUES
('admin', SHA2('admin123',256), 'Administrator', 'admin'),
('student', SHA2('student123',256), 'Demo Student', 'student');

-- ==========================================
-- SUBJECTS TABLE
-- ==========================================
CREATE TABLE IF NOT EXISTS subjects (
  subject_id INT AUTO_INCREMENT PRIMARY KEY,
  subject_name VARCHAR(255) NOT NULL UNIQUE,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ==========================================
-- QUESTIONS TABLE
-- ==========================================
CREATE TABLE IF NOT EXISTS questions (
  question_id INT AUTO_INCREMENT PRIMARY KEY,
  subject_id INT NOT NULL,
  question_text TEXT NOT NULL,
  option_a VARCHAR(500) NOT NULL,
  option_b VARCHAR(500) NOT NULL,
  option_c VARCHAR(500) NOT NULL,
  option_d VARCHAR(500) NOT NULL,
  correct_option ENUM('A','B','C','D') NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (subject_id) REFERENCES subjects(subject_id) ON DELETE CASCADE
);

-- ==========================================
-- EXAMS TABLE
-- ==========================================
CREATE TABLE IF NOT EXISTS exams (
  exam_id INT AUTO_INCREMENT PRIMARY KEY,
  exam_title VARCHAR(255) NOT NULL,
  subject_id INT NOT NULL,
  start_time DATETIME NOT NULL,
  end_time DATETIME NOT NULL,
  total_questions INT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (subject_id) REFERENCES subjects(subject_id) ON DELETE CASCADE
);

-- ==========================================
-- EXAM ATTEMPTS TABLE
-- ==========================================
CREATE TABLE IF NOT EXISTS exam_attempts (
  attempt_id INT AUTO_INCREMENT PRIMARY KEY,
  exam_id INT NOT NULL,
  student_id INT NOT NULL,
  start_time DATETIME DEFAULT CURRENT_TIMESTAMP,
  end_time DATETIME,
  score INT DEFAULT 0,
  submitted TINYINT(1) DEFAULT 0,
  FOREIGN KEY (exam_id) REFERENCES exams(exam_id) ON DELETE CASCADE,
  FOREIGN KEY (student_id) REFERENCES users(user_id) ON DELETE CASCADE
);

-- ==========================================
-- EXAM ANSWERS TABLE
-- ==========================================
CREATE TABLE IF NOT EXISTS exam_answers (
  answer_id INT AUTO_INCREMENT PRIMARY KEY,
  attempt_id INT NOT NULL,
  question_id INT NOT NULL,
  chosen_option ENUM('A','B','C','D') DEFAULT NULL,
  FOREIGN KEY (attempt_id) REFERENCES exam_attempts(attempt_id) ON DELETE CASCADE,
  FOREIGN KEY (question_id) REFERENCES questions(question_id) ON DELETE CASCADE
);


-- ==========================================
-- DONE ✅
-- ==========================================


Screenshots
![WhatsApp Image 2025-11-07 at 17 05 07_6cb0d13f](https://github.com/user-attachments/assets/c17b8d51-9d5b-4a10-ad45-bfe09e9b1782)
![WhatsApp Image 2025-11-07 at 17 05 06_831f47a2](https://github.com/user-attachments/assets/b062265f-f580-44bf-be0d-1bb6c1966854)
![WhatsApp Image 2025-11-07 at 17 05 06_246348ca](https://github.com/user-attachments/assets/dd659088-b5b9-42a6-b8d3-cd052fecc2be)
![WhatsApp Image 2025-11-07 at 17 05 06_26b09d96](https://github.com/user-attachments/assets/860bb711-e71c-48eb-be17-228e7cd13979)
![WhatsApp Image 2025-11-07 at 17 05 05_5fc80d99](https://github.com/user-attachments/assets/a4976987-bf6d-4307-bea6-49562a47525f)
![WhatsApp Image 2025-11-07 at 17 05 05_58b9aea1](https://github.com/user-attachments/assets/38107509-2495-4058-850e-d62519737b73)
![WhatsApp Image 2025-11-07 at 17 05 04_3355b4ce](https://github.com/user-attachments/assets/53ff1718-3cc1-4782-9e04-6aa5a6127169)
![WhatsApp Image 2025-11-07 at 17 05 04_ef97f89b](https://github.com/user-attachments/assets/854ac70f-5470-4e6a-89ba-8a51383b5002)
![WhatsApp Image 2025-11-07 at 17 05 03_43aae3da](https://github.com/user-attachments/assets/f591aa1c-21a2-4ccd-9384-465214ef82d3)
![WhatsApp Image 2025-11-07 at 17 05 03_042bca64](https://github.com/user-attachments/assets/90a4b82a-b3e0-4294-b4e4-2cc951b62eba)
![WhatsApp Image 2025-11-07 at 17 05 01_da86a055](https://github.com/user-attachments/assets/2f7da4f9-98d2-47bb-85eb-adf93dafaf0e)
![WhatsApp Image 2025-11-07 at 17 05 01_a11ca21b](https://github.com/user-attachments/assets/f0d8e52b-5820-4bde-85f1-553a4b1196f9)



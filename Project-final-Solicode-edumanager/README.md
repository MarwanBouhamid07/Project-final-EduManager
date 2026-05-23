# 🎓 EduManager

**EduManager** is a complete, lightweight, and highly responsive **Student and Financial Management System** built to help educational institutions (schools, tutoring centers, and academies) easily track their day-to-day operations.

---

## 🚀 Key Features

* **Student Management**: Easily register new students, edit their profiles, and assign them to specific Classes and Grades.
* **Organized Classrooms**: A dynamic nested interface to seamlessly browse through exactly which students belong to which class (`Class A`, `Class B`, etc.) inside each grade levels.
* **Payment Tracking**: Record monthly tuition fees safely with color-coded status badges (`Paid`, `Due Soon`, `Late`, `Unpaid`).
* **Expense Management**: Log the school's general and operational expenses directly in the dashboard alongside income.
* **Financial Reports**: Get real-time, side-by-side breakdowns of Revenue vs. Expenses so you always know exactly how much the business is earning.
* **Overdue Highlights**: Automatically flag students who are falling behind on payments.
* **PDF Receipts**: Generate professional, downloadable PDF receipts dynamically for student payments.

---

## 🛠️ Tech Stack

* **Backend**: PHP 8+
* **Database**: MySQL (using PDO for highly secure querying)
* **Frontend Design**: Custom HTML5, Vanilla CSS3 (with responsive layouts and Glassmorphism design aesthetics), and modern Vanilla JavaScript interactions.

---

## 🛡️ Security Measures Built-In

EduManager was built with top-tier security standards in mind. Here is exactly how the system protects your data:

#### 1. Preventing SQL Injection Attacks (SQLi)
* All database transactions going through the system heavily utilize **PDO Prepared Statements with bound parameters**. This means that user inputs are treated strictly as "data" and not executable code automatically preventing malicious users from destroying the database or stealing private student records.

#### 2. Cross-Site Request Forgery Protection (CSRF Tokens)
* Every single sensitive action in the system (adding a student, submitting a payment, clearing the database, executing deletions) requires a unique **CSRF Token** strictly injected into the frontend HTML forms. 
* The backend (`verify_csrf_token()`) authenticates that exact token before altering the database, guaranteeing that an attacker cannot spoof destructive requests originating from fake external websites.

#### 3. Cross-Site Scripting Protection (XSS)
* **Input Sanitization**: Before saving any data submitted in a form, EduManager passes it through a central `sanitize()` helper function to dynamically strip slashes, trailing spaces, and potentially malicious tags.
* **Safe Output Formatting**: When printing dynamic database content on the dashboard screens (like student names or class lists), the system consistently uses `htmlspecialchars()` to escape string properties. If an attacker somehow slipped script code (`<script>`) into a field, it is rendered harmlessly as plain text.

#### 4. Session-Based Authentication
* All private dashboard files unconditionally execute `check_login()` continuously verifying the backend session states securely. No unauthenticated user can skip the login page and browse internal school data by guessing URLs. 

---

## ⚙️ Installation Instructions

1. **Prerequisites**: Install XAMPP, WAMP, or any Apache/MySQL server.
2. **Move to Host**: Place the project folder into your `htdocs` (or `www`) local server directory.
3. **Database Import**: 
   * Open `PhpMyAdmin` and create a blank database named: `student_payment_system` (or whatever aligns with your `config/database.php`).
   * Import the provided SQL structure dump to automatically build out the unified `students`, `payments`, `expenses`, and `users` tables.
4. **Configuration Check**: Ensure `/config/database.php` possesses your local server username (often `root`) and password.
5. **Start Application**: Proceed to `http://localhost/path-to-folder/login.php` on your browser to authorize access.

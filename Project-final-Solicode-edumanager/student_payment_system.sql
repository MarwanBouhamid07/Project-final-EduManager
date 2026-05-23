
CREATE DATABASE student_payment_system;
USE student_payment_system;


CREATE TABLE students (
    id INT AUTO_INCREMENT PRIMARY KEY,          
    full_name VARCHAR(100) NOT NULL,           
    phone VARCHAR(20) NOT NULL,               
    email VARCHAR(100),                         
    grade VARCHAR(50),                         
    classroom VARCHAR(50),                    
    monthly_fee DECIMAL(10,2) NOT NULL,        
    billing_day INT DEFAULT 1,                  
    status VARCHAR(20) DEFAULT 'active',       
    registration_date DATE NOT NULL            
);

CREATE TABLE payments (
    id INT AUTO_INCREMENT PRIMARY KEY,         
    student_id INT NOT NULL,                   
    amount DECIMAL(10,2) NOT NULL,           
    payment_date DATE NOT NULL,          
    next_due_date DATE NOT NULL,              
    payment_method VARCHAR(20) DEFAULT 'cash',   
    receipt_number VARCHAR(50) UNIQUE NOT NULL   
);

ALTER TABLE payments 
ADD FOREIGN KEY (student_id) REFERENCES students(id);


CREATE TABLE expenses (
    id INT AUTO_INCREMENT PRIMARY KEY,        
    category VARCHAR(100) NOT NULL,           
    amount DECIMAL(10,2) NOT NULL,             
    description TEXT,                          
    expense_date DATE NOT NULL                 
);


CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,       
    password VARCHAR(255) NOT NULL              
);

INSERT INTO users (username, password) VALUES
('admin', '$2y$10$8uiZYgEqe8LMWqwMeLVDv.moD3Vww7Xt9SoJnP7rbKC3yN5bLiW8a');


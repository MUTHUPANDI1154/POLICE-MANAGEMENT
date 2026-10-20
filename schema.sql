-- Three-Tier Online Examination Database Schema Script
-- Database Name: online_exam

CREATE DATABASE IF NOT EXISTS online_exam;
USE online_exam;

-- 1. Table for Storing Examination Questions
CREATE TABLE IF NOT EXISTS questions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    question_text TEXT NOT NULL,
    option1 VARCHAR(255) NOT NULL,
    option2 VARCHAR(255) NOT NULL,
    option3 VARCHAR(255) NOT NULL,
    option4 VARCHAR(255) NOT NULL,
    correct_option INT NOT NULL
);

-- Seed Sample Questions
TRUNCATE TABLE questions;

INSERT INTO questions (question_text, option1, option2, option3, option4, correct_option) VALUES
('Which of the following technology is used in Presentation Tier for web applications?', 'Java Beans', 'JSP', 'JDBC Driver', 'Servlet API', 2),
('What is the full form of JSP?', 'Java Special Pages', 'Java Server Pages', 'Java System Protocol', 'Java Socket Process', 2),
('Which implicit object is used to transfer request data between JSP pages?', 'request', 'response', 'session', 'application', 1),
('What role does JDBC play in a 3-tier Java web application architecture?', 'Renders user interface', 'Performs client-side validation', 'Connects Java logic to Database Tier', 'Handles HTTP sessions', 3),
('Which method of HttpServlet is invoked during HTTP POST submission?', 'doGet()', 'doPost()', 'service()', 'init()', 2);

-- 2. Table for Storing Examination Results
CREATE TABLE IF NOT EXISTS exam_results (
    id INT AUTO_INCREMENT PRIMARY KEY,
    candidate_name VARCHAR(100) NOT NULL,
    score INT NOT NULL,
    total_questions INT NOT NULL,
    percentage DOUBLE NOT NULL,
    status VARCHAR(20) NOT NULL,
    exam_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

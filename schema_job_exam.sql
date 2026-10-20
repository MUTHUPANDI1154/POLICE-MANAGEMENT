-- Three-Tier Online Examination / Job Application Database Schema
-- Database Name: job_portal_db

CREATE DATABASE IF NOT EXISTS `job_portal_db`;
USE `job_portal_db`;

-- 1. Candidate Examination & Job Results Table
CREATE TABLE IF NOT EXISTS `job_exam_results` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `app_number` VARCHAR(50) NOT NULL,
    `score` INT NOT NULL,
    `total_questions` INT NOT NULL,
    `submission_time` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 2. Questions Repository Table (Optional 3-Tier Data Layer extension)
CREATE TABLE IF NOT EXISTS `job_questions` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `question_text` TEXT NOT NULL,
    `option1` VARCHAR(255) NOT NULL,
    `option2` VARCHAR(255) NOT NULL,
    `option3` VARCHAR(255) NOT NULL,
    `correct_option` INT NOT NULL
);

-- Seed Initial Questions matching the UI
TRUNCATE TABLE `job_questions`;

INSERT INTO `job_questions` (`question_text`, `option1`, `option2`, `option3`, `correct_option`) VALUES
('Which skill is important for a software developer?', 'Programming', 'Cooking', 'Driving', 1),
('Which document is commonly required when applying for a job?', 'Resume', 'Movie Ticket', 'Recipe', 1),
('Which platform is commonly used for professional networking?', 'LinkedIn', 'Instagram', 'Gaming Console', 1);

CREATE DATABASE IF NOT EXISTS basc_quiz CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE basc_quiz;

CREATE TABLE IF NOT EXISTS students (
  id INT AUTO_INCREMENT PRIMARY KEY,
  first_name VARCHAR(50) NOT NULL,
  last_name  VARCHAR(50) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY uniq_student_name (first_name, last_name)
);

-- One attempt only per student:
CREATE TABLE IF NOT EXISTS attempts (
  id INT AUTO_INCREMENT PRIMARY KEY,
  student_id INT NOT NULL,
  score_mcq INT NOT NULL DEFAULT 0,
  score_ident INT NOT NULL DEFAULT 0,
  total_score INT NOT NULL DEFAULT 0,
  time_seconds INT NOT NULL DEFAULT 0,
  submitted TINYINT(1) NOT NULL DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
  UNIQUE KEY uniq_one_attempt (student_id)
);

CREATE TABLE IF NOT EXISTS attempt_answers (
  id INT AUTO_INCREMENT PRIMARY KEY,
  attempt_id INT NOT NULL,
  q_key VARCHAR(40) NOT NULL,
  q_type ENUM('mcq','ident') NOT NULL,
  given_answer VARCHAR(255) NOT NULL,
  is_correct TINYINT(1) NOT NULL DEFAULT 0,
  FOREIGN KEY (attempt_id) REFERENCES attempts(id) ON DELETE CASCADE
);

-- Fee Categories (Tuition, Misc, Lab, etc.)
CREATE TABLE fee_schedules (
    fee_id INT AUTO_INCREMENT PRIMARY KEY,
    fee_name VARCHAR(100),
    amount DECIMAL(10,2),
    category ENUM('Tuition', 'Miscellaneous', 'Other', 'Laboratory')
);

-- Student Financial Accounts
CREATE TABLE student_accounts (
    account_id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT,
    total_assessment DECIMAL(10,2),
    total_paid DECIMAL(10,2) DEFAULT 0.00,
    balance DECIMAL(10,2),
    FOREIGN KEY (student_id) REFERENCES school_db.students(student_id)
);

-- Realistic Sample Data
INSERT INTO fee_schedules (fee_name, amount, category) VALUES 
('Tuition Fee (Per Unit)', 550.00, 'Tuition'),
('Registration Fee', 250.00, 'Miscellaneous'),
('Library Fee', 500.00, 'Miscellaneous'),
('Medical/Dental Fee', 350.00, 'Miscellaneous'),
('Athletic Fee', 300.00, 'Miscellaneous'),
('IT/Computer Lab Fee', 1200.00, 'Laboratory'),
('Energy Fee', 1500.00, 'Other'),
('Student Council Fee', 150.00, 'Other');

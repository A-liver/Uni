CREATE TABLE billing_master (
    id INT AUTO_INCREMENT PRIMARY KEY,
    school_year VARCHAR(20),
    semester VARCHAR(10),
    fee_type VARCHAR(100),
    amount DECIMAL(10,2)
);

INSERT INTO billing_master (school_year, semester, fee_type, amount)
VALUES 
('2025-2026', '1st', 'Tuition Fee', 15000),
('2025-2026', '1st', 'Library Fee', 500),
('2025-2026', '1st', 'Miscellaneous', 2000);


CREATE TABLE billing_master (
    id INT AUTO_INCREMENT PRIMARY KEY,
    school_year VARCHAR(20),
    semester VARCHAR(10),
    fee_type VARCHAR(100),
    amount DECIMAL(10,2),
    category VARCHAR(50)   -- added to match your photo
);

INSERT INTO billing_master (school_year, semester, fee_type, amount, category)
VALUES
('2025-2026', '1st', 'Tuition Fee ', 550.00, 'Tuition'),
('2025-2026', '1st', 'Registration Fee', 250.00, 'Miscellaneous'),
('2025-2026', '1st', 'Library Fee', 500.00, 'Miscellaneous'),
('2025-2026', '1st', 'Medical/Dental Fee', 350.00, 'Miscellaneous'),
('2025-2026', '1st', 'Athletic Fee', 300.00, 'Miscellaneous'),
('2025-2026', '1st', 'IT/Computer Lab Fee', 1200.00, 'Laboratory'),
('2025-2026', '1st', 'Energy Fee', 1500.00, 'Other'),
('2025-2026', '1st', 'Student Council Fee', 150.00, 'Other');

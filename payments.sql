CREATE TABLE payments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT,
    school_year VARCHAR(20),
    semester VARCHAR(10),
    amount DECIMAL(10,2),
    payment_date DATETIME
);

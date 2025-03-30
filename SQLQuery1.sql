create database Library;
use Library;

CREATE TABLE Publisher (
    publisher_id INT PRIMARY KEY,
    name VARCHAR(255) NOT NULL
);

INSERT INTO Publisher (publisher_id, name) VALUES
(1, 'Penguin Books'),
(2, 'HarperCollins'),
(3, 'Oxford Press'),
(4, 'Macmillan'),
(5, 'Random House');

CREATE TABLE Member (
    member_id INT PRIMARY KEY,
    name VARCHAR(255) NOT NULL
);

INSERT INTO Member (member_id, name) VALUES
(101, 'Ahmed Ali'),
(102, 'Sarah Mohammed'),
(103, 'Khalid Hassan'),
(104, 'Lina Saeed'),
(105, 'Omar Youssef');

CREATE TABLE Book (
    book_id INT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    publisher_id INT,
    FOREIGN KEY (publisher_id) REFERENCES Publisher(publisher_id) ON DELETE CASCADE
);

INSERT INTO Book (book_id, title, publisher_id) VALUES
(1001, 'Database Systems', 1),
(1002, 'Machine Learning Basics', 2),
(1003, 'Artificial Intelligence', 3),
(1004, 'Networking Essentials', 4),
(1005, 'Python Programming', 5),
(1006, 'Data Structures', 1),
(1007, 'Cloud Computing', 2);

CREATE TABLE Demand (
    demand_date DATE,
    product_id INT,
    qty INT
);
INSERT INTO Demand (demand_date, product_id, qty) VALUES
('2025-03-01', 1, 10),
('2025-03-02', 1, 5),
('2025-03-03', 1, 2),
('2025-03-04', 1, 7),
('2025-03-01', 2, 15),
('2025-03-02', 2, 3);


CREATE TABLE Borrowed (
    member_id INT,
    book_id INT,
    borrow_date DATE,
    PRIMARY KEY (member_id, book_id, borrow_date),
    FOREIGN KEY (member_id) REFERENCES Member(member_id) ON DELETE CASCADE,
    FOREIGN KEY (book_id) REFERENCES Book(book_id) ON DELETE CASCADE
);

INSERT INTO Borrowed (member_id, book_id, borrow_date) VALUES
(101, 1001, '2025-03-01'),
(101, 1003, '2025-03-02'),
(102, 1002, '2025-03-03'),
(102, 1005, '2025-03-05'),
(103, 1004, '2025-03-06'),
(103, 1001, '2025-03-07'),
(104, 1006, '2025-03-08'),
(104, 1003, '2025-03-09'),
(105, 1002, '2025-03-10'),
(105, 1007, '2025-03-11');

SELECT DISTINCT m.member_id, m.name
FROM Member m
JOIN Borrowed b ON m.member_id = b.member_id
JOIN Book bk ON b.book_id = bk.book_id
JOIN Publisher p ON bk.publisher_id = p.publisher_id
WHERE p.name = 'Your Favorite Publisher';

SELECT m.member_id, m.name
FROM Member m
WHERE NOT EXISTS (
    SELECT b.book_id
    FROM Book b
    JOIN Publisher p ON b.publisher_id = p.publisher_id
    WHERE p.name = 'Your Favorite Publisher'
    EXCEPT
    SELECT br.book_id
    FROM Borrowed br
    WHERE br.member_id = m.member_id
);

SELECT p.name AS publisher_name, m.member_id, m.name
FROM Borrowed b
JOIN Book bk ON b.book_id = bk.book_id
JOIN Publisher p ON bk.publisher_id = p.publisher_id
JOIN Member m ON b.member_id = m.member_id
GROUP BY p.name, m.member_id, m.name
HAVING COUNT(bk.book_id) > 5;
 
 SELECT * FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_NAME = 'Demand';


SELECT (COUNT(*) * 1.0 / (SELECT COUNT(*) FROM Member)) AS avg_books_per_member
FROM Borrowed;

SELECT demand_date, product_id, qty,
       SUM(qty) OVER (PARTITION BY product_id ORDER BY demand_date) AS cumulative_qty
FROM Demand;


SELECT product_id, demand_date, qty
FROM (
    SELECT product_id, demand_date, qty,
           RANK() OVER (PARTITION BY product_id ORDER BY qty ASC) AS rank_order
    FROM Demand
) ranked
WHERE rank_order <= 2;
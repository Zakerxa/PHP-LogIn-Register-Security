CREATE TABLE users (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(100) NOT NULL,
    salt VARCHAR(10) NOT NULL,
    email VARCHAR(50) NOT NULL,
    token VARCHAR(30) NOT NULL,
    created_date VARCHAR(50) NOT NULL
)
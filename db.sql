
CREATE DATABASE IF NOT EXISTS daewoo_transport;
USE daewoo_transport;


CREATE TABLE IF NOT EXISTS admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);


INSERT INTO admins (username, password) VALUES 
('admin', '$2y$10$9BJeKFe8r9Z4kWo8z6coEeZcLBuk8oY96ydGbzRgfwCgkUg0ZFnLa');


CREATE TABLE IF NOT EXISTS routes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    source VARCHAR(100),
    destination VARCHAR(100),
    fare DECIMAL(10,2)
);


INSERT INTO routes (source, destination, fare) VALUES
('Abbottabad', 'Lahore', 2000.00),
('Lahore', 'Karachi', 3500.00),
('Abbottabad', 'Peshawar', 1500.00),
('Abbottabad', 'Multan', 2200.00);


CREATE TABLE IF NOT EXISTS bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    phone VARCHAR(20),
    route VARCHAR(255), 
    time TIME,
    date DATE,
    fare DECIMAL(10,2),
    seat_no INT
);

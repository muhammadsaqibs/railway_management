CREATE DATABASE IF NOT EXISTS railway_management;
USE railway_management;

-- Admin Table
CREATE TABLE Admin (
    admin_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(100) NOT NULL
);

-- Passenger Table
CREATE TABLE Passenger (
    passenger_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    phone VARCHAR(15),
    cnic VARCHAR(20),
    password VARCHAR(100) NOT NULL
);

-- Train Table
CREATE TABLE Train (
    train_id INT AUTO_INCREMENT PRIMARY KEY,
    train_name VARCHAR(100),
    from_station VARCHAR(100),
    to_station VARCHAR(100),
    total_seats INT,
    available_seats INT,
    schedule_time TIME
);

-- Ticket Table
CREATE TABLE Ticket (
    ticket_id INT AUTO_INCREMENT PRIMARY KEY,
    passenger_id INT,
    train_id INT,
    seat_no INT,
    source_city VARCHAR(100),
    destination_city VARCHAR(100),
    cnic VARCHAR(20),
    status VARCHAR(20) DEFAULT 'Booked',
    FOREIGN KEY (passenger_id) REFERENCES Passenger(passenger_id),
    FOREIGN KEY (train_id) REFERENCES Train(train_id)
);

-- Payment Table
CREATE TABLE Payment (
    payment_id INT AUTO_INCREMENT PRIMARY KEY,
    ticket_id INT,
    amount DECIMAL(10,2),
    payment_method VARCHAR(50),
    payment_status VARCHAR(20) DEFAULT 'Paid',
    FOREIGN KEY (ticket_id) REFERENCES Ticket(ticket_id)
);

-- Insert Default Admin
INSERT INTO Admin (username, password) VALUES ('admin', 'admin123');

-- Insert 4 Default Trains
INSERT INTO Train (train_name, from_station, to_station, total_seats, available_seats, schedule_time) VALUES
('Green Line', 'Karachi', 'Lahore', 80, 80, '08:00:00'),
('Karachi Express', 'Karachi', 'Islamabad', 100, 100, '10:30:00'),
('Awam Express', 'Lahore', 'Multan', 90, 90, '12:00:00'),
('Tezgam', 'Rawalpindi', 'Karachi', 120, 120, '18:00:00');

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
    schedule_time TIME,
    departure_time TIME,
    arrival_time TIME,
    fare DECIMAL(10,2)
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
    ticket_token VARCHAR(20),
    booking_time DATETIME,
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

-- Insert Default Trains (3 trains per route)
INSERT INTO Train (train_name, from_station, to_station, total_seats, available_seats, schedule_time, departure_time, arrival_time, fare) VALUES
-- Karachi to Lahore
('Green Line Express', 'Karachi', 'Lahore', 80, 80, '08:00:00', '08:00:00', '16:00:00', 1500.00),
('Karachi Lahore Fast', 'Karachi', 'Lahore', 100, 100, '14:00:00', '14:00:00', '22:00:00', 1600.00),
('Lahore Express', 'Karachi', 'Lahore', 90, 90, '20:00:00', '20:00:00', '04:00:00', 1400.00),

-- Karachi to Islamabad
('Karachi Express', 'Karachi', 'Islamabad', 100, 100, '10:30:00', '10:30:00', '22:30:00', 2500.00),
('Islamabad Direct', 'Karachi', 'Islamabad', 120, 120, '06:00:00', '06:00:00', '18:00:00', 2600.00),
('Capital Express', 'Karachi', 'Islamabad', 110, 110, '16:00:00', '16:00:00', '04:00:00', 2400.00),

-- Lahore to Multan
('Awam Express', 'Lahore', 'Multan', 90, 90, '12:00:00', '12:00:00', '15:00:00', 800.00),
('Multan Fast', 'Lahore', 'Multan', 85, 85, '18:00:00', '18:00:00', '21:00:00', 750.00),
('Punjab Express', 'Lahore', 'Multan', 95, 95, '08:00:00', '08:00:00', '11:00:00', 850.00),

-- Rawalpindi to Karachi
('Tezgam', 'Rawalpindi', 'Karachi', 120, 120, '18:00:00', '18:00:00', '06:00:00', 2000.00),
('Karachi Tezgam', 'Rawalpindi', 'Karachi', 115, 115, '22:00:00', '22:00:00', '10:00:00', 2100.00),
('Northern Express', 'Rawalpindi', 'Karachi', 125, 125, '14:00:00', '14:00:00', '02:00:00', 1900.00);

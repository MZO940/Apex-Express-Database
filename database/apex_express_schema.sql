/**
* APEX EXPRESS DATABASE SCHEMA
* INTRODUCTION TO DATABASE SYSTEMS - GROUP PROJECT
* 
* MUHAMMAD HUZAIFA HAMEED - BCS251022
* ZAIN UL ABIDEEN MOHSIN - BCS251065
* NOOR UL AIN AHMED - BCS251066
* ATIQUE UR REHMAN - BCS251080
* HAIDER IFTIKHAR - BCS251062
*/

DROP SCHEMA IF EXISTS apex_express_db;
CREATE SCHEMA apex_express_db COLLATE = utf8_general_ci;

USE apex_express_db;


/* ------------------- TABLE CREATION ------------------- */
CREATE TABLE `admin` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `admin_username` VARCHAR(50) NOT NULL,
  `admin_pswd` VARCHAR(255) NOT NULL
);

CREATE TABLE `branch` (
  `Branch_ID` varchar(50) NOT NULL,
  `Name` varchar(100) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `Street_no` varchar(50) NOT NULL,
  `Area` varchar(100) NOT NULL,
  `City` varchar(50) NOT NULL,
  PRIMARY KEY (`Branch_ID`)
);

CREATE TABLE `branch_phone` (
  `Branch_ID` varchar(50) NOT NULL,
  `Phone_no` varchar(20) NOT NULL,
  PRIMARY KEY (`Branch_ID`,`Phone_no`)
);

CREATE TABLE `delivery_status` (
  `Status_id` varchar(50) NOT NULL,
  `Status_type` enum('Booked','In-Transit','Out for Delivery','Completed','Failed') NOT NULL,
  `Remarks` text DEFAULT NULL,
  `Attempts` int(11) DEFAULT 0,
  PRIMARY KEY (`Status_id`)
);

CREATE TABLE `parcel` (
  `Parcel_ID` int(11) NOT NULL AUTO_INCREMENT,
  `Tracking_id` varchar(50) NOT NULL,
  `Weight` decimal(10,2) NOT NULL,
  `Book_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `Origin_city` varchar(50) NOT NULL,
  `Destination_city` varchar(50) NOT NULL,
  `Sender_ID` varchar(50) NOT NULL,
  `Reciever_ID` varchar(50) NOT NULL,
  `Branch_ID` varchar(50) NOT NULL,
  `Rider_ID` varchar(50) DEFAULT NULL,
  `Status_id` varchar(50) NOT NULL,
  `price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `payment_option` enum('Cash on Delivery','Easypaisa','JazzCash','Bank Transfer') NOT NULL DEFAULT 'Cash on Delivery',
  PRIMARY KEY (`Parcel_ID`),
  UNIQUE KEY `Tracking_id` (`Tracking_id`),
  UNIQUE KEY `Unique_Parcel_Status` (`Status_id`),
  KEY `Sender_ID` (`Sender_ID`),
  KEY `Reciever_ID` (`Reciever_ID`),
  KEY `Branch_ID` (`Branch_ID`),
  KEY `Rider_ID` (`Rider_ID`)
);

CREATE TABLE `reciever` (
  `Reciever_ID` varchar(50) NOT NULL,
  `First_name` varchar(50) NOT NULL,
  `Last_name` varchar(50) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `Postal_code` varchar(20) NOT NULL,
  `Street_no` varchar(50) NOT NULL,
  `Area` varchar(100) NOT NULL,
  `City` varchar(50) NOT NULL,
  PRIMARY KEY (`Reciever_ID`)
);

CREATE TABLE `reciever_phone` (
  `Reciever_ID` varchar(50) NOT NULL,
  `Phone_no` varchar(20) NOT NULL,
  PRIMARY KEY (`Reciever_ID`,`Phone_no`)
);

CREATE TABLE `rider` (
  `Rider_ID` varchar(50) NOT NULL,
  `First_name` varchar(50) NOT NULL,
  `Last_name` varchar(50) NOT NULL,
  `Cnic_no` varchar(20) NOT NULL,
  `Branch_ID` varchar(50) NOT NULL,
  PRIMARY KEY (`Rider_ID`),
  UNIQUE KEY `Cnic_no` (`Cnic_no`),
  KEY `Branch_ID` (`Branch_ID`)
);

CREATE TABLE `rider_phone` (
  `Rider_ID` varchar(50) NOT NULL,
  `Phone_no` varchar(20) NOT NULL,
  PRIMARY KEY (`Rider_ID`,`Phone_no`)
);

CREATE TABLE `sender` (
  `Sender_ID` varchar(50) NOT NULL,
  `First_name` varchar(50) NOT NULL,
  `Last_name` varchar(50) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `Street_no` varchar(50) NOT NULL,
  `Area` varchar(100) NOT NULL,
  `City` varchar(50) NOT NULL,
  PRIMARY KEY (`Sender_ID`)
);

CREATE TABLE `sender_phone` (
  `Sender_ID` varchar(50) NOT NULL,
  `Phone_no` varchar(20) NOT NULL,
  PRIMARY KEY (`Sender_ID`,`Phone_no`)
);

/* ------------------- DATA INSERTION ------------------- */
LOCK TABLES `admin` WRITE;
INSERT INTO `admin` (`admin_username`, `admin_pswd`) VALUES ('admin', 'admin123');
UNLOCK TABLES;

LOCK TABLES `branch` WRITE;
INSERT INTO `branch` (`Branch_ID`, `Name`, `Email`, `Street_no`, `Area`, `City`) VALUES
('BR-001', 'Apex Express Blue Area', 'bluearea@apex.com', 'Plot 14-B', 'Blue Area', 'Islamabad'),
('BR-002', 'Apex Express Gulberg', 'gulberg@apex.com', '52-Main Blvd', 'Gulberg III', 'Lahore'),
('BR-003', 'Apex Express Clifton', 'clifton@apex.com', 'D-12', 'Block 5, Clifton', 'Karachi'),
('BR-004', 'Apex Express Saddar', 'saddar.rwp@apex.com', 'Shop 4', 'Saddar', 'Rawalpindi'),
('BR-005', 'Apex Express Nishatabad', 'faisalabad@apex.com', 'St 2', 'Nishatabad', 'Faisalabad'),
('BR-006', 'Apex Express Bosan Road', 'multan@apex.com', '12-A', 'Bosan Road', 'Multan'),
('BR-007', 'Apex Express University Rd', 'peshawar@apex.com', 'G-5', 'University Road', 'Peshawar'),
('BR-008', 'Apex Express Cantt', 'quetta@apex.com', '24', 'Cantt Area', 'Quetta'),
('BR-009', 'Apex Express Small Industrial', 'sialkot@apex.com', 'B-III', 'SIE', 'Sialkot'),
('BR-010', 'Apex Express Satellite Town', 'gujranwala@apex.com', 'Block C', 'Satellite Town', 'Gujranwala');
UNLOCK TABLES;

LOCK TABLES `branch_phone` WRITE;
INSERT INTO `branch_phone` (`Branch_ID`, `Phone_no`) VALUES
('BR-001', '051-111-273-901'),
('BR-001', '051-2271011'),
('BR-002', '042-35712345'),
('BR-003', '021-35876543'),
('BR-004', '051-5561122'),
('BR-005', '041-8754321'),
('BR-006', '061-4512345'),
('BR-007', '091-5843210'),
('BR-008', '081-2834567'),
('BR-009', '052-4298765'),
('BR-010', '055-3841122');
UNLOCK TABLES;

LOCK TABLES `sender` WRITE;
INSERT INTO `sender` (`Sender_ID`, `First_name`, `Last_name`, `Email`, `Street_no`, `Area`, `City`) VALUES
('SND-001', 'Ahmed', 'Ali', 'ahmed.ali@email.com', 'House 12', 'G-11/2', 'Islamabad'),
('SND-002', 'Fatima', 'Khan', 'fatima.k@email.com', 'Apartment 4B', 'Model Town', 'Lahore'),
('SND-003', 'Muhammad', 'Mustafa', 'mustafa@email.com', 'Street 3', 'DHA Phase 6', 'Karachi'),
('SND-004', 'Zainab', 'Bibi', 'zainab.b@email.com', 'House 55A', 'Chaklala Scheme 3', 'Rawalpindi'),
('SND-005', 'Bilal', 'Siddiqui', 'bilal.s@email.com', 'Shop 12', 'Anarkali', 'Lahore'),
('SND-006', 'Ayesha', 'Omar', 'ayesha.o@email.com', 'House 9', 'Gulgasht Colony', 'Multan'),
('SND-007', 'Hamza', 'Sheikh', 'hamza.s@email.com', 'St 5', 'Hayatabad Phase 3', 'Peshawar'),
('SND-008', 'Sana', 'Malik', 'sana.m@email.com', 'House 102', 'Samungli Road', 'Quetta'),
('SND-009', 'Usman', 'Dar', 'usman.d@email.com', 'Plot 45', 'Paris Road', 'Sialkot'),
('SND-010', 'Mariam', 'Javed', 'mariam.j@email.com', 'House 18', 'DC Colony', 'Gujranwala');
UNLOCK TABLES;

LOCK TABLES `sender_phone` WRITE;
INSERT INTO `sender_phone` (`Sender_ID`, `Phone_no`) VALUES
('SND-001', '0300-1234567'),
('SND-002', '0321-7654321'),
('SND-003', '0333-9876543'),
('SND-004', '0345-1122334'),
('SND-005', '0312-4455667'),
('SND-006', '0301-9988776'),
('SND-007', '0322-5544332'),
('SND-008', '0334-6677889'),
('SND-009', '0346-8899001'),
('SND-010', '0315-2233445');
UNLOCK TABLES;

LOCK TABLES `reciever` WRITE;
INSERT INTO `reciever` (`Reciever_ID`, `First_name`, `Last_name`, `Email`, `Postal_code`, `Street_no`, `Area`, `City`) VALUES
('RCV-001', 'Zayd', 'Aslam', 'zayd.a@email.com', '44000', 'House 99', 'F-8/1', 'Islamabad'),
('RCV-002', 'Hina', 'Riaz', 'hina.r@email.com', '54000', 'Flat 12', 'Johar Town', 'Lahore'),
('RCV-003', 'Ali', 'Raza', 'ali.raza@email.com', '75500', 'House 23-C', 'DHA Phase 2', 'Karachi'),
('RCV-004', 'Amna', 'Farooq', 'amna.f@email.com', '46000', 'St 10', 'Westridge', 'Rawalpindi'),
('RCV-005', 'Tariq', 'Mehmood', 'tariq.m@email.com', '38000', 'House 4', 'Peoples Colony', 'Faisalabad'),
('RCV-006', 'Khadija', 'Shah', 'khadija.s@email.com', '60000', 'House 77', 'Shah Rukn-e-Alam', 'Multan'),
('RCV-007', 'Asif', 'Khan', 'asif.k@email.com', '25000', 'Khyber Bazar', 'City Area', 'Peshawar'),
('RCV-008', 'Noreen', 'Akram', 'noreen.a@email.com', '87300', 'St 2', 'Jinnah Town', 'Quetta'),
('RCV-009', 'Faisal', 'Iqbal', 'faisal.i@email.com', '51310', 'House 15', 'Marala Road', 'Sialkot'),
('RCV-010', 'Waqas', 'Ahmed', 'waqas.a@email.com', '52250', 'Plot 8', 'Peoples Colony', 'Gujranwala');
UNLOCK TABLES;

LOCK TABLES `reciever_phone` WRITE;
INSERT INTO `reciever_phone` (`Reciever_ID`, `Phone_no`) VALUES
('RCV-001', '0300-9876543'),
('RCV-002', '0321-1234567'),
('RCV-003', '0333-4455667'),
('RCV-004', '0345-9988776'),
('RCV-005', '0312-1122334'),
('RCV-006', '0301-5544332'),
('RCV-007', '0322-6677889'),
('RCV-008', '0334-8899001'),
('RCV-009', '0346-2233445'),
('RCV-010', '0315-7654321');
UNLOCK TABLES;

LOCK TABLES `rider` WRITE;
INSERT INTO `rider` (`Rider_ID`, `First_name`, `Last_name`, `Cnic_no`, `Branch_ID`) VALUES
('RDR-001', 'Kamran', 'Akmal', '35202-1234567-1', 'BR-001'),
('RDR-002', 'Sajid', 'Khan', '35202-7654321-3', 'BR-002'),
('RDR-003', 'Asad', 'Shafiq', '42201-9876543-5', 'BR-003'),
('RDR-004', 'Babark', 'Zaman', '37405-1122334-7', 'BR-004'),
('RDR-005', 'Imran', 'Nazir', '33100-4455667-9', 'BR-005'),
('RDR-006', 'Junaid', 'Khan', '36302-9988776-1', 'BR-006'),
('RDR-007', 'Yasir', 'Shah', '17301-5544332-3', 'BR-007'),
('RDR-008', 'Anwar', 'Ali', '54401-6677889-5', 'BR-008'),
('RDR-009', 'Haris', 'Rauf', '34603-8899001-7', 'BR-009'),
('RDR-010', 'Zaman', 'Khan', '34101-2233445-9', 'BR-010');
UNLOCK TABLES;

LOCK TABLES `rider_phone` WRITE;
INSERT INTO `rider_phone` (`Rider_ID`, `Phone_no`) VALUES
('RDR-001', '0302-1112223'),
('RDR-002', '0323-4445556'),
('RDR-003', '0331-7778889'),
('RDR-004', '0342-9990001'),
('RDR-005', '0311-2223334'),
('RDR-006', '0305-4445556'),
('RDR-007', '0324-7778889'),
('RDR-008', '0336-9990001'),
('RDR-009', '0347-2223334'),
('RDR-010', '0316-5556667');
UNLOCK TABLES;

LOCK TABLES `delivery_status` WRITE;
INSERT INTO `delivery_status` (`Status_id`, `Status_type`, `Remarks`, `Attempts`) VALUES
('STAT-001', 'Booked', 'Parcel registered successfully', 0),
('STAT-002', 'In-Transit', 'Left Lahore Hub going to Islamabad', 0),
('STAT-003', 'Out for Delivery', 'Rider out to drop package', 1),
('STAT-004', 'Completed', 'Delivered and cash collected', 1),
('STAT-005', 'Failed', 'Customer house locked', 3),
('STAT-006', 'Booked', 'Awaiting customs approval/clearance', 0),
('STAT-007', 'In-Transit', 'Arrived at Peshawar Sorting Facility', 0),
('STAT-008', 'Completed', 'Handed over to recipient family member', 1),
('STAT-009', 'Out for Delivery', 'Rider is making the first attempt', 1),
('STAT-010', 'Completed', 'Payment received via Bank Transfer', 1);
UNLOCK TABLES;

LOCK TABLES `parcel` WRITE;
INSERT INTO `parcel` (`Parcel_ID`, `Tracking_id`, `Weight`, `Book_date`, `Origin_city`, `Destination_city`, `Sender_ID`, `Reciever_ID`, `Branch_ID`, `Rider_ID`, `Status_id`, `price`, `payment_option`) VALUES
(1, 'APX-2026-001', 1.50, '2026-05-20 10:00:00', 'Islamabad', 'Karachi', 'SND-001', 'RCV-003', 'BR-001', 'RDR-001', 'STAT-001', 350.00, 'Cash on Delivery'),
(2, 'APX-2026-002', 0.50, '2026-05-21 11:30:00', 'Lahore', 'Islamabad', 'SND-002', 'RCV-001', 'BR-002', 'RDR-002', 'STAT-002', 200.00, 'Easypaisa'),
(3, 'APX-2026-003', 4.25, '2026-05-22 14:15:00', 'Karachi', 'Karachi', 'SND-003', 'RCV-003', 'BR-003', 'RDR-003', 'STAT-003', 650.00, 'Cash on Delivery'),
(4, 'APX-2026-004', 2.00, '2026-05-23 09:00:00', 'Rawalpindi', 'Lahore', 'SND-004', 'RCV-002', 'BR-004', 'RDR-004', 'STAT-004', 400.00, 'JazzCash'),
(5, 'APX-2026-005', 10.00, '2026-05-24 16:45:00', 'Faisalabad', 'Multan', 'SND-005', 'RCV-006', 'BR-005', 'RDR-005', 'STAT-005', 1200.00, 'Bank Transfer'),
(6, 'APX-2026-006', 1.20, '2026-05-25 12:00:00', 'Multan', 'Peshawar', 'SND-006', 'RCV-007', 'BR-006', NULL, 'STAT-006', 300.00, 'Cash on Delivery'),
(7, 'APX-2026-007', 3.10, '2026-05-25 15:30:00', 'Peshawar', 'Quetta', 'SND-007', 'RCV-008', 'BR-007', 'RDR-007', 'STAT-007', 550.00, 'Easypaisa'),
(8, 'APX-2026-008', 0.75, '2026-05-26 10:45:00', 'Quetta', 'Sialkot', 'SND-008', 'RCV-009', 'BR-008', 'RDR-008', 'STAT-008', 250.00, 'Cash on Delivery'),
(9, 'APX-2026-009', 5.00, '2026-05-26 13:20:00', 'Sialkot', 'Gujranwala', 'SND-009', 'RCV-010', 'BR-009', 'RDR-009', 'STAT-009', 700.00, 'JazzCash'),
(10, 'APX-2026-010', 2.50, '2026-05-27 08:10:00', 'Gujranwala', 'Islamabad', 'SND-010', 'RCV-001', 'BR-010', 'RDR-010', 'STAT-010', 450.00, 'Bank Transfer');
UNLOCK TABLES;

COMMIT;
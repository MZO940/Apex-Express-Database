# Apex Express Courier & Shipping Management System

> A comprehensive courier and shipping management system for Pakistan-wide parcel delivery operations.

**Project by:** Zain ul Abideen Mohsin, Muhammad Huzaifa Hameed, Noor ul Ain Ahmed, Atique ur Rehman, Haider Iftikhar  
**Course:** Introduction to Database Systems (Group Project)

---

## 📋 Table of Contents

1. [Project Overview](#project-overview)
2. [Tech Stack](#tech-stack)
3. [Features](#features)
4. [Database Structure](#database-structure)
5. [System Workflow](#system-workflow)
6. [File Structure](#file-structure)
7. [Key Functions](#key-functions)
8. [Setup Instructions](#setup-instructions)
9. [How to Use](#how-to-use)
10. [Known Issues & Limitations](#known-issues--limitations)
11. [Planned Improvements](#planned-improvements)
12. [Security Considerations](#security-considerations)

---

## 🎯 Project Overview

**Apex Express** is a professional courier management system designed to handle parcel bookings, tracking, and delivery operations across multiple branches in Pakistan. The system allows administrators to manage branches, riders, and parcels while customers can track shipments in real-time.

### Core Features

- **Admin Authentication System** - Secure login for system administrators
- **Branch Management** - Add and manage distribution centers across cities
- **Rider Management** - Register and organize delivery personnel
- **Parcel Booking** - Complete parcel registration with sender/receiver details
- **Real-time Tracking** - Customers can track parcels using tracking IDs
- **Delivery Status System** - Track parcel progress through delivery lifecycle
- **Multi-city Operations** - Support for 10+ major Pakistani cities
- **Payment Options** - Cash on Delivery, Easypaisa, JazzCash, Bank Transfer
- **Data Management** - View and manage all system data through admin dashboard

---

## 🛠️ Tech Stack

| Layer        | Technology           | Purpose                                                  |
| ------------ | -------------------- | -------------------------------------------------------- |
| **Backend**  | PHP 7.4+             | Server-side logic, database operations, business logic   |
| **Database** | MySQL 5.7+           | Data persistence, relationships, transactions            |
| **Frontend** | HTML5                | Semantic markup and structure                            |
|              | CSS3                 | Styling, responsive design, UI components                |
|              | JavaScript (Vanilla) | Client-side interactivity, form validation, image slider |
| **Server**   | Apache (XAMPP/WAMP)  | HTTP server for local/production deployment              |

---

## ✨ Features in Detail

### For Administrators

- Login with credentials (username: `admin`, password: `admin123`)
- Dashboard with navigation menu
- Add new branches with contact details
- Add new riders with CNIC verification
- Register complete parcel shipments
- View all system tables (branches, riders, senders, receivers, parcels, statuses)

### For Customers

- Track parcels using tracking ID
- View sender/receiver information
- Monitor delivery status (Booked, In-Transit, Out for Delivery, Completed, Failed)
- See delivery attempts and remarks

### Data Management

- Automatic ID generation for tracking
- Transaction-based parcel creation (all-or-nothing)
- Phone number support for multiple contacts per entity
- Postal code tracking for receivers

---

## 🗄️ Database Structure

### Database: `apex_express_db`

#### **1. ADMIN Table**

Stores administrator login credentials.

```
CREATE TABLE `admin` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `admin_username` VARCHAR(50) NOT NULL,
  `admin_pswd` VARCHAR(255) NOT NULL
);
```

**Fields:**

- `id` - Unique admin identifier
- `admin_username` - Login username
- `admin_pswd` - Password (plaintext in current version)

**Default Credentials:** `admin` / `admin123`

---

#### **2. BRANCH Table** + **branch_phone Table**

Represents distribution centers across Pakistan.

```
CREATE TABLE `branch` (
  `Branch_ID` VARCHAR(50) PRIMARY KEY,
  `Name` VARCHAR(100) NOT NULL,
  `Email` VARCHAR(100) NOT NULL,
  `Street_no` VARCHAR(50) NOT NULL,
  `Area` VARCHAR(100) NOT NULL,
  `City` VARCHAR(50) NOT NULL
);

CREATE TABLE `branch_phone` (
  `Branch_ID` VARCHAR(50) NOT NULL,
  `Phone_no` VARCHAR(20) NOT NULL,
  PRIMARY KEY (`Branch_ID`, `Phone_no`)
);
```

**Example Branches:**

- BR-001: Apex Express Blue Area (Islamabad)
- BR-002: Apex Express Gulberg (Lahore)
- BR-003: Apex Express Clifton (Karachi)
- ... 10 branches total across Pakistan

**Relationship:** One branch can have multiple phone numbers.

---

#### **3. RIDER Table** + **rider_phone Table**

Represents delivery personnel assigned to branches.

```
CREATE TABLE `rider` (
  `Rider_ID` VARCHAR(50) PRIMARY KEY,
  `First_name` VARCHAR(50) NOT NULL,
  `Last_name` VARCHAR(50) NOT NULL,
  `Cnic_no` VARCHAR(20) NOT NULL UNIQUE,
  `Branch_ID` VARCHAR(50) NOT NULL,
  FOREIGN KEY (`Branch_ID`) REFERENCES `branch`(`Branch_ID`)
);

CREATE TABLE `rider_phone` (
  `Rider_ID` VARCHAR(50) PRIMARY KEY,
  `Phone_no` VARCHAR(20) NOT NULL,
  PRIMARY KEY (`Rider_ID`, `Phone_no`)
);
```

**Example Riders:**

- RDR-001: Kamran Akmal (CNIC: 35202-1234567-1, Branch: BR-001)
- RDR-002: Sajid Khan (CNIC: 35202-7654321-3, Branch: BR-002)
- ... 10 riders total

**Relationship:**

- One branch has many riders
- One rider can have multiple phone numbers
- CNIC is unique (one rider per CNIC)

---

#### **4. SENDER Table** + **sender_phone Table**

Represents parcel originators.

```
CREATE TABLE `sender` (
  `Sender_ID` VARCHAR(50) PRIMARY KEY,
  `First_name` VARCHAR(50) NOT NULL,
  `Last_name` VARCHAR(50) NOT NULL,
  `Email` VARCHAR(100) NOT NULL,
  `Street_no` VARCHAR(50) NOT NULL,
  `Area` VARCHAR(100) NOT NULL,
  `City` VARCHAR(50) NOT NULL
);

CREATE TABLE `sender_phone` (
  `Sender_ID` VARCHAR(50) PRIMARY KEY,
  `Phone_no` VARCHAR(20) NOT NULL,
  PRIMARY KEY (`Sender_ID`, `Phone_no`)
);
```

**Data:**

- 10 sample senders from major cities
- Contact addresses (street, area, city)
- Email and phone for communication

---

#### **5. RECEIVER (RECIEVER) Table** + **reciever_phone Table**

Represents parcel recipients.

```
CREATE TABLE `reciever` (
  `Reciever_ID` VARCHAR(50) PRIMARY KEY,
  `First_name` VARCHAR(50) NOT NULL,
  `Last_name` VARCHAR(50) NOT NULL,
  `Email` VARCHAR(100) NOT NULL,
  `Postal_code` VARCHAR(20) NOT NULL,
  `Street_no` VARCHAR(50) NOT NULL,
  `Area` VARCHAR(100) NOT NULL,
  `City` VARCHAR(50) NOT NULL
);

CREATE TABLE `reciever_phone` (
  `Reciever_ID` VARCHAR(50) PRIMARY KEY,
  `Phone_no` VARCHAR(20) NOT NULL,
  PRIMARY KEY (`Reciever_ID`, `Phone_no`)
);
```

**Data:**

- 10 sample receivers across Pakistan
- Includes postal code for address precision
- Multiple contact numbers supported

---

#### **6. DELIVERY_STATUS Table**

Tracks parcel delivery progress and status.

```
CREATE TABLE `delivery_status` (
  `Status_id` VARCHAR(50) PRIMARY KEY,
  `Status_type` ENUM('Booked','In-Transit','Out for Delivery','Completed','Failed') NOT NULL,
  `Remarks` TEXT DEFAULT NULL,
  `Attempts` INT DEFAULT 0
);
```

**Status Types:**

1. **Booked** - Parcel registered, awaiting pickup
2. **In-Transit** - Parcel in transit between hubs
3. **Out for Delivery** - Rider is delivering
4. **Completed** - Successfully delivered
5. **Failed** - Delivery failed, retry needed

**Fields:**

- `Status_id` - Unique status identifier (e.g., STAT-001)
- `Status_type` - Current delivery status (enum)
- `Remarks` - Notes about delivery status
- `Attempts` - Number of delivery attempts

---

#### **7. PARCEL Table** (Core Table)

The central table linking all entities together.

```
CREATE TABLE `parcel` (
  `Parcel_ID` INT AUTO_INCREMENT PRIMARY KEY,
  `Tracking_id` VARCHAR(50) NOT NULL UNIQUE,
  `Weight` DECIMAL(10,2) NOT NULL,
  `Book_date` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `Origin_city` VARCHAR(50) NOT NULL,
  `Destination_city` VARCHAR(50) NOT NULL,
  `Sender_ID` VARCHAR(50) NOT NULL,
  `Reciever_ID` VARCHAR(50) NOT NULL,
  `Branch_ID` VARCHAR(50) NOT NULL,
  `Rider_ID` VARCHAR(50) DEFAULT NULL,
  `Status_id` VARCHAR(50) NOT NULL,
  `price` DECIMAL(10,2) DEFAULT 0.00,
  `payment_option` ENUM('Cash on Delivery','Easypaisa','JazzCash','Bank Transfer') DEFAULT 'Cash on Delivery',
  FOREIGN KEY (`Sender_ID`) REFERENCES `sender`(`Sender_ID`),
  FOREIGN KEY (`Reciever_ID`) REFERENCES `reciever`(`Reciever_ID`),
  FOREIGN KEY (`Branch_ID`) REFERENCES `branch`(`Branch_ID`),
  FOREIGN KEY (`Rider_ID`) REFERENCES `rider`(`Rider_ID`),
  FOREIGN KEY (`Status_id`) REFERENCES `delivery_status`(`Status_id`)
);
```

**Fields:**

- `Parcel_ID` - Auto-incremented system ID
- `Tracking_id` - Customer-facing tracking number (e.g., APX-2026-001)
- `Weight` - Parcel weight in kg
- `Book_date` - Timestamp when parcel was booked
- `Origin_city` - Sender's city
- `Destination_city` - Receiver's city
- `Sender_ID` - Foreign key to sender
- `Reciever_ID` - Foreign key to receiver
- `Branch_ID` - Foreign key to origin branch
- `Rider_ID` - Foreign key to assigned rider (nullable)
- `Status_id` - Foreign key to current status
- `price` - Shipping cost
- `payment_option` - Payment method

**Relationships:**

```
PARCEL → SENDER
PARCEL → RECEIVER
PARCEL → BRANCH
PARCEL → RIDER (optional)
PARCEL → DELIVERY_STATUS
```

---

## 🔄 System Workflow

### Data Flow Architecture

```
USER REQUEST (HTML)
    ↓
BROWSER PROCESSES (JavaScript)
    ↓
SUBMIT TO PHP (via POST/GET)
    ↓
PHP VALIDATION & PROCESSING
    ↓
DATABASE TRANSACTION (MySQL)
    ↓
RESPONSE TO BROWSER
    ↓
DISPLAY IN HTML
```

---

### 1. Admin Login Process

**Step-by-Step Flow:**

1. User navigates to `Frontend/admin_dashboard.php`
2. Login form is displayed (username & password inputs)
3. User submits credentials via POST
4. PHP calls `loginAdmin()` function
5. Function queries `admin` table for matching username
6. If password matches, `$_SESSION['logged_in']` is set to `true`
7. User is redirected to dashboard menu
8. Session persists across navigation

**Key Files:**

- `Frontend/admin_dashboard.php` - Login form & main dashboard
- `includes/admin_auth.php` - Authentication logic
- Session variable: `$_SESSION['logged_in']`

---

### 2. Adding a Branch

**Step-by-Step Flow:**

1. Admin clicks "Add Branch" in dashboard menu
2. Form displays with fields:
   - Branch ID (e.g., BR-011)
   - Name, Email
   - Street Number, Area, City
   - Phone Number (optional)
3. Admin fills form and submits
4. PHP calls `addBranch()` function with POST data
5. Function inserts into `branch` table
6. If phone provided, insert into `branch_phone` table
7. Alert shown: "Branch added successfully!"
8. Table view refreshed with new data

**Database Operations:**

```sql
INSERT INTO branch (Branch_ID, Name, Email, Street_no, Area, City)
VALUES ('BR-011', 'Name', 'email@example.com', 'Street', 'Area', 'City');

INSERT INTO branch_phone (Branch_ID, Phone_no)
VALUES ('BR-011', '0300-1234567');
```

**Key Function:** `addBranch()` in `includes/admin_functions.php`

---

### 3. Adding a Rider

**Step-by-Step Flow:**

1. Admin clicks "Add Rider" in dashboard menu
2. Form displays with fields:
   - Rider ID (e.g., RDR-011)
   - First Name, Last Name
   - CNIC Number (must be unique)
   - Branch ID (dropdown of existing branches)
   - Phone Number (optional)
3. Admin fills form and submits
4. PHP calls `addRider()` function
5. Function inserts into `rider` table
6. If phone provided, insert into `rider_phone` table
7. Validation ensures CNIC is unique
8. Alert shown: "Rider added successfully!"

**Database Operations:**

```sql
INSERT INTO rider (Rider_ID, First_name, Last_name, Cnic_no, Branch_ID)
VALUES ('RDR-011', 'Name', 'Surname', '35202-1234567-1', 'BR-001');

INSERT INTO rider_phone (Rider_ID, Phone_no)
VALUES ('RDR-011', '0300-1234567');
```

**Key Function:** `addRider()` in `includes/admin_functions.php`

---

### 4. Creating a Parcel (Complete Flow) IMPORTANT

**This is the most complex operation involving multiple tables.**

**Step-by-Step Flow:**

1. Admin clicks "Add Parcel" in dashboard
2. **SENDER SECTION** - Admin fills:
   - Sender ID (e.g., SND-011)
   - First Name, Last Name, Email
   - Street, Area, City
   - Phone Number
3. **RECEIVER SECTION** - Admin fills:
   - Receiver ID (e.g., RCV-011)
   - First Name, Last Name, Email
   - Postal Code, Street, Area, City
   - Phone Number
4. **PARCEL DETAILS** - Admin fills:
   - Tracking ID (e.g., APX-2026-011)
   - Weight (kg)
   - Origin City (from sender)
   - Destination City (to receiver)
   - Branch ID (origin)
   - Rider ID (optional - can be assigned later)
   - Price (shipping cost)
   - Payment Option (Cash on Delivery, Easypaisa, etc.)
5. **STATUS SECTION** - Admin fills:
   - Status ID (e.g., STAT-011)
   - Status Type (Booked, In-Transit, etc.)
   - Remarks (optional notes)
6. Admin submits form
7. PHP calls `createParcel()` function
8. **TRANSACTION BEGINS** - All-or-nothing execution
9. Step-by-step database inserts:
   - Insert new sender into `sender` table
   - Insert sender phone into `sender_phone` table
   - Insert new receiver into `reciever` table
   - Insert receiver phone into `reciever_phone` table
   - Insert new status into `delivery_status` table
   - Insert parcel into `parcel` table (links all above)
10. **TRANSACTION COMMITS** - All inserts succeed together
11. Alert shown: "All records compiled & saved successfully!"
12. If any step fails, **TRANSACTION ROLLBACK** occurs
13. All changes are undone, maintaining data integrity
14. Error message shown: "Database error: Unable to save parcel data."

**Database Operations (In Order):**

```sql
-- 1. Insert Sender
INSERT INTO sender (Sender_ID, First_name, Last_name, Email, Street_no, Area, City)
VALUES ('SND-011', 'Ahmed', 'Khan', 'ahmed@email.com', 'House 1', 'Area', 'City');

-- 2. Insert Sender Phone
INSERT INTO sender_phone (Sender_ID, Phone_no)
VALUES ('SND-011', '0300-1234567');

-- 3. Insert Receiver
INSERT INTO reciever (Reciever_ID, First_name, Last_name, Email, Postal_code, Street_no, Area, City)
VALUES ('RCV-011', 'Ali', 'Ahmed', 'ali@email.com', '44000', 'House 2', 'Area', 'City');

-- 4. Insert Receiver Phone
INSERT INTO reciever_phone (Reciever_ID, Phone_no)
VALUES ('RCV-011', '0300-9876543');

-- 5. Insert Status
INSERT INTO delivery_status (Status_id, Status_type, Remarks, Attempts)
VALUES ('STAT-011', 'Booked', 'Parcel registered', 0);

-- 6. Insert Parcel (LINKS ALL ABOVE)
INSERT INTO parcel (Tracking_id, Weight, Origin_city, Destination_city,
                    Sender_ID, Reciever_ID, Branch_ID, Rider_ID,
                    Status_id, price, payment_option)
VALUES ('APX-2026-011', 1.50, 'Islamabad', 'Karachi',
        'SND-011', 'RCV-011', 'BR-001', NULL,
        'STAT-011', 350.00, 'Cash on Delivery');
```

**Key Function:** `createParcel()` in `includes/admin_functions.php`

**Transaction Safety:** Uses `mysqli_begin_transaction()`, `mysqli_commit()`, and `mysqli_rollback()`

---

### 5. Parcel Tracking System

**Step-by-Step Flow:**

1. Customer navigates to `Frontend/index.php` (home page)
2. Parcel tracking form displayed with input: "Tracking ID"
3. Customer enters tracking ID (e.g., APX-2026-001)
4. Form submits to `Frontend/tracking.php`
5. PHP calls `getParcelTrackingData()` function
6. Function queries database with JOIN to fetch:
   - Parcel details
   - Sender name
   - Receiver name
   - Current delivery status
7. If tracking ID found:
   - All details displayed to customer
   - Status, remarks, and delivery attempts shown
   - Sender and receiver information visible
8. If tracking ID not found:
   - Error message: "Tracking ID not found."

**SQL Query Used:**

```sql
SELECT p.*, s.First_name AS s_fname, r.First_name AS r_fname, ds.Status_type
FROM parcel p
JOIN sender s ON p.Sender_ID = s.Sender_ID
JOIN reciever r ON p.Reciever_ID = r.Reciever_ID
JOIN delivery_status ds ON p.Status_id = ds.Status_id
WHERE p.Tracking_id = 'APX-2026-001';
```

**Key Function:** `getParcelTrackingData()` in `includes/tracking_functions.php`

---

### 6. Viewing All Tables

**Step-by-Step Flow:**

1. Admin is logged in
2. Admin clicks "View Data" menu option
3. Admin selects which table to view (branches, riders, senders, etc.)
4. PHP calls `renderTable()` function with table name
5. Function executes: `SELECT * FROM table_name`
6. Fetches all columns from result set
7. Dynamically creates HTML table with:
   - Column headers from field names
   - All rows and values
   - Proper HTML escaping for security
8. Table displayed in admin dashboard

**Key Function:** `renderTable()` in `includes/admin_functions.php`

**Tables Available:**

- admin
- branch
- branch_phone
- rider
- rider_phone
- sender
- sender_phone
- reciever
- reciever_phone
- delivery_status
- parcel

---

## 📂 File Structure

```
Apex-Express-Database/
│
├── Frontend/                          # Public-facing pages
│   ├── admin_dashboard.php            # Admin login & control center
│   ├── index.php                      # Home page with parcel tracker
│   ├── tracking.php                   # Parcel tracking results page
│   ├── contact.php                    # Contact page
│   └── style/
│       └── style.css                  # All CSS styling
│
├── includes/                          # Backend logic
│   ├── db_connect.php                 # Database connection
│   ├── admin_auth.php                 # Login/logout functions
│   ├── admin_functions.php            # Core business logic
│   ├── tracking_functions.php         # Tracking queries
│   ├── header.php                     # HTML header component
│   └── footer.php                     # HTML footer component
│
├── database/                          # SQL files
│   └── apex_express_schema.sql        # Complete database schema
│
├── Entity Relationship Diagram - ERD/ # Database documentation
│
└── README.md                          # This file
```

---

## 🔧 Key Functions

### admin_auth.php

#### `loginAdmin($conn, $user, $pass)`

**Purpose:** Authenticate admin login credentials

**Parameters:**

- `$conn` (MySQLi) - Database connection
- `$user` (string) - Username
- `$pass` (string) - Password

**Returns:**

- `true` - If credentials match
- `false` - If credentials invalid

**In Plain Terms:**
Checks if the provided username and password exist in the admin table. Returns true if they match, false otherwise.

**Example:**

```php
if (loginAdmin($conn, 'admin', 'admin123')) {
    $_SESSION['logged_in'] = true;
}
```

---

#### `logoutAdmin()`

**Purpose:** Destroy admin session

**Parameters:** None

**Returns:** Nothing

**In Plain Terms:**
Destroys the current session, logging the user out.

**Example:**

```php
logoutAdmin();
header("Location: admin_dashboard.php"); // Redirect to login
```

---

### admin_functions.php

#### `addBranch($conn, $data)`

**Purpose:** Add new distribution branch to system

**Parameters:**

- `$conn` (MySQLi) - Database connection
- `$data` (array) - Form data containing:
  - `branch_id` - Branch ID (e.g., BR-011)
  - `name` - Branch name
  - `email` - Branch email
  - `street` - Street address
  - `area` - Area name
  - `city` - City name
  - `phone` - Phone number (optional)

**Returns:**

- `true` - If insertion successful
- `false` - If insertion failed

**In Plain Terms:**
Inserts a new branch record into the branch table and optionally adds a phone number to the branch_phone table.

**Database Operations:**

1. Inserts into `branch` table
2. If phone provided, inserts into `branch_phone` table

**Example:**

```php
$data = [
    'branch_id' => 'BR-011',
    'name' => 'Apex Express Karachi',
    'email' => 'karachi@apex.com',
    'street' => 'Plot 5',
    'area' => 'Clifton',
    'city' => 'Karachi',
    'phone' => '021-1234567'
];
if (addBranch($conn, $data)) {
    echo "Branch added!";
}
```

---

#### `addRider($conn, $data)`

**Purpose:** Register new delivery rider to system

**Parameters:**

- `$conn` (MySQLi) - Database connection
- `$data` (array) - Form data containing:
  - `rider_id` - Rider ID (e.g., RDR-011)
  - `first_name` - Rider's first name
  - `last_name` - Rider's last name
  - `cnic` - CNIC number (must be unique)
  - `branch_id` - Assigned branch ID
  - `phone` - Phone number (optional)

**Returns:**

- `true` - If insertion successful
- `false` - If insertion failed

**In Plain Terms:**
Inserts a new rider record into the rider table and optionally adds a phone number to the rider_phone table.

**Database Operations:**

1. Inserts into `rider` table
2. If phone provided, inserts into `rider_phone` table

**Example:**

```php
$data = [
    'rider_id' => 'RDR-011',
    'first_name' => 'Hassan',
    'last_name' => 'Khan',
    'cnic' => '35202-1234567-1',
    'branch_id' => 'BR-001',
    'phone' => '0300-1234567'
];
if (addRider($conn, $data)) {
    echo "Rider added!";
}
```

---

#### `createParcel($conn, $data)` MOST IMPORTANT

**Purpose:** Complete parcel registration with transaction support

**Parameters:**

- `$conn` (MySQLi) - Database connection
- `$data` (array) - Comprehensive parcel data:
  - `sender_id`, `sender_fname`, `sender_lname`, `sender_email`, `sender_street`, `sender_area`, `sender_city`, `sender_phone`
  - `reciever_id`, `receiver_fname`, `receiver_lname`, `receiver_email`, `receiver_zip`, `receiver_street`, `receiver_area`, `receiver_city`, `receiver_phone`
  - `status_id`, `status_type`, `remarks`
  - `tracking_id`, `weight`, `sender_city`, `receiver_city`, `branch_id`, `rider_id` (optional), `price`, `payment_option`

**Returns:**

- `true` - If all operations successful
- `false` - If any operation fails

**In Plain Terms:**
This is the most complex function. It inserts data into 6 tables in a specific order, within a database transaction. If any step fails, the entire operation is rolled back (undone) to maintain data integrity.

**Database Operations (In Order):**

1. Insert sender into `sender` table
2. Insert sender phone into `sender_phone` table
3. Insert receiver into `reciever` table
4. Insert receiver phone into `reciever_phone` table
5. Insert status into `delivery_status` table
6. Insert parcel into `parcel` table (links all above)

**Transaction Safety:**

- `mysqli_begin_transaction()` - Start transaction
- `mysqli_commit()` - If all succeed
- `mysqli_rollback()` - If any fails

**Error Handling:**

- Catches exceptions
- Logs errors to error log
- Returns false on failure

---

#### `renderTable($conn, $tableName)`

**Purpose:** Display database table as HTML

**Parameters:**

- `$conn` (MySQLi) - Database connection
- `$tableName` (string) - Table to display (e.g., 'branch', 'rider', 'parcel')

**Returns:** Outputs HTML directly (no return value)

**In Plain Terms:**
Fetches all rows from a table and displays them as an HTML table with headers and formatted values. Includes security measures like htmlspecialchars() to prevent code injection.

**Output:**

- Table heading with uppercase table name
- HTML `<table>` with headers
- All rows and columns from database
- Message if table is empty

**Example Output:**

```
BRANCH DATA
| Branch_ID | Name | Email | ...
| BR-001 | Apex Express Blue Area | bluearea@apex.com | ...
| BR-002 | Apex Express Gulberg | gulberg@apex.com | ...
```

---

#### `showAlert($message)`

**Purpose:** Display JavaScript alert to user

**Parameters:**

- `$message` (string) - Message to display

**Returns:** Outputs JavaScript directly

**In Plain Terms:**
Shows a browser alert popup with the provided message. Escapes special characters for security.

**Example:**

```php
showAlert("Branch added successfully!");
// Shows: Browser popup with message
```

---

### tracking_functions.php

#### `getParcelTrackingData($conn, $trackingId)`

**Purpose:** Retrieve parcel tracking information

**Parameters:**

- `$conn` (MySQLi) - Database connection
- `$trackingId` (string) - Tracking ID (e.g., APX-2026-001)

**Returns:**

- `array` - Parcel data if found
- `null` - If tracking ID not found

**In Plain Terms:**
Searches the database for a parcel with the given tracking ID. Joins multiple tables to get sender name, receiver name, and current status. Returns all information if found, or null if not found.

**SQL Query:**

```sql
SELECT p.*, s.First_name AS s_fname, r.First_name AS r_fname, ds.Status_type
FROM parcel p
JOIN sender s ON p.Sender_ID = s.Sender_ID
JOIN reciever r ON p.Reciever_ID = r.Reciever_ID
JOIN delivery_status ds ON p.Status_id = ds.Status_id
WHERE p.Tracking_id = ?
```

**Returns (Example):**

```php
[
    'Parcel_ID' => 1,
    'Tracking_id' => 'APX-2026-001',
    'Weight' => '1.50',
    'Origin_city' => 'Islamabad',
    'Destination_city' => 'Karachi',
    'price' => '350.00',
    'Status_type' => 'In-Transit',
    's_fname' => 'Ahmed',
    'r_fname' => 'Ali',
    ...
]
```

---

## 🚀 Setup Instructions

### Prerequisites

- PHP 7.4 or higher
- MySQL 5.7 or higher
- Apache web server (XAMPP or WAMP)
- A modern web browser

### Step 1: Install XAMPP/WAMP

**For Windows/Mac/Linux:**

- Download from [Apache Friends (XAMPP)](https://www.apachefriends.org/)
- Install and start Apache and MySQL services

### Step 2: Copy Project Files

```bash
# Copy project to XAMPP htdocs folder
cp -r Apex-Express-Database /path/to/xampp/htdocs/
# Or use Windows file explorer to copy the folder
```

**Location:**

- **Windows:** `C:\xampp\htdocs\Apex-Express-Database\`
- **Mac:** `/Applications/XAMPP/xamppfiles/htdocs/Apex-Express-Database/`
- **Linux:** `/opt/lampp/htdocs/Apex-Express-Database/`

### Step 3: Create Database

1. Open phpMyAdmin
   - URL: `http://localhost/phpmyadmin`
   - Username: `root`
   - Password: (leave blank)

2. Click "New" to create new database
   - Database name: `apex_express_db`
   - Collation: `utf8_general_ci`
   - Click "Create"

3. Select the new database `apex_express_db`

4. Click "Import" tab

5. Click "Choose File" and select:
   - `database/apex_express_schema.sql`

6. Click "Go" to import

**Database created with:**

- All 8 tables
- All relationships and constraints
- 10 sample branches, riders, senders, receivers, parcels

### Step 4: Configure Database Connection

Edit `includes/db_connect.php`:

```php
<?php
// Current settings (default for XAMPP)
$host = "localhost";
$user = "root";
$pass = "";              // Leave blank for default XAMPP
$db   = "apex_express_db";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}
?>
```

**Change if needed:**

- `$host` - Database server (usually `localhost`)
- `$user` - MySQL username (usually `root`)
- `$pass` - MySQL password (blank for default XAMPP)
- `$db` - Database name (must be `apex_express_db`)

### Step 5: Access the Application

**Home Page:**

- URL: `http://localhost/Apex-Express-Database/Frontend/index.php`
- Shows parcel tracker form

**Admin Dashboard:**

- URL: `http://localhost/Apex-Express-Database/Frontend/admin_dashboard.php`
- Login with: `admin` / `admin123`

---

## 📖 How to Use

### For Customers: Track a Parcel

1. Go to home page: `Frontend/index.php`
2. Scroll to "Track Your Parcel" section
3. Enter tracking ID (e.g., `APX-2026-001`)
4. Click "Track Now"
5. View parcel status, sender, receiver, and delivery details

**Sample Tracking IDs:**

- APX-2026-001
- APX-2026-002
- APX-2026-003
- ... (APX-2026-010)

---

### For Admins: Complete Workflow

#### Login

1. Go to: `Frontend/admin_dashboard.php`
2. Enter credentials:
   - Username: `admin`
   - Password: `admin123`
3. Click "Log In to System"

#### Main Menu

Once logged in, navigate using menu:

**1. Add Branch**

- Click "Add Branch"
- Fill form:
  - Branch ID: BR-011 (must be unique)
  - Name: Apex Express [City]
  - Email: city@apex.com
  - Street, Area, City
  - Phone (optional)
- Click "Add"
- Confirm: "Branch added successfully!"

**2. Add Rider**

- Click "Add Rider"
- Fill form:
  - Rider ID: RDR-011 (must be unique)
  - First Name, Last Name
  - CNIC: [Valid CNIC format]
  - Branch: [Select from dropdown]
  - Phone (optional)
- Click "Add"
- Confirm: "Rider added successfully!"

**3. Add Parcel** ⭐ Most Important

- Click "Add Parcel"
- **SENDER SECTION:**
  - Sender ID: SND-011
  - First/Last Name
  - Email, Street, Area, City
  - Phone Number
- **RECEIVER SECTION:**
  - Receiver ID: RCV-011
  - First/Last Name
  - Email, Postal Code, Street, Area, City
  - Phone Number
- **PARCEL DETAILS:**
  - Tracking ID: APX-2026-011
  - Weight (kg)
  - Origin City (from sender)
  - Destination City (to receiver)
  - Origin Branch: [Select dropdown]
  - Rider: [Select or leave blank]
  - Price: [Enter shipping cost]
  - Payment: [Select option]
- **STATUS:**
  - Status ID: STAT-011
  - Status Type: Booked
  - Remarks: (optional)
- Click "Register Parcel"
- Confirm: "All records compiled & saved successfully!"

**4. View Data**

- Click "View Data"
- Select table:
  - Branches
  - Riders
  - Senders
  - Receivers
  - Parcels
  - Statuses
- View all records in formatted table

**5. Logout**

- Click "Logout"
- Session destroyed
- Returned to login page

---

## ⚠️ Known Issues & Limitations

### Current Limitations

1. **Password Storage**
   - Admin password stored in plaintext in database
   - No password hashing or encryption
   - Security risk in production

2. **Input Validation**
   - Frontend validation minimal
   - No comprehensive server-side validation
   - Risk of invalid data entry

3. **ID Generation**
   - All IDs (Branch, Rider, Sender, Receiver) manually entered
   - No auto-generation system
   - Risk of duplicate IDs if not managed carefully
   - Inconsistent ID formatting

4. **UI/UX Issues**
   - Parcel tracking UI styling inconsistent
   - Limited responsive design for mobile
   - Form layouts could be optimized

5. **Error Handling**
   - Minimal user-friendly error messages
   - Technical errors shown to end users
   - Limited logging for debugging

6. **Database Constraints**
   - No cascading delete rules
   - Deleting branches doesn't remove riders
   - Orphaned records possible

7. **Field Redundancy**
   - Parcel table has `Origin_city` and `Destination_city` (duplication from sender/receiver)
   - Some fields in forms may be unnecessary
   - Address data scattered across multiple tables

8. **Phone Number Handling**
   - Phone tables separate from main entities
   - Complex queries needed to fetch phone data
   - Could be simplified

9. **Rider Assignment**
   - Rider ID nullable in parcel table
   - No validation if rider can accept parcels
   - No workload management

10. **Status Management**
    - Status manually assigned on parcel creation
    - No workflow to update status
    - Remarks and attempts not tracked during delivery

11. **Session Management**
    - No timeout mechanism
    - Session persists indefinitely
    - Security risk for shared computers

12. **Search & Filter**
    - No search functionality
    - No filtering for tables
    - No pagination for large datasets

---

## 🎯 Planned Improvements

### 1. Remove Irrelevant Fields from Input Forms

**Current Issues:**

- Parcel form collects `Postal_code` from receiver but doesn't use it in tracking
- `Attempts` field in status is pre-set to 0 and never updated
- Some address fields may be redundant

**Recommendations:**

- **Sender Form:**
  - Keep: First Name, Last Name, Email, Address (Street, Area, City)
  - Remove: Postal Code (not needed for senders)
- **Receiver Form:**
  - Keep: First Name, Last Name, Email, Address (Street, Area, City), Postal Code
  - Consider: Keep Postal Code for sorting/delivery optimization
- **Parcel Form:**
  - Remove: Origin_city, Destination_city (can be auto-populated from sender/receiver)
  - Keep: Weight, Price, Payment Option, Rider ID (optional), Remarks
  - Remove: Manual Status_id, Status_type (auto-set to "Booked")

**Implementation:**

```php
// Remove redundant field collection in createParcel()
// Auto-populate Origin_city from sender address
// Auto-populate Destination_city from receiver address
// Auto-create status with type "Booked" on parcel creation
```

---

### 2. Identify & Fix Redundant Fields in Database

**Current Redundancies:**

| Issue                       | Current                                                 | Better Approach                   |
| --------------------------- | ------------------------------------------------------- | --------------------------------- |
| **Parcel Origin City**      | Stored in `parcel.Origin_city` AND `sender.City`        | Query from sender city only       |
| **Parcel Destination City** | Stored in `parcel.Destination_city` AND `reciever.City` | Query from receiver city only     |
| **Phone Numbers**           | Separate tables for each entity                         | Keep phone tables for flexibility |
| **Status Attempts**         | Field exists but not used                               | Track delivery attempts in status |
| **Postal Code**             | Only in receiver, not consistent                        | Add to sender if needed           |

**Recommendations:**

- **Remove:** `Origin_city` and `Destination_city` from parcel table
- **Keep:** All address fields in sender/receiver
- **Normalize:** Ensure consistent fields across all contact tables
- **Standardize:** Use same phone number format everywhere

**Database Change:**

```sql
-- ALTER TABLE parcel DROP COLUMN Origin_city;
-- ALTER TABLE parcel DROP COLUMN Destination_city;
-- Update queries to join sender.City and reciever.City instead
```

---

### 3. Simplify UI Input Forms

**Current Issues:**

- Forms are lengthy and information-heavy
- Multiple sections could be progressive
- Mobile experience poor
- Form validation minimal

**Recommendations:**

**A. Multi-Step Form for Parcel Booking:**

```
Step 1: Sender Information
  - Name, Email, Phone
  - Address (Street, Area, City)

Step 2: Receiver Information
  - Name, Email, Phone
  - Address (Street, Area, City)
  - Postal Code

Step 3: Parcel Details
  - Weight
  - Content Description (new)
  - Special Handling (new)

Step 4: Shipping & Payment
  - Branch Selection
  - Rider Assignment (optional)
  - Price
  - Payment Method
  - Review & Confirm
```

**B. Form Improvements:**

- Add JavaScript validation before submit
- Show/hide conditional fields
- Auto-populate cities from branch selection
- Dropdown for cities (instead of free text)
- Phone format validation (03XX-XXXXXXX)
- CNIC format validation (XXXXX-XXXXXXX-X)

**C. UI Enhancements:**

- Responsive grid layout
- Better spacing and typography
- Icons for form sections
- Progress indicator for multi-step forms
- Mobile-friendly input (date picker, select, etc.)

---

### 4. Standardize ID System

**Current Issues:**

- All IDs manually entered
- No consistent format
- Risk of duplicates
- Difficult to generate

**Recommended ID Format:**

| Entity       | Format         | Example              | Strategy                                     |
| ------------ | -------------- | -------------------- | -------------------------------------------- |
| **Branch**   | BR-XXX         | BR-001 to BR-999     | Manual or auto-increment                     |
| **Rider**    | RDR-XXX        | RDR-001 to RDR-999   | Manual or auto-increment                     |
| **Sender**   | SND-XXX        | SND-001 to SND-9999  | Manual or auto-increment                     |
| **Receiver** | RCV-XXX        | RCV-001 to RCV-9999  | Manual or auto-increment                     |
| **Parcel**   | APX-YYYY-XXXXX | APX-2026-00001       | Auto-generate (APX-[year]-[5-digit counter]) |
| **Status**   | STAT-XXX       | STAT-001 to STAT-999 | Manual                                       |

**Auto-Generation Strategy:**

```php
function generateBranchID($conn) {
    // Get highest ID and increment
    $result = $conn->query("SELECT MAX(Branch_ID) FROM branch");
    $row = $result->fetch_assoc();
    $num = (int)substr($row['MAX(Branch_ID)'], 3) + 1;
    return 'BR-' . str_pad($num, 3, '0', STR_PAD_LEFT);
}

function generateTrackingID($conn) {
    // Format: APX-YYYY-XXXXX
    $year = date('Y');
    $result = $conn->query("
        SELECT COUNT(*) as count
        FROM parcel
        WHERE YEAR(Book_date) = $year
    ");
    $row = $result->fetch_assoc();
    $num = $row['count'] + 1;
    return 'APX-' . $year . '-' . str_pad($num, 5, '0', STR_PAD_LEFT);
}
```

**Implementation:**

- Create helper functions for ID generation
- Call before form submission
- Display generated ID to user
- Allow override if manual entry needed

---

### 5. Suggest Consistency Rules Across All Tables

**Current Inconsistencies:**

| Area               | Issue                                 | Fix                                    |
| ------------------ | ------------------------------------- | -------------------------------------- |
| **Field Names**    | `reciever` (misspelled) vs `receiver` | Standardize spelling to `receiver`     |
| **ID Naming**      | `Reciever_ID` vs `Receiver_ID`        | Use consistent camelCase or snake_case |
| **Phone Handling** | Separate table for each entity        | Consistent phone table structure       |
| **Timestamps**     | Only parcel has `Book_date`           | Add timestamps to all entities         |
| **Status Fields**  | Enum values hardcoded                 | Use lookup table for statuses          |
| **Postal Code**    | Only in receiver                      | Add to all address fields              |
| **Email**          | In all contact tables                 | Ensure all entities have email         |

**Recommendations:**

**1. Fix Spelling:**

```sql
-- Rename tables
RENAME TABLE reciever TO receiver;
RENAME TABLE reciever_phone TO receiver_phone;

-- Update column names
ALTER TABLE receiver CHANGE Reciever_ID Receiver_ID VARCHAR(50);
ALTER TABLE parcel CHANGE Reciever_ID Receiver_ID VARCHAR(50);
```

**2. Add Timestamps to All Tables:**

```sql
ALTER TABLE sender ADD COLUMN created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP;
ALTER TABLE sender ADD COLUMN updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;
-- Repeat for all entities
```

**3. Create Status Lookup Table:**

```sql
CREATE TABLE status_types (
    id INT AUTO_INCREMENT PRIMARY KEY,
    status_name VARCHAR(50) UNIQUE,
    description TEXT
);

INSERT INTO status_types (status_name) VALUES
    ('Booked'),
    ('In-Transit'),
    ('Out for Delivery'),
    ('Completed'),
    ('Failed');
```

**4. Consistent Email Fields:**

- Ensure all contact tables (sender, receiver, branch) have email
- Make email unique or allow duplicates
- Add email validation in forms

**5. Standardized Address Fields:**

```sql
-- Ensure all address fields are identical structure
-- sender: Street_no, Area, City
-- receiver: Street_no, Area, City, Postal_code
-- branch: Street_no, Area, City

-- Consider adding Postal_code to sender and branch
```

---

### 6. Ensure Auto-Generation Strategy is Recommended

**Current:** All IDs manually entered

**Recommendation:** Implement auto-generation for:

**A. Tracking IDs** (HIGHEST PRIORITY)

```php
function generateTrackingID($conn) {
    $year = date('Y');
    $month = date('m');
    $result = $conn->query(
        "SELECT COUNT(*) as count FROM parcel WHERE YEAR(Book_date) = $year"
    );
    $row = $result->fetch_assoc();
    $sequence = str_pad($row['count'] + 1, 5, '0', STR_PAD_LEFT);
    return "APX-$year-$sequence";
}

// Usage in form:
// <input type="hidden" name="tracking_id" value="<?php echo generateTrackingID($conn); ?>">
```

**B. Entity IDs** (MODERATE PRIORITY)

```php
function getNextBranchID($conn) {
    $result = $conn->query(
        "SELECT MAX(CAST(SUBSTRING(Branch_ID, 4) AS UNSIGNED)) as max_id FROM branch"
    );
    $row = $result->fetch_assoc();
    $next = ($row['max_id'] ?? 0) + 1;
    return 'BR-' . str_pad($next, 3, '0', STR_PAD_LEFT);
}

function getNextRiderID($conn) {
    $result = $conn->query(
        "SELECT MAX(CAST(SUBSTRING(Rider_ID, 5) AS UNSIGNED)) as max_id FROM rider"
    );
    $row = $result->fetch_assoc();
    $next = ($row['max_id'] ?? 0) + 1;
    return 'RDR-' . str_pad($next, 3, '0', STR_PAD_LEFT);
}
```

**C. Status IDs** (LOW PRIORITY)

```php
function getNextStatusID($conn) {
    $result = $conn->query(
        "SELECT MAX(CAST(SUBSTRING(Status_id, 6) AS UNSIGNED)) as max_id FROM delivery_status"
    );
    $row = $result->fetch_assoc();
    $next = ($row['max_id'] ?? 0) + 1;
    return 'STAT-' . str_pad($next, 3, '0', STR_PAD_LEFT);
}
```

**Implementation in Forms:**

```html
<!-- Form automatically shows generated ID -->
<div class="form-group">
  <label>Tracking ID (Auto-generated)</label>
  <input
    type="text"
    name="tracking_id"
    value="<?php echo generateTrackingID($conn); ?>"
    readonly
  />
</div>
```

---

### 7. Fix Trace Parcel Styling

**Current Issues:**

- Parcel tracking page UI mismatched with dashboard
- Inconsistent styling between pages
- Poor readability of tracking results
- Mobile responsiveness lacking

**Recommendations:**

**A. Improve Tracking Display:**

```html
<!-- Current: Raw data display -->
<!-- Better: Structured, styled information -->

<div class="tracking-result">
  <div class="tracking-status">
    <h2>Status: In-Transit</h2>
    <p class="status-description">Your parcel is on its way</p>
  </div>

  <div class="tracking-timeline">
    <div class="timeline-item">
      <time>2026-05-20 10:00 AM</time>
      <h4>Booked</h4>
      <p>Parcel registered successfully</p>
    </div>
    <div class="timeline-item active">
      <time>2026-05-21 02:30 PM</time>
      <h4>In-Transit</h4>
      <p>Left Lahore Hub going to Islamabad</p>
    </div>
    <div class="timeline-item">
      <time>Pending</time>
      <h4>Out for Delivery</h4>
      <p>Rider out to drop package</p>
    </div>
  </div>

  <div class="tracking-details">
    <div class="detail-row">
      <label>From:</label>
      <span>Ahmed Ali (Islamabad)</span>
    </div>
    <div class="detail-row">
      <label>To:</label>
      <span>Ali Raza (Karachi)</span>
    </div>
    <div class="detail-row">
      <label>Weight:</label>
      <span>1.50 kg</span>
    </div>
    <div class="detail-row">
      <label>Price:</label>
      <span>Rs. 350</span>
    </div>
  </div>
</div>
```

**B. CSS Improvements:**

```css
/* Unified styling across pages */
.tracking-result {
  max-width: 600px;
  margin: 2rem auto;
  background: #fff;
  border-radius: 8px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
  padding: 2rem;
}

.tracking-status {
  border-left: 4px solid #4caf50;
  padding-left: 1rem;
  margin-bottom: 2rem;
}

.tracking-timeline {
  position: relative;
  padding: 2rem 0;
}

.timeline-item {
  position: relative;
  padding-left: 40px;
  margin-bottom: 1.5rem;
  opacity: 0.6;
}

.timeline-item::before {
  content: "";
  position: absolute;
  left: 0;
  top: 0;
  width: 20px;
  height: 20px;
  border-radius: 50%;
  background: #ddd;
  border: 2px solid #fff;
}

.timeline-item.active {
  opacity: 1;
}

.timeline-item.active::before {
  background: #4caf50;
}
```

**C. Mobile Responsive:**

```css
@media (max-width: 600px) {
  .tracking-result {
    padding: 1rem;
    margin: 1rem;
  }

  .detail-row {
    flex-direction: column;
  }
}
```

---

### 8. Identify UI Inconsistency Issues

**Current Issues:**

| Page                    | Issue                        | Fix                            |
| ----------------------- | ---------------------------- | ------------------------------ |
| **index.php**           | Image slider without styling | Add CSS for smooth transitions |
| **admin_dashboard.php** | Table styling varies         | Use consistent table classes   |
| **tracking.php**        | Plain text results           | Add styled cards/sections      |
| **All pages**           | Inconsistent header/footer   | Unify design system            |
| **Forms**               | Different input styling      | Standardize form components    |
| **Buttons**             | Inconsistent colors          | Use single button style guide  |
| **Typography**          | Font sizes not normalized    | Define heading/body hierarchy  |

**Recommendations:**

**1. Create Design System (style.css improvements):**

```css
/* Color Palette */
:root {
  --primary: #2196f3;
  --success: #4caf50;
  --danger: #f44336;
  --warning: #ff9800;
  --light: #f5f5f5;
  --dark: #333;
}

/* Typography Scale */
h1 {
  font-size: 2.5rem;
}
h2 {
  font-size: 2rem;
}
h3 {
  font-size: 1.75rem;
}
h4 {
  font-size: 1.25rem;
}
body {
  font-size: 1rem;
  line-height: 1.6;
}

/* Button Styles */
.btn {
  padding: 0.75rem 1.5rem;
  border-radius: 4px;
  font-size: 1rem;
  cursor: pointer;
  border: none;
  transition: all 0.3s;
}

.btn-primary {
  background: var(--primary);
  color: white;
}

.btn-primary:hover {
  background: darker(var(--primary));
}

/* Form Styles */
.form-group {
  margin-bottom: 1.5rem;
}

input,
textarea,
select {
  width: 100%;
  padding: 0.75rem;
  border: 1px solid #ddd;
  border-radius: 4px;
  font-size: 1rem;
}

input:focus {
  outline: none;
  border-color: var(--primary);
  box-shadow: 0 0 0 3px rgba(33, 150, 243, 0.1);
}

/* Table Styles */
table {
  width: 100%;
  border-collapse: collapse;
}

th {
  background: var(--light);
  padding: 1rem;
  text-align: left;
  font-weight: 600;
  border-bottom: 2px solid #ddd;
}

td {
  padding: 1rem;
  border-bottom: 1px solid #eee;
}

/* Card Styles */
.card {
  background: white;
  border-radius: 8px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
  padding: 1.5rem;
  margin-bottom: 1.5rem;
}
```

**2. Page Templates:**
Create consistent layouts for all pages:

```html
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo $page_title; ?></title>
    <link rel="stylesheet" href="/style/style.css" />
  </head>
  <body>
    <?php include 'includes/header.php'; ?>

    <main class="container">
      <div class="page-content">
        <!-- Page specific content -->
      </div>
    </main>

    <?php include 'includes/footer.php'; ?>
  </body>
</html>
```

**3. Responsive Grid:**

```css
.container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 1rem;
}

.grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 1.5rem;
}

@media (max-width: 768px) {
  .grid {
    grid-template-columns: 1fr;
  }
}
```

---

### 9. Suggest CSS Cleanup and Unified Styling System

**Current Issues:**

- CSS likely has redundant rules
- No consistent naming convention
- Colors hardcoded in multiple places
- Styling scattered across inline and external CSS

**Recommendations:**

**1. CSS Architecture:**

```
style/
├── reset.css          # Browser reset
├── variables.css      # Color, font, spacing variables
├── typography.css     # Font and text styles
├── layout.css         # Grid, flexbox, containers
├── components.css     # Buttons, cards, forms, tables
├── pages.css          # Page-specific styles
└── responsive.css     # Media queries
```

**2. CSS Variables (Modern Approach):**

```css
/* variables.css */
:root {
  /* Colors */
  --color-primary: #2196f3;
  --color-secondary: #1976d2;
  --color-success: #4caf50;
  --color-danger: #f44336;
  --color-warning: #ff9800;
  --color-light: #f5f5f5;
  --color-dark: #333;
  --color-border: #ddd;

  /* Typography */
  --font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
  --font-size-base: 1rem;
  --font-size-sm: 0.875rem;
  --font-size-lg: 1.125rem;
  --font-size-xl: 1.5rem;

  /* Spacing */
  --spacing-xs: 0.25rem;
  --spacing-sm: 0.5rem;
  --spacing-md: 1rem;
  --spacing-lg: 1.5rem;
  --spacing-xl: 2rem;

  /* Shadows */
  --shadow-sm: 0 1px 3px rgba(0, 0, 0, 0.12);
  --shadow-md: 0 2px 8px rgba(0, 0, 0, 0.15);
  --shadow-lg: 0 4px 16px rgba(0, 0, 0, 0.15);

  /* Borders */
  --border-radius: 4px;
  --border-width: 1px;
}

/* Usage throughout CSS */
.btn-primary {
  background: var(--color-primary);
  color: white;
  padding: var(--spacing-md) var(--spacing-lg);
  border-radius: var(--border-radius);
  font-size: var(--font-size-base);
}
```

**3. BEM Naming Convention:**

```css
/* Block Element Modifier */

.card { }                    /* Block */
.card__header { }           /* Element */
.card__content { }          /* Element */
.card--featured { }         /* Modifier */
.card--featured__header { } /* Modifier + Element */

/* Usage */
<div class="card card--featured">
    <div class="card__header">
        <h2>Title</h2>
    </div>
    <div class="card__content">
        <p>Content</p>
    </div>
</div>
```

**4. Utility Classes:**

```css
/* Quick styling without custom classes */
.text-center {
  text-align: center;
}
.text-bold {
  font-weight: bold;
}
.text-muted {
  color: #999;
}
.mt-1 {
  margin-top: var(--spacing-sm);
}
.mb-2 {
  margin-bottom: var(--spacing-md);
}
.p-3 {
  padding: var(--spacing-lg);
}
.flex {
  display: flex;
}
.flex-center {
  display: flex;
  align-items: center;
  justify-content: center;
}
.gap-2 {
  gap: var(--spacing-md);
}
```

**5. CSS Cleanup Checklist:**

- Remove inline styles (move to CSS)
- Remove duplicate rules
- Remove unused classes
- Organize CSS by component
- Use variables instead of hardcoded values
- Follow consistent naming
- Add comments for sections
- Use CSS Grid/Flexbox for layouts
- Test responsive breakpoints

---

## 🔐 Security Considerations

### Current Security Gaps

1. **Password Storage** HIGH PRIORITY
   - Admin passwords stored in plaintext
   - No hashing or encryption

   **Fix:**

   ```php
   // Hash password on admin creation/update
   $hashed = password_hash($password, PASSWORD_BCRYPT);

   // Verify on login
   if (password_verify($pass, $row['admin_pswd'])) {
       // Login successful
   }
   ```

2. **SQL Injection Prevention** GOOD
   - Using prepared statements with parameterized queries
   - Binding parameters properly
   - Continue this practice

3. **XSS (Cross-Site Scripting) Prevention** GOOD
   - Using `htmlspecialchars()` for output escaping
   - Add more comprehensive escaping

4. **CSRF Protection** MISSING
   - No CSRF tokens on forms

   **Fix:**

   ```php
   // Generate CSRF token
   if (empty($_SESSION['csrf_token'])) {
       $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
   }

   // In form
   <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

   // Verify on submission
   if ($_POST['csrf_token'] !== $_SESSION['csrf_token']) {
       die('CSRF token validation failed');
   }
   ```

5. **Input Validation** WEAK
   - Limited server-side validation
   - No type checking or length limits

   **Fix:**

   ```php
   function validateBranchID($id) {
       return preg_match('/^BR-\d{3}$/', $id);
   }

   function validateEmail($email) {
       return filter_var($email, FILTER_VALIDATE_EMAIL);
   }

   function validatePhone($phone) {
       return preg_match('/^03\d{2}-\d{7}$/', $phone);
   }
   ```

6. **Session Security** MEDIUM PRIORITY
   - No session timeout
   - No activity tracking

   **Fix:**

   ```php
   $timeout = 30 * 60; // 30 minutes
   if (isset($_SESSION['last_activity']) &&
       (time() - $_SESSION['last_activity']) > $timeout) {
       session_destroy();
       header("Location: admin_dashboard.php");
   }
   $_SESSION['last_activity'] = time();
   ```

7. **Database Connection** GOOD
   - Proper error handling
   - No sensitive data exposed

8. **File Upload Security** ℹ️ N/A
   - No file upload functionality currently

---

## Support & Troubleshooting

### Common Issues

**Issue:** "Database connection failed"

- Check `includes/db_connect.php` settings
- Verify MySQL is running
- Confirm database name is correct

**Issue:** "Tables not found"

- Import SQL file: `database/apex_express_schema.sql`
- Verify database creation was successful
- Check database name matches connection file

**Issue:** "Login fails even with correct credentials"

- Verify database has admin table with data
- Check credentials: `admin` / `admin123`
- Clear browser cache and cookies

**Issue:** "Parcel creation fails"

- Check all required fields are filled
- Verify sender/receiver IDs are unique
- Check branch and rider IDs exist
- Review error logs for details

---

## License

This project is part of an academic course and is intended for educational purposes.

---

**Last Updated:** June 2026  
**Status:** Functional with Planned Improvements  
**Version:** 1.0

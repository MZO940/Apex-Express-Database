<?php
session_start();
$conn = mysqli_connect("localhost", "root", "", "apex_express_db");

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Logout Logic
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: admin_dashboard.php");
    exit();
}

// Login Logic
if (isset($_POST['login'])) {
    if ($_POST['user'] == "apex_express" && $_POST['pass'] == "1234") {
        $_SESSION['logged_in'] = true;
        $_SESSION['current_view'] = 'menu'; // Default view on login
    } else {
        echo "<script>alert('Wrong Password!');</script>";
    }
}

// Navigation Handling via GET parameters
if (isset($_GET['view']) && isset($_SESSION['logged_in'])) {
    $_SESSION['current_view'] = $_GET['view'];
}

/* ------------------- FORM SUBMISSION LOGIC ------------------- */

// 1. ADD BRANCH
if (isset($_POST['save_branch'])) {
    $b_id   = mysqli_real_escape_string($conn, $_POST['branch_id']);
    $name   = mysqli_real_escape_string($conn, $_POST['name']);
    $email  = mysqli_real_escape_string($conn, $_POST['email']);
    $street = mysqli_real_escape_string($conn, $_POST['street']);
    $area   = mysqli_real_escape_string($conn, $_POST['area']);
    $city   = mysqli_real_escape_string($conn, $_POST['city']);
    $phone  = mysqli_real_escape_string($conn, $_POST['phone']);

    mysqli_begin_transaction($conn);
    try {
        mysqli_query($conn, "INSERT INTO branch (Branch_ID, Name, Email, Street_no, Area, City) VALUES ('$b_id', '$name', '$email', '$street', '$area', '$city')");
        if (!empty($phone)) {
            mysqli_query($conn, "INSERT INTO branch_phone (Branch_ID, Phone_no) VALUES ('$b_id', '$phone')");
        }
        mysqli_commit($conn);
        echo "<script>alert('Branch added successfully!');</script>";
    } catch (Exception $e) {
        mysqli_rollback($conn);
        echo "<script>alert('Error adding branch: " . mysqli_real_escape_string($conn, $e->getMessage()) . "');</script>";
    }
}

// 2. ADD RIDER
if (isset($_POST['save_rider'])) {
    $r_id   = mysqli_real_escape_string($conn, $_POST['rider_id']);
    $fname  = mysqli_real_escape_string($conn, $_POST['first_name']);
    $lname  = mysqli_real_escape_string($conn, $_POST['last_name']);
    $cnic   = mysqli_real_escape_string($conn, $_POST['cnic']);
    $b_id   = mysqli_real_escape_string($conn, $_POST['branch_id']);
    $phone  = mysqli_real_escape_string($conn, $_POST['phone']);

    // Check if branch exists
    $check_branch = mysqli_query($conn, "SELECT Branch_ID FROM branch WHERE Branch_ID = '$b_id'");
    if (mysqli_num_rows($check_branch) == 0) {
        echo "<script>alert('Error: Branch ID does not exist! Please add the branch first.');</script>";
    } else {
        mysqli_begin_transaction($conn);
        try {
            mysqli_query($conn, "INSERT INTO rider (Rider_ID, First_name, Last_name, Cnic_no, Branch_ID) VALUES ('$r_id', '$fname', '$lname', '$cnic', '$b_id')");
            if (!empty($phone)) {
                mysqli_query($conn, "INSERT INTO rider_phone (Rider_ID, Phone_no) VALUES ('$r_id', '$phone')");
            }
            mysqli_commit($conn);
            echo "<script>alert('Rider added successfully!');</script>";
        } catch (Exception $e) {
            mysqli_rollback($conn);
            echo "<script>alert('Error adding rider: " . mysqli_real_escape_string($conn, $e->getMessage()) . "');</script>";
        }
    }
}

// 3. COMPLETE PARCEL REGISTRATION
if (isset($_POST['save_parcel_complete'])) {
    $b_id = mysqli_real_escape_string($conn, $_POST['branch_id']);

    // REQUIRED VALIDATION: Verify if Branch ID exists
    $check_branch = mysqli_query($conn, "SELECT Branch_ID FROM branch WHERE Branch_ID = '$b_id'");
    if (mysqli_num_rows($check_branch) == 0) {
        echo "<script>alert('CRITICAL ERROR: Branch ID ($b_id) does not exist! Operation cancelled.');</script>";
    } else {
        // Collect all variables
        $s_id     = mysqli_real_escape_string($conn, $_POST['sender_id']);
        $s_fname  = mysqli_real_escape_string($conn, $_POST['sender_fname']);
        $s_lname  = mysqli_real_escape_string($conn, $_POST['sender_lname']);
        $s_email  = mysqli_real_escape_string($conn, $_POST['sender_email']);
        $s_street = mysqli_real_escape_string($conn, $_POST['sender_street']);
        $s_area   = mysqli_real_escape_string($conn, $_POST['sender_area']);
        $s_city   = mysqli_real_escape_string($conn, $_POST['sender_city']);
        $s_phone  = mysqli_real_escape_string($conn, $_POST['sender_phone']);

        $r_id     = mysqli_real_escape_string($conn, $_POST['reciever_id']);
        $r_fname  = mysqli_real_escape_string($conn, $_POST['receiver_fname']);
        $r_lname  = mysqli_real_escape_string($conn, $_POST['receiver_lname']);
        $r_email  = mysqli_real_escape_string($conn, $_POST['receiver_email']);
        $r_zip    = mysqli_real_escape_string($conn, $_POST['receiver_zip']);
        $r_street = mysqli_real_escape_string($conn, $_POST['receiver_street']);
        $r_area   = mysqli_real_escape_string($conn, $_POST['receiver_area']);
        $r_city   = mysqli_real_escape_string($conn, $_POST['receiver_city']);
        $r_phone  = mysqli_real_escape_string($conn, $_POST['receiver_phone']);

        $tid      = mysqli_real_escape_string($conn, $_POST['tracking_id']);
        $weight   = mysqli_real_escape_string($conn, $_POST['weight']);
        $price    = mysqli_real_escape_string($conn, $_POST['price']);
        $pay_opt  = mysqli_real_escape_string($conn, $_POST['payment_option']);
        $rider_id = !empty($_POST['rider_id']) ? "'" . mysqli_real_escape_string($conn, $_POST['rider_id']) . "'" : "NULL";

        $st_id    = mysqli_real_escape_string($conn, $_POST['status_id']);
        $st_type  = mysqli_real_escape_string($conn, $_POST['status_type']);
        $remarks  = mysqli_real_escape_string($conn, $_POST['remarks']);

        // Start Transaction to guarantee database integrity
        mysqli_begin_transaction($conn);
        try {
            // Check/Insert Sender
            $chk_s = mysqli_query($conn, "SELECT Sender_ID FROM sender WHERE Sender_ID='$s_id'");
            if (mysqli_num_rows($chk_s) == 0) {
                mysqli_query($conn, "INSERT INTO sender VALUES ('$s_id', '$s_fname', '$s_lname', '$s_email', '$s_street', '$s_area', '$s_city')");
                mysqli_query($conn, "INSERT INTO sender_phone VALUES ('$s_id', '$s_phone')");
            }

            // Check/Insert Receiver
            $chk_r = mysqli_query($conn, "SELECT Reciever_ID FROM reciever WHERE Reciever_ID='$r_id'");
            if (mysqli_num_rows($chk_r) == 0) {
                mysqli_query($conn, "INSERT INTO reciever VALUES ('$r_id', '$r_fname', '$r_lname', '$r_email', '$r_zip', '$r_street', '$r_area', '$r_city')");
                mysqli_query($conn, "INSERT INTO reciever_phone VALUES ('$r_id', '$r_phone')");
            }

            // Insert Delivery Status
            mysqli_query($conn, "INSERT INTO delivery_status (Status_id, Status_type, Remarks, Attempts) VALUES ('$st_id', '$st_type', '$remarks', 0)");

            // Insert Parcel
            $sql_parcel = "INSERT INTO parcel (Tracking_id, Weight, Origin_city, Destination_city, Sender_ID, Reciever_ID, Branch_ID, Rider_ID, Status_id, price, payment_option) 
                           VALUES ('$tid', '$weight', '$s_city', '$r_city', '$s_id', '$r_id', '$b_id', $rider_id, '$st_id', '$price', '$pay_opt')";
            mysqli_query($conn, $sql_parcel);

            mysqli_commit($conn);
            echo "<script>alert('All records compiled & saved successfully!');</script>";
        } catch (Exception $e) {
            mysqli_rollback($conn);
            echo "<script>alert('Database Error: " . mysqli_real_escape_string($conn, $e->getMessage()) . "');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apex Express - Admin Dashboard</title>
    <link rel="icon" type="image/png" href="logo.png">
    <link rel="stylesheet" href="admin_dashboard.css">
</head>

<body>

    <nav class="apex-navbar">
        <div class="nav-wrapper">
            <div class="nav-logo">
                <img src="logo.png" alt="Apex Express Logo" class="logo-truck">
                <div class="logo-info">
                    <div class="logo-text">
                        <span class="brand-bold">Apex</span><span class=\"brand-light\">Express</span>
                    </div>
                    <div class="logo-slogan">Fast. Secure. Right on Time.</div>
                </div>
            </div>

            <ul class="nav-menu">
                <li><a href="home.html" class="nav-item">Home</a></li>
                <li><a href="tracking.php" class="nav-item">Track Parcel</a></li>
                <li><a href="contactus.html" class="nav-item">Contact Us</a></li>
            </ul>

            <div class="nav-btn-wrapper">
                <a href="admin_dashboard.php?view=menu" class="btn-portal active">Admin Dashboard</a>
            </div>
        </div>
        <hr class="line-break">
    </nav>

    <div class="admin-wrapper-workspace">
        <?php if (!isset($_SESSION['logged_in'])) { ?>
            <div class="admin-login-card">
                <h2>Admin Control Center</h2>
                <p class="login-subtitle">Please authenticate to gain access</p>
                <form method="POST">
                    <div class="input-group">
                        <input type="text" name="user" placeholder="Username" required>
                    </div>
                    <div class="input-group">
                        <input type="password" name="pass" placeholder="Password" required>
                    </div>
                    <button type="submit" name="login" class="btn-brand-submit">Log In to System</button>
                </form>
            </div>

        <?php } else {
            $view = $_SESSION['current_view'] ?? 'menu';
        ?>

            <div class="dashboard-central-holder">
                <div class="dashboard-action-bar">
                    <?php if ($view !== 'menu') { ?>
                        <a href="?view=menu" class="btn-action-back">← Back to Menu Hub</a>
                    <?php } else {
                        echo "<div></div>";
                    } ?>
                    <a href="?logout=true" class="btn-action-logout">Terminate Session</a>
                </div>

                <?php if ($view === 'menu') { ?>
                    <h2 class="workspace-main-title">System Administration Modules</h2>
                    <div class="dashboard-tiles-grid">
                        <a href="?view=tables" class="hub-tile-btn">
                            <div class="tile-art-box">📊</div>
                            <span class="tile-label-text">View Table Records</span>
                        </a>
                        <a href="?view=add_branch" class="hub-tile-btn">
                            <div class="tile-art-box">🏢</div>
                            <span class="tile-label-text">Add New Branch</span>
                        </a>
                        <a href="?view=add_rider" class="hub-tile-btn">
                            <div class="tile-art-box">🛵</div>
                            <span class="tile-label-text">Add Fleet Rider</span>
                        </a>
                        <a href="?view=add_parcel" class="hub-tile-btn hub-tile-highlighted">
                            <div class="tile-art-box">📦</div>
                            <span class="tile-label-text">Book Complete Shipment</span>
                        </a>
                    </div>

                <?php } elseif ($view === 'tables') { ?>
                    <h2 class="workspace-main-title">Global Database Records</h2>
                    <div class="tables-view-scroll-chamber">
                        <?php
                        $tables = ['parcel', 'branch', 'rider', 'sender', 'reciever', 'delivery_status'];
                        foreach ($tables as $tbl) {
                            echo "<h3 class='table-section-divider-title'>" . strtoupper($tbl) . " DATA</h3>";
                            $res = mysqli_query($conn, "SELECT * FROM `$tbl` LIMIT 10");
                            if (mysqli_num_rows($res) > 0) {
                                echo "<div class='table-alignment-box'><table class='brand-data-table'><thead><tr>";
                                while ($field = mysqli_fetch_field($res)) {
                                    echo "<th>{$field->name}</th>";
                                }
                                echo "</tr></thead><tbody>";
                                while ($row = mysqli_fetch_assoc($res)) {
                                    echo "<tr>";
                                    foreach ($row as $val) {
                                        echo "<td>" . htmlspecialchars($val ?? 'NULL') . "</td>";
                                    }
                                    echo "</tr>";
                                }
                                echo "</tbody></table></div>";
                            } else {
                                echo "<p class='no-records-banner'>No active entries located in table: $tbl</p>";
                            }
                        }
                        ?>
                    </div>

                <?php } elseif ($view === 'add_branch') { ?>
                    <h2 class="workspace-main-title">Register New Operational Hub</h2>
                    <form method="POST" class="brand-engineered-form">
                        <input type="text" name="branch_id" placeholder="Branch ID (e.g., BR-011)" required>
                        <input type="text" name="name" placeholder="Branch Operations Name" required>
                        <input type="email" name="email" placeholder="Official Operations Email" required>
                        <input type="text" name="street" placeholder="Street Number / Suite" required>
                        <input type="text" name="area" placeholder="Area Sector / District" required>
                        <input type="text" name="city" placeholder="Operating City" required>
                        <input type="text" name="phone" placeholder="Direct Contact Line">
                        <button type="submit" name="save_branch" class="btn-form-commit">Commit Branch Unit</button>
                    </form>

                <?php } elseif ($view === 'add_rider') { ?>
                    <h2 class="workspace-main-title">Onboard Fleet Logistics Rider</h2>
                    <form method="POST" class="brand-engineered-form">
                        <input type="text" name="rider_id" placeholder="Rider Tracking ID (e.g., RDR-011)" required>
                        <input type="text" name="first_name" placeholder="First Name" required>
                        <input type="text" name="last_name" placeholder="Last Name" required>
                        <input type="text" name="cnic" placeholder="National Identification CNIC (Unique)" required>
                        <input type="text" name="branch_id" placeholder="Assigned Logistics Hub ID" required>
                        <input type="text" name="phone" placeholder="Active Mobile Number">
                        <button type="submit" name="save_rider" class="btn-form-commit">Commit Rider Profile</button>
                    </form>

                <?php } elseif ($view === 'add_parcel') { ?>
                    <h2 class="workspace-main-title">Consignment Booking Manifest</h2>
                    <form method="POST" class="brand-engineered-form expanded-manifest-form">
                        <h3 class="form-sub-division-header">1. Logistics Operations Mapping</h3>
                        <input type="text" name="branch_id" placeholder="Processing Hub ID (Mandatory Check)" required>
                        <input type="text" name="rider_id" placeholder="Assigned Fleet Rider ID (Optional)">

                        <h3 class="form-sub-division-header">2. Sender Identity & Address</h3>
                        <input type="text" name="sender_id" placeholder="Sender Profile System ID" required>
                        <div style="display:grid; grid-template-columns:1fr 1fr; gap:15px;">
                            <input type="text" name="sender_fname" placeholder="First Name" required>
                            <input type="text" name="sender_lname" placeholder="Last Name" required>
                        </div>
                        <input type="email" name="sender_email" placeholder="Primary Email Contact" required>
                        <input type="text" name="sender_phone" placeholder="Mobile Number" required>
                        <input type="text" name="sender_street" placeholder="Street / Property Plot Number" required>
                        <input type="text" name="sender_area" placeholder="Area / Sector" required>
                        <input type="text" name="sender_city" placeholder="Origin Routing City" required>

                        <h3 class="form-sub-division-header">3. Receiver Consignment Destination</h3>
                        <input type="text" name="reciever_id" placeholder="Recipient Profile System ID" required>
                        <div style="display:grid; grid-template-columns:1fr 1fr; gap:15px;">
                            <input type="text" name="receiver_fname" placeholder="First Name" required>
                            <input type="text" name="receiver_lname" placeholder="Last Name" required>
                        </div>
                        <input type="email" name="receiver_email" placeholder="Primary Email Contact" required>
                        <input type="text" name="receiver_phone" placeholder="Mobile Number" required>
                        <input type="text" name="receiver_zip" placeholder="Postal Code / ZIP Code" required>
                        <input type="text" name="receiver_street" placeholder="Street / Property Plot Number" required>
                        <input type="text" name="receiver_area" placeholder="Area / Sector" required>
                        <input type="text" name="receiver_city" placeholder="Destination Routing City" required>

                        <h3 class="form-sub-division-header">4. Physical Specs & Invoicing</h3>
                        <input type="text" name="tracking_id" placeholder="Unique Consignment Tracking ID (e.g. APX-2026-XYZ)" required>
                        <input type="number" step="0.01" name="weight" placeholder="Dead Weight Assessment (Kg)" required>
                        <input type="number" step="0.01" name="price" placeholder="Calculated Freight Cost (PKR)" required>
                        <select name="payment_option" class="brand-dropdown-select">
                            <option value="Cash on Delivery">Cash on Delivery</option>
                            <option value="Easypaisa">Easypaisa</option>
                            <option value="JazzCash">JazzCash</option>
                            <option value="Bank Transfer">Bank Transfer</option>
                        </select>

                        <h3 class="form-sub-division-header">5. Tracking Lifecycle Entry</h3>
                        <input type="text" name="status_id" placeholder="Lifecycle Node ID (e.g. STAT-101)" required>
                        <select name="status_type" class="brand-dropdown-select">
                            <option value="Booked">Booked</option>
                            <option value="In-Transit">In-Transit</option>
                            <option value="Out for Delivery">Out for Delivery</option>
                            <option value="Completed">Completed</option>
                            <option value="Failed">Failed</option>
                        </select>
                        <input type="text" name="remarks" placeholder="Initial Lifecycle Node Status Remarks">

                        <button type="submit" name="save_parcel_complete" class="btn-form-commit btn-accent-commit">Compile and Deploy Shipment</button>
                    </form>
                <?php } ?>

            </div>
        <?php } ?>
    </div>

    <div class="footer">
        <div class="footer-container">
            <div class="footer-col">
                <h3>Services</h3>
                <ul>
                    <li><a href="#">- Domestic</a></li>
                    <li><a href="#">- Corporate</a></li>
                    <li><a href="#">- International</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h3>Head Office</h3>
                <p>
                    <strong>Apex Express Logistics</strong><br>
                    Office No. 42, 3rd Floor, Centaurus Corporate Tower,<br>
                    Jinnah Avenue, Blue Area, Islamabad, Pakistan.
                </p>
            </div>
            <div class="footer-col">
                <h3>Follow Us</h3>
                <div class="social-links">
                    <a href="#"><img src="linkedin.png" class="social-icon"></a>
                    <a href="#"><img src="facebook-app-symbol.png" class="social-icon"></a>
                    <a href="#"><img src="instagram.png" class="social-icon"></a>
                    <a href="#"><img src="whatsapp.png" class="social-icon"></a>
                </div>
            </div>
        </div>
        <br>
        <hr class="line">
        <div class="footer-bottom">
            <div class="bottom-content">
                <div class="brand-group">
                    <img src="logo.png" alt="Brand Logo" class="footer-logo">
                    <span class="brand-name">Apex Express</span>
                </div>
                <p>@ 1983 - 2026 Apex Express Logistics. All rights reserved.</p>
                <div class="bottom-links">
                    <a href="#">Terms of Use</a>
                    <a href="#">Privacy Policy</a>
                </div>
            </div>
        </div>
    </div>

</body>

</html>
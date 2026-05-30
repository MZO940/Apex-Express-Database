<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apex Express</title>
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
                        <span class="brand-bold">Apex</span><span class="brand-light">Express</span>
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
                <a href="admin_dashboard.php" class="btn-portal active">Admin Dashboard</a>
            </div>
        </div>
        <hr class="line">
    </nav>
   

<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$conn = mysqli_connect("localhost", "root", "", "apex_express_schema");

// Logout Logic
if(isset($_GET['logout'])) { session_destroy(); header("Location: admin_dashboard.php"); exit(); }

// 2. LOGIN Logic (Updated)
if(isset($_POST['login'])) {
    if($_POST['user'] == "apex_express" && $_POST['pass'] == "1234") {
        $_SESSION['logged_in'] = true;
    } else {
        echo "<script>alert('Wrong Password!');</script>";
    }
}
// SAVE DATA Logic -
// if(isset($_POST['save_parcel'])) {
//     $tid = mysqli_real_escape_string($conn, $_POST['tracking_id']);
//     $weight = mysqli_real_escape_string($conn, $_POST['weight']);
//     $origin = mysqli_real_escape_string($conn, $_POST['origin']);
//     $dest = mysqli_real_escape_string($conn, $_POST['destination']);
//     $s_id = mysqli_real_escape_string($conn, $_POST['sender_id']);
//     $r_id = mysqli_real_escape_string($conn, $_POST['reciever_id']);
//     $b_id = mysqli_real_escape_string($conn, $_POST['branch_id']);
//     $rdr_id = mysqli_real_escape_string($conn, $_POST['rider_id']);
//     $st_id = mysqli_real_escape_string($conn, $_POST['status_id']);

//     $sql = "INSERT INTO parcel (Tracking_id, Weight, Origin_city, Destination_city, Sender_ID, Reciever_ID, Branch_ID, Rider_ID, Status_id) 
//             VALUES ('$tid', '$weight', '$origin', '$dest', '$s_id', '$r_id', '$b_id', '$rdr_id', '$st_id')";
            
//     if(mysqli_query($conn, $sql)) {
//         echo "<script>alert('Record Successfull Save Ho Gaya!');</script>";
//     } else {
//         echo "<script>alert('Error: " . mysqli_error($conn) . "');</script>";
//     }
// }
// 
if(isset($_POST['save_parcel'])) {

    // Insert Sender
    $s_id   = mysqli_real_escape_string($conn, $_POST['sender_id']);
    $s_fname = mysqli_real_escape_string($conn, $_POST['sender_fname']);
    $s_lname = mysqli_real_escape_string($conn, $_POST['sender_lname']);
    mysqli_query($conn, "INSERT INTO sender (Sender_ID, First_name, Last_name) VALUES ('$s_id', '$s_fname', '$s_lname')");

    // Insert Receiver
    $r_id   = mysqli_real_escape_string($conn, $_POST['reciever_id']);
    $r_fname = mysqli_real_escape_string($conn, $_POST['receiver_fname']);
    $r_lname = mysqli_real_escape_string($conn, $_POST['receiver_lname']);
    mysqli_query($conn, "INSERT INTO reciever (Reciever_ID, First_name, Last_name) VALUES ('$r_id', '$r_fname', '$r_lname')");

    // Insert Parcel
    $tid    = mysqli_real_escape_string($conn, $_POST['tracking_id']);
    $weight = mysqli_real_escape_string($conn, $_POST['weight']);
    $origin = mysqli_real_escape_string($conn, $_POST['origin']);
    $dest   = mysqli_real_escape_string($conn, $_POST['destination']);
    $st_id  = mysqli_real_escape_string($conn, $_POST['status_id']);

    $sql = "INSERT INTO parcel (Tracking_id, Weight, Origin_city, Destination_city, Sender_ID, Reciever_ID, Status_id) 
            VALUES ('$tid', '$weight', '$origin', '$dest', '$s_id', '$r_id', '$st_id')";

    if(mysqli_query($conn, $sql)) {
        echo "<script>alert('Parcel save ho gaya!');</script>";
    } else {
        echo "<script>alert('Error: " . mysqli_error($conn) . "');</script>";
    }
}
?>

<!-- <!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="admin_style.css">
</head>
<body>
    <div class="admin-container">
        <?php if(!isset($_SESSION['logged_in'])) { ?>
            <h2>Admin Login</h2>
            <form method="POST">
                <input type="text" name="user" placeholder="Username" required>
                <input type="password" name="pass" placeholder="Password" required>
                <button type="submit" name="login">Login</button>
            </form>
        <?php } else { ?>
            <a href="?logout=true">Logout</a>
            <h2>Add Shipment Details</h2>
            <form method="POST">
                <input type="text" name="tracking_id" placeholder="Tracking ID" required>
                <input type="number" step="0.01" name="weight" placeholder="Weight" required>
                <input type="text" name="origin" placeholder="Origin City" required>
                <input type="text" name="destination" placeholder="Destination City" required>
                <input type="text" name="sender_id" placeholder="Sender ID" required>
                <input type="text" name="reciever_id" placeholder="Receiver ID" required>
                <input type="text" name="branch_id" placeholder="Branch ID" required>
                <input type="text" name="rider_id" placeholder="Rider ID" required>
                <input type="text" name="status_id" placeholder="Status ID" required>
                <button type="submit" name="save_parcel">Save Shipment</button>
            </form>
        <?php } ?>
    </div>
</body>
</html> -->
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="admin_style.css">
</head>
<body>
    <div class="admin-container">
        <?php if(!isset($_SESSION['logged_in'])) { ?>
            <h2>Admin Login</h2>
            <form method="POST">
                <input type="text" name="user" placeholder="Username" required>
                <input type="password" name="pass" placeholder="Password" required>
                <button type="submit" name="login">Login</button>
            </form>
        <?php } else { ?>
            <a href="?logout=true">Logout</a>
            <h2>Add Shipment Details</h2>
            <form method="POST">
                <input type="text" name="tracking_id" placeholder="Tracking ID" required>
                <input type="number" step="0.01" name="weight" placeholder="Weight" required>
                <input type="text" name="origin" placeholder="Origin City" required>
                <input type="text" name="destination" placeholder="Destination City" required>

                <!-- Sender Fields -->
                <input type="text" name="sender_id" placeholder="Sender ID" required>
                <input type="text" name="sender_fname" placeholder="Sender First Name" required>
                <input type="text" name="sender_lname" placeholder="Sender Last Name" required>

                <!-- Receiver Fields -->
                <input type="text" name="reciever_id" placeholder="Receiver ID" required>
                <input type="text" name="receiver_fname" placeholder="Receiver First Name" required>
                <input type="text" name="receiver_lname" placeholder="Receiver Last Name" required>

                <input type="text" name="branch_id" placeholder="Branch ID" required>
                <input type="text" name="rider_id" placeholder="Rider ID" required>
                <input type="text" name="status_id" placeholder="Status ID" required>
                <button type="submit" name="save_parcel">Save Shipment</button>
            </form>
        <?php } ?>
    </div>
</body>
</html>

<hr class="line">
<footer class="footer">
    <div class="footer-container">
        <div class="footer-col">
            <h3>Our Company</h3>
            <ul>
                <li><a href="#">- About Us</a></li>
                <li><a href="#">- Track Parcel</a></li>
                <li><a href="#">- Locate Us</a></li>
                <li><a href="#">- Contact Us</a></li>
            </ul>
        </div>

        <div class="footer-col">
            <h3>Business Units</h3>
            <ul>
                <li><a href="#">- Express</a></li>
                <li><a href="#">- Logistics</a></li>
                <li><a href="#">- Ecommerce</a></li>
                <li><a href="#">- International</a></li>
            </ul>
        </div>

        <div class="footer-col">
            <h3>Head Office</h3>
            <p>
                <strong>Apex Express Logistics</strong><br>
                Office No. 42, 3rd Floor, Centaurus Corporate Tower,
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
                <a href="#">FAQs</a>
            </div>
        </div>
    </div>
</footer>

</html>
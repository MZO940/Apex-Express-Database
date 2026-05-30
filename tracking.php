<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Apex Express</title>
    <link rel="icon" type="image/png" href="logo.png">
    <link rel="stylesheet" href="tracking_style.css">
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
                <li><a href="tracking.php" class="nav-item active">Track Parcel</a></li>
                <li><a href="contactus.html" class="nav-item">Contact Us</a></li>
            </ul>

            <div class="nav-btn-wrapper">
                <a href="admin_dashboard.php" class="btn-portal">Admin Dashboard</a>
            </div>
        </div>
        <hr class="line">
    </nav>
    <div class="track-form">
        <h5>Track Your Parcel</h5>
        <p class="p">Enter your tracking number below to see the latest status of your shipment.</p>
    </div>

<section class="tracking-section">
    <form action="" method="POST">
    <input type="text" name="tracking_id" placeholder="Enter Tracking ID (e.g. APX-2026-XXX)" required>
    <button type="submit" name="search">Track Now</button>
</form>
</section>

</body>
<div id="result-area">
<?php
$conn = mysqli_connect("localhost", "root", "", "apex_express_schema");

$search_id = "";
if(isset($_POST['search'])) {
    $search_id = $_POST['tracking_id'];
} elseif(isset($_GET['tracking_id'])) {
    $search_id = $_GET['tracking_id'];
}

if(!empty($search_id)) {
    $id = mysqli_real_escape_string($conn, $search_id);
    $query = "SELECT p.*, s.First_name as s_fname, r.First_name as r_fname, ds.Status_type 
              FROM parcel p
              JOIN sender s ON p.Sender_ID = s.Sender_ID
              JOIN reciever r ON p.Reciever_ID = r.Reciever_ID
              JOIN delivery_status ds ON p.Status_id = ds.Status_id
              WHERE p.Tracking_id = '$id'";
    
    $result = mysqli_query($conn, $query);
    
    if(mysqli_num_rows($result) > 0) {
        $data = mysqli_fetch_assoc($result);
        echo '
        <div class="tracking-result-box">
            <div class="consignment-header">Consignment No: ' . $data['Tracking_id'] . '</div>
            <div class="details-grid">
                <div class="detail-item"><strong>Origin:</strong> ' . $data['Origin_city'] . '</div>
                <div class="detail-item"><strong>Destination:</strong> ' . $data['Destination_city'] . '</div>
                <div class="detail-item"><strong>Shipper:</strong> ' . $data['s_fname'] . '</div>
                <div class="detail-item"><strong>Consignee:</strong> ' . $data['r_fname'] . '</div>
            </div>
            <div style="padding:15px; border-top:1px solid #eee; text-align:center;">
                <strong>Current Status:</strong> ' . $data['Status_type'] . '
            </div>
        </div>';
    } else {
        echo '<p style="color: red; text-align: center;">ID found</p>';
    }
}
?>
</div>
</div>
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
                 <a href="https://www.linkedin.com/"><img src="linkedin.png" class="social-icon"></a>
                <a href="https://www.linkedin.com/"><img src="facebook-app-symbol.png" class="social-icon"></a>
                <a href="https://www.instagram.com/"><img src="instagram.png" class="social-icon"></a>
                <a href="https://www.whatsapp.com/"><img src="whatsapp.png" class="social-icon"></a>
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
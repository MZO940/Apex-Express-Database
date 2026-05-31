<?php

require_once __DIR__ . '/../includes/db_connect.php';
require_once __DIR__ . '/../includes/tracking_functions.php';
include '../includes/header.php';

$search_id = "";
$data = null;
$error = "";

// Handle search request
if (
    $_SERVER["REQUEST_METHOD"] === "POST"
    && isset($_POST['search'])
) {
    $search_id = trim($_POST['tracking_id']);
} elseif (isset($_GET['tracking_id'])) {
    $search_id = trim($_GET['tracking_id']);
}

// Lookup tracking information
if (!empty($search_id)) {

    $data = getParcelTrackingData(
        $conn,
        $search_id
    );

    if (!$data) {
        $error = "Tracking ID not found.";
    }
}

?>

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
    <div class="track-form">
        <h5>Track Your Parcel</h5>
        <p class="p">Enter your tracking number below to see the latest status of your shipment.</p>
    </div>

    <section class="tracking-section">
        <form action="tracking.php" method="POST">
            <input type="text" name="tracking_id" placeholder="Enter Tracking ID (e.g. APX-2026-XXX)" required>
            <button type="submit" name="search">Track Now</button>
        </form>
    </section>

    <div id="result-area">
        <?php if ($data): ?>
            <div class="tracking-result-box">
                <div class="consignment-header">
                    Consignment No: <?= htmlspecialchars($data['Tracking_id']) ?>
                </div>

                <div class="details-grid">
                    <div class="detail-item">
                        <strong>Origin:</strong>
                        <?= htmlspecialchars($data['Origin_city']) ?>
                    </div>

                    <div class="detail-item">
                        <strong>Destination:</strong>
                        <?= htmlspecialchars($data['Destination_city']) ?>
                    </div>

                    <div class="detail-item">
                        <strong>Shipper:</strong>
                        <?= htmlspecialchars($data['s_fname']) ?>
                    </div>

                    <div class="detail-item">
                        <strong>Consignee:</strong>
                        <?= htmlspecialchars($data['r_fname']) ?>
                    </div>
                </div>

                <div style="padding:15px; border-top:1px solid #eee; text-align:center;">
                    <strong>Current Status:</strong>
                    <?= htmlspecialchars($data['Status_type']) ?>
                </div>
            </div>

        <?php elseif ($error): ?>

            <p style="color: red; text-align: center;">
                <?= htmlspecialchars($error) ?>
            </p>

        <?php endif; ?>
    </div>
</body>

</html>

<?php
include '../includes/footer.php';
?>
<?php
session_start();

require_once __DIR__ . '/../includes/db_connect.php';
require_once __DIR__ . '/../includes/admin_auth.php';
require_once __DIR__ . '/../includes/admin_functions.php';
include '../includes/header.php';

?>

<?php
// ---------------- LOGIN ----------------
if (isset($_POST['login'])) {
    if (loginAdmin($conn, $_POST['user'], $_POST['pass'])) {
        $_SESSION['logged_in'] = true;
        $_SESSION['current_view'] = 'menu';
    } else {
        showAlert("Invalid Username or Password");
    }
}

// ---------------- LOGOUT ----------------
if (isset($_GET['logout'])) {
    logoutAdmin();
    header("Location: admin_dashboard.php");
    exit();
}

// ---------------- NAVIGATION HANDLING ----------------
if (isset($_GET['view']) && isset($_SESSION['logged_in'])) {
    $_SESSION['current_view'] = $_GET['view'];
}

// ---------------- FORM SUBMISSION LOGIC ----------------

// ADD BRANCH
if (isset($_POST['save_branch'])) {
    addBranch($conn, $_POST);
    showAlert("Branch added successfully!");
}

// ADD RIDER
if (isset($_POST['save_rider'])) {
    if (addRider($conn, $_POST)) {
        showAlert("Rider added successfully!");
    }
}

// COMPLETE PARCEL REGISTRATION
if (isset($_POST['save_parcel_complete'])) {
    if (createParcel($conn, $_POST)) {
        showAlert("All records compiled & saved successfully!");
    } else {
        showAlert("Database Error");
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apex Express - Admin Dashboard</title>
    <link rel="icon" type="image/png" href="img/logo.png">
    <link rel="stylesheet" href="style/style.css">
</head>

<body>
    <!-- ================= MAIN WRAPPER ================= -->
    <div class="admin-wrapper-workspace">

        <?php if (!isset($_SESSION['logged_in'])) { ?>

            <!-- ================= LOGIN CARD ================= -->
            <div class="admin-login-card">
                <h2>Admin Control Center</h2>

                <form method="POST">
                    <div class="input-group">
                        <input type="text" name="user" placeholder="Username" required>
                    </div>

                    <div class="input-group">
                        <input type="password" name="pass" placeholder="Password" required>
                    </div>

                    <button type="submit" name="login" class="btn-brand-submit">
                        Log In to System
                    </button>
                </form>
            </div>

        <?php } else {

            $view = $_SESSION['current_view'] ?? 'menu';
        ?>

            <div class="dashboard-central-holder">

                <!-- ================= ACTION BAR ================= -->
                <div class="dashboard-action-bar">

                    <?php echo ($view !== 'menu')
                        ? '<a href="?view=menu" class="btn-action-back">← Back to Menu Hub</a>'
                        : '<div></div>';
                    ?>

                    <a href="?logout=true" class="btn-action-logout">
                        Terminate Session
                    </a>

                </div>

                <!-- ================= MENU VIEW ================= -->
                <?php if ($view === 'menu') { ?>

                    <h2 class="workspace-main-title">
                        System Administration Modules
                    </h2>

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

                    <!-- ================= TABLE VIEW ================= -->
                <?php } elseif ($view === 'tables') {

                    $tables = [
                        'parcel',
                        'branch',
                        'rider',
                        'sender',
                        'reciever',
                        'delivery_status'
                    ];

                    foreach ($tables as $tbl) {
                        renderTable($conn, $tbl);
                    }

                ?>

                    <!-- ================= ADD BRANCH ================= -->
                <?php } elseif ($view === 'add_branch') { ?>

                    <form method="POST" class="brand-engineered-form">

                        <input type="text" name="branch_id" placeholder="Branch ID" required>
                        <input type="text" name="name" placeholder="Name" required>
                        <input type="email" name="email" placeholder="Email" required>
                        <input type="text" name="street" placeholder="Street" required>
                        <input type="text" name="area" placeholder="Area" required>
                        <input type="text" name="city" placeholder="City" required>
                        <input type="text" name="phone" placeholder="Phone">

                        <button type="submit" name="save_branch" class="btn-form-commit">
                            Commit
                        </button>

                    </form>

                    <!-- ================= ADD RIDER ================= -->
                <?php } elseif ($view === 'add_rider') { ?>

                    <form method="POST" class="brand-engineered-form">

                        <input type="text" name="rider_id" placeholder="Rider ID" required>
                        <input type="text" name="first_name" placeholder="First Name" required>
                        <input type="text" name="last_name" placeholder="Last Name" required>
                        <input type="text" name="cnic" placeholder="CNIC" required>
                        <input type="text" name="branch_id" placeholder="Branch ID" required>
                        <input type="text" name="phone" placeholder="Phone">

                        <button type="submit" name="save_rider" class="btn-form-commit">
                            Commit
                        </button>

                    </form>

                    <!-- ================= ADD PARCEL ================= -->
                <?php } elseif ($view === 'add_parcel') { ?>

                    <form method="POST" class="brand-engineered-form expanded-manifest-form">

                        <!-- ================= SENDER ================= -->
                        <input type="text" name="branch_id" placeholder="Branch ID" required>
                        <input type="text" name="rider_id" placeholder="Rider ID">

                        <input type="text" name="sender_id" placeholder="Sender ID" required>
                        <input type="text" name="sender_fname" placeholder="Sender First Name" required>
                        <input type="text" name="sender_lname" placeholder="Sender Last Name" required>
                        <input type="email" name="sender_email" placeholder="Sender Email" required>
                        <input type="text" name="sender_phone" placeholder="Sender Phone" required>
                        <input type="text" name="sender_street" placeholder="Sender Street" required>
                        <input type="text" name="sender_area" placeholder="Sender Area" required>
                        <input type="text" name="sender_city" placeholder="Sender City" required>

                        <!-- ================= RECEIVER ================= -->
                        <input type="text" name="reciever_id" placeholder="Receiver ID" required>
                        <input type="text" name="receiver_fname" placeholder="Receiver First Name" required>
                        <input type="text" name="receiver_lname" placeholder="Receiver Last Name" required>
                        <input type="email" name="receiver_email" placeholder="Receiver Email" required>
                        <input type="text" name="receiver_phone" placeholder="Receiver Phone" required>
                        <input type="text" name="receiver_zip" placeholder="Receiver Zip" required>
                        <input type="text" name="receiver_street" placeholder="Receiver Street" required>
                        <input type="text" name="receiver_area" placeholder="Receiver Area" required>
                        <input type="text" name="receiver_city" placeholder="Receiver City" required>

                        <!-- ================= PARCEL ================= -->
                        <input type="text" name="tracking_id" placeholder="Tracking ID" required>
                        <input type="number" step="0.01" name="weight" placeholder="Weight" required>
                        <input type="number" step="0.01" name="price" placeholder="Price" required>

                        <select name="payment_option">
                            <option value="Cash on Delivery">Cash on Delivery</option>
                            <option value="Easypaisa">Easypaisa</option>
                        </select>

                        <input type="text" name="status_id" placeholder="Status ID" required>

                        <select name="status_type">
                            <option value="Booked">Booked</option>
                            <option value="In-Transit">In-Transit</option>
                        </select>

                        <input type="text" name="remarks" placeholder="Remarks">

                        <button type="submit" name="save_parcel_complete" class="btn-form-commit">
                            Deploy Shipment
                        </button>

                    </form>

                <?php } ?>

            </div>

        <?php } ?>

    </div>

</body>

</html>

<?php
include '../includes/footer.php';
?>
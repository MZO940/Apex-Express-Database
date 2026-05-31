<?php
include '../includes/header.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apex Express</title>
    <link rel="icon" type="image/png" href="imgs/logo.png">
    <link rel="stylesheet" href="style/style.css">
</head>

<body>
    <div class="slider">
        <div class="slides">
            <div class="slide fade">
                <img src="imgs/image1.jpeg" alt="Image 1">

            </div>
            <div class="slide fade">
                <img src="imgs/image2.jpeg" alt="Image 2">

            </div>
            <div class="slide fade">
                <img src="imgs/image3.jpeg" alt="Image 3">

            </div>
            <div class="slide fade">
                <img src="imgs/image4.jpeg" alt="Image 4">
            </div>
        </div>
    </div>

    <section class="parcel-tracker-section">
        <div class="parcel-tracker-container">
            <h2 class="parcel-tracker-title">Track Your Parcel</h2>

            <form action="tracking.php" method="GET" class="parcel-tracker-form">
                <input type="text" name="tracking_id" placeholder="e.g., APX-2026-XXX" class="parcel-tracker-input" required>
                <button type="submit" class="parcel-tracker-btn">Track Now</button>
            </form>
        </div>
    </section>

</body>

</html>

<?php
include '../includes/footer.php';
?>
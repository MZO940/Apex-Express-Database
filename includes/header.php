<?php
$currentPage = basename($_SERVER['PHP_SELF']);
?>

<nav class="apex-navbar">
    <div class="nav-wrapper">
        <div class="nav-logo">
            <img src="imgs/logo.png" alt="Apex Express Logo" class="logo-truck">
            <div class="logo-info">
                <div class="logo-text">
                    <span class="brand-bold">Apex</span><span class="brand-light">Express</span>
                </div>
                <div class="logo-slogan">Fast. Secure. Right on Time.</div>
            </div>
        </div>
        <ul class="nav-menu">
            <li>
                <a href="index.php"
                    class="nav-item <?= ($currentPage == 'index.php') ? 'active' : '' ?>">
                    Home
                </a>
            </li>

            <li>
                <a href="tracking.php"
                    class="nav-item <?= ($currentPage == 'tracking.php') ? 'active' : '' ?>">
                    Track Parcel
                </a>
            </li>

            <li>
                <a href="contact.php"
                    class="nav-item <?= ($currentPage == 'contact.php') ? 'active' : '' ?>">
                    Contact Us
                </a>
            </li>
        </ul>
        <div class="nav-btn-wrapper">
            <a href="admin_dashboard.php" class="btn-portal">Admin Dashboard</a>
        </div>
    </div>
</nav>
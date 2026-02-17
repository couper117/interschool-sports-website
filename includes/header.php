<?php
// includes/header.php
$page_title = $page_title ?? 'Imikino y\'Amashuri mu Rwanda';
?>
<!DOCTYPE html>
<html lang="rw">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($page_title) ?> | Interschool Sports Rwanda</title>
    <link rel="stylesheet" href="css/style.css">
    <script src="js/script.js" defer></script>
</head>
<body>

<header class="site-header">
    <div class="top-bar">
        <div class="container">
            <span class="logo-text">Imikino y'Amashuri</span>
            <span class="slogan">Murakaza neza mu mikino y'amashuri mu Rwanda</span>
        </div>
    </div>

    <nav class="main-nav">
        <div class="container">
            <button class="menu-toggle" aria-label="Toggle menu">
                <span class="hamburger-icon">☰</span>
            </button>

            <ul class="nav-menu">
                <li><a href="index.php">Ahabanza</a></li>
                <li><a href="about.php">Ibyerekeye</a></li>
                <li><a href="sports.php">Imikino</a></li>
                <li><a href="fixtures.php">Imikino izaba</a></li>
                <li><a href="results.php">Ibyavuye</a></li>
                <li><a href="standings.php">Imyanya</a></li>
                <li><a href="announcements.php">Amatangazo</a></li>
                <li><a href="schools.php">Amashuri</a></li>
                <li><a href="regulations.php">Amabwiriza</a></li>
                <li><a href="rssf-updates.php">RSSF</a></li>
                <li><a href="contact.php">Twandikire</a></li>
                <li class="search-item">
                    <form action="search.php" method="get" class="nav-search">
                        <input type="text" name="q" placeholder="Shakisha..." minlength="2">
                        <button type="submit">Shaka</button>
                    </form>
                </li>
                <li><a href="admin/login.php" class="admin-btn">Admin</a></li>
            </ul>
        </div>
    </nav>
</header>

<main class="site-main">

<main class="site-main">
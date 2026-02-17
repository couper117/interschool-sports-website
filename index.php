<?php include 'includes/header.php'; ?>
<?php include 'config.php'; ?>
<!DOCTYPE html>
<html lang="rw">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Imikino y'Amashuri mu Rwanda</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    
    <div id="preloader">
    <h2 style="color: white; font-family: sans-serif;">MURAKAZA NEZA</h2>
    <div class="loader-box">
        <div class="loader-bar"></div>
    </div>
    <p style="color: #666; margin-top: 15px;">Loading your experience...</p>
</div>

<script>
    // This hides the loader once the page is fully loaded
    window.addEventListener('load', () => {
        const loader = document.getElementById('preloader');
        setTimeout(() => {
            loader.style.opacity = '0';
            setTimeout(() => loader.style.display = 'none', 500);
        }, 1500); // 1.5s delay to show the "game" feel
    });
</script>
   

    <h1>Murakaza neza mu mikino y'amashuri mu Rwanda!<br>Welcome to Rwanda Interschool Sports!</h1>

    <h2>Amatangazo mashya / Latest Announcements</h2>
    <?php
    $result = $conn->query("SELECT * FROM announcements ORDER BY date DESC LIMIT 3");
    while ($row = $result->fetch_assoc()) {
        echo "<h3>{$row['title']}</h3><p>{$row['content']}</p><hr>";
    }
    ?>

    <footer style="margin-top:40px; text-align:center; color:#555;">
        Ikurikije amabwiriza ya MINEDUC na RSSF • Compliant with MINEDUC & RSSF guidelines
    </footer>
</body>
</html>
<?php include 'includes/footer.php'; ?>
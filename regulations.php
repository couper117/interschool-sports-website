<?php include 'includes/header.php'; ?>
<?php include 'config.php'; ?>
<!DOCTYPE html>
<html lang="rw">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Amabwiriza y'Imikino y'Amashuri – Regulations</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <nav><!-- existing navbar --></nav>
    <h1>Amabwiriza y'Imikino y'Amashuri (Rwanda School Sports Regulations)</h1>
    
    <div class="regulations-grid">
        <?php
        $result = $conn->query("SELECT * FROM regulations");
        while ($row = $result->fetch_assoc()) {
            echo "<div class='card'>
                <h3>{$row['title']}</h3>
                <p>{$row['content']}</p>
                <a href='{$row['pdf_url']}' target='_blank' class='btn'>Download PDF (Icyemezo cy'Ubutegetsi)</a>
            </div>";
        }
        ?>
    </div>

    <h2>Key Rules Summary (Summary in Kinyarwanda & English)</h2>
    <ul>
        <li><strong>Age Groups:</strong> U13, U15, U17, U20 (Amashuri Kagame Cup)</li>
        <li><strong>Progression:</strong> Cell → Sector → District → Province → National</li>
        <li><strong>Eligibility:</strong> Only enrolled students (proof required)</li>
        <li><strong>Inclusion:</strong> Full participation for learners with disabilities</li>
        <li><strong>Gender:</strong> Separate male/female categories</li>
    </ul>
    
    <p class="official">Compliant with MINEDUC School Sports Policy 2020 & RSSF Guidelines • Last updated: 2026</p>
</body>
</html>
<?php include 'includes/footer.php'; ?>

<?php include 'includes/header.php'; ?>
<?php 
include 'config.php'; 
// Optional: require_login() if you want admin-only advanced view
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Results - Interschool Sports Rwanda</title>
    <link rel="stylesheet" href="css/style.css">
  
</head>
<body>

    <h1>Match Results</h1>
    <p>Official results from Cell to National level competitions (Compliant with RSSF & MINEDUC guidelines)</p>

    <!-- Filters -->
    <div class="filters">
        <label>Sport:</label>
        <select id="sportFilter" onchange="applyFilters()">
            <option value="">All Sports</option>
            <?php
            $sports = $conn->query("SELECT id, name FROM sports");
            while ($s = $sports->fetch_assoc()) {
                echo "<option value='{$s['id']}'>{$s['name']}</option>";
            }
            ?>
        </select>

        <label>Level:</label>
        <select id="levelFilter" onchange="applyFilters()">
            <option value="">All Levels</option>
            <option value="cell">Cell</option>
            <option value="sector">Sector</option>
            <option value="district">District</option>
            <option value="province">Province</option>
            <option value="national">National</option>
        </select>

        <label>Gender:</label>
        <select id="genderFilter" onchange="applyFilters()">
            <option value="">All</option>
            <option value="male">Male</option>
            <option value="female">Female</option>
        </select>

        <label>Age Category:</label>
        <select id="ageFilter" onchange="applyFilters()">
            <option value="">All Ages</option>
            <option value="U13">U13</option>
            <option value="U15">U15</option>
            <option value="U17">U17</option>
            <option value="U20">U20 (Amashuri Kagame Cup)</option>
        </select>
    </div>

    <input type="text" id="search" onkeyup="searchTable()" placeholder="Search by team, score...">

    <table id="dataTable" class="results-table">
        <thead>
            <tr>
                <th>Date</th>
                <th>Sport</th>
                <th>Level</th>
                <th>Age</th>
                <th>Gender</th>
                <th>Match</th>
                <th>Result</th>
                <th>Winner</th>
            </tr>
        </thead>
        <tbody>
        <?php
        $sql = "SELECT m.id, m.date, m.result, m.winner_id, 
                       s.name AS sport, m.level, m.age_category, t1.gender,
                       CONCAT(sch1.name, ' ', t1.gender) AS team1,
                       CONCAT(sch2.name, ' ', t2.gender) AS team2,
                       CONCAT(sch1.name, ' vs ', sch2.name) AS match_name
                FROM matches m
                JOIN sports s ON m.sport_id = s.id
                JOIN teams t1 ON m.team1_id = t1.id
                JOIN teams t2 ON m.team2_id = t2.id
                JOIN schools sch1 ON t1.school_id = sch1.id
                JOIN schools sch2 ON t2.school_id = sch2.id
                WHERE m.result IS NOT NULL
                ORDER BY m.date DESC";

        $result = $conn->query($sql);
        while ($row = $result->fetch_assoc()) {
            $winner = $row['winner_id'] ? ($row['winner_id'] == $row['team1_id'] ? $row['team1'] : $row['team2']) : 'Draw';
            echo "<tr data-sport='{$row['sport']}' data-level='{$row['level']}' data-gender='{$row['gender']}' data-age='{$row['age_category']}'>
                <td>{$row['date']}</td>
                <td>{$row['sport']}</td>
                <td>{$row['level']}</td>
                <td>{$row['age_category']}</td>
                <td>{$row['gender']}</td>
                <td>{$row['team1']} vs {$row['team2']}</td>
                <td>{$row['result']}</td>
                <td>$winner</td>
            </tr>";
        }
        ?>
        </tbody>
    </table>

    <footer>
        <p>Compliant with Rwanda School Sports Federation (RSSF) & MINEDUC School Sports Policy • Updated: <?php echo date('F Y'); ?></p>
    </footer>
<input type="text" id="search" placeholder="Search..." style="width:100%; padding:10px; margin:15px 0; font-size:16px;">
<table id="dataTable"> ... </table>
<script src="js/script.js" defer></script>
    <script>
    function applyFilters() {
        let rows = document.querySelectorAll('#dataTable tbody tr');
        let sport = document.getElementById('sportFilter').value.toLowerCase();
        let level = document.getElementById('levelFilter').value.toLowerCase();
        let gender = document.getElementById('genderFilter').value.toLowerCase();
        let age = document.getElementById('ageFilter').value;

        rows.forEach(row => {
            let show = true;
            if (sport && row.dataset.sport.toLowerCase() !== sport) show = false;
            if (level && row.dataset.level !== level) show = false;
            if (gender && row.dataset.gender !== gender) show = false;
            if (age && row.dataset.age !== age) show = false;

            row.style.display = show ? '' : 'none';
        });
    }

    // Reuse your existing searchTable() from js/script.js for text search
    </script>
</body>
</html>
<?php include 'includes/footer.php'; ?>
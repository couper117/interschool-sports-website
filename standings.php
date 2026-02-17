<?php 
include 'includes/header.php';
include 'config.php'; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Standings - Interschool Sports Rwanda</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <h1>Standings & Rankings</h1>
    <p>Points tables by competition level, age category, gender, and sport (3 pts win, 1 pt draw, 0 pt loss – RSSF standard for team sports)</p>

    <!-- Filters (same as results) -->
    <div class="filters">
        <!-- Same select elements as in results.php for sport, level, gender, age -->
        <!-- Add onchange="loadStandings()" to each -->
    </div>

    <div id="standingsContainer">
        <?php
        // Example: Show national U20 male Football by default, or use GET params for filters
        $sport_id = 1; // Assume Football = 1; make dynamic with $_GET
        $level = 'national';
        $gender = 'male';
        $age = 'U20';

        // Calculate standings
        $standings = [];
        $query = "SELECT t.id AS team_id, sch.name AS school, t.gender, SUM(
                    CASE 
                        WHEN m.winner_id = t.id THEN 3
                        WHEN m.result LIKE '%draw%' OR m.result LIKE '%Draw%' THEN 1
                        ELSE 0 
                    END
                ) AS points,
                COUNT(m.id) AS played,
                SUM(CASE WHEN m.winner_id = t.id THEN 1 ELSE 0 END) AS wins,
                SUM(CASE WHEN m.result LIKE '%draw%' THEN 1 ELSE 0 END) AS draws,
                SUM(CASE WHEN m.winner_id != t.id AND m.winner_id IS NOT NULL THEN 1 ELSE 0 END) AS losses
                FROM teams t
                JOIN schools sch ON t.school_id = sch.id
                LEFT JOIN matches m ON (m.team1_id = t.id OR m.team2_id = t.id) AND m.result IS NOT NULL
                WHERE t.sport_id = ? AND t.level = ? AND t.gender = ? AND t.age_category = ?
                GROUP BY t.id
                ORDER BY points DESC, wins DESC, played DESC";

        $stmt = $conn->prepare($query);
        $stmt->bind_param("isss", $sport_id, $level, $gender, $age);
        $stmt->execute();
        $res = $stmt->get_result();

        echo "<table class='standings-table'>
            <thead><tr><th>Pos</th><th>Team / School</th><th>P</th><th>W</th><th>D</th><th>L</th><th>Pts</th></tr></thead><tbody>";

        $pos = 1;
        while ($row = $res->fetch_assoc()) {
            $inclusive = $conn->query("SELECT is_inclusive FROM teams WHERE id = {$row['team_id']}")->fetch_assoc()['is_inclusive'] ? ' (Inclusive)' : '';
            echo "<tr>
                <td>$pos</td>
                <td>{$row['school']}$inclusive</td>
                <td>{$row['played']}</td>
                <td>{$row['wins']}</td>
                <td>{$row['draws']}</td>
                <td>{$row['losses']}</td>
                <td><strong>{$row['points']}</strong></td>
            </tr>";
            $pos++;
        }
        echo "</tbody></table>";
        ?>
    </div>

    <p class="note">For Athletics/individual sports: Standings show top performers based on medals/points (1st=10, 2nd=8, 3rd=6...)</p>

    <footer><!-- same as above --></footer>

    <script src="js/script.js" defer></script>
    <!-- JS for dynamic reload via AJAX or simple page refresh on filter change -->
    <script>
    function loadStandings() {
        // For full dynamic: use fetch/AJAX to standings_api.php with filter params
        // For simplicity now: location.reload() after changing filters (add ?sport=..&level=.. etc.)
        let params = new URLSearchParams();
        params.append('sport', document.getElementById('sportFilter').value);
        // ... add others
        window.location.search = params.toString();
    }
    </script>
</body>
</html>
<?php include 'includes/footer.php'; ?>
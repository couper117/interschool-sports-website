<?php
include '../config.php';
include '../auth.php';
require_login();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $result = $_POST['result'];
    $winner_id = $_POST['winner_id']; // 0 for draw

    $stmt = prepare_stmt("UPDATE matches SET result = ?, winner_id = ? WHERE id = ?");
    $stmt->bind_param("sii", $result, $winner_id, $_POST['match_id']);
    $stmt->execute();

    // Auto-promote winner to next level (Rwanda progression)
    if ($winner_id != 0) {
        $match = $conn->query("SELECT level FROM matches WHERE id = {$_POST['match_id']}")->fetch_assoc();
        $current = $match['level'];
        $next = ($current == 'cell') ? 'sector' : ($current == 'sector') ? 'district' : ($current == 'district') ? 'province' : ($current == 'province') ? 'national' : 'national';
        $conn->query("UPDATE teams SET level = '$next' WHERE id = $winner_id");
    }

    echo "<p style='color:green'>Result saved and progression updated!</p>";
}
?>
<!DOCTYPE html>
<html><head><meta charset="UTF-8"><title>Enter Result</title><link rel="stylesheet" href="../css/style.css"></head>
<body>
<h2>Enter Match Result</h2>
<form method="get">
    <label>Select Match:</label>
    <select name="match_id" required onchange="this.form.submit()">
        <option value="">Choose a fixture</option>
        <?php
        $matches = $conn->query("SELECT m.id, s.name AS sport, m.level, sch1.name AS t1, sch2.name AS t2 
                                 FROM matches m 
                                 JOIN sports s ON m.sport_id=s.id 
                                 JOIN teams t1 ON m.team1_id=t1.id JOIN schools sch1 ON t1.school_id=sch1.id
                                 JOIN teams t2 ON m.team2_id=t2.id JOIN schools sch2 ON t2.school_id=sch2.id
                                 WHERE m.result IS NULL ORDER BY m.date DESC");
        while ($m = $matches->fetch_assoc()) {
            $selected = ($_GET['match_id'] ?? '') == $m['id'] ? 'selected' : '';
            echo "<option value='{$m['id']}' $selected>{$m['sport']} - {$m['t1']} vs {$m['t2']} ({$m['level']})</option>";
        }
        ?>
    </select>
</form>

<?php if (isset($_GET['match_id'])): 
    $match_id = $_GET['match_id'];
    $match = $conn->query("SELECT team1_id, team2_id FROM matches WHERE id = $match_id")->fetch_assoc();
?>
<form method="post">
    <input type="hidden" name="match_id" value="<?php echo $match_id; ?>">
    <label>Result (e.g. 3-1 or Draw):</label><input type="text" name="result" required><br><br>
    <label>Winner:</label>
    <select name="winner_id" required>
        <option value="0">Draw</option>
        <option value="<?php echo $match['team1_id']; ?>">Team 1 wins</option>
        <option value="<?php echo $match['team2_id']; ?>">Team 2 wins</option>
    </select><br><br>
    <button type="submit">Save Result</button>
</form>
<?php endif; ?>
<a href="dashboard.php">← Back to Dashboard</a>
</body></html>
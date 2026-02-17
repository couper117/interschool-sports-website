<?php
include '../config.php';
include '../auth.php';
require_login();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $stmt = prepare_stmt("INSERT INTO matches (sport_id, level, team1_id, team2_id, date, age_category) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isiiss", $_POST['sport_id'], $_POST['level'], $_POST['team1_id'], $_POST['team2_id'], $_POST['date'], $_POST['age_category']);
    if ($stmt->execute()) {
        echo "<p style='color:green'>Fixture created successfully!</p>";
    }
}
?>
<!DOCTYPE html>
<html><head><meta charset="UTF-8"><title>Create Fixture</title><link rel="stylesheet" href="../css/style.css"></head>
<body>
<h2>Create New Fixture</h2>
<form method="post">
    <label>Sport:</label>
    <select name="sport_id" required onchange="this.form.submit()">
        <option value="">Select Sport</option>
        <?php
        $sports = $conn->query("SELECT id, name FROM sports");
        while ($s = $sports->fetch_assoc()) {
            $selected = ($_POST['sport_id'] ?? '') == $s['id'] ? 'selected' : '';
            echo "<option value='{$s['id']}' $selected>{$s['name']}</option>";
        }
        ?>
    </select><br><br>

    <?php if (isset($_POST['sport_id'])): ?>
    <label>Level:</label>
    <select name="level" required><option>cell</option><option>sector</option><option>district</option><option>province</option><option>national</option></select><br><br>
    <label>Age Category:</label>
    <select name="age_category" required><option>U13</option><option>U15</option><option>U17</option><option>U20</option></select><br><br>

    <label>Team 1:</label>
    <select name="team1_id" required>
        <?php
        $teams = $conn->query("SELECT t.id, sch.name, t.gender, t.age_category FROM teams t JOIN schools sch ON t.school_id=sch.id WHERE t.sport_id = {$_POST['sport_id']}");
        while ($t = $teams->fetch_assoc()) {
            echo "<option value='{$t['id']}'>{$t['name']} ({$t['gender']} {$t['age_category']})</option>";
        }
        ?>
    </select><br><br>

    <label>Team 2:</label>
    <select name="team2_id" required>
        <?php $teams->data_seek(0); while ($t = $teams->fetch_assoc()) {
            echo "<option value='{$t['id']}'>{$t['name']} ({$t['gender']} {$t['age_category']})</option>";
        } ?>
    </select><br><br>

    <label>Date & Time:</label><input type="datetime-local" name="date" required><br><br>
    <button type="submit">Create Fixture</button>
    <?php endif; ?>
</form>
<a href="dashboard.php">← Back to Dashboard</a>
</body></html>
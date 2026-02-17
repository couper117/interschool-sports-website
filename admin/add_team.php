<?php
include '../config.php';
include '../auth.php';
require_login();
?>
<!DOCTYPE html>
<html><head><meta charset="UTF-8"><title>Add Team</title><link rel="stylesheet" href="../css/style.css"></head>
<body>
<h2>Add New Team</h2>
<form method="post">
    <label>School:</label>
    <select name="school_id" required>
        <?php
        $schools = $conn->query("SELECT id, name FROM schools ORDER BY name");
        while ($s = $schools->fetch_assoc()) {
            echo "<option value='{$s['id']}'>{$s['name']}</option>";
        }
        ?>
    </select><br><br>

    <label>Sport:</label>
    <select name="sport_id" required>
        <?php
        $sports = $conn->query("SELECT id, name FROM sports ORDER BY name");
        while ($s = $sports->fetch_assoc()) {
            echo "<option value='{$s['id']}'>{$s['name']}</option>";
        }
        ?>
    </select><br><br>

    <label>Gender:</label>
    <select name="gender" required>
        <option value="male">Male</option>
        <option value="female">Female</option>
    </select><br><br>

    <label>Age Category:</label>
    <select name="age_category" required>
        <option value="U13">U13</option>
        <option value="U15">U15</option>
        <option value="U17">U17</option>
        <option value="U20">U20 (Amashuri Kagame Cup)</option>
    </select><br><br>

    <label>Current Level:</label>
    <select name="level" required>
        <option value="cell">Cell</option>
        <option value="sector">Sector</option>
        <option value="district">District</option>
        <option value="province">Province</option>
        <option value="national">National</option>
    </select><br><br>

    <label>Inclusive Team? (for learners with disabilities)</label>
    <input type="checkbox" name="is_inclusive" value="1"><br><br>

    <button type="submit">Add Team</button>
</form>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $stmt = prepare_stmt("INSERT INTO teams (school_id, sport_id, gender, age_category, level, is_inclusive) VALUES (?, ?, ?, ?, ?, ?)");
    $is_inclusive = isset($_POST['is_inclusive']) ? 1 : 0;
    $stmt->bind_param("iisssi", $_POST['school_id'], $_POST['sport_id'], $_POST['gender'], $_POST['age_category'], $_POST['level'], $is_inclusive);
    if ($stmt->execute()) {
        echo "<p style='color:green'>Team added successfully!</p>";
    } else {
        echo "<p style='color:red'>Error adding team.</p>";
    }
}
?>
<a href="dashboard.php">← Back to Dashboard</a>
</body></html>
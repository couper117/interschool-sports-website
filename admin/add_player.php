<?php
include '../config.php';
include '../auth.php';
require_login();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $team_id = $_POST['team_id'];
    $name = $_POST['name'];
    $dob = $_POST['date_of_birth'];
    $age = date_diff(date_create($dob), date_create('today'))->y;
    $category = $_POST['age_category'];

    $valid = false;
    if ($category == 'U13' && $age <= 13) $valid = true;
    elseif ($category == 'U15' && $age <= 15) $valid = true;
    elseif ($category == 'U17' && $age <= 17) $valid = true;
    elseif ($category == 'U20' && $age <= 20) $valid = true;

    if (!$valid) {
        die("Age does not match selected category (RSSF rules)");
    }

    // Proof upload (optional)
    $proof_path = '';
    if (isset($_FILES['proof']) && $_FILES['proof']['error'] == 0) {
        $proof_path = "proofs/" . basename($_FILES["proof"]["name"]);
        move_uploaded_file($_FILES["proof"]["tmp_name"], "../uploads/" . $proof_path);
    }

    $stmt = $conn->prepare("INSERT INTO players (team_id, name, date_of_birth, age_category, proof_document) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("issss", $team_id, $name, $dob, $category, $proof_path);
    $stmt->execute();

    echo "Player added successfully.";
}
?>

<form method="post" enctype="multipart/form-data">
    <label>Team ID:</label><input type="number" name="team_id" required><br>
    <label>Name:</label><input type="text" name="name" required><br>
    <label>Date of Birth:</label><input type="date" name="date_of_birth" required><br>
    <label>Age Category:</label>
    <select name="age_category" required>
        <option value="U13">U13</option>
        <option value="U15">U15</option>
        <option value="U17">U17</option>
        <option value="U20">U20</option>
    </select><br>
    <label>Proof Document (optional):</label><input type="file" name="proof" accept=".pdf,.jpg,.png"><br><br>
    <button type="submit">Add Player</button>
</form>
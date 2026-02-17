<?php
include '../config.php';
include '../auth.php';
require_login();

// Handle Delete
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $stmt = prepare_stmt("DELETE FROM schools WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    header("Location: list_schools.php?msg=deleted");
    exit;
}

// Handle Edit (POST)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update'])) {
    $id = (int)$_POST['id'];
    $name = $_POST['name'];
    $cell = $_POST['cell'];
    $sector = $_POST['sector'];
    $district = $_POST['district'];
    $province = $_POST['province'];

    $stmt = prepare_stmt("UPDATE schools SET name=?, cell=?, sector=?, district=?, province=? WHERE id=?");
    $stmt->bind_param("sssssi", $name, $cell, $sector, $district, $province, $id);
    $stmt->execute();
    header("Location: list_schools.php?msg=updated");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Schools</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <h2>Manage Schools</h2>
    <?php
    if (isset($_GET['msg'])) {
        if ($_GET['msg'] == 'deleted') echo "<p style='color:green'>School deleted.</p>";
        if ($_GET['msg'] == 'updated') echo "<p style='color:green'>School updated.</p>";
    }
    ?>

    <table border="1" cellpadding="8">
        <tr><th>ID</th><th>Name</th><th>Cell</th><th>Sector</th><th>District</th><th>Province</th><th>Actions</th></tr>
        <?php
        $result = $conn->query("SELECT * FROM schools ORDER BY name");
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                <td>{$row['id']}</td>
                <td>{$row['name']}</td>
                <td>{$row['cell']}</td>
                <td>{$row['sector']}</td>
                <td>{$row['district']}</td>
                <td>{$row['province']}</td>
                <td>
                    <a href='?edit={$row['id']}'>Edit</a> |
                    <a href='?delete={$row['id']}' onclick='return confirm(\"Delete this school?\");'>Delete</a>
                </td>
            </tr>";

            // Show edit form inline when ?edit=ID
            if (isset($_GET['edit']) && $_GET['edit'] == $row['id']) {
                echo "<tr><td colspan='7'>
                    <form method='post'>
                        <input type='hidden' name='id' value='{$row['id']}'>
                        Name: <input type='text' name='name' value='{$row['name']}' required>
                        Cell: <input type='text' name='cell' value='{$row['cell']}' required>
                        Sector: <input type='text' name='sector' value='{$row['sector']}' required>
                        District: <input type='text' name='district' value='{$row['district']}' required>
                        Province: <input type='text' name='province' value='{$row['province']}' required>
                        <button type='submit' name='update'>Save Changes</button>
                    </form>
                </td></tr>";
            }
        }
        ?>
    </table>

    <br><a href="dashboard.php">← Back to Dashboard</a>
</body>
</html>
<?php
$page_title = "Imikino | Sports Categories";
include 'includes/header.php';
include 'config.php';
?>

<h1>Imikino yose / All Sports Categories</h1>

<div class="filters">
    <label>Shakisha / Search:</label>
    <input type="text" id="search" placeholder="Shakisha imikino...">
</div>

<table id="dataTable">
    <thead>
        <tr>
            <th>Imikino / Sport</th>
            <th>Ubwoko / Type</th>
            <th>Imyaka / Age Categories</th>
            <th>Igender / Gender</th>
        </tr>
    </thead>
    <tbody>
    <?php
    $result = $conn->query("SELECT s.name, s.category, GROUP_CONCAT(DISTINCT t.age_category SEPARATOR ', ') AS ages,
                                   GROUP_CONCAT(DISTINCT t.gender SEPARATOR ', ') AS genders
                            FROM sports s
                            LEFT JOIN teams t ON s.id = t.sport_id
                            GROUP BY s.id, s.name, s.category");
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
            <td>" . htmlspecialchars($row['name']) . "</td>
            <td>" . htmlspecialchars($row['category']) . "</td>
            <td>" . htmlspecialchars($row['ages'] ?: 'Nta makipe arashyirwamo') . "</td>
            <td>" . htmlspecialchars($row['genders'] ?: 'Nta makipe') . "</td>
        </tr>";
    }
    ?>
    </tbody>
</table>

<?php include 'includes/footer.php'; ?>
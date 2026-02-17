<?php
$page_title = "Amashuri | Schools";
include 'includes/header.php';
include 'config.php';
?>

<h1>Amashuri yose / All Participating Schools</h1>

<input type="text" id="search" placeholder="Shakisha ishuri cyangwa akarere..." style="width:100%; padding:10px; margin:15px 0;">

<table id="dataTable">
    <thead>
        <tr>
            <th>Ishuri / School</th>
            <th>Cell</th>
            <th>Sector</th>
            <th>District</th>
            <th>Province</th>
        </tr>
    </thead>
    <tbody>
    <?php
    $result = $conn->query("SELECT * FROM schools ORDER BY name");
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
            <td>" . htmlspecialchars($row['name']) . "</td>
            <td>" . htmlspecialchars($row['cell']) . "</td>
            <td>" . htmlspecialchars($row['sector']) . "</td>
            <td>" . htmlspecialchars($row['district']) . "</td>
            <td>" . htmlspecialchars($row['province']) . "</td>
        </tr>";
    }
    ?>
    </tbody>
</table>

<?php include 'includes/footer.php'; ?>
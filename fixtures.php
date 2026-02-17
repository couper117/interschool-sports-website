<?php
$page_title = "Imikino izaba | Fixtures";
include 'includes/header.php';
include 'config.php';
?>

<h1>Imikino izaba / Upcoming Fixtures</h1>

<div class="filters">
    <label>Imikino / Sport:</label>
    <select id="sportFilter" onchange="applyFilters()">
        <option value="">Zose / All</option>
        <?php
        $sports = $conn->query("SELECT id, name FROM sports ORDER BY name");
        while ($s = $sports->fetch_assoc()) {
            echo "<option value='{$s['id']}'>{$s['name']}</option>";
        }
        ?>
    </select>

    <label>Level:</label>
    <select id="levelFilter" onchange="applyFilters()">
        <option value="">Zose</option>
        <option value="cell">Cell</option><option value="sector">Sector</option>
        <option value="district">District</option><option value="province">Province</option>
        <option value="national">National</option>
    </select>

    <input type="text" id="search" placeholder="Shakisha amakipe...">
</div>

<table id="dataTable">
    <thead>
        <tr>
            <th>Itariki / Date</th>
            <th>Imikino / Sport</th>
            <th>Level</th>
            <th>Imyaka / Age</th>
            <th>Igender / Gender</th>
            <th>Amakipe / Match</th>
        </tr>
    </thead>
    <tbody>
    <?php
    $result = $conn->query("
        SELECT m.date, s.name AS sport, m.level, m.age_category, t1.gender,
               CONCAT(sch1.name, ' vs ', sch2.name) AS match_name
        FROM matches m
        JOIN sports s ON m.sport_id = s.id
        JOIN teams t1 ON m.team1_id = t1.id
        JOIN teams t2 ON m.team2_id = t2.id
        JOIN schools sch1 ON t1.school_id = sch1.id
        JOIN schools sch2 ON t2.school_id = sch2.id
        WHERE m.result IS NULL
        ORDER BY m.date ASC
    ");
    while ($row = $result->fetch_assoc()) {
        echo "<tr data-sport='{$row['sport']}' data-level='{$row['level']}'>
            <td>" . date('d/m/Y H:i', strtotime($row['date'])) . "</td>
            <td>{$row['sport']}</td>
            <td>{$row['level']}</td>
            <td>{$row['age_category']}</td>
            <td>{$row['gender']}</td>
            <td>{$row['match_name']}</td>
        </tr>";
    }
    ?>
    </tbody>
</table>

<script>
function applyFilters() {
    let rows = document.querySelectorAll('#dataTable tbody tr');
    let sport = document.getElementById('sportFilter').value;
    let level = document.getElementById('levelFilter').value;
    let search = document.getElementById('search').value.toUpperCase();

    rows.forEach(row => {
        let show = true;
        if (sport && row.dataset.sport !== sport) show = false;
        if (level && row.dataset.level !== level) show = false;
        if (search) {
            if (!row.textContent.toUpperCase().includes(search)) show = false;
        }
        row.style.display = show ? '' : 'none';
    });
}
document.getElementById('search').addEventListener('keyup', applyFilters);
</script>

<?php include 'includes/footer.php'; ?>
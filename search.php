<?php
include 'config.php';

// Get search term
$q = isset($_GET['q']) ? trim($_GET['q']) : '';
$results = [];
$searchTerm = '%' . $q . '%';

if (strlen($q) >= 2) {  // avoid tiny searches
    // 1. Schools
    $stmt = $conn->prepare("
        SELECT 'School' AS type, name, CONCAT(cell, ' - ', sector, ' - ', district, ' - ', province) AS location, id
        FROM schools 
        WHERE name LIKE ? OR cell LIKE ? OR sector LIKE ? OR district LIKE ? OR province LIKE ?
        LIMIT 10
    ");
    $stmt->bind_param("sssss", $searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm);
    $stmt->execute();
    $results['schools'] = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    // 2. Teams (with school & sport)
    $stmt = $conn->prepare("
        SELECT 'Team' AS type, CONCAT(s.name, ' ', t.gender, ' ', t.age_category, ' ', sp.name) AS name,
               t.level, t.id
        FROM teams t
        JOIN schools s ON t.school_id = s.id
        JOIN sports sp ON t.sport_id = sp.id
        WHERE s.name LIKE ? OR sp.name LIKE ? OR t.gender LIKE ? OR t.age_category LIKE ? OR t.level LIKE ?
        LIMIT 10
    ");
    $stmt->bind_param("sssss", $searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm);
    $stmt->execute();
    $results['teams'] = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    // 3. Players
    $stmt = $conn->prepare("
        SELECT 'Player' AS type, p.name, CONCAT(s.name, ' ', t.gender, ' ', t.age_category) AS team,
               p.id
        FROM players p
        JOIN teams t ON p.team_id = t.id
        JOIN schools s ON t.school_id = s.id
        WHERE p.name LIKE ?
        LIMIT 10
    ");
    $stmt->bind_param("s", $searchTerm);
    $stmt->execute();
    $results['players'] = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    // 4. Announcements
    $stmt = $conn->prepare("
        SELECT 'Announcement' AS type, title, LEFT(content, 120) AS snippet, date, id
        FROM announcements 
        WHERE title LIKE ? OR content LIKE ?
        ORDER BY date DESC LIMIT 10
    ");
    $stmt->bind_param("ss", $searchTerm, $searchTerm);
    $stmt->execute();
    $results['announcements'] = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    // 5. Matches/Fixtures (basic)
    $stmt = $conn->prepare("
        SELECT 'Match/Fixture' AS type, 
               CONCAT(s1.name, ' vs ', s2.name, ' (', sp.name, ' - ', m.level, ' - ', m.age_category, ')') AS description,
               m.date, m.result, m.id
        FROM matches m
        JOIN teams t1 ON m.team1_id = t1.id
        JOIN teams t2 ON m.team2_id = t2.id
        JOIN schools s1 ON t1.school_id = s1.id
        JOIN schools s2 ON t2.school_id = s2.id
        JOIN sports sp ON m.sport_id = sp.id
        WHERE s1.name LIKE ? OR s2.name LIKE ? OR sp.name LIKE ? OR m.result LIKE ?
        LIMIT 10
    ");
    $stmt->bind_param("ssss", $searchTerm, $searchTerm, $searchTerm, $searchTerm);
    $stmt->execute();
    $results['matches'] = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search - Interschool Sports Rwanda</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <nav><!-- your existing navbar --></nav>

    <h1>Search Results</h1>

    <form action="search.php" method="get" style="max-width:600px; margin:20px auto;">
        <input type="text" name="q" value="<?php echo htmlspecialchars($q); ?>" placeholder="Search schools, teams, players, announcements, matches..." style="width:80%; padding:12px; font-size:16px;">
        <button type="submit" style="padding:12px 20px;">Search</button>
    </form>

    <?php if (strlen($q) < 2): ?>
        <p style="text-align:center;">Enter at least 2 characters to search.</p>
    <?php elseif (empty(array_filter($results))): ?>
        <p style="text-align:center; color:#d00;">No results found for "<strong><?php echo htmlspecialchars($q); ?></strong>".</p>
    <?php else: ?>
        <?php foreach ($results as $section => $items): ?>
            <?php if (!empty($items)): ?>
                <h2><?php echo ucfirst($section); ?> (<?php echo count($items); ?>)</h2>
                <ul style="list-style:none; padding:0;">
                    <?php foreach ($items as $item): ?>
                        <li style="margin:10px 0; padding:10px; border:1px solid #ddd; border-radius:4px;">
                            <strong><?php echo htmlspecialchars($item['type']); ?>:</strong> 
                            <?php
                            if ($section === 'schools') {
                                echo '<a href="schools.php#school-' . $item['id'] . '">' . htmlspecialchars($item['name']) . '</a> - ' . htmlspecialchars($item['location']);
                            } elseif ($section === 'teams') {
                                echo htmlspecialchars($item['name']) . ' (' . htmlspecialchars($item['level']) . ')';
                            } elseif ($section === 'announcements') {
                                echo '<a href="announcements.php#ann-' . $item['id'] . '">' . htmlspecialchars($item['title']) . '</a> - ' . htmlspecialchars($item['snippet']) . '...';
                            } else {
                                echo htmlspecialchars($item['name'] ?? $item['description'] ?? $item['team'] ?? 'View');
                            }
                            ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        <?php endforeach; ?>
    <?php endif; ?>

    <footer><!-- your footer --></footer>
</body>
</html>
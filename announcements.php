<?php
$page_title = "Amatangazo | Announcements";
include 'includes/header.php';
include 'config.php';
?>

<h1>Amatangazo yose / All Announcements</h1>

<input type="text" id="search" placeholder="Shakisha amatangazo..." style="width:100%; padding:10px; margin:15px 0;">

<div id="announcements">
<?php
$result = $conn->query("SELECT title, content, date FROM announcements ORDER BY date DESC");
while ($row = $result->fetch_assoc()) {
    echo "<div class='card'>
        <h3>" . htmlspecialchars($row['title']) . "</h3>
        <p><small>" . date('d/m/Y H:i', strtotime($row['date'])) . "</small></p>
        <p>" . nl2br(htmlspecialchars($row['content'])) . "</p>
    </div>";
}
?>
</div>

<script>
// Simple client-side search for announcements
document.getElementById('search').addEventListener('keyup', function() {
    let filter = this.value.toUpperCase();
    let cards = document.querySelectorAll('.card');
    cards.forEach(card => {
        let text = card.textContent.toUpperCase();
        card.style.display = text.includes(filter) ? '' : 'none';
    });
});
</script>

<?php include 'includes/footer.php'; ?>
<?php
// Protected admin page
require_login();

// Simple CSV import form (e.g., from RSSF Excel/PDF export if they share)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['csv_file'])) {
    $file = $_FILES['csv_file']['tmp_name'];
    if (($handle = fopen($file, "r")) !== FALSE) {
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            // Example: $data[0] = date, $data[1] = match, $data[2] = result
            $stmt = prepare_stmt("INSERT INTO matches (date, result, /* other fields */) VALUES (?, ?, ...)");
            $stmt->bind_param("ss...", $data[0], $data[2] /* etc */);
            $stmt->execute();
        }
        fclose($handle);
        echo "Imported RSSF data successfully!";
    }
}
?>
<form method="post" enctype="multipart/form-data">
    <input type="file" name="csv_file" accept=".csv">
    <button type="submit">Import RSSF Results CSV</button>
</form>
<p>Obtain CSV from RSSF emails, social media screenshots-to-CSV, or MINEDUC portal exports.</p>
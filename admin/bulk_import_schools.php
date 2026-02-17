<?php
include '../config.php';
include '../auth.php';
require_login();

$message = '';
$preview_data = [];
$import_summary = null;
$columns_expected = ['name', 'cell', 'sector', 'district', 'province'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // ── PREVIEW ───────────────────────────────────────────────────────────────
    if (isset($_POST['preview']) && isset($_FILES['csv_file']) && $_FILES['csv_file']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['csv_file']['tmp_name'];
        $ext  = strtolower(pathinfo($_FILES['csv_file']['name'], PATHINFO_EXTENSION));

        if ($ext !== 'csv') {
            $message = '<p style="color:red">File must be .csv</p>';
        } else {
            if (($handle = fopen($file, 'r')) !== false) {
                $header = fgetcsv($handle);
                $header = array_map('trim', array_map('strtolower', $header));

                $missing = array_diff($columns_expected, $header);
                if (!empty($missing)) {
                    $message = '<p style="color:red">Missing columns: ' . implode(', ', $missing) . '</p>';
                } else {
                    $row_count = 0;
                    while (($data = fgetcsv($handle)) !== false && $row_count < 30) { // preview limit
                        if (count($data) === count($header)) {
                            $preview_data[] = array_combine($header, array_map('trim', $data));
                        }
                        $row_count++;
                    }
                    fclose($handle);

                    if (empty($preview_data)) {
                        $message = '<p style="color:orange">No valid data rows found.</p>';
                    } else {
                        $_SESSION['csv_preview_schools'] = $preview_data;
                        $_SESSION['csv_header_schools']  = $header;
                    }
                }
            } else {
                $message = '<p style="color:red">Cannot read uploaded file.</p>';
            }
        }
    }

    // ── CONFIRM & IMPORT with duplicate check ────────────────────────────────
    if (isset($_POST['import']) && !empty($_SESSION['csv_preview_schools'])) {
        $preview_data = $_SESSION['csv_preview_schools'];
        $header       = $_SESSION['csv_header_schools'];

        $success = 0;
        $skipped = 0;
        $errors  = 0;
        $skipped_reasons = [];

        foreach ($preview_data as $idx => $row) {
            // Normalize keys (in case header case varies)
            $row = array_change_key_case($row, CASE_LOWER);

            // Required fields check
            if (empty($row['name']) || empty($row['district']) || empty($row['province'])) {
                $errors++;
                $skipped_reasons[] = "Row " . ($idx+2) . ": missing name/district/province → skipped";
                continue;
            }

            // Duplicate check: name + district + province
            $check_stmt = $conn->prepare("
                SELECT COUNT(*) FROM schools 
                WHERE LOWER(name) = LOWER(?) 
                  AND LOWER(district) = LOWER(?) 
                  AND LOWER(province) = LOWER(?)
            ");
            $check_stmt->bind_param("sss", $row['name'], $row['district'], $row['province']);
            $check_stmt->execute();
            $check_stmt->bind_result($count);
            $check_stmt->fetch();
            $check_stmt->close();

            if ($count > 0) {
                $skipped++;
                $skipped_reasons[] = "Row " . ($idx+2) . ": " . htmlspecialchars($row['name']) . 
                                     " (" . htmlspecialchars($row['district']) . ", " . 
                                     htmlspecialchars($row['province']) . ") already exists → skipped";
                continue;
            }

            // Insert
            $stmt = $conn->prepare("
                INSERT INTO schools (name, cell, sector, district, province) 
                VALUES (?, ?, ?, ?, ?)
            ");
            $stmt->bind_param("sssss",
                $row['name'],
                $row['cell']   ?? '',
                $row['sector'] ?? '',
                $row['district'],
                $row['province']
            );

            if ($stmt->execute()) {
                $success++;
            } else {
                $errors++;
                $skipped_reasons[] = "Row " . ($idx+2) . ": DB error → " . $conn->error;
            }
        }

        $import_summary = "
            <div style='background:#e8f5e9; padding:15px; border:1px solid #4caf50; border-radius:6px;'>
                <strong>Import completed:</strong><br>
                Added: <strong>$success</strong><br>
                Skipped (duplicates or invalid): <strong>" . ($skipped + $errors) . "</strong><br>
                Total rows processed: <strong>" . count($preview_data) . "</strong>
            </div>";

        if (!empty($skipped_reasons)) {
            $import_summary .= "<h4>Skipped rows details:</h4><ul style='color:#d32f2f;'>";
            foreach ($skipped_reasons as $reason) {
                $import_summary .= "<li>$reason</li>";
            }
            $import_summary .= "</ul>";
        }

        // Clean up session
        unset($_SESSION['csv_preview_schools']);
        unset($_SESSION['csv_header_schools']);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bulk Import Schools – with Duplicate Check</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <h2>Bulk Import Schools (CSV) – Skips Duplicates</h2>

    <p>
        Columns (case-insensitive): <strong>name, cell, sector, district, province</strong><br>
        <strong>Duplicate check:</strong> school is skipped if name + district + province already exist.<br>
        First row = headers.
    </p>

    <?php echo $message; ?>
    <?php if ($import_summary) echo $import_summary; ?>

    <form method="post" enctype="multipart/form-data" style="margin:20px 0;">
        <input type="file" name="csv_file" accept=".csv" required>
        <button type="submit" name="preview" style="padding:8px 16px;">Upload & Preview</button>
    </form>

    <?php if (!empty($preview_data)): ?>
        <h3>Preview (showing first 30 rows)</h3>
        <table border="1" cellpadding="6" style="border-collapse:collapse; width:100%; margin:20px 0;">
            <thead style="background:#f0f0f0;">
                <tr>
                    <?php foreach ($columns_expected as $col): ?>
                        <th style="padding:8px;"><?php echo ucfirst($col); ?></th>
                    <?php endforeach; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($preview_data as $row): ?>
                    <tr>
                        <?php foreach ($columns_expected as $col): ?>
                            <td style="padding:6px;"><?php echo htmlspecialchars($row[$col] ?? '—'); ?></td>
                        <?php endforeach; ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <form method="post">
            <button type="submit" name="import" 
                    style="background:#4caf50; color:white; padding:12px 28px; font-size:1.1em; border:none; border-radius:4px; cursor:pointer;">
                Confirm & Import (skip duplicates)
            </button>
            <a href="bulk_import_schools.php" style="margin-left:30px; color:#d32f2f;">Cancel / New file</a>
        </form>
    <?php endif; ?>

    <br><br>
    <a href="dashboard.php">← Back to Dashboard</a>
</body>
</html>
<?php
include 'db.php';

try {
    $sql = "SELECT id, name, email, location, issue_type, message, created_at FROM network_issues ORDER BY created_at DESC";
    $stmt = $conn->query($sql);
    $reports = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Laporan Gangguan - QuickTech</title>
    <style>
        :root {
            --primary-color: #007bff;
            --primary-hover: #0056b3;
            --background-color: #f8f9fa;
            --text-color: #212529;
            --border-color: #dee2e6;
            --card-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif;
            line-height: 1.6;
            color: var(--text-color);
            background-color: var(--background-color);
            padding: 2rem;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }

        h1 {
            color: var(--primary-color);
            font-size: 2rem;
        }

        .back-button {
            background-color: var(--primary-color);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 4px;
            text-decoration: none;
            transition: background-color 0.3s;
        }

        .back-button:hover {
            background-color: var(--primary-hover);
        }

        .reports-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 1.5rem;
        }

        .report-card {
            background: white;
            border-radius: 8px;
            padding: 1.5rem;
            box-shadow: var(--card-shadow);
        }

        .report-id {
            color: #6c757d;
            font-size: 0.875rem;
            margin-bottom: 1rem;
        }

        .report-field {
            margin-bottom: 1rem;
        }

        .report-field-label {
            font-weight: 500;
            color: #495057;
            margin-bottom: 0.25rem;
        }

        .report-field-value {
            color: var(--text-color);
        }

        .report-timestamp {
            color: #6c757d;
            font-size: 0.875rem;
            margin-top: 1rem;
            text-align: right;
        }

        @media (max-width: 768px) {
            body {
                padding: 1rem;
            }

            .header {
                flex-direction: column;
                gap: 1rem;
                text-align: center;
            }

            .reports-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Daftar Laporan Gangguan</h1>
            <a href="report_issue.php" class="back-button">← Kembali ke Form</a>
        </div>

        <div class="reports-grid">
            <?php foreach($reports as $report): ?>
                <div class="report-card">
                    <div class="report-id">ID: <?php echo htmlspecialchars($report['id']); ?></div>
                    
                    <div class="report-field">
                        <div class="report-field-label">Email</div>
                        <div class="report-field-value"><?php echo htmlspecialchars($report['email']); ?></div>
                    </div>

                    <div class="report-field">
                        <div class="report-field-label">Lokasi</div>
                        <div class="report-field-value"><?php echo htmlspecialchars($report['location']); ?></div>
                    </div>

                    <div class="report-field">
                        <div class="report-field-label">Jenis Gangguan</div>
                        <div class="report-field-value"><?php echo htmlspecialchars($report['issue_type']); ?></div>
                    </div>

                    <div class="report-field">
                        <div class="report-field-label">Deskripsi</div>
                        <div class="report-field-value"><?php echo htmlspecialchars($report['message']); ?></div>
                    </div>

                    <div class="report-timestamp">
                        Dilaporkan pada: 
                        <?php 
                            if (!empty($report['created_at'])) {
                                echo date('d/m/Y H:i', strtotime($report['created_at']));
                            } else {
                                echo 'Waktu tidak tercatat';
                            }
                        ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>
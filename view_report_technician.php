<?php
include 'db.php';

// Menangani filter status dari parameter GET
$status_filter = isset($_GET['status']) ? $_GET['status'] : '';

try {
    // Query dasar untuk mengambil laporan gangguan
    $sql = "SELECT id, name, email, location, issue_type, message, status, created_at FROM network_issues";
    
    // Menambahkan kondisi filter jika ada status yang dipilih
    if ($status_filter) {
        $sql .= " WHERE status = :status";
    }
    
    // Menambahkan urutan berdasarkan waktu laporan
    $sql .= " ORDER BY created_at DESC";
    
    // Menyiapkan query untuk dijalankan
    $stmt = $conn->prepare($sql);

    // Mengikat parameter jika ada filter status
    if ($status_filter) {
        $stmt->bindParam(':status', $status_filter, PDO::PARAM_STR);
    }
    
    // Menjalankan query
    $stmt->execute();
    
    // Menyimpan hasil query dalam array asosiatif
    $reports = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    // Menangani error jika terjadi
    echo "Error: " . $e->getMessage();
}

// Menangani pembaruan status
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $status = $_POST['status'];

    try {
        $sql = "UPDATE network_issues SET status = :status WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':status', $status, PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        // Redirect untuk mencegah pengiriman ulang form
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Gangguan - Teknisi QuickTech</title>
    <style>
        /* Root Variables */
        :root {
            --primary-color: #007bff;
            --primary-hover: #0056b3;
            --background-color: #f8f9fa;
            --text-color: #212529;
            --border-color: #dee2e6;
            --card-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        /* Reset CSS */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Body Styling */
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif;
            line-height: 1.6;
            color: var(--text-color);
            background-color: var(--background-color);
            padding: 2rem;
        }

        /* Container */
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        /* Header */
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

        /* Filter Form */
        .filters {
            margin-bottom: 1.5rem;
        }

        .filters form {
            display: flex;
            gap: 0.5rem;
            align-items: center;
        }

        select, button {
            padding: 0.5rem;
            border: 1px solid var(--border-color);
            border-radius: 4px;
            font-size: 1rem;
        }

        button {
            background-color: var(--primary-color);
            color: white;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: var(--primary-hover);
        }

        /* Reports Grid */
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

        .report-actions {
            margin-top: 1rem;
            display: flex;
            gap: 0.5rem;
            align-items: center;
        }

        .report-actions select {
            padding: 0.3rem;
            font-size: 0.875rem;
        }

        .report-actions button {
            padding: 0.3rem 0.5rem;
            font-size: 0.875rem;
            border: none;
            background-color: var(--primary-color);
            color: white;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .report-actions button:hover {
            background-color: var(--primary-hover);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Laporan Gangguan untuk Teknisi</h1>
            <a href="index.php" class="back-button">← Kembali</a>
        </div>

        <!-- Filter Section -->
        <div class="filters">
            <form method="GET" action="">
                <label for="filter-status">Filter Status:</label>
                <select id="filter-status" name="status">
                    <option value="">Semua</option>
                    <option value="pending" <?php echo $status_filter === 'pending' ? 'selected' : ''; ?>>Belum Ditangani</option>
                    <option value="in_progress" <?php echo $status_filter === 'in_progress' ? 'selected' : ''; ?>>Dalam Penanganan</option>
                    <option value="resolved" <?php echo $status_filter === 'resolved' ? 'selected' : ''; ?>>Selesai</option>
                </select>
                <button type="submit">Filter</button>
            </form>
        </div>

        <!-- Reports Grid -->
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

                    <div class="report-field">
                        <div class="report-field-label">Status</div>
                        <div class="report-field-value"><?php echo ucfirst(htmlspecialchars($report['status'])); ?></div>
                    </div>

                    <div class="report-timestamp">
                        <?php echo date('d M Y, H:i', strtotime($report['created_at'])); ?>
                    </div>

                    <div class="report-actions">
                        <form method="POST" action="">
                            <input type="hidden" name="id" value="<?php echo $report['id']; ?>">
                            <select name="status" required>
                                <option value="pending" <?php echo $report['status'] == 'pending' ? 'selected' : ''; ?>>Belum Ditangani</option>
                                <option value="in_progress" <?php echo $report['status'] == 'in_progress' ? 'selected' : ''; ?>>Dalam Penanganan</option>
                                <option value="resolved" <?php echo $report['status'] == 'resolved' ? 'selected' : ''; ?>>Selesai</option>
                            </select>
                            <button type="submit">Update Status</button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>

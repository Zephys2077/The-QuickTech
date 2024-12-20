<?php
include 'db.php';

$errors = [];
$submitted = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate required fields
    $required_fields = ['name', 'email', 'location', 'issue_type', 'message'];
    $form_data = [];
    
    foreach ($required_fields as $field) {
        if (empty($_POST[$field])) {
            $errors[$field] = "Field ini wajib diisi";
        } else {
            $form_data[$field] = htmlspecialchars($_POST[$field]);
        }
    }

    // Validate email format
    if (!empty($_POST['email']) && !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Format email tidak valid";
    }

    // If no errors, proceed with database insertion
    if (empty($errors)) {
        try {
            $sql = "INSERT INTO network_issues (name, email, location, issue_type, message) 
                    VALUES (:name, :email, :location, :issue_type, :message)";
            $stmt = $conn->prepare($sql);
            
            if ($stmt->execute($form_data)) {
                header("Location: view_report.php");
                exit();
            } else {
                $errors['database'] = "Error: Gagal mengirim laporan.";
            }
        } catch (PDOException $e) {
            $errors['database'] = "Error: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporkan Gangguan Jaringan - QuickTech</title>
    <link rel="stylesheet" href="report.css">
    <style>
        /* Previous styles remain the same */
        
        .error-message {
            color: var(--error-color);
            font-size: 0.875rem;
            margin-top: 0.25rem;
            display: block;
        }

        .form-error {
            border-color: var(--error-color) !important;
        }

        .error-summary {
            background-color: #fff3f3;
            border: 1px solid var(--error-color);
            border-radius: var(--border-radius);
            padding: var(--spacing-unit);
            margin-bottom: var(--spacing-unit);
            color: var(--error-color);
        }
    </style>
</head>
<body>
    <div class="report-container">
        <div class="header">
            <h2>Laporkan Gangguan Jaringan</h2>
            <p>Isi form di bawah ini untuk melaporkan gangguan jaringan. Tim dukungan kami akan segera menghubungi Anda.</p>
        </div>

        <?php if (!empty($errors['database'])): ?>
        <div class="error-summary">
            <?php echo $errors['database']; ?>
        </div>
        <?php endif; ?>

        <form method="POST" action="" id="networkIssueForm" novalidate>
            <div class="form-group">
                <label for="name" class="required">Nama Lengkap</label>
                <input 
                    type="text" 
                    name="name" 
                    id="name" 
                    placeholder="Masukkan nama lengkap Anda" 
                    value="<?php echo isset($form_data['name']) ? $form_data['name'] : ''; ?>"
                    class="<?php echo isset($errors['name']) ? 'form-error' : ''; ?>"
                    required
                >
                <?php if (isset($errors['name'])): ?>
                    <span class="error-message"><?php echo $errors['name']; ?></span>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="email" class="required">Alamat Email</label>
                <input 
                    type="email" 
                    name="email" 
                    id="email" 
                    placeholder="Masukkan alamat email Anda" 
                    value="<?php echo isset($form_data['email']) ? $form_data['email'] : ''; ?>"
                    class="<?php echo isset($errors['email']) ? 'form-error' : ''; ?>"
                    required
                >
                <?php if (isset($errors['email'])): ?>
                    <span class="error-message"><?php echo $errors['email']; ?></span>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="location" class="required">Lokasi</label>
                <input 
                    type="text" 
                    name="location" 
                    id="location" 
                    placeholder="Kota atau area tempat gangguan terjadi" 
                    value="<?php echo isset($form_data['location']) ? $form_data['location'] : ''; ?>"
                    class="<?php echo isset($errors['location']) ? 'form-error' : ''; ?>"
                    required
                >
                <?php if (isset($errors['location'])): ?>
                    <span class="error-message"><?php echo $errors['location']; ?></span>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="issue_type" class="required">Jenis Gangguan</label>
                <select 
                    name="issue_type" 
                    id="issue_type" 
                    class="<?php echo isset($errors['issue_type']) ? 'form-error' : ''; ?>"
                    required
                >
                    <option value="" disabled <?php echo !isset($form_data['issue_type']) ? 'selected' : ''; ?>>Pilih jenis gangguan</option>
                    <?php
                    $issue_types = ['Internet Lambat', 'Tidak Ada Koneksi', 'Koneksi Terputus-putus', 'Lainnya'];
                    foreach ($issue_types as $type) {
                        $selected = isset($form_data['issue_type']) && $form_data['issue_type'] === $type ? 'selected' : '';
                        echo "<option value=\"$type\" $selected>$type</option>";
                    }
                    ?>
                </select>
                <?php if (isset($errors['issue_type'])): ?>
                    <span class="error-message"><?php echo $errors['issue_type']; ?></span>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="message" class="required">Deskripsi Gangguan</label>
                <textarea 
                    name="message" 
                    id="message" 
                    rows="5" 
                    placeholder="Berikan rincian tambahan (misal: waktu kejadian, frekuensi gangguan)" 
                    class="<?php echo isset($errors['message']) ? 'form-error' : ''; ?>"
                    required
                ><?php echo isset($form_data['message']) ? $form_data['message'] : ''; ?></textarea>
                <?php if (isset($errors['message'])): ?>
                    <span class="error-message"><?php echo $errors['message']; ?></span>
                <?php endif; ?>
            </div>

            <button type="submit" class="submit-button">Kirim Laporan</button>
        </form>
    </div>

    <script>
        document.getElementById('networkIssueForm').addEventListener('submit', function(event) {
            let hasError = false;
            const requiredFields = ['name', 'email', 'location', 'issue_type', 'message'];
            
            // Remove existing error messages
            document.querySelectorAll('.error-message').forEach(el => el.remove());
            document.querySelectorAll('.form-error').forEach(el => el.classList.remove('form-error'));

            // Validate each required field
            requiredFields.forEach(fieldName => {
                const field = document.getElementById(fieldName);
                if (!field.value.trim()) {
                    hasError = true;
                    field.classList.add('form-error');
                    const errorSpan = document.createElement('span');
                    errorSpan.className = 'error-message';
                    errorSpan.textContent = 'Field ini wajib diisi';
                    field.parentNode.appendChild(errorSpan);
                }
            });

            // Validate email format
            const emailField = document.getElementById('email');
            const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
            if (emailField.value.trim() && !emailRegex.test(emailField.value.trim())) {
                hasError = true;
                emailField.classList.add('form-error');
                const errorSpan = document.createElement('span');
                errorSpan.className = 'error-message';
                errorSpan.textContent = 'Format email tidak valid';
                emailField.parentNode.appendChild(errorSpan);
            }

            if (hasError) {
                event.preventDefault();
            }
        });
    </script>
</body>
</html>
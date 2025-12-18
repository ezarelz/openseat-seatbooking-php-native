<?php
require 'config/db.php';

$results = [];
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $phone = trim($_POST['phone']);

    if ($phone === '') {
        $error = "No. HP wajib diisi.";
    } else {
        $stmt = $pdo->prepare(
            "SELECT r.created_at, s.title
             FROM registrations r
             JOIN services s ON s.id = r.service_id
             WHERE r.phone = ?
             ORDER BY r.created_at DESC"
        );
        $stmt->execute([$phone]);
        $results = $stmt->fetchAll();
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Cek Pendaftaran</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
    body {
        font-family: Arial, sans-serif;
    }
    .card {
        border-radius: 12px;
    }
    .form-control {
        height: 48px;
        font-size: 16px;
    }
    .btn-primary {
        height: 48px;
        font-size: 16px;
        font-weight: bold;
    }
</style>
</head>

<body class="bg-light">

<div class="container py-4">
    <div class="card shadow-sm p-3 p-md-4">

        <h4 class="text-center mb-3">Cek Pendaftaran</h4>

        <form method="post" class="mb-4">
            <div class="mb-3">
                <input
                    name="phone"
                    class="form-control"
                    placeholder="Masukkan No. HP (08xxx)"
                    inputmode="numeric"
                    value="<?= isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : '' ?>"
                    required
                >
            </div>

            <button class="btn btn-primary w-100">
                CEK PENDAFTARAN
            </button>
        </form>

        <?php if ($error): ?>
            <div class="alert alert-danger">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <?php if ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>

            <?php if (count($results) > 0): ?>
                <div class="alert alert-success">
                    Data pendaftaran ditemukan 🙏
                </div>

                <ul class="list-group">
                    <?php foreach ($results as $row): ?>
                        <li class="list-group-item">
                            <strong><?= htmlspecialchars($row['title']) ?></strong><br>
                            <small class="text-muted">
                                Terdaftar pada:
                                <?= date('d M Y H:i', strtotime($row['created_at'])) ?>
                            </small>
                        </li>
                    <?php endforeach; ?>
                </ul>

            <?php else: ?>
                <div class="alert alert-warning">
                    No. HP ini belum terdaftar pada ibadah manapun.
                </div>
            <?php endif; ?>

        <?php endif; ?>

        <div class="text-center mt-4">
            <a href="quota.php" class="btn btn-outline-secondary w-100">
                Kembali ke Daftar Ibadah
            </a>
        </div>

    </div>
</div>

</body>
</html>

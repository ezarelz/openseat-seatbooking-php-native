<?php
require 'config/db.php';

// ambil service_id dari URL
$service_id = isset($_GET['service_id']) ? (int) $_GET['service_id'] : 0;

if ($service_id <= 0) {
    die("Service tidak valid.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $name  = trim($_POST['name']);
    $phone = trim($_POST['phone']);

    try {
        $pdo->beginTransaction();

        // LOCK service yang DIPILIH
        $stmt = $pdo->prepare(
            "SELECT * FROM services WHERE id = ? FOR UPDATE"
        );
        $stmt->execute([$service_id]);
        $service = $stmt->fetch();

        if (!$service) {
            throw new Exception("Service tidak ditemukan.");
        }

        // hitung pendaftar realtime
        $stmt = $pdo->prepare(
            "SELECT COUNT(*) FROM registrations WHERE service_id = ?"
        );
        $stmt->execute([$service_id]);
        $registered = (int) $stmt->fetchColumn();

        if ($registered >= $service['quota']) {
            throw new Exception("Kuota sudah penuh.");
        }

        // hitung jumlah pendaftaran oleh nomor HP ini
        $stmt = $pdo->prepare(
            "SELECT COUNT(*) 
            FROM registrations 
            WHERE service_id = ? AND phone = ?"
        );
        $stmt->execute([$service_id, $phone]);
        $phoneCount = (int) $stmt->fetchColumn();

        if ($phoneCount >= 5) {
            throw new Exception(
                "Nomor HP ini sudah mencapai batas maksimal 5 pendaftaran."
            );
        }

        // INSERT pendaftaran
        $stmt = $pdo->prepare(
            "INSERT INTO registrations (service_id, name, phone)
             VALUES (?, ?, ?)"
        );
        $stmt->execute([$service_id, $name, $phone]);

        $pdo->commit();
        $success = true;

        

    } catch (Exception $e) {
        $pdo->rollBack();
        $error = $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Ibadah</title>
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
        font-size: 16px; /* penting agar tidak auto-zoom di iOS */
    }

    .btn-primary {
        height: 48px;
        font-size: 16px;
        font-weight: bold;
        border-radius: 8px;
    }

    .btn-full {
        width: 100%;
    }

    @media (max-width: 576px) {
        .container {
            padding-left: 12px;
            padding-right: 12px;
        }
    }
</style>



</head>
<body class="bg-light">
  <script src="https://cdn.jsdelivr.net/npm/emailjs-com@3/dist/email.min.js"></script>
<script>
(function(){
    emailjs.init("<?= EMAILJS_PUBLIC_KEY ?>");
})();</script>

<div class="container py-5">
    <div class="card p-4 shadow-sm">

        <h4>Form Pendaftaran</h4>

       <?php 
    
       if (!empty($success) && !empty($email)): ?>
            <div class="alert alert-success">
                Pendaftaran berhasil 🙏
            </div>
             <a href="quota.php" class="btn btn-secondary mt-2">
                Kembali ke Daftar Ibadah
            </a>
<script>
emailjs.send(
    "<?= EMAILJS_SERVICE_ID ?>",
    "<?= EMAILJS_TEMPLATE_ID ?>",
    {
        name: "<?= htmlspecialchars($name, ENT_QUOTES) ?>",
        phone: "<?= htmlspecialchars($phone, ENT_QUOTES) ?>",
        service_title: "<?= htmlspecialchars($service['title'], ENT_QUOTES) ?>",
        email: "<?= htmlspecialchars($email, ENT_QUOTES) ?>"
    }
).then(
    function() {
        console.log("Email sent");
    },
    function(error) {
        console.error("Email failed", error);
    }
);
</script>
<?php endif; ?>

           

       <?php if (!empty($error)): ?>
            <div class="alert alert-danger">
                <?= htmlspecialchars($error) ?>
            </div>
<?php endif; ?>

      <?php if (empty($success)): ?>
<form method="post">

    <input
        name="name"
        class="form-control mb-2"
        placeholder="Nama Lengkap"
        required
    >

    <input
        name="phone"
        class="form-control mb-2"
        placeholder="No. HP (08xxx)"
        inputmode="numeric"
        required
    >

    <input
        name="email"
        type="email"
        class="form-control mb-3"
        placeholder="Email (opsional)"
    >

    <button class="btn btn-success btn-full">
        DAFTAR
    </button>

     <a href="quota.php" class="btn btn-secondary mt-2">
                Kembali ke Daftar Ibadah
            </a>

</form>
<?php endif; ?>

   

    </div>
</div>



</body>
</html>

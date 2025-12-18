<?php
require 'config/db.php';
/* ===============================
   DATA IBADAH (bisa dari DB)
================================ */
$serviceTitle = "IBADAH PERAYAAN NATAL";
$serviceDate  = "24 DES 2025";
$location     = "Gereja Bekasi";
$services = $pdo->query("SELECT * FROM services")->fetchAll();

?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Quota Ibadah</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
    body {
        font-family: Arial, sans-serif;
        background:#fff;
    }
    .header-red {
        background:#ff0000;
        color:#fff;
        text-align:center;
        padding:15px;
        font-weight:bold;
    }
    .sub-title {
        text-align:center;
        margin:10px 0;
        font-weight:bold;
    }
    table {
        border-collapse: collapse;
        width:100%;
    }
    th, td {
        border:1px solid #000;
        padding:8px;
        text-align:center;
        font-size:14px;
    }
    thead th {
        background:#57c978;
        color:#000;
    }
    tbody td {
        background:#e0e0e0;
    }
    .status-habis {
        font-weight:bold;
        color:#000;
    }
    .btn-daftar {
        background:#198754;
        color:#fff;
        padding:4px 10px;
        text-decoration:none;
        border-radius:4px;
    }
    .btn-daftar.disabled {
        background:#999;
        pointer-events:none;
    }

    /* STATUS DAFTAR / HABIS */
.status-btn {
    display: inline-block;
    padding: 6px 14px;
    font-weight: bold;
    text-decoration: none;
    border-radius: 4px;
    color: #fff;
}

.status-open {
    background-color: #198754; /* hijau */
}

.status-closed {
    background-color: #9e9e9e; /* abu-abu */
    color: #000;
}

</style>
</head>

<body>

<div class="container mt-4">

    <!-- HEADER MERAH -->
    <div class="header-red">
        <?= $serviceTitle ?><br>
        <?= $serviceDate ?><br>
        <?= $location ?>
    </div>

    <!-- SUBTITLE -->
<div class="sub-title">
    DAFTAR KETERSEDIAAN REALTIME<br>
    <span id="realtime-clock"></span>
</div>

    <!-- TABLE -->
    <table>
        <thead>
            <tr>
                <th style="text-align:left">ACARA </th>
                <th>QUOTA</th>
                <th>DAFTAR</th>
                <th>SISA</th>
                <th>KLIK<br>UNTUK<br>DAFTAR</th>
            </tr>
        </thead>
        <tbody>

        <?php
        $totalQuota = 0;
        $totalReg   = 0;

       foreach ($services as $s):

    // hitung jumlah pendaftar per service
    $stmt = $pdo->prepare(
        "SELECT COUNT(*) FROM registrations WHERE service_id = ?"
    );
    $stmt->execute([$s['id']]);
    $registered = (int) $stmt->fetchColumn();

    $quota = (int) $s['quota'];
    $remaining = $quota - $registered;
    if ($remaining < 0) $remaining = 0;

    $isOpen = ($remaining > 0);

    $totalQuota += $quota;
    $totalReg   += $registered;
?>
<tr>
    <td style="text-align:left"><?= htmlspecialchars($s['title']) ?></td>
    <td><?= $quota ?></td>
    <td><?= $registered ?></td>
    <td><?= $remaining ?></td>
    <td>
        <?php if ($isOpen): ?>
            <a href="daftar.php?service_id=<?= $s['id'] ?>"
               class="status-btn status-open">
               DAFTAR
            </a>
        <?php else: ?>
            <span class="status-btn status-closed">
               HABIS
            </span>
        <?php endif; ?>
    </td>
</tr>
<?php endforeach; ?>

        <!-- TOTAL -->
        <tr>
            <td style="text-align:left;font-weight:bold">TOTAL</td>
            <td><?= $totalQuota ?></td>
            <td><?= $totalReg ?></td>
            <td><?= max(0, $totalQuota - $totalReg) ?></td>
            <td></td>
        </tr>

        </tbody>
    </table>

    <div class="mt-4 text-center">
    <a href="cek.php" class="btn btn-outline-primary">
        Cek Pendaftaran (via No. HP)
    </a>
</div>


</div>

<!-- 
   JAM REALTIME (SERVER TIME) 
-->

<script>
function updateClock() {
    const now = new Date();

    const hari = now.toLocaleDateString('id-ID', { weekday: 'long' });
    const tanggal = now.toLocaleDateString('id-ID', {
        day: '2-digit',
        month: 'short',
        year: 'numeric'
    });

    const jam = String(now.getHours()).padStart(2, '0');
    const menit = String(now.getMinutes()).padStart(2, '0');
    const detik = String(now.getSeconds()).padStart(2, '0');

    const waktu = `${jam}:${menit}:${detik}`;

    document.getElementById('realtime-clock').innerText =
        `${hari}, ${tanggal} | ${waktu}`;
}

updateClock();
setInterval(updateClock, 1000);

// auto reload tiap 30 detik
setTimeout(() => {
    location.reload();
}, 30000);
</script>


</body>
</html>

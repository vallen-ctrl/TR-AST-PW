
<?php
session_start();

$pdo = new PDO('mysql:host=localhost;dbname=beasiswa_db;charset=utf8mb4', 'root', '', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
]);

if (empty($_SESSION['csrf'])) {
    $_SESSION['csrf'] = bin2hex(random_bytes(16));
}

function e($v) {
    return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8');
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!hash_equals($_SESSION['csrf'], $_POST['csrf'] ?? '')) {
        http_response_code(419);
        exit('Token tidak valid.');
    }

    $id = (int)($_POST['id'] ?? 0);
    $aksi = $_POST['aksi'] ?? '';

    $map = [
        'setujui' => 'diterima',
        'tolak' => 'ditolak'
    ];

    if ($id > 0 && isset($map[$aksi])) {
        $stmt = $pdo->prepare("
            UPDATE memiliki
            SET StatusDiterima = ?
            WHERE id = ? AND StatusDiterima = 'menunggu'
        ");

        $stmt->execute([$map[$aksi], $id]);

        if ($stmt->rowCount() > 0) {
            $_SESSION['flash'] = $aksi === 'setujui'
                ? 'Pengajuan berhasil disetujui.'
                : 'Pengajuan berhasil ditolak.';
        } else {
            $_SESSION['flash'] = 'Pengajuan tidak ditemukan atau sudah diproses.';
        }
    }

    header('Location: ' . strtok($_SERVER['REQUEST_URI'], '?'));
    exit;
}

$status = $_GET['status'] ?? '';

$sql = "
    SELECT
        m.*,
        u.name,
        u.email,
        b.Nama AS beasiswa
    FROM memiliki m
    JOIN users u ON u.id = m.id_user
    JOIN beasiswa b ON b.id = m.id_beasiswa
";

$params = [];

if (in_array($status, ['menunggu', 'diterima', 'ditolak'], true)) {
    $sql .= " WHERE m.StatusDiterima = ?";
    $params[] = $status;
} else {
    $status = '';
}

$sql .= " ORDER BY m.TanggalDaftar DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);

$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

$jumlah = [
    'menunggu' => 0,
    'diterima' => 0,
    'ditolak' => 0
];

$stmtJumlah = $pdo->query("
    SELECT StatusDiterima, COUNT(*) AS total
    FROM memiliki
    GROUP BY StatusDiterima
");

foreach ($stmtJumlah as $r) {
    if (isset($jumlah[$r['StatusDiterima']])) {
        $jumlah[$r['StatusDiterima']] = (int)$r['total'];
    }
}

$warna = [
    'menunggu' => 'st-pending',
    'diterima' => 'st-setuju',
    'ditolak' => 'st-tolak'
];

$labelStatus = [
    'menunggu' => 'Menunggu',
    'diterima' => 'Diterima',
    'ditolak' => 'Ditolak'
];

$filter = [
    '' => 'Semua',
    'menunggu' => 'Menunggu',
    'diterima' => 'Diterima',
    'ditolak' => 'Ditolak'
];

?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Pengajuan Beasiswa — Edu.com</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Baloo+2:wght@400;600;700;800&family=Patrick+Hand&display=swap" rel="stylesheet">
<style>
:root{
  --kuning:#FFE600; --oranye:#F7941D; --lime:#B8FF00; --ungu:#8A2BE2; --biru:#1E88E5;
  --krem:#FFFDF5; --langit:#9ED8FF; --pasir:#FFD27A; --blush:#FF8A80; --merah:#E53935; --tinta:#000;
}
*{box-sizing:border-box;margin:0}
::selection{background:var(--lime);color:#000}
body{
  font-family:'Patrick Hand',cursive;font-size:18px;line-height:1.35;color:#000;min-height:100vh;
  display:flex;flex-direction:column;-webkit-font-smoothing:antialiased;
  background-color:var(--kuning);
  background-image:radial-gradient(circle,var(--oranye) 0 11px,transparent 11.5px);
  background-size:96px 96px;background-position:12px 12px;
}
h1,h2,.display{font-family:'Baloo 2',cursive}
nav{display:flex;align-items:center;justify-content:space-between;gap:16px;flex-wrap:wrap;padding:14px 32px;border-bottom:3px solid #000}
.logo{display:flex;align-items:center;gap:12px;text-decoration:none;color:#000;font-family:'Baloo 2',cursive;font-weight:800;font-size:27px;letter-spacing:-.01em}
.logo svg{width:44px;height:44px;transition:transform .15s}
.logo:hover svg{transform:rotate(-6deg)}
.logo b{color:var(--ungu)}
.menu{display:flex;gap:10px;flex-wrap:wrap}
.menu a{color:#000;text-decoration:underline;text-decoration-thickness:2px;text-underline-offset:4px;border-radius:6px;padding:2px 8px;font-size:19px;transition:background .15s}
.menu a:hover{background:var(--lime);text-decoration:none}
.menu a.aktif{background:var(--lime);text-decoration:none;box-shadow:2px 2px 0 #000;outline:2px solid #000}
.menu a:focus-visible,.btn:focus-visible,.chip:focus-visible,button:focus-visible{outline:4px solid rgba(0,0,0,.35);outline-offset:2px}
main{flex:1;width:100%;max-width:1120px;margin:0 auto;padding:28px 20px 48px}
.badge{display:inline-block;border:2px solid #000;border-radius:6px;padding:1px 12px;font-family:'Baloo 2',cursive;font-weight:800;font-size:12px;text-transform:uppercase;letter-spacing:.05em;box-shadow:2px 2px 0 #000}
.b-ungu{background:var(--ungu);color:#fff}
h1{margin-top:12px;font-size:clamp(30px,5vw,40px);font-weight:800;line-height:1.1}
.sub{margin:8px 0 22px;color:rgba(0,0,0,.75);max-width:62ch}
.kartu{background:var(--krem);border:3px solid #000;border-radius:28px;box-shadow:7px 7px 0 #000}
.flash{margin-bottom:20px;padding:10px 16px;background:var(--lime);border:3px solid #000;border-radius:14px;box-shadow:4px 4px 0 #000}
.btn,button{
  display:inline-flex;align-items:center;justify-content:center;font-family:'Baloo 2',cursive;font-weight:800;font-size:16px;
  cursor:pointer;text-decoration:none;color:#fff;background:var(--biru);
  border:3px solid #000;border-radius:12px;padding:8px 22px;box-shadow:4px 4px 0 #000;
  transition:transform .15s,box-shadow .15s
}
.btn:hover,button:hover{transform:translateY(-2px);box-shadow:5px 6px 0 #000}
.btn:active,button:active{transform:translateY(2px);box-shadow:2px 2px 0 #000}
.btn-ghost{background:#fff;color:#000}
.btn-lime{background:var(--lime);color:#000}
.btn-bahaya{background:#fff;color:var(--merah)}
.btn-kecil,button.btn-kecil{padding:4px 14px;font-size:14px;border-radius:10px;box-shadow:3px 3px 0 #000}
footer{border-top:3px solid #000;background:var(--krem);text-align:center;padding:16px;font-size:15px;color:rgba(0,0,0,.7)}
footer b{font-family:'Baloo 2',cursive;font-weight:800;color:#000}
@media (max-width:640px){nav{padding:12px 16px}main{padding:20px 14px 36px}}

.stat{display:grid;grid-template-columns:repeat(3,1fr);gap:16px;margin-bottom:26px}
.stat div{border:3px solid #000;border-radius:20px;box-shadow:5px 5px 0 #000;padding:14px 18px}
.stat strong{display:block;font-family:'Baloo 2',cursive;font-weight:800;font-size:40px;line-height:1}
.stat span{font-size:18px}
.s1{background:var(--pasir)}.s2{background:var(--lime)}.s3{background:var(--blush)}
.filter{display:flex;flex-wrap:wrap;gap:10px;margin-bottom:20px}
.chip{color:#000;text-decoration:none;background:#fff;border:3px solid #000;border-radius:999px;padding:3px 18px;box-shadow:3px 3px 0 #000;transition:transform .15s,box-shadow .15s,background .15s}
.chip:hover{background:var(--lime);transform:translateY(-2px);box-shadow:4px 5px 0 #000}
.chip.aktif{background:var(--ungu);color:#fff}
.tabel{overflow-x:auto}
table{width:100%;border-collapse:collapse;font-size:17px}
thead th{background:var(--langit);text-align:left;padding:12px 16px;font-family:'Baloo 2',cursive;font-weight:800;font-size:13px;text-transform:uppercase;letter-spacing:.05em;border-bottom:3px solid #000;white-space:nowrap}
tbody td{padding:12px 16px;border-bottom:2px solid rgba(0,0,0,.15);vertical-align:middle}
tbody tr:last-child td{border-bottom:0}
tbody tr:hover td{background:rgba(184,255,0,.18)}
.nama{font-family:'Baloo 2',cursive;font-weight:700;font-size:18px;line-height:1.2}
.surel{font-size:14px;color:rgba(0,0,0,.6)}
.nw{white-space:nowrap}.kanan{text-align:right}
.kosong{padding:44px 16px;text-align:center;color:rgba(0,0,0,.65)}
.st{display:inline-block;border:2px solid #000;border-radius:6px;padding:0 12px;box-shadow:2px 2px 0 #000;font-family:'Baloo 2',cursive;font-weight:800;font-size:12px;text-transform:uppercase;letter-spacing:.05em}
.st-pending{background:var(--pasir)}.st-setuju{background:var(--lime)}.st-tolak{background:var(--blush)}
.aksi{display:flex;justify-content:flex-end;gap:10px}
.selesai{font-size:15px;color:rgba(0,0,0,.5)}
@media (max-width:560px){.stat{gap:10px}.stat strong{font-size:30px}.stat span{font-size:15px}.stat div{padding:10px 12px}}
</style>
</head>
<body>
<nav>
  <a href="dasboard.php" class="logo">
    <svg viewBox="0 0 48 48" fill="none" aria-hidden="true"><rect x="1.5" y="1.5" width="45" height="45" rx="11" fill="#fff" stroke="#000" stroke-width="3"/><path d="M11 20.5 24 14l13 6.5-13 6.5-13-6.5Z" fill="#B8FF00" stroke="#000" stroke-width="2.5" stroke-linejoin="round"/><path d="M15.5 23.5V30c0 2.5 3.8 4.5 8.5 4.5s8.5-2 8.5-4.5v-6.5" stroke="#000" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M37 21v8" stroke="#000" stroke-width="2.5" stroke-linecap="round"/></svg>
    <span>Edu<b>.</b>com</span>
  </a>
  <div class="menu">
    <a href="dasboard.php" class="aktif" aria-current="page">Pengajuan masuk</a>
    <a href="addbeasiswa.php">Tambah beasiswa</a>
  </div>
</nav>

<main>
  <?php if (!empty($_SESSION['flash'])): ?>
    <div class="flash" role="status"><?= e($_SESSION['flash']) ?></div>
    <?php unset($_SESSION['flash']); ?>
  <?php endif; ?>

  <span class="badge b-ungu">Admin</span>
  <h1>Pengajuan masuk</h1>
  <p class="sub">Periksa data pendaftar, lalu setujui atau tolak.</p>

  <div class="stat">
    <div class="s1">
      <strong><?= (int)$jumlah['menunggu'] ?></strong>
      <span>Menunggu</span>
    </div>
    <div class="s2">
      <strong><?= (int)$jumlah['diterima'] ?></strong>
      <span>Diterima</span>
    </div>
    <div class="s3">
      <strong><?= (int)$jumlah['ditolak'] ?></strong>
      <span>Ditolak</span>
    </div>
  </div>

  <div class="filter">
    <?php foreach ($filter as $k => $label): ?>
      <a href="?<?= $k ? 'status=' . e($k) : '' ?>" class="chip<?= $status === $k ? ' aktif' : '' ?>"><?= e($label) ?></a>
    <?php endforeach; ?>
  </div>

  <div class="kartu tabel">
    <table>
      <thead>
        <tr>
          <th>Pendaftar</th>
          <th>Beasiswa</th>
          <th>IPK</th>
          <th>Tanggal</th>
          <th>Status</th>
          <th class="kanan">Tindakan</th>
        </tr>
      </thead>
      <tbody>
      <?php if (!$rows): ?>
        <tr>
          <td colspan="6" class="kosong">Belum ada pengajuan. Pengajuan baru akan muncul di sini.</td>
        </tr>
      <?php endif; ?>

      <?php foreach ($rows as $p): ?>
        <tr>
          <td>
            <div class="nama"><?= e($p['name']) ?></div>
            <div class="surel"><?= e($p['email']) ?></div>
          </td>
          <td><?= e($p['beasiswa']) ?></td>
          <td><?= isset($p['ipk']) && $p['ipk'] !== '' ? number_format((float)$p['ipk'], 2) : '-' ?></td>
          <td class="nw">
            <?= !empty($p['TanggalDaftar']) ? date('d M Y', strtotime($p['TanggalDaftar'])) : '-' ?>
          </td>
          <td>
            <span class="st <?= e($warna[$p['StatusDiterima']] ?? '') ?>">
              <?= e($labelStatus[$p['StatusDiterima']] ?? $p['StatusDiterima']) ?>
            </span>
          </td>
          <td>
            <?php if ($p['StatusDiterima'] === 'menunggu'): ?>
              <div class="aksi">
                <form method="POST">
                  <input type="hidden" name="csrf" value="<?= e($_SESSION['csrf']) ?>">
                  <input type="hidden" name="id" value="<?= (int)$p['id'] ?>">
                  <input type="hidden" name="aksi" value="setujui">
                  <button type="submit" class="btn-kecil btn-lime">Setujui</button>
                </form>

                <form method="POST" onsubmit="return confirm('Tolak pengajuan ini?')">
                  <input type="hidden" name="csrf" value="<?= e($_SESSION['csrf']) ?>">
                  <input type="hidden" name="id" value="<?= (int)$p['id'] ?>">
                  <input type="hidden" name="aksi" value="tolak">
                  <button type="submit" class="btn-kecil btn-bahaya">Tolak</button>
                </form>
              </div>
            <?php else: ?>
              <div class="kanan selesai">Selesai diproses</div>
            <?php endif; ?>
          </td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</main>

<footer>©2026 <b>Edu.com</b> — Beasiswa untuk semua.</footer>
</body>
</html>

<?php
session_start();

try {
    $pdo = new PDO(
        'mysql:host=localhost;dbname=beasiswa_db;charset=utf8mb4',
        'root',
        '',
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );
} catch (PDOException $e) {
    die('Koneksi database gagal. Periksa konfigurasi MySQL dan database.');
}

if (empty($_SESSION['csrf'])) {
    $_SESSION['csrf'] = bin2hex(random_bytes(32));
}

function e($v) {
    return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8');
}

$err = [];
$generalError = '';

$old = [
    'nama' => '',
    'penyelenggara' => '',
    'program' => '',
    'lokasi' => '',
    'semester' => '',
    'syarat' => '',
    'kuota' => '',
    'dibuka' => '',
    'ditutup' => ''
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!hash_equals($_SESSION['csrf'], $_POST['csrf'] ?? '')) {
        http_response_code(419);
        exit('Token tidak valid. Silakan muat ulang halaman.');
    }

    foreach ($old as $k => $v) {
        $old[$k] = trim($_POST[$k] ?? '');
    }

    if ($old['nama'] === '' || mb_strlen($old['nama']) > 150) {
        $err['nama'] = 'Nama beasiswa wajib diisi (maks. 150 karakter).';
    }

    if ($old['penyelenggara'] === '' || mb_strlen($old['penyelenggara']) > 150) {
        $err['penyelenggara'] = 'Penyelenggara wajib diisi (maks. 150 karakter).';
    }

    if ($old['syarat'] === '') {
        $err['syarat'] = 'Syarat utama wajib diisi.';
    }

    if (!ctype_digit($old['kuota']) || (int)$old['kuota'] < 1) {
        $err['kuota'] = 'Kuota harus berupa angka minimal 1.';
    }

    $d1 = DateTime::createFromFormat('!Y-m-d', $old['dibuka']);

    if (!$d1 || $d1->format('Y-m-d') !== $old['dibuka']) {
        $err['dibuka'] = 'Tanggal dibuka tidak valid.';
        $d1 = false;
    }

    $d2 = DateTime::createFromFormat('!Y-m-d', $old['ditutup']);

    if (!$d2 || $d2->format('Y-m-d') !== $old['ditutup']) {
        $err['ditutup'] = 'Tanggal ditutup tidak valid.';
        $d2 = false;
    }

    if ($d1 && $d2 && $d2 < $d1) {
        $err['ditutup'] = 'Tanggal ditutup tidak boleh sebelum tanggal dibuka.';
    }

    if (empty($err)) {
        try {
            $sql = "INSERT INTO beasiswa
                    (nama, penyelenggara, program, lokasi, semester,
                     syarat, kuota, dibuka, ditutup)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

            $stmt = $pdo->prepare($sql);

            $stmt->execute([
                $old['nama'],
                $old['penyelenggara'],
                $old['program'],
                $old['lokasi'],
                $old['semester'],
                $old['syarat'],
                (int)$old['kuota'],
                $old['dibuka'],
                $old['ditutup']
            ]);

            $_SESSION['flash'] = 'Beasiswa berhasil ditambahkan.';
            $_SESSION['csrf'] = bin2hex(random_bytes(32));

            header('Location: ' . strtok($_SERVER['REQUEST_URI'], '?'));
            exit;

        } catch (PDOException $e) {
            $generalError = 'Gagal menyimpan data beasiswa. Periksa kembali struktur tabel database.';
        }
    }
}

function field($name, $label, $old, $err, $type = 'text', $extra = '') {

    $msg = $err[$name] ?? '';

    echo '<div class="f' . ($msg ? ' f-err' : '') . '">';

    echo '<label for="' . e($name) . '">' . e($label) . '</label>';

    if ($type === 'textarea') {
        echo '<textarea id="' . e($name) . '" name="' . e($name) . '" rows="3" ' . $extra . '>' . e($old[$name]) . '</textarea>';
    } else {
        echo '<input id="' . e($name) . '" type="' . e($type) . '" name="' . e($name) . '" value="' . e($old[$name]) . '" ' . $extra . '>';
    }

    if ($msg) {
        echo '<div class="pesan">' . e($msg) . '</div>';
    }

    echo '</div>';
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Tambah Beasiswa — Edu.com</title>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Baloo+2:wght@400;600;700;800&family=Patrick+Hand&display=swap" rel="stylesheet">

<style>
:root {
    --kuning: #FFE600;
    --oranye: #F7941D;
    --lime: #B8FF00;
    --ungu: #8A2BE2;
    --biru: #1E88E5;
    --krem: #FFFDF5;
    --langit: #9ED8FF;
    --pasir: #FFD27A;
    --blush: #FF8A80;
    --merah: #E53935;
    --tinta: #000;
}

* {
    box-sizing: border-box;
    margin: 0;
}

::selection {
    background: var(--lime);
    color: #000;
}

body {
    font-family: 'Patrick Hand', cursive;
    font-size: 18px;
    line-height: 1.35;
    color: #000;
    min-height: 100vh;
    display: flex;
    flex-direction: column;
    -webkit-font-smoothing: antialiased;
    background-color: var(--kuning);
    background-image: radial-gradient(circle, var(--oranye) 0 11px, transparent 11.5px);
    background-size: 96px 96px;
    background-position: 12px 12px;
}

h1, h2, .display {
    font-family: 'Baloo 2', cursive;
}

nav {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    flex-wrap: wrap;
    padding: 14px 32px;
    border-bottom: 3px solid #000;
}

.logo {
    display: flex;
    align-items: center;
    gap: 12px;
    text-decoration: none;
    color: #000;
    font-family: 'Baloo 2', cursive;
    font-weight: 800;
    font-size: 27px;
    letter-spacing: -.01em;
}

.logo svg {
    width: 44px;
    height: 44px;
    transition: transform .15s;
}

.logo:hover svg {
    transform: rotate(-6deg);
}

.logo b {
    color: var(--ungu);
}

.menu {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

.menu a {
    color: #000;
    text-decoration: underline;
    text-decoration-thickness: 2px;
    text-underline-offset: 4px;
    border-radius: 6px;
    padding: 2px 8px;
    font-size: 19px;
    transition: background .15s;
}

.menu a:hover {
    background: var(--lime);
    text-decoration: none;
}

.menu a.aktif {
    background: var(--lime);
    text-decoration: none;
    box-shadow: 2px 2px 0 #000;
    outline: 2px solid #000;
}

.menu a:focus-visible,
.btn:focus-visible,
button:focus-visible {
    outline: 4px solid rgba(0,0,0,.35);
    outline-offset: 2px;
}

main {
    flex: 1;
    width: 100%;
    max-width: 1120px;
    margin: 0 auto;
    padding: 28px 20px 48px;
}

.badge {
    display: inline-block;
    border: 2px solid #000;
    border-radius: 6px;
    padding: 1px 12px;
    font-family: 'Baloo 2', cursive;
    font-weight: 800;
    font-size: 12px;
    text-transform: uppercase;
    letter-spacing: .05em;
    box-shadow: 2px 2px 0 #000;
}

.b-ungu {
    background: var(--ungu);
    color: #fff;
}

h1 {
    margin-top: 12px;
    font-size: clamp(30px, 5vw, 40px);
    font-weight: 800;
    line-height: 1.1;
}

.sub {
    margin: 8px 0 22px;
    color: rgba(0,0,0,.75);
    max-width: 62ch;
}

.kartu {
    background: var(--krem);
    border: 3px solid #000;
    border-radius: 28px;
    box-shadow: 7px 7px 0 #000;
}

.flash {
    margin-bottom: 20px;
    padding: 10px 16px;
    background: var(--lime);
    border: 3px solid #000;
    border-radius: 14px;
    box-shadow: 4px 4px 0 #000;
}

.error-umum {
    margin-bottom: 20px;
    padding: 12px 16px;
    background: #FFE3E0;
    border: 3px solid var(--merah);
    border-radius: 14px;
}

.btn, button {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-family: 'Baloo 2', cursive;
    font-weight: 800;
    font-size: 16px;
    cursor: pointer;
    text-decoration: none;
    color: #fff;
    background: var(--biru);
    border: 3px solid #000;
    border-radius: 12px;
    padding: 8px 22px;
    box-shadow: 4px 4px 0 #000;
    transition: transform .15s, box-shadow .15s;
}

.btn:hover, button:hover {
    transform: translateY(-2px);
    box-shadow: 5px 6px 0 #000;
}

.btn:active, button:active {
    transform: translateY(2px);
    box-shadow: 2px 2px 0 #000;
}

.btn-ghost {
    background: #fff;
    color: #000;
}

.btn-lime {
    background: var(--lime);
    color: #000;
}

.btn-bahaya {
    background: #fff;
    color: var(--merah);
}

.btn-kecil, button.btn-kecil {
    padding: 4px 14px;
    font-size: 14px;
    border-radius: 10px;
    box-shadow: 3px 3px 0 #000;
}

footer {
    border-top: 3px solid #000;
    background: var(--krem);
    text-align: center;
    padding: 16px;
    font-size: 15px;
    color: rgba(0,0,0,.7);
}

footer b {
    font-family: 'Baloo 2', cursive;
    font-weight: 800;
    color: #000;
}

.grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 18px;
}

.penuh {
    grid-column: 1 / -1;
}

.form {
    padding: 28px;
    max-width: 820px;
}

.f label {
    display: block;
    margin-bottom: 6px;
    font-family: 'Baloo 2', cursive;
    font-weight: 700;
    font-size: 14px;
    text-transform: uppercase;
    letter-spacing: .04em;
}

input, textarea {
    width: 100%;
    font-family: 'Patrick Hand', cursive;
    font-size: 19px;
    color: #000;
    background: #fff;
    border: 3px solid #000;
    border-radius: 12px;
    padding: 10px 14px;
}

input::placeholder, textarea::placeholder {
    color: rgba(0,0,0,.4);
}

textarea {
    resize: vertical;
}

input:focus, textarea:focus {
    outline: none;
    box-shadow: 0 0 0 4px var(--lime);
}

.f-err input, .f-err textarea {
    background: #FFE3E0;
    box-shadow: 3px 3px 0 var(--merah);
}

.pesan {
    margin-top: 6px;
    display: inline-block;
    background: var(--blush);
    border: 2px solid #000;
    border-radius: 8px;
    padding: 1px 10px;
    font-size: 15px;
}

.bar {
    display: flex;
    flex-wrap: wrap;
    gap: 14px;
    margin-top: 26px;
    padding-top: 20px;
    border-top: 3px dashed #000;
}

@media (max-width: 640px) {
    nav {
        padding: 12px 16px;
    }

    main {
        padding: 20px 14px 36px;
    }

    .grid {
        grid-template-columns: 1fr;
    }

    .form {
        padding: 20px;
    }

    .penuh {
        grid-column: auto;
    }
}

@media (prefers-reduced-motion: reduce) {
    * {
        transition: none !important;
    }
}
</style>
</head>

<body>

<nav>
    <a href="dasbord.php" class="logo">
        <svg viewBox="0 0 48 48" fill="none" aria-hidden="true">
            <rect x="1.5" y="1.5" width="45" height="45" rx="11" fill="#fff" stroke="#000" stroke-width="3"/>
            <path d="M11 20.5 24 14l13 6.5-13 6.5-13-6.5Z" fill="#B8FF00" stroke="#000" stroke-width="2.5" stroke-linejoin="round"/>
            <path d="M15.5 23.5V30c0 2.5 3.8 4.5 8.5 4.5s8.5-2 8.5-4.5v-6.5" stroke="#000" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M37 21v8" stroke="#000" stroke-width="2.5" stroke-linecap="round"/>
        </svg>
        <span>Edu<b>.</b>com</span>
    </a>

    <div class="menu">
        <a href="dasbord.php">Pengajuan masuk</a>
        <a href="addbeasiswa.php" class="aktif" aria-current="page">Tambah beasiswa</a>
    </div>
</nav>

<main>

    <?php if (!empty($_SESSION['flash'])): ?>
        <div class="flash" role="status">
            <?= e($_SESSION['flash']) ?>
        </div>
        <?php unset($_SESSION['flash']); ?>
    <?php endif; ?>

    <?php if ($generalError): ?>
        <div class="error-umum" role="alert">
            <?= e($generalError) ?>
        </div>
    <?php endif; ?>

    <span class="badge b-ungu">Admin</span>

    <h1>Tambah beasiswa</h1>

    <p class="sub">
        Isi data beasiswa. Status (Dibuka, Ditutup, Belum Dibuka, Kuota Penuh) dihitung otomatis dari tanggal dan kuota.
    </p>

    <form method="POST" class="kartu form" novalidate>

        <input type="hidden" name="csrf" value="<?= e($_SESSION['csrf']) ?>">

        <div class="grid">

            <div class="penuh">
                <?php field('nama', 'Nama beasiswa', $old, $err, 'text', 'maxlength="150" required placeholder="Contoh: Kartu Indonesia Pintar Kuliah (KIP-K)"'); ?>
            </div>

            <div class="penuh">
                <?php field('penyelenggara', 'Penyelenggara / kampus', $old, $err, 'text', 'maxlength="150" required placeholder="Contoh: Sekolah Tinggi Ilmu Ekonomi"'); ?>
            </div>

            <?php field('program', 'Program', $old, $err, 'text', 'placeholder="Contoh: S1 Manajemen"'); ?>

            <?php field('lokasi', 'Lokasi', $old, $err, 'text', 'placeholder="Contoh: Dalam Negeri"'); ?>

            <?php field('semester', 'Semester', $old, $err, 'text', 'placeholder="Contoh: Semester 3"'); ?>

            <?php field('kuota', 'Kuota', $old, $err, 'number', 'min="1" required placeholder="30"'); ?>

            <?php field('dibuka', 'Dibuka', $old, $err, 'date', 'required'); ?>

            <?php field('ditutup', 'Ditutup', $old, $err, 'date', 'required'); ?>

            <div class="penuh">
                <?php field('syarat', 'Syarat utama', $old, $err, 'textarea', 'required placeholder="Contoh: Mahasiswa aktif min. semester 3, IPK minimal 3.00, melampirkan transkrip nilai terbaru."'); ?>
            </div>

        </div>

        <div class="bar">
            <button type="submit">Simpan beasiswa</button>
            <a class="btn btn-ghost" href="dasbord.php">Batal</a>
        </div>

    </form>

</main>

<footer>
    ©2026 <b>Edu.com</b> — Beasiswa untuk semua.
</footer>

</body>
</html>
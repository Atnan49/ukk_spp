
<?php include '../app/controllers/view/templates/header.php'; ?>
<h1>Selamat Datang di Aplikasi UKK SPP</h1>
<?php if (!empty($_SESSION['LOGIN'])): ?>
	<p>Login sebagai <strong><?= htmlspecialchars($_SESSION['username']); ?></strong> (<?= htmlspecialchars($_SESSION['level']); ?>)</p>
<?php else: ?>
	<p>Silakan login untuk mengakses fitur lengkap.</p>
<?php endif; ?>
<?php include '../app/controllers/view/templates/footer.php'; ?>


<?php include '../app/controllers/view/templates/header.php'; ?>
<h1>Profil Pengguna</h1>
<div class="card" style="max-width: 500px; margin: 20px auto;">
	<div class="card-body">
		<h5 class="card-title">Informasi Akun</h5>
		<?php if (!empty($_SESSION['LOGIN'])): ?>
			<ul class="list-group list-group-flush">
				<li class="list-group-item"><strong>Username:</strong> <?= htmlspecialchars($_SESSION['username']); ?></li>
				<li class="list-group-item"><strong>Level:</strong> <?= htmlspecialchars($_SESSION['level']); ?></li>
			</ul>
			<a href="<?= urlTo('/login/logout'); ?>" class="btn btn-danger mt-3">Logout</a>
		<?php else: ?>
			<div class="alert alert-warning">Anda belum login.</div>
			<a href="<?= urlTo('/login'); ?>" class="btn btn-primary mt-3">Login</a>
		<?php endif; ?>
	</div>
</div>
<?php include '../app/controllers/view/templates/footer.php'; ?>
<?php
// Generate a bcrypt hash. Usage:
// - Browser: http://localhost/ukk_spp/generate_hash.php?p=admin
// - CLI: php generate_hash.php admin

$plain = 'admin';
if (php_sapi_name() === 'cli') {
	if (isset($argv[1]) && $argv[1] !== '') {
		$plain = $argv[1];
	}
} else {
	if (isset($_GET['p']) && $_GET['p'] !== '') {
		$plain = $_GET['p'];
	}
}

$hash = password_hash($plain, PASSWORD_DEFAULT);
header('Content-Type: text/plain');
echo $hash;
?>

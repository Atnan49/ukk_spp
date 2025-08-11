<?php
// Simple CLI/HTTP setup script to initialize the ukk_spp database.
// - Creates database if missing
// - Executes databases.sql (schema, triggers, views)
// - Executes insert_admin.sql (seed data except admin)
// - Ensures exactly one admin user exists with a secure hashed password

error_reporting(E_ALL);
ini_set('display_errors', 1);

$dbHost = getenv('DB_HOST') ?: 'localhost';
$dbUser = getenv('DB_USER') ?: 'root';
$dbPass = getenv('DB_PASS') ?: '';
$dbName = getenv('DB_NAME') ?: 'ukk_spp';

function out($msg) {
	if (php_sapi_name() === 'cli') {
		echo $msg . PHP_EOL;
	} else {
		echo nl2br($msg . "\n");
		@ob_flush();
		@flush();
	}
}

function run_sql_file(mysqli $conn, string $filePath): void {
	if (!file_exists($filePath)) {
		out("Skip: $filePath not found");
		return;
	}
	$sql = file_get_contents($filePath);
	if ($sql === false) {
		out("Error: cannot read $filePath");
		return;
	}
	// Very simple splitter that respects DELIMITER changes for triggers.
	$statements = [];
	$delimiter = ';';
	$buffer = '';
	$lines = preg_split('/\R/', $sql);
	foreach ($lines as $line) {
		if (preg_match('/^\s*DELIMITER\s+(\S+)\s*$/i', $line, $m)) {
			// push existing buffer if any using previous delimiter leftover
			if (trim($buffer) !== '') {
				$statements[] = $buffer;
				$buffer = '';
			}
			$delimiter = $m[1];
			continue;
		}
		$buffer .= $line . "\n";
		// If the current buffer ends with the delimiter, split
		if (substr(rtrim($buffer), -strlen($delimiter)) === $delimiter) {
			$stmt = substr(rtrim($buffer), 0, -strlen($delimiter));
			if (trim($stmt) !== '') {
				$statements[] = $stmt;
			}
			$buffer = '';
		}
	}
	if (trim($buffer) !== '') {
		$statements[] = $buffer;
	}

	foreach ($statements as $i => $stmt) {
		if (trim($stmt) === '') continue;
		if (!$conn->query($stmt)) {
			out("SQL Error in $filePath [part " . ($i+1) . "]: " . $conn->error);
			// Continue executing others, but report error
		}
	}
}

out('Connecting to MySQL...');
$root = @new mysqli($dbHost, $dbUser, $dbPass);
if ($root->connect_errno) {
	out('Connection failed: ' . $root->connect_error);
	http_response_code(500);
	exit(1);
}

out("Ensuring database `$dbName` exists...");
if (!$root->query("CREATE DATABASE IF NOT EXISTS `$dbName` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci")) {
	out('Create DB error: ' . $root->error);
}

out('Selecting database...');
if (!$root->select_db($dbName)) {
	out('Select DB error: ' . $root->error);
	exit(1);
}

out('Running databases.sql ...');
run_sql_file($root, __DIR__ . DIRECTORY_SEPARATOR . 'databases.sql');

out('Running insert_admin.sql (seed data excluding admin) ...');
run_sql_file($root, __DIR__ . DIRECTORY_SEPARATOR . 'insert_admin.sql');

// Ensure exactly one admin with a known password exists
$adminUser = 'admin';
$adminPassPlain = isset($_GET['admin_pass']) ? (string)$_GET['admin_pass'] : 'admin';
$adminHash = password_hash($adminPassPlain, PASSWORD_DEFAULT);

out('Ensuring single admin user ...');
$root->query("DELETE FROM users WHERE user_name='" . $root->real_escape_string($adminUser) . "'");
$stmt = $root->prepare("INSERT INTO users (user_name, password, level) VALUES (?,?, 'Admin')");
if ($stmt) {
	$stmt->bind_param('ss', $adminUser, $adminHash);
	if (!$stmt->execute()) {
		out('Insert admin failed: ' . $stmt->error);
	} else {
		out('Admin user created: ' . $adminUser . ' (level=Admin)');
	}
	$stmt->close();
} else {
	out('Prepare insert admin failed: ' . $root->error);
}

out('Setup finished.');
?>

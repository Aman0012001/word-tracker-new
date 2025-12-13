<?php
// Reset test user password
try {
    $pdo = new PDO("mysql:host=127.0.0.1;dbname=word_tracker;charset=utf8mb4", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Update test user password
    $newPassword = password_hash('test123', PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("UPDATE users SET password_hash = ? WHERE email = 'test@example.com'");
    $stmt->execute([$newPassword]);

    echo "✅ Password updated successfully!\n";
    echo "Email: test@example.com\n";
    echo "Password: test123\n";

    // Verify it works
    $stmt = $pdo->prepare("SELECT password_hash FROM users WHERE email = 'test@example.com'");
    $stmt->execute();
    $hash = $stmt->fetchColumn();

    if (password_verify('test123', $hash)) {
        echo "✅ Password verification successful!\n";
    } else {
        echo "❌ Password verification failed!\n";
    }

} catch (PDOException $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
?>
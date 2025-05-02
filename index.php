<?php
$email = $_SERVER['X-MS-CLIENT-PRINCIPAL-NAME'] ?? null;

if (!$email) {
    echo '<a href="/.auth/login/google"><button>Login with Google</button></a>';
    exit;
}

// DB config
$host = 'your-db.mysql.database.azure.com';
$db = 'authapp';
$user = 'dbadmin';
$pass = 'Bristol@123';

try {
    $dsn = "mysql:host=$host;dbname=$db;sslmode=require";
    $pdo = new PDO($dsn, $user, $pass);
    $stmt = $pdo->prepare("SELECT role FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $row = $stmt->fetch();

    if ($row && $row['role'] === 'admin') {
        echo "<h1>Welcome, Admin $email</h1>";
    } elseif ($row) {
        echo "<h1>Hello $email</h1><p>Your role is: {$row['role']}</p>";
    } else {
        echo "<h1>Access Denied</h1><p>Your email is not in the database.</p>";
    }
} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage();
}

echo '<br><a href="/.auth/logout"><button>Logout</button></a>';
?>

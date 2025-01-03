<?php
session_start();
ob_clean();
if (array_key_exists('register', $_POST)) {
    testRegister();
}
if (array_key_exists('login', $_POST)) {
    testLogin();
}
$inputUsername = "";
$inputPassword = "";

function testLogin() {
    ob_clean();
    try {
        $pdo = new PDO("sqlite:./users.db");
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $inputUsername = $_POST['username'];
        $inputPassword = $_POST['password'];
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username=:usern");
        $stmt->execute(['usern' => $inputUsername]); 
        $user = $stmt->fetch();
        if ($user != false) {
            if ($user[2] != $inputPassword) {
                echo'wrong password';
            }
            $_SESSION['loggedin'] = true;
            header("Location: welcome.php");
            exit();
        }
        else{
            echo 'Invalid username or password';
        }
    } catch (PDOException $e) {
        echo"Database connection failed: " . $e->getMessage();
    }
    
}
function testRegister() {
    ob_clean();
    try {
        $pdo = new PDO("sqlite:/afs/elte.hu/user/b/bakosdominik/web/JavaScript/users.db");
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        var_dump($pdo);
        $inputUsername = $_POST['username'];
        $inputPassword = $_POST['password'];
        $sql = "INSERT INTO users (username, password) VALUES (:username, :password)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':username', $inputUsername, PDO::PARAM_STR);
        $stmt->bindParam(':password', $inputPassword, PDO::PARAM_STR);
        $stmt->execute();
        $stmt = $pdo->prepare($sql);
        var_dump($sql);
        $stmt->execute();
    } catch (PDOException $e) {
        echo"Database connection failed: " . $e->getMessage();
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <?php if (!empty($error)) : ?>
        <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>
    <form method="post" action="">
        <fieldset>
            <legend class="qlegend">
                <h1>Character database</h1>
            </legend>
            <p>Please log in as admin 12345</p>
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>
        <br><br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
        <br><br>
        <button name="login" type="submit">Login</button>
    </fieldset>
</body>
</html>
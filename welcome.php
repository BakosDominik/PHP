<?php
session_start();
ob_clean();
$text = "";

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;

}
function Find() {
    try {
        $pdo = new PDO("sqlite:./char.db");
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $char = $_POST['name'];
        $stmt = $pdo->prepare("SELECT * FROM chars WHERE name=:nam");
        $stmt->execute(['nam' => $char]); 
        $user = $stmt->fetch();
        if ($user != false) {
            $text = $user[1]." has the mirage: ".$user[2]." and their eyes are ".$user[3];
            return $text;
        }
        else{
            return 'Character does not exist.';
        }
    } catch (PDOException $e) {
        echo"Database connection failed: " . $e->getMessage();
    }
    
}


if (array_key_exists('sub', $_POST)) {
    $text = Find();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome</title>
</head>
<body>
    <h1>Welcome!</h1>
    <p>This is a character attribute finder for the characters of Copycat. You can find aspects of them.</p>
    <form method="post" action="">
        <fieldset>
            <legend class="qlegend">
                <h1>Character attribute finder</h1>
                Name: <input type="text" name="name"><p><?php echo $text?></p><br><button type="submit" name="sub">Find</button>
            </legend>
    </fieldset>
    <p><a href="logout.php">Logout</a></p>
</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form method="POST">
    <input placeholder="Login" name="LoginInput" type="text">
    <br><br>
    <input name="PasswordInput" placeholder="Hasło" type="password">
    <br><br>
    <button name="LogInBtn">Log In</button>
    <br><br>
    <button name="SignInBtn">Sign In</button>
    </form>

    <?php
$conn = mysqli_connect("localhost", "root", "", "Rejestracja");

if(isset($_POST['SignInBtn'])){
    $NewLogin = $_POST["LoginInput"];
    $NewPassword = $_POST["PasswordInput"];
    $q1 = "INSERT INTO users (Nazwa, Hasło) VALUES ('$NewLogin', '$NewPassword');";
    $result1 = mysqli_query($conn, $q1);
    if ($result1) {
        header("Location: stronaa.php");
    };
}

if (isset($_POST['LogInBtn'])) {
    $Login = $_POST['LoginInput'];
    $Password = $_POST['PasswordInput'];

    $q2 = "SELECT * FROM users WHERE Nazwa='$Login' AND Hasło='$Password'";
    $result2 = mysqli_query($conn, $q2);

    if (mysqli_num_rows($result2) > 0){
        header("Location: stronaa.php");
    };
}
mysqli_close($conn)
?>
</body>
</html>
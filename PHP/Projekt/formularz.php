<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zaloguj sie</title>
</head>
<body>
    <style>
        body {
            margin: 0;
            padding: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: linear-gradient(135deg, #2e2e2e, #121212);
            color: white;
            font-family: system-ui, 'Segoe UI', 'Open Sans', 'Helvetica Neue', sans-serif;
        }
        form {
            background-color: rgba(0, 0, 0, 0.7);
            background: linear-gradient(225deg, #2c2c2cff, #121212);
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.5);
            text-align: center;
            width: 300px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-size: 16px;
        }
        input {
            width: 100%;
            padding-bottom: 10px;
            padding-top: 10px;
            margin: 8px 0;
            border: none;
            border-radius: 4px;
            background-color: #444;
            color: white;
            font-size: 14px;
            margin-bottom: 16px;
        }
        button {
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 4px;
            background-color: #555;
            color: white;
            font-size: 16px;
            cursor: pointer;
            margin-top: 10px;
        }
        button:hover {
            background-color: #777;
        }
        p {
            color: #ffffffff;
            font-size: 14px;
            margin-top: 10px;
        }
    </style>
    <form method="POST">
        <label for="login">Login: </label>
        <input type="text" name="login" required>
        <br>
        <label for="password">Hasło: </label>
        <input type="password" name="password" required>
        <br>
        <button type="submit" name="signin">Zaloguj Się</button>
        <button type="submit" name="signup">Zarejestruj Się</button>
        <p>
            <?php
session_start();

$conn = mysqli_connect("localhost", "root", "", "users");
$conn->set_charset("utf8mb4");

$msg = "";

if (!$conn) {
    die("Błąd połączenia z bazą.");
}

if (isset($_POST['signup'])) {

    $newLogin = $_POST['login'];
    $newPassword = $_POST['password'];

    $q_check = "SELECT * FROM users WHERE login = '$newLogin'"; 
    $result_check = mysqli_query($conn, $q_check);

    if (mysqli_num_rows($result_check) != 0) {
        $msg = "Użytkownik już istnieje.";
    } else {

        $q_main = "INSERT INTO users(login, haslo) VALUES ('$newLogin', '$newPassword')";
        mysqli_query($conn, $q_main);

        $user_id = mysqli_insert_id($conn);

        $check_author = mysqli_query($conn, "SELECT * FROM author WHERE user_ID = $user_id");

        if (mysqli_num_rows($check_author) == 0) {
            $q_author = "INSERT INTO author(user_ID, name, surname) VALUES ($user_id, 'Nowy', 'Autor')";
            mysqli_query($conn, $q_author);
        }

        $msg = "Zarejestrowano. Możesz się zalogować.";
    }
}

if (isset($_POST['signin'])) {

    $login = $_POST['login'];
    $password = $_POST['password'];

    $q_login = "SELECT * FROM users WHERE login = '$login' AND haslo = '$password'";
    $result_login = mysqli_query($conn, $q_login);

    if (mysqli_num_rows($result_login) == 1) {

        $user = mysqli_fetch_assoc($result_login);
        $_SESSION['user_id'] = $user['ID'];


        header("Location: strona_glowna.php");
        exit();

    } else {
        $msg = "Użytkownik nie istnieje lub dane są błędne.";
    }
}


            ?>
        </p>
    </form>
</body>
</html>

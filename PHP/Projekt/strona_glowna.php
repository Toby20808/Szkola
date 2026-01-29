<?php
$conn = new mysqli("localhost","root","","users");
$conn->set_charset("utf8mb4");

$msg = "";
$author_id = 1;

$check = $conn->prepare("SELECT a.ID, u.login FROM author a LEFT JOIN users u ON a.user_ID = u.ID WHERE a.ID = ?");
$check->bind_param("i", $author_id);
$check->execute();
$author_data = $check->get_result()->fetch_assoc();

if (!$author_data) {
    die("<p style='color:red;font-size:20px;'>Autor o ID $author_id nie istnieje w tabeli <b>author</b>!</p>");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    if (!empty($_POST['title']) && !empty($_POST['text'])) {

        $title = $_POST['title'];
        $text  = $_POST['text'];

        $q = $conn->prepare("INSERT INTO posts (author_ID, title, text, created_at) VALUES (?, ?, ?, NOW())");
        $q->bind_param("iss", $author_id, $title, $text);

        if ($q->execute()) {
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        } else {
            $msg = "Błąd podczas dodawania posta: " . $conn->error;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Strona Główna</title>

    <style>
        body {
            margin: 0;
            padding: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            background: linear-gradient(135deg, #2e2e2e, #121212);
            color: white;
            font-family: system-ui, 'Segoe UI', 'Open Sans', 'Helvetica Neue', sans-serif;
            position: relative;
            padding-bottom: 100px;
        }
        .box {
            background: #1e1e1e;
            padding: 25px;
            border-radius: 12px;
            width: 400px;
            box-shadow: 0 0 15px rgba(0,0,0,0.5);
        }
        input, textarea {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            margin-bottom: 15px;
            border-radius: 6px;
            border: none;
            box-sizing: border-box;
        }
        button {
            width: 100%;
            padding: 10px;
            background: #4CAF50;
            border: none;
            color: white;
            font-weight: bold;
            border-radius: 6px;
            cursor: pointer;
        }
        button:hover { background: #45a049; }
        .post {
            width: 450px;
            background: #1c1c1c;
            padding: 15px;
            border-radius: 10px;
            margin-top: 20px;
        }

        .chat-widget {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 1000;
        }
        .chat-button {
            width: 60px;
            height: 60px;
            background: #7289da;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(0,0,0,0.4);
            transition: all 0.3s;
        }
        .chat-button:hover {
            transform: scale(1.1);
        }
        .chat-button i {
            color: white;
            font-size: 28px;
        }
        .chat-panel {
            position: absolute;
            bottom: 70px;
            right: 0;
            background: #2d2d2d;
            border-radius: 12px;
            width: 220px;
            padding: 15px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.6);
            display: none;
            flex-direction: column;
            gap: 12px;
        }
        .chat-panel a {
            display: block;
            padding: 12px;
            background: #3a3a3a;
            color: white;
            text-align: center;
            text-decoration: none;
            border-radius: 8px;
            font-size: 14px;
            transition: background 0.2s;
        }
        .chat-panel a:hover {
            background: #7289da;
        }
        .chat-panel.active {
            display: flex;
        }

        .logout {
            margin-top:20px;
            background:#b23838;
            padding:12px 22px;
            color:white;
            text-decoration:none;
            border-radius:8px;
            font-weight:bold;
            box-shadow:0 4px 10px rgba(0,0,0,0.4);
            transition:0.2s;
        }
        .logout:hover {
            background:#992f2f;
        }
    </style>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

<div class="box">
    <h2>Dodaj nowy post</h2>

    <?php if (!empty($msg)) echo "<p style='color:#ff6b6b;'>$msg</p>"; ?>

    <form method="POST">
        <label>Tytuł:</label>
        <input type="text" name="title" required>

        <label>Treść posta:</label>
        <textarea name="text" rows="5" required></textarea>

        <button type="submit">Opublikuj</button>
    </form>
</div>

<a class="logout" href="formularz.php">Wyloguj</a>

<h2>Ostatnie posty</h2>

<?php
$posts = $conn->query("SELECT posts.*, author.name AS author_name, author.surname AS author_surname FROM posts LEFT JOIN author ON posts.author_ID = author.ID ORDER BY posts.created_at DESC");

while ($p = $posts->fetch_assoc()) {
    $autor = (!empty($p['author_name'])) 
        ? $p['author_name'] . " " . $p['author_surname']
        : "Nieznany autor";

    echo "<div class='post'> <h3>{$p['title']}</h3> <p>{$p['text']}</p> <small>Autor: $autor • {$p['created_at']}</small> </div>";
}
?>

<div class="chat-widget">
    <div class="chat-button" id="chatBtn">
        <i class="fas fa-comment-dots"></i>
    </div>
    <div class="chat-panel" id="chatPanel">
        <a href="hieronimtyton.html">Hieronim Tytoń</a>
        <a href="gorgoniuszwaligora.html">Gorgoniusz Waligóra</a>
        <a href="januszkopara.html">Janusz Kopara</a>
        <a href="RipVanWinkleAI.html">Rip Van Winkl3</a>
    </div>
</div>

<script>
    const btn = document.getElementById('chatBtn');
    const panel = document.getElementById('chatPanel');

    btn.addEventListener('click', () => {
        panel.classList.toggle('active');
    });

    document.addEventListener('click', (e) => {
        if (!btn.contains(e.target) && !panel.contains(e.target)) {
            panel.classList.remove('active');
        }
    });
</script>

</body>
</html>

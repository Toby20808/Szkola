<style>
    table, tr, td { border: black solid 1px; }
</style>

<?php
$conn = new mysqli("localhost", "root", "", "sklep");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'], $_POST['id'])) {
    $id = intval($_POST['id']);
    $nazwa = $_POST['nazwa'];
    $cena = floatval($_POST['cena']);
    $zdjecie = $_POST['zdjecie'];
    $opis = $_POST['opis'];
    $kategoria = $_POST['kategoria'];

    $stmt = $conn->prepare("UPDATE produkty SET nazwa=?, cena=?, zdjecie=?, opis=?, kategoria=? WHERE id=?");
    if ($stmt) {
        $stmt->bind_param("sdsssi", $nazwa, $cena, $zdjecie, $opis, $kategoria, $id);
        $stmt->execute();
        $stmt->close();
        echo "<p>Produkt zaktualizowany!</p>";
    } else {
        echo "<p>Błąd: " . $conn->error . "</p>";
    }
}

$sql = "SELECT * FROM produkty";
$result = $conn->query($sql);
?>

<h2>Edytuj produkty</h2>
<form action="index.php" method="get">
    <button type="submit">⬅ Powrót do dodawania produktów</button>
</form>
<br><br>

<?php
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo '<form method="post">';
        echo '<input type="hidden" name="id" value="' . $row['id'] . '">';
        echo 'Nazwa: <input type="text" name="nazwa" value="' . htmlspecialchars($row['nazwa']) . '" required><br>';
        echo 'Cena: <input type="number" step="0.01" name="cena" value="' . htmlspecialchars($row['cena']) . '" required><br>';
        echo 'Zdjęcie: <input type="text" name="zdjecie" value="' . htmlspecialchars($row['zdjecie']) . '"><br>';
        echo 'Opis: <textarea name="opis">' . htmlspecialchars($row['opis']) . '</textarea><br>';
        echo 'Kategoria: <input type="text" name="kategoria" value="' . htmlspecialchars($row['kategoria']) . '" required><br>';
        echo '<button type="submit" name="update">Zapisz zmiany</button>';
        echo '<hr>';
        echo '</form>';
    }
} else {
    echo "<p>Brak produktów do edycji.</p>";
}

$conn->close();
?>

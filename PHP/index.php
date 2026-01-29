<style>
    table, tr, td { border: black solid 1px; }
</style>

<?php
$conn = new mysqli("localhost", "root", "", "sklep");

if ($_SERVER['REQUEST_METHOD'] === 'POST' &&
    isset($_POST['nazwa'], $_POST['cena'], $_POST['zdjecie'], $_POST['opis'], $_POST['kategoria'])) {

    $nazwa = $_POST['nazwa'];
    $cena = floatval($_POST['cena']);
    $zdjecie = $_POST['zdjecie'];
    $opis = $_POST['opis'];
    $kategoria = $_POST['kategoria'];


    $stmt = $conn->prepare("INSERT INTO produkty (nazwa, cena, zdjecie, opis, kategoria)
                            VALUES (?, ?, ?, ?, ?)");
    if ($stmt) {
        $stmt->bind_param("sdsss", $nazwa, $cena, $zdjecie, $opis, $kategoria);
        $stmt->execute();
        $stmt->close();
        echo "<p>Produkt dodany pomyślnie!</p>";
    } else {
        echo "<p>Błąd: " . $conn->error . "</p>";
    }
}
?>

<h2>Dodaj nowy produkt</h2>
<form method="post">
    <label>Nazwa: <input type="text" name="nazwa" required></label><br><br>
    <label>Cena: <input type="number" step="0.01" name="cena" required></label><br><br>
    <label>Nazwa zdjęcia: <input type="text" name="zdjecie"></label><br><br>
    <label>Opis: <textarea name="opis"></textarea></label><br><br>
    <label>Kategoria: <input type="text" name="kategoria" required></label><br><br>
    <button type="submit">Dodaj produkt</button>
</form>

<br>
<form action="edycja.php" method="get">
    <button type="submit">Przejdź do edycji produktów</button>
</form>

<hr>
<h2>Lista produktów</h2>
<?php
$sql = "SELECT * FROM produkty";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    echo "<table cellpadding='5'><tr>";
    while ($fieldinfo = $result->fetch_field()) {
        echo "<th>" . htmlspecialchars($fieldinfo->name) . "</th>";
    }
    echo "</tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        foreach ($row as $key => $cell) {
            if ($key == 'zdjecie') {
                echo "<td><img src='" . htmlspecialchars($cell) . "' width='100'></td>";
            } else {
                echo "<td>" . htmlspecialchars($cell) . "</td>";
            }
        }
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p>Brak produktów w bazie.</p>";
}
$conn->close();
?>

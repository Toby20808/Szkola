<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>WOLONTARIAT SZKOLNY</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<header>
    <h1>KONKURS - WOLONTARIAT SZKOLNY</h1>
</header>

<main>
    <section class="lewy">
        <h3>Konkursowe nagrody</h3>
        <form method="post">
            <button type="submit">Losuj nowe nagrody</button>
        </form>
        <table>
            <thead>
                <tr>
                    <th>Nr</th>
                    <th>Nazwa</th>
                    <th>Opis</th>
                    <th>Wartość</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $polaczenie = mysqli_connect("localhost", "root", "", "baza");
                if (!$polaczenie) {
                    die("Błąd połączenia z bazą danych: " . mysqli_connect_error());
                }

                mysqli_select_db($polaczenie, "baza");

                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    $zapytanie = "SELECT nazwa, opis, cena FROM nagrody ORDER BY RAND() LIMIT 5";
                } else {
                    $zapytanie = "SELECT nazwa, opis, cena FROM nagrody LIMIT 3";
                }

                $wynik = mysqli_query($polaczenie, $zapytanie);

                if (mysqli_num_rows($wynik) > 0) {
                    $licznik = 1;
                    while ($wiersz = mysqli_fetch_row($wynik)) {
                        echo "<tr>";
                        echo "<td>$licznik</td>";
                        echo "<td>$wiersz[0]</td>";
                        echo "<td>$wiersz[1]</td>";
                        echo "<td>$wiersz[2]</td>";
                        echo "</tr>";
                        $licznik++;
                    }
                }

                mysqli_close($polaczenie);
                ?>
            </tbody>
        </table>
    </section>

    <section class="prawy">
        <img src="puchar.bmp" alt="Puchar dla wolontariusza" width="260" height="260">
        <h4>Polecane linki</h4>
        <ul>
            <li><a href="Kwerenda1">Kwerenda1</a></li>
            <li><a href="Kwerenda2">Kwerenda2</a></li>
            <li><a href="Kwerenda3">Kwerenda3</a></li>
            <li><a href="Kwerenda4">Kwerenda4</a></li>
        </ul>
    </section>
</main>

<footer>
    <p>Numer zdającego: 00000000000</p>
</footer>

</body>
</html>

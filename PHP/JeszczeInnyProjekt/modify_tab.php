<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modify</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<div class="container">
<?php
include("db_connect.php");

$q = "SHOW DATABASES";
$result = mysqli_query($conn, $q);

echo "<form method='POST'>";
echo "<select name='database'>";
while($row = mysqli_fetch_row($result)){
    echo "<option>".$row[0]."</option>";
}
echo "</select>
      <button type='submit' name='show_tables'>Pokaż tabele</button>
      </form>";

if(isset($_POST["show_tables"])){
    $q1 = "SHOW TABLES FROM ".$_POST["database"];
    $result1 = mysqli_query($conn, $q1);
    
    echo "<form method='POST'>";
    echo "<select name='table'>";
    while($row1 = mysqli_fetch_row($result1)){
        echo "<option>".$row1[0]."</option>";
    }
    echo "</select>
          <input type='text' name='database' value='".$_POST["database"]."' hidden />
          <button type='submit' name='show_columns'>Pokaż kolumny</button>
          </form>";
}

if(isset($_POST["show_columns"])){
    $q2 = "SHOW COLUMNS FROM ".$_POST["database"].".".$_POST["table"];
    $result2 = mysqli_query($conn, $q2);
    
    echo "<h3>Modyfikuj tabelę: ".$_POST["table"]."</h3>";
    echo "<form method='post'>";
    echo "<input type='text' name='tableDB' value='".$_POST["database"].".".$_POST["table"]."' hidden/>";
    
    while($row = mysqli_fetch_assoc($result2)){
        echo "<div class='form-group'>
                <label>".$row['Field']." (".$row['Type'].")</label>
                <input type='text' name='".$row['Field']."' class='form-control'/>
              </div>";
    }
    echo "<button type='submit' name='update_data'>Aktualizuj dane</button>
          </form>";
}

if(isset($_POST["update_data"])){
    $table = $_POST["tableDB"];
    $q = "UPDATE $table SET ";
    $first = true;
    
    foreach($_POST as $field => $value){
        if($field != "tableDB" && $field != "update_data"){
            if(!$first) $q .= ", ";
            $q .= "$field = '".mysqli_real_escape_string($conn, $value)."'";
            $first = false;
        }
    }
    
    if(mysqli_query($conn, $q)){
        echo "<div class='success'>Dane zaktualizowane pomyślnie</div>";
    } else {
        echo "<div class='error'>Błąd podczas aktualizacji: ".mysqli_error($conn)."</div>";
    }
}
?>
</div>
</body>
</html>

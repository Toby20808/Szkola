<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Insert</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<div class="container">
<?php
include("db_connect.php");

if (!isset($_POST["tab_name_btn"]) && !isset($_POST["tab_btn"]) && !isset($_POST["insert_into"])) {
    $q = "SHOW DATABASES";
    $result = mysqli_query($conn, $q);

    echo "<form method='POST'>";
    echo "  <select name='database'>";
    while($row = mysqli_fetch_row($result)){
        echo "<option>".$row[0]."</option>";
    }
    echo "  </select>
            <button type='submit' name='tab_name_btn'>Show</button>
          </form>";
}

if(isset($_POST["tab_name_btn"])) {
    $q1 = "SHOW TABLES FROM ".$_POST["database"];
    $result1 = mysqli_query($conn, $q1);

    echo "<form method='POST'>";
    echo "  <select name='table'>";
    while($row1 = mysqli_fetch_row($result1)){
        echo "<option>".$row1[0]."</option>";
    }
    echo "  </select>
            <input type='hidden' name='database' value='".$_POST["database"]."' />
            <button type='submit' name='tab_btn'>Show</button>
          </form>";
}

if(isset($_POST["tab_btn"])) {
    $q2 = "SHOW COLUMNS FROM ".$_POST["database"].".".$_POST["table"];
    $result2 = mysqli_query($conn, $q2);

    echo "<form method='post'>";
    echo "<input type='hidden' name='tableDB' value='".$_POST["database"].".".$_POST["table"]."'/>";

    while($row = mysqli_fetch_row($result2)){
        echo"   <br><br>
                <label>".$row[0]."</label>";
        if(str_contains($row[1], "int")){
            echo "<input type='number' name='".$row[0]."'/>";
        }else if(str_contains($row[1], "var")){
            echo "<input type='text' name='".$row[0]."'/>";
        }
    }
    echo "<button type='submit' name='insert_into'>INSERT INTO</button>
    </form>";
}

if(isset($_POST["insert_into"])) {
    $q = "INSERT INTO ".$_POST["tableDB"]." VALUES (";
    $q2 = "SHOW COLUMNS FROM ".$_POST["tableDB"];
    $result2 = mysqli_query($conn, $q2);
    $values = [];
    while($row = mysqli_fetch_row($result2)){
        foreach($row as $col){
            if(isset($_POST[$col])){
                if(preg_match("/^[0-9]+$/", $_POST[$col])){
                    $values[] = $_POST[$col];
                }else{
                    $values[] = "'".$_POST[$col]."'";
                }
            }
        }
    }
    $q .= implode(", ", $values) . ")";
    if(mysqli_query($conn, $q)){
        echo "<div class='success'>Record inserted successfully.</div>";
    } else {
        echo "<div class='error'>Error inserting record: ".mysqli_error($conn)."</div>";
    }
}
?>
</div>
</body>
</html>

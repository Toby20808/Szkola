<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<div class="container">
    <form method="POST" class="form-control">
        <label>Nazwa: </label>
        <input type="text" name="db_name"/>
        <button type="submit" name="create_db_sql">Utwórz Bazę Danych</button>
    </form>
    <form method="POST" class="form-control">
        <input type="number" name="columns" min="1"/>
        <button type="submit" name="table_add_btn">Dodaj tabele</button>
    </form>
</div>
</body>
</html>
<?php
if(isset($_POST["create_db_sql"])){
    include("db_connect.php");
    $db_name = $_POST["db_name"];

    $q = "CREATE DATABASE IF NOT EXISTS ".$db_name;
    mysqli_query($conn, $q);
}
if(isset($_POST["table_add_btn"])){
    $col = $_POST["columns"];

    echo "<form method='post'>
            <input type='text' name='tab_name'/>    
        ";

    for($i=1; $i<=$col; $i++){
        echo $i.".";
        include("select_section.php");
        echo "<br><br>";
    }
    echo "  <input type='number' name='len' value='$col' hidden/>
            <button name='create_tab_btn'>Utwórz Tabelę</button>
        </form>";
}
if(isset($_POST["create_tab_btn"])){
    $col = $_POST["len"];
    $name = $_POST["tab_name"];
    $q = "CREATE TABLE ".$name."( ";
    for($i=1; $i<=$col; $i++){
        $q .= $_POST["col_name_$i"]." ".$_POST["col_type_$i"];

        if(!empty($_POST["col_len_$i"])){
            $q .= "(".$_POST["col_len_$i"].")";
        }

        if(!empty($_POST["primaryKey_$i"])){
            $q .= " ".$_POST["primaryKey_$i"]." ";
        }

        if(!empty($_POST["notNull_$i"])){
            $q .= " ".$_POST["notNull_$i"];
        }

        if($i == $col){
            $q.=");";
        }else{
            $q.=", ";
        }
    }
    echo $q;
  }
  
?>

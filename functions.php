<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>You shouldn't be able to see this.</title>
</head>
<body>
    <?php

    //Připojení do databáze

    $connectDB = mysqli_connect("localhost","root","","kraje");

    /*-----------------------------------------------------------------------------------*/

    //Načtení potřebných dat do dvourozměrného pole $kraj
    function getKraj($connectDB){
        mysqli_query($connectDB,"SET CHARACTER SET UTF8") or die();
        $kraj = mysqli_query($connectDB,"SELECT id,nazev FROM kraj");
        return $kraj;
    }

    //Načtení id a názvu z tabulky kraj a získání id kraje vybraného ze <select>
    function getIdKraj($connectDB){
        mysqli_query($connectDB,"SET CHARACTER SET UTF8") or die();
        $kraj = mysqli_query($connectDB,"SELECT id,nazev FROM kraj");
        echo "<form action='#' method='post'>";
            echo "<td>";
                echo "<select class='selectWidth' name='selectKraj'>";
                while($zaznam = mysqli_fetch_array($kraj)){
                    echo "<option value=".$zaznam['id'].">";
                        echo $zaznam["id"].". ".$zaznam["nazev"];
                    echo "</option>";
                }
                echo "</select>";
            echo "</td>";
            echo "<td><input class='submitWidth' type='submit' name='submitKraj' value='Vybrat kraj.'>"."</input></td>";

            // Získání hodnoty $idKraj
            if(isset($_POST["submitKraj"])){
                $idKraj = $_POST["selectKraj"]; 
                return $idKraj;   
            }
        echo "</form>";
    }

    function getLastRowISPIndexes($connectDB){ //Získání indexu poslední řádky v ispindexes, použito pro vypsání chyby Není Nic Dostupné pokud uživatel vybere index který není v databázi místo PHP chyby
        mysqli_query($connectDB,"SET CHARACTER SET UTF8") or die();
        $lastRowA = mysqli_query($connectDB,"SELECT id_okres,id FROM ispindexes ORDER by id_okres DESC LIMIT 0,1");
        while($zaznam = mysqli_fetch_array($lastRowA)){
            $lastRow = $zaznam["id_okres"];
            if($lastRow != NULL){
                break;   
            }
        }
        return $lastRow;
    }

    //Načtení id a názvu z tabulky které odpovídají $idKraj (Načtení řádků kde kraj_id = $idKraj) do pole okres a získání id ze <select>
    function getIdOkres($connectDB,$idKraj){
        echo "<form action='#' method='post'>";
            mysqli_query($connectDB,"SET CHARACTER SET UTF8") or die();
            $okres = mysqli_query($connectDB,"SELECT okres.nazev,okres.id FROM okres INNER JOIN kraj ON okres.kraj_id = kraj.id WHERE okres.kraj_id = $idKraj"); //Spojení tabulky kraj a okres pomocí $idKraj
            if($idKraj != NULL){
                echo "<td>";
                    echo "<select class='selectWidth' name='selectOkres'>";
                        while($zaznam = mysqli_fetch_array($okres)){
                            echo "<option value=".$zaznam["id"].">";
                                echo $zaznam["id"].". ".$zaznam["nazev"];
                            echo "</option>";
                        }
                    echo "</select>";
                echo "</td>";
                
                echo "<td><input class='submitWidth' type='submit' name='submitOkres' value='Vybrat okres.'></td>";

            }else{ // Oveření že máme nějakou hodnotu kraj_id, $krajId == NULL při reloadu stránky, prázdný <select> pokud $idKraj == NULL
                ?> 
                <td>
                    <select class='selectWidth'>    
                        <option>0. Prosím vyberte kraj</option>
                    </select>
                </td>

                <!-- Zobrazení vypnutého inputu-->
                <td><input disabled class='submitWidth' type='submit' name='submitOkres' value='Vybrat okres.'></td>
                <?php
            }
                
            // Získání hodnoty $idOkres
            if(isset($_POST["submitOkres"])){
                $idOkres = $_POST["selectOkres"];
                return $idOkres;
            }

        echo "</form>";
    }

    // Výpis dat do tabulky
    function printISPInfo($connectDB,$idOkres){
        mysqli_query($connectDB,"SET CHARACTER SET UTF8") or die();
        $nazev = mysqli_query($connectDB,"SELECT ispdata.name,ispdata.minPrice,ispdata.link FROM ispdata INNER JOIN ispindexes ON ispindexes.id_isp = ispdata.id WHERE ispindexes.id_okres = $idOkres"); //Spojení ispadata a ispindexes pomocí $idokres
        while($zaznam = mysqli_fetch_array($nazev)){
            echo "<tr>";
                echo "<td>".$zaznam["name"]."</td>";
                echo "<td>Od ".$zaznam["minPrice"].",- kč"."</td>";
                echo "<td>"."<a href=".$zaznam['link'].">".$zaznam['name']."</a>"."</td>";
            echo "</tr>";
        }
    }
    
    /*-----------------------------------------------------------------------------------*/
    /*
    $kraj = getKraj($connectDB);

    $idKraj = getIdKraj($kraj);
    //echo "<p>idKraj = ".$idKraj."</p>";

    $idOkres = getIdOkres($connectDB,$idKraj);
    //echo "<p>idOkres = ".$idOkres."</p>";
    
    if($idOkres == NULL){
        echo "<p>variable is NULL</p>";
    }else{
        echo "<p>variable is not NULL</p>";
        printISPInfo($connectDB,$idOkres);
    }*/
    /*-----------------------------------------------------------------------------------*/

    ?>
</body>
</html>
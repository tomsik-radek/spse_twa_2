<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="./css/main.css">
    <title>tomsikr</title>

    <!-- Nastavení faviconu -->
    <link rel="apple-touch-icon" sizes="57x57" href="./favicon/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="./favicon/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="./favicon/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="./favicon/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="./favicon/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="./favicon/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="./favicon/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="./favicon/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="./favicon/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192"  href="./favicon/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="./favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="./favicon/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="./favicon/favicon-16x16.png">
    <link rel="manifest" href="./favicon/manifest.json">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="./favicon/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">
</head> 
<body>
    
    <!-- Připojení souboru functions.php, chová se jako by celý obsah byl nad touto řádkou-->
    <?php
        include('functions.php');
        $lastRow = getLastRowISPIndexes($connectDB);
    ?>

    <div class="main">
        <div class="warningTop">
            <?php
                //Samotné připojení do databáze v souboru functions.php, lepší by bylo použít PHP class ale tohle momentálně funguje jak potřebuju
                //echo "Připojení k databázi bylo úspěšné.";
            
                if (!$connectDB) {
                    echo "Error: Unable to connect to MySQL." . PHP_EOL;
                    echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
                    echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
                    exit;
                }
            ?>
        </div class="warningTop">

        <div class="navbar">
        <ul>
            <li><a href="index.php">Domů</a></li>
            <li><a href="zadani.php">Zadání</a></li>
            <li style="float:right"><a href="https://github.com/tomsik-radek/spse_twa_2">Github</a></li>
        </ul>
        </div class="navbar">

        <div class="content">
            <h2 style="text-align:center">Zde si můžete najít poskytovatele internetu ve vaší lokalitě.</h2>
            <table class="selectTable">
                <tr>
                    <?php $idKraj = getIdKraj($connectDB); ?>
                <tr>
                <tr>
                    <?php $idOkres = getIdOkres($connectDB,$idKraj); ?>
                </tr>
            </table class="selectTable">
            
            <?php
                if($idOkres > $lastRow){
                        echo "<h3 id='errortext1'>Ve vybrané lokalitě není nic dostupné, zkuste vybrat jinou.</h3>";
                }elseif($idOkres == NULL){
                        echo "<h3 id='errortext1'>Není vybrán žádný okres, prosím vyberte.</h3>";         
                }
                else{
            ?>
            <table class="contentTable">
                <tr>
                    <th>Název poskytovatele</th>
                    <th>Cena</th>
                    <th>Odkaz</th>
                </tr>

                <?php    
                    printISPInfo($connectDB,$idOkres);
                }
                ?>
            </table class="contentTable">
        </div class="content">

        <div class="warningBottom">
            <?php
                mysqli_close($connectDB);
                //echo "Spojení s zatabází ukončeno.";
            ?>
        </div class="warningBottom">
    </div class="main">  
</body>
</html>
<!-- 
    Zadanie
    1. Wylistowac wszystkie uslugi z tabeli
    2. Obliczanie BMI wedlug wzoru, wysyla wynik do bazy, bmi_id dostosowuje w zaleznosci od stopnia
    3. Wykazywanie w jakim stopniu jestes (niedowaga etc) i zarzucic jakies porady co mozna zrobic
-->

<!-- Osobny plik z funkcjami, zeby syfu nie bylo w kodzie -->
<?php require './php/functions.php'; ?>

<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FitMetrics</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="./css/main.css">
</head>



<body>
    <!-- HEADER -->
    <div class="header">
        <div class="header-text">
            FitMetrics
        </div>
        <div class="header-underline"></div>
    </div>
    <!-- --- -->

    <!-- USŁUGI -->
    <div class="services">
        <div class="services-title">
            <p class="services-header">Nie czekaj na doskonałość - osiągnij ją z nami</p>
            <p>Nasze usługi w salonie:</p>
            <div class="underline"></div>
        </div>

        <div class="sets">
            <div class="set-1">
                <?php
                $connect = mysqli_connect('localhost', 'root', '', 'bmi');
                $query = "SELECT nazwa, cena FROM `uslugi` ORDER BY nazwa ASC LIMIT 5;";

                $exe = mysqli_query($connect, $query);
                echo "<ol>";

                while ($row = $exe->fetch_assoc()) {
                    echo "
                        <li>" . $row['nazwa'] . " " . $row['cena'] . "zł</li>";
                }

                echo "</ol>"; ?>
            </div>
            <div class="set-2">
                <?php
                $connect = mysqli_connect('localhost', 'root', '', 'bmi');
                $query = "SELECT nazwa, cena FROM `uslugi` ORDER BY nazwa DESC LIMIT 5;";

                $exe = mysqli_query($connect, $query);
                echo "<ul>";

                while ($row = $exe->fetch_assoc()) {
                    echo "
                        <li>" . $row['nazwa'] . " " . $row['cena'] . "zł</li>";
                }
                echo "</ul>"; ?>
            </div>
        </div>
    </div>
    <!-- --- -->

    <!-- KALKULATOR BMI -->
    <div class="calc">
        <div class="calc-header">
            <p class="calc-text">Oblicz swoje BMI</p>
        </div>
        <div class="calc-body">

            <form action="" method="POST">

                <label for="">Podaj swój wzrost:</label>
                <br>
                <input type="text" name="height">
                <br>
                <label for="">Podaj swoją wagę:</label>
                <br>
                <input type="text" name="weight">
                <br>
                <input class="btn" type="submit" value="OBLICZ">
            </form>

            <?php
            $bmi = "";
            // Obliczanie stanu osoby na bazie BMI
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                if (
                    isset($_POST['height'])  && $_POST['height'] > 0 && isset($_POST['weight']) && $_POST['weight'] > 0
                ) {
                    // wynik ma bycc w metrach, nie centymetrach
                    $height = $_POST['height'] / 100;
                    $weight = $_POST['weight'];
                    $date = date("Y-m-d");
                    $bmi = round(calculateBMi($weight, $height), 0);
                    $bmi_id = getBmiId($bmi, $connect);

                    $query = "INSERT INTO wynik (id, bmi_id, data_pomiaru, wynik) VALUES ('', '$bmi_id', '$date', $bmi )";

                    $connect->query($query);

                    if ($height > 0 && $weight > 0) {
                        if ($bmi < 18) {
                            $output = "Masz niedowagę.";
                        } elseif ($bmi >= 19 && $bmi < 25) {
                            $output = "Masz prawidłową masę ciała.";
                        } elseif ($bmi >= 26 && $bmi < 30) {
                            $output = "Masz nadwagę.";
                        } else {
                            $output = "Masz otyłość.";
                        }
                    }
                } else {
                    $output = "Wprowadź poprawne wartości.";
                }
            }

            if (!empty($bmi)) {
                echo "<div class='bmi-result'>";
                echo "Twoje BMI wynosi: " . $bmi . "<br>";
                echo $output;
                echo "</div>";
            }
            ?>
        </div>
    </div>
    <!-- --- -->

    <!-- PORADY -->
    <div class="tips">
        <h1>Porady</h1>
        <?php
        if ($bmi != 0) {
            $tips = getTips($bmi);
        }

        if (!empty($tips)) {
            foreach ($tips as $tip) {
                echo  $tip . "<br>";
            }
        }
        mysqli_close($connect); ?>
    </div>
    <!-- --- -->

</body>

</html>
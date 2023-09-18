<!-- 
    Zadanie
    1. Wylistowac wszystkie uslugi z tabeli
    2. Obliczanie BMI wedlug wzoru, wysyla wynik do bazy
    3. Wykazywanie w jakim stopniu jestes (niedowaga etc) i zarzucic jakies porady co mozna zrobic
-->

<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FitMetrics</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Ubuntu:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="./css/main.css">
</head>

<body>
    <!-- FUNKCJE -->
    <?php


    function calculateBMI($weight, $height)
    {
        return $weight / ($height * $height);
    }


    // Na bazie wyniku BMI wysyla odpowiednie porady
    function getTips($bmi)
    {
        if ($bmi < 18.5) {
            return [
                "Jedz więcej białka i tłuszczów.",
                "Ćwicz regularnie, ale unikaj nadmiernego wysiłku.",
                "Skonsultuj się z dietetykiem, aby ustalić odpowiednią dietę.",
                "Spożywaj więcej kalorii, niż spalasz."
            ];
        } elseif ($bmi >= 18.5 && $bmi < 24.9) {
            return [
                "Kontynuuj zdrową dietę.",
                "Regularnie ćwicz, aby utrzymać dobrą kondycję.",
                "Unikaj stresu i dbaj o regularny sen.",
                "Pij wystarczającą ilość wody każdego dnia."
            ];
        } elseif ($bmi >= 25 && $bmi < 29.9) {
            return [
                "Zmniejsz spożycie tłuszczów i cukrów.",
                "Zwiększ aktywność fizyczną, dodając codzienne ćwiczenia.",
                "Staraj się spożywać mniej kalorii, niż spalasz.",
                "Unikaj jedzenia późno wieczorem."
            ];
        } else {
            return [
                "Skonsultuj się z lekarzem lub dietetykiem w sprawie planu odchudzania.",
                "Unikaj przetworzonej żywności i napojów gazowanych.",
                "Zaangażuj się w regularne ćwiczenia o wysokiej intensywności.",
                "Uważaj na ilość spożywanych kalorii."
            ];
        }
    }

    // na bazie wyniku BMI sprawdza do jakiego bmi_id pasuje w bazie, by moc wyslac dobra kwerende do niej
    function getBmiId($bmi, $connect)
    {
        $query = "SELECT id FROM bmi WHERE $bmi >= wart_min AND $bmi <= wart_max LIMIT 1";
        $result = mysqli_query($connect, $query);

        // Jesli iloscz zwroconych wierszy to 1, to znaczy ze BMI dobrze obliczono
        if (mysqli_num_rows($result) == 1) {
            $row = mysqli_fetch_assoc($result);
            return $row['id'];
        } else {
            return null;
        }
    }
    ?>

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
                echo "
                <ol>";

                while ($row = $exe->fetch_assoc()) {
                    echo "
                        <li>" . $row['nazwa'] . " " . $row['cena'] . "zł</li>";
                }

                echo "
                </ol>
                ";
                ?>
            </div>
            <div class="set-2">

                <?php
                $connect = mysqli_connect('localhost', 'root', '', 'bmi');
                $query = "SELECT nazwa, cena FROM `uslugi` ORDER BY nazwa DESC LIMIT 5;";

                $exe = mysqli_query($connect, $query);
                echo "
                <ul>";

                while ($row = $exe->fetch_assoc()) {
                    echo "
                        <li>" . $row['nazwa'] . " " . $row['cena'] . "zł</li>";
                }

                echo "
                </ul>
                ";

                ?>

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
            $output = "";

            // Obliczanie stanu osoby na bazie BMI
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                if (
                    isset($_POST['height'])  && $_POST['height'] > 0 && isset($_POST['weight']) && $_POST['weight'] > 0
                ) {
                    $height = $_POST['height'] / 100;
                    $weight = $_POST['weight'];
                    $date = date("Y-m-d");
                    $bmi = calculateBMi($weight, $height);
                    $bmi_id = getBmiId($bmi, $connect);


                    $query = "INSERT INTO wynik (id, bmi_id, data_pomiaru, wynik) VALUES ('', '$bmi_id', '$date', $bmi )";


                    $connect->query($query);

                    if ($height > 0 && $weight > 0) {


                        if ($bmi < 18.5) {
                            $output = "Masz niedowagę.";
                        } elseif ($bmi >= 18.5 && $bmi < 24.9) {
                            $output = "Masz prawidłową masę ciała.";
                        } elseif ($bmi >= 25 && $bmi < 29.9) {
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
        $tips = getTips($bmi);

        if (!empty($tips)) {
            foreach ($tips as $tip) {
                echo  $tip . "<br>";
            }
        }
        mysqli_close($connect);
        ?>

    </div>
    <!-- --- -->

</body>

</html>
<!-- 
    1. Wylistowac wszystkie uslugi z tabeli
    2. Obliczanie BMI wedlug wzoru, wysyla wynik do bazy
    3. Wykazywanie w jakim stopniu jestes (niedowaga etc) i zarzucic jakies takei co mozna zrobic
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

    <div class="header">
        <div class="header-text">
            FitMetrics
        </div>
        <div class="header-underline"></div>
    </div>
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
                mysqli_close($connect);
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
                mysqli_close($connect);
                ?>

            </div>


        </div>



    </div>
    <div class="calc">
        <div class="calc-header">
            <p class="calc-text">Oblicz swoje BMI</p>
        </div>
        <div class="calc-body">
            <form action="" method="POST">

                <label for="">Podaj swój wzrost:</label>
                <br>
                <input type="text" name="wzrost">
                <br>
                <label for="">Podaj swoją wagę:</label>
                <br>
                <input type="text" name="waga">
                <br>
                <input class="btn" type="submit" value="OBLICZ">
            </form>
        </div>
    </div>
    <div class="tips">
        <h1>Porady</h1>
    </div>
</body>

</html>
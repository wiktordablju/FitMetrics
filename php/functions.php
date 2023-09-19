<?php
// Obliczanie BMI
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

    // Jesli ilosc zwroconych wierszy to 1, to znaczy ze BMI dobrze obliczono
    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        return $row['id'];
    } else {
        return null;
    }
}

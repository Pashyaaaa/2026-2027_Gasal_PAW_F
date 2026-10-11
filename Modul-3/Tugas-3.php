<?php
$height = array("Andy" => "176", "Barry" => "165", "Charlie" => "170");

$height["David"] = "180";
$height["Ethan"] = "172";
$height["Frank"] = "168";
$height["George"] = "175";
$height["Harry"] = "182";

function print_assoc($label, $arr)
{
    $formatted = [];
    foreach ($arr as $k => $v) {
        $formatted[] = '"' . $k . '"=>"' . $v . '"';
    }
    echo $label . ' = (' . implode(', ', $formatted) . ')<br>';
}

print_assoc('height', $height);
echo "Nilai dengan indeks terakhir: " . end($height) . "<br><br>";


unset($height["Barry"]);

print_assoc('height', $height);
echo "Nilai dengan indeks terakhir setelah dihapus: " . end($height) . "<br><br><hr>";


// === SOAL 3.2 ===
$weight = array("Andy" => "70", "Barry" => "65", "Charlie" => "75");

print_assoc('weight', $weight);

$values = array_values($weight);
echo "Data kedua: " . $values[1];

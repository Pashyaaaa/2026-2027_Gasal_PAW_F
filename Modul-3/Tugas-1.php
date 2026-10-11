<?php
$fruits = array("Avocado", "Blueberry", "Cherry");

array_push($fruits, "Durian", "Elderberry", "Fig", "Grape", "Honeydew");

echo 'fruits = ( "' . implode('", "', $fruits) . '" )<br>';

$max_index = count($fruits) - 1;
echo "Nilai dengan indeks tertinggi: " . $fruits[$max_index] . "<br><br>";


// === SOAL 1.2 ===
$key = array_search("Blueberry", $fruits);
if ($key !== false) {
    unset($fruits[$key]);
}

echo "Data Blueberry dihapus.<br>";
echo 'fruits = ( "' . implode('", "', $fruits) . '" )<br>';

$max_index = max(array_keys($fruits));
echo "Nilai dengan indeks tertinggi: " . $fruits[$max_index];

<?php
$height = array("Andy" => "176", "Barry" => "165", "Charlie" => "170");

$height["David"] = "180";
$height["Ethan"] = "172";
$height["Frank"] = "168";
$height["George"] = "175";
$height["Harry"] = "182";

$formatted = [];
foreach ($height as $k => $v) {
    $formatted[] = '"' . $k . '"=>"' . $v . '"';
}
echo 'height = (' . implode(', ', $formatted) . ')<br><br>';

foreach ($height as $name => $h) {
    echo "$name is $h cm tall.<br>";
}

echo "<hr>";


$weight = array("Andy" => "70", "Barry" => "65", "Charlie" => "75");

$formatted = [];
foreach ($weight as $k => $v) {
    $formatted[] = '"' . $k . '"=>"' . $v . '"';
}
echo 'weight = (' . implode(', ', $formatted) . ')<br><br>';

$keys = array_keys($weight);
$length = count($keys);

for ($i = 0; $i < $length; $i++) {
    $key = $keys[$i];
    echo $key . " is " . $weight[$key] . " kg.<br>";
}

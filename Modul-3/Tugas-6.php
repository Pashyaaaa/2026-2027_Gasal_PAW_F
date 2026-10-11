<?php
// 1. array_push()
$arr1 = array("A");
echo 'Array awal: ("A")<br>';
array_push($arr1, "B");
echo "Hasil array_push: " . implode(" ", $arr1) . "<br><br>";

// 2. array_merge()
$m1 = array("A", "B");
$m2 = array("C");
echo 'Array awal: ("A", "B") digabung dengan ("C")<br>';
$merged = array_merge($m1, $m2);
echo "Hasil array_merge: " . implode(" ", $merged) . "<br><br>";

// 3. array_values()
$assoc = array("x" => 1, "y" => 2);
echo 'Array awal: ("x" => 1, "y" => 2)<br>';
$vals = array_values($assoc);
echo "Hasil array_values: " . implode(" ", $vals) . "<br><br>";

// 4. array_search()
$s_arr = array("A", "B", "C");
echo 'Mencari "B" pada array: ("A", "B", "C")<br>';
$searched = array_search("B", $s_arr);
echo "Hasil array_search: " . $searched . "<br><br>";

// 5. array_filter()
$f_arr = array(0, 1, false, 2, "", 3, "array");
echo 'Array awal: (0, 1, false, 2, "", 3, "array")<br>';
$filtered = array_filter($f_arr);
echo "Hasil array_filter: " . implode(" ", $filtered) . "<br><br>";

// 6. Sorting Indexed Array (sort & rsort)
$num_arr = array(3, 1, 2);
echo "Array awal: (3, 1, 2)<br>";
$s_num = $num_arr;
sort($s_num);
echo "Hasil sort: " . implode(" ", $s_num) . "<br>";
$rs_num = $num_arr;
rsort($rs_num);
echo "Hasil rsort: " . implode(" ", $rs_num) . "<br><br>";

// 7. Sorting Associative Array (asort, ksort, arsort, krsort)
$age = array("Peter" => 35, "Ben" => 37, "Joe" => 43);
echo 'Array awal: ("Peter"=>35, "Ben"=>37, "Joe"=>43)<br>';

// asort (Urut nilai ascending)
$a1 = $age;
asort($a1);
$out = [];
foreach ($a1 as $k => $v) $out[] = "$k=> $v";
echo "Hasil asort: " . implode(", ", $out) . ",<br>";

// ksort (Urut kunci ascending)
$a2 = $age;
ksort($a2);
$out = [];
foreach ($a2 as $k => $v) $out[] = "$k=> $v";
echo "Hasil ksort: " . implode(", ", $out) . ",<br>";

// arsort (Urut nilai descending)
$a3 = $age;
arsort($a3);
$out = [];
foreach ($a3 as $k => $v) $out[] = "$k=> $v";
echo "Hasil arsort: " . implode(", ", $out) . ",<br>";

// krsort (Urut kunci descending)
$a4 = $age;
krsort($a4);
$out = [];
foreach ($a4 as $k => $v) $out[] = "$k=> $v";
echo "Hasil krsort: " . implode(", ", $out) . ",<br>";

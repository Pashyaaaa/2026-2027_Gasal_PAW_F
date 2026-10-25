<?php
echo "<h3>Hasil Input Data Pribadi:</h3>";
foreach ($_POST as $key => $value) {
    echo "<b>" . htmlspecialchars($key) . "</b> : " . htmlspecialchars($value) . "<br>";
}

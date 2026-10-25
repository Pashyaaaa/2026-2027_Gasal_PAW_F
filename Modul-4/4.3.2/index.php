<?php
$errors = array();

if (isset($_POST['submit'])) {
    require 'validate.inc';

    validateName($errors, $_POST, 'surname');
    validateEmail($errors, $_POST, 'email');

    if ($errors) {
        echo '<h3 style="color:red;">Invalid, correct the following errors:</h3>';
        foreach ($errors as $field => $error) {
            echo "<b>$field</b>: $error<br/>";
        }
        include 'form.inc';
    } else {
        echo '<h3 style="color:green;">Form submitted successfully with no errors</h3>';
    }
} else {
    include 'form.inc';
}

<?php

echo "<select name='" . $name . "' id='" . $id . "' class='rx-core-select' onchange='" . $onchange . "'>";
if ($intial_value != '') {
    echo "<option value=". $default_value ." selected>" . $intial_value . "</option>";
}

for ($i = $min; $i <= $max; $i = $i + $step) {
    $selected = '';
    if ($i == 0) {
        $selected = "selected";
    }

    $plus = ($i > 0) ? '+' : '';

    echo "<option value='" . $plus . number_format($i, 2) . "' " . $selected . ">" . $plus .number_format($i, 2) . "</option>";

}
echo "</select>";

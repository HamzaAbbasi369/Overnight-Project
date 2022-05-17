<?php

echo "<select name='" . $name . "' id='" . $id . "' class='rx-core-select' onchange='" . $onchange . "'>";

if (empty($default_value)) {
    echo "<option value=\"0\" selected=\"selected\">" . $initial_value . "</option>";
}

for ($i = $min; $i <= $max; $i = $i + $step) {
    $selected = '';
    if (!empty($default_value) && $i == $default_value) {
        echo "<option value=\"0\" selected=\"selected\">" . $initial_value . "</option>";
    }

    if (floor($i)==$i) {
        echo "<option value='" . number_format($i, 0) . "' " . $selected . ">" . number_format($i, 0) . "</option>";
    } else  {
        echo "<option value='" . number_format($i, 2) . "' " . $selected . ">" . number_format($i, 2) . "</option>";
    }
}
echo "</select>";

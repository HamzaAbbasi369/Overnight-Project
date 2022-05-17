<?php

$color = array("Green", "Gray", "Brown");
$img = array("gg", "gr", "br");
$level = array("25", "50", "75", "max");
$image_file = "";
$tint_level = "";
$tint_value = "";
for ($i = 0; $i < 3; $i++) {
    ?>
    <div style="text-align:center;  width:100%; max-width: 660px;margin-bottom:10px; float:left;">
        <div style="width:100px; height:70px;; float:left;margin-bottom:5px;margin-top:5px;"><h2><?= $color[$i] ?></h2>
        </div>
        <?php
        for ($j = 0; $j < 4; $j++) {
            if ($j == 3) {
                $tint_level = "Max " . $color[$i];
                $tint_value = $color[$i] . " Max Tint";
            } else {
                $tint_level = $level[$j] . "%";
                $tint_value = $color[$i] . " " . $level[$j] . "%";
            }
            $image_file = $img[$i] . $level[$j] . ".jpg";
            draw_selector(
                "tint_". $img[$i] . $level[$j],
                "",
                "",
                "rdo_tint_color",
                "rx.lensColor.changeTintColor('" . $tint_value . "')",
                "",
                $tint_level,
                "width:100px; height:50px;",
                null,
                null,
                $image_file
            );
        }
        ?>
    </div>
    <?php

}

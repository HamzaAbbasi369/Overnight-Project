<?php
/**
 * theme-customisations
 *
 * @author     Eugene Odokiienko <eugene.odokienko@agilefuel.com>
 * @copyright  Copyright (c) 2017 Overnightglasses LLC. (http://www.overnightglasses.com)
 */

?>
<div class="text-input-container">
    <form action="">
        <label class="check-box-container">
            <input class="checkbox" type="checkbox" id="useMySize" name="usemysize">
            <span class="checkbox-custom"></span>
            <label for="useMySize" class="label-usesize"><span>Use my size</span> (mm)</label>
            <a href="" class="size-href open-first">I don’t know my size</a>
            <a href="" class="btn-edit-my-size open-first">Edit</a>
        </label> <!-- end check-box-container -->


        <div class="inputs-container">

            <?php foreach ($group_items as $key => $group_member) {
                $shortcode->setName($group_member);
                $values = $shortcode::extractValues($atts, $shortcode::$group, $group_member);
                if (!empty($values)) {
                    continue;
                }
                try {
                    if ($shortcode::isValid($group_member)) {

                        $shortcode_name  = $shortcode->getName();
                        $shortcode_code  = $shortcode->getSlagName();
                        $shortcode_title = $shortcode->getAttributeTitle();

                        ?>
                        <div class="box<?php echo ($key ? ' shtrich' : '')?>">
                            <label class="user-size-title <?=$shortcode_code?>-title"><?=$shortcode_title?></label>
                            <input type="number" id="user_<?=$shortcode_code?>" class="user-size-input" required
                                   data-name="<?=$shortcode_name?>"
                                   data-code="<?=$shortcode_code?>"
                            >
                        </div>
                        <?php
                    }
                } catch (\Throwable $e) {
                    var_dump($e->getMessage());
                }
            } ?>
        </div>

        <div class="button-container">
            <input type="button" value="Remember my size">
        </div>
    </form> <!-- end form -->
</div> <!-- end text-input-container -->
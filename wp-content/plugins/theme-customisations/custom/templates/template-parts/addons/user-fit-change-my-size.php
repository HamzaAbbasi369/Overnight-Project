<?php
/**
 * theme-customisations
 *
 * @author     Eugene Odokiienko <eugene.odokienko@agilefuel.com>
 * @copyright  Copyright (c) 2017 Overnightglasses LLC. (http://www.overnightglasses.com)
 */

?>

<!-- Modal window for size -->
<div id="ChangeMySizeWindow" class="modal-window">
    <div class="wrapper-modal">

        <div class='reveal-modal size-modal' id='change-my-size-modal' data-reveal>
            <a class='close'>x</a>
            <div class="text-input-container">
                <form action="">
                    <div class="inputs-container">
                        <div class="box">
                            <label class="width-title"></label>
                            <input type="number" class="changeSizeLens" required>
                        </div>

                        <div class="box shtrich">
                            <label class="bridge-width"></label>
                            <input type="number" class="changeSizeBridge" required>
                        </div>

                        <div class="box shtrich">
                            <label class="temple-length"></label>
                            <input type="number" class="changeSizeTemple" required>
                        </div>
                    </div>

                    <div class="button-container">
                        <input type="button" value="Remember my size">
                    </div>
                </form> <!-- end form -->
            </div> <!-- end text-input-container -->
        </div> <!-- end first modal -->
    </div>
</div>

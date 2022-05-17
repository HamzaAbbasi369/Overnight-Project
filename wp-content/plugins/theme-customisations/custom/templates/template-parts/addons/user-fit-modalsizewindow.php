<?php
/**
 * theme-customisations
 *
 * @author     Eugene Odokiienko <eugene.odokienko@agilefuel.com>
 * @copyright  Copyright (c) 2017 Overnightglasses LLC. (http://www.overnightglasses.com)
 */
?>
<!-- Modal window for size -->
<!--<div id="modalSizeWindow" class="modal-window">-->
<!--    <div class="wrapper-modal">-->

        <div class='reveal-modal' id='modalSize' data-reveal>
            <h3 class="modal-title">How to Find The Size of Your Frame</h3>
            <a class='close'>x</a>

            <div class="tabs-block">
                <ul class="tabs" data-deep-link="true" data-update-history="true" data-deep-link-smudge="true" data-deep-link-smudge="500" data-tabs id="deeplinked-tabs">
                    <li class="tabs-title is-active"><a href="#panel1d" aria-selected="true">I <span>have</span> current frame that fits me</a></li>
<!--                    <li class="tabs-title"><a href="#panel2d">I <span>don’t have</span> current frame that fits me</a></li>-->
                </ul>

                <div class="tabs-content" data-tabs-content="deeplinked-tabs">

                    <div class="tabs-panel is-active" id="panel1d">
                        <p class="check-size title"><span>Check size on your frame</span></p>
                        <p class="check-size">Most frames have size displayed on the inside of the frame arm</p>
                        <div class="picture-block-frame"></div>

                        <p class="check-size title"><span>Or use a ruler</span></p>
                        <p class="check-size">
                            Use a ruler to measure your existing frame as shown below.
                            Frame sizes are measured in millimeters, so you need to use a
                            ruler that is marked in centimeters and millimeters. If you don't
                            have millimeter ruler, you can  <span><a href="https://printable-ruler.net/" target="printable-ruler">click here</a></span> to print it,
                            or take measurements in inches. Note that measurements in inches
                            need to be taken with the precision of 1/16 (one half of 1/8 if
                            your ruler does not have more precise markings)
                        </p>
                        <div class="picture-block-lenses"></div>

                        <p class="got-here-size">I got it! Here is My Size</p>

                        <div class="tabs-units">
                            <ul class="tabs second-ul-tabs" data-deep-link="true" data-update-history="true" data-deep-link-smudge="true" data-deep-link-smudge="500" data-tabs id="deeplinked-tabs">
                                <li class="tabs-title is-active tabs-second tab-millimiters"><a href="#panel3d" aria-selected="true">mm</a></li>
                                <li class="tabs-title tabs-second tab-inches"><a href="#panel4d">inches</a></li>
                            </ul>

                            <div class="tabs-content second-tabs-content" data-tabs-content="deeplinked-tabs">
                                <form action="">
                                    <div class="tabs-panel is-active panel-ci" id="panel3d">
                                        <div class="inputs-container">
                                            <div class="box">
                                                <label class="width-title"></label>
                                                <input type="number" class="userSizeLens" required>
                                            </div>

                                            <div class="box shtrich">
                                                <label class="bridge-width"></label>
                                                <input type="number" class="userSizeBridge" required>
                                            </div>

                                            <div class="box shtrich">
                                                <label class="temple-length"></label>
                                                <input type="number" class="userSizeTemple" required>
                                            </div>
                                        </div>
                                        <div class="button-container">
                                            <input type="button" value="DONE">
                                        </div>
                                    </div>
                                    <div class="tabs-panel is-active panel-inches" id="panel4d">
                                        <div class="inputs-container">
                                            <div class="box inches-box">
                                                <label class="width-title"></label>
                                                <div class="inches-inputs">
                                                    <input type="number" class="userSizeLensInches" placeholder="_ _" required>
                                                    <input type="number" class="userSizeLensOne16Inches shares" placeholder="_ _" required>
                                                    <label for="shares" class="label-shares">/16</label>
                                                </div>
                                            </div>

                                            <div class="box shtrich inches-box">
                                                <label class="bridge-width"></label>
                                                <div class="inches-inputs">
                                                    <input type="number" class="userSizeBridgeInches" placeholder="_ _" required>
                                                    <input type="number" class="userSizeBridgeOne16Inches shares" placeholder="_ _" required>
                                                    <label for="shares" class="label-shares">/16</label>
                                                </div>
                                            </div>

                                            <div class="box shtrich inches-box">
                                                <label class="temple-length"></label>
                                                <div class="inches-inputs">
                                                    <input type="number" class="userSizeTempleInches" placeholder="_ _" required>
                                                    <input type="number" class="userSizeTempleOne16Inches shares" placeholder="_ _" required>
                                                    <label for="shares" class="label-shares">/16</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="button-container">
                                            <input type="button" value="DONE">
                                        </div>
                                    </div>
                                </form>
                            </div>

                        </div>
                    </div>

<!--                    <div class="tabs-panel" id="panel2d">-->
<!--                        <p class="check-size title"><span>Check size on your frame</span></p>-->
<!--                        <p class="check-size">The most importan size you need to know to pick properly fitting glasses is Lens Width.-->
<!--                            Please watch the video below to learn how to easly measure your lens width at home. </p>-->
<!--                        <div class="video">-->
<!--                            <iframe width="530" height="315" src="https://www.youtube.com/embed/xApldi2rbk8" frameborder="0"-->
<!--                                    allowfullscreen></iframe>-->
<!--                        </div>-->
<!---->
<!---->
<!--                        <p class="got-here-size">I got it! Here is My Size</p>-->
<!---->
<!--                        <div class="tabs-units">-->
<!--                            <ul class="tabs second-ul-tabs" data-deep-link="true" data-update-history="true" data-deep-link-smudge="true" data-deep-link-smudge="500" data-tabs id="deeplinked-tabs">-->
<!--                                <li class="tabs-title is-active tabs-second tab-millimiters"><a href="#panel5d" aria-selected="true">mm</a></li>-->
<!--                                <li class="tabs-title tabs-second tab-inches"><a href="#panel6d">inches</a></li>-->
<!--                            </ul>-->
<!---->
<!--                            <div class="tabs-content second-tabs-content" data-tabs-content="deeplinked-tabs">-->
<!--                                <div class="tabs-panel is-active" id="panel5d">-->
<!--                                    <form action="">-->
<!--                                        <div class="inputs-container">-->
<!--                                            <div class="box">-->
<!--                                                <label class="width-title"></label>-->
<!--                                                <input type="number" required>-->
<!--                                            </div>-->
<!---->
<!--                                            <div class="box shtrich">-->
<!--                                                <label class="bridge-width"></label>-->
<!--                                                <input type="number" required>-->
<!--                                            </div>-->
<!---->
<!--                                            <div class="box shtrich">-->
<!--                                                <label class="temple-length"></label>-->
<!--                                                <input type="number" required>-->
<!--                                            </div>-->
<!--                                        </div>-->
<!--                                        <div class="button-container">-->
<!--                                            <input type="submit" value="DONE">-->
<!--                                        </div>-->
<!--                                    </form>-->
<!--                                </div>-->
<!--                                <div class="tabs-panel is-active panel-inches" id="panel6d">-->
<!--                                    <form action="">-->
<!--                                        <div class="inputs-container">-->
<!--                                            <div class="box inches-box">-->
<!--                                                <label class="width-title"></label>-->
<!--                                                <div class="inches-inputs">-->
<!--                                                    <input type="number" placeholder="_ _" required>-->
<!--                                                    <input type="number" class="shares" placeholder="_ _" required>-->
<!--                                                    <label for="shares" class="label-shares">/16</label>-->
<!--                                                </div>-->
<!--                                            </div>-->
<!---->
<!--                                            <div class="box shtrich inches-box">-->
<!--                                                <label class="bridge-width"></label>-->
<!--                                                <div class="inches-inputs">-->
<!--                                                    <input type="number" placeholder="_ _" required>-->
<!--                                                    <input type="number" class="shares" placeholder="_ _" required>-->
<!--                                                    <label for="shares" class="label-shares">/16</label>-->
<!--                                                </div>-->
<!--                                            </div>-->
<!---->
<!--                                            <div class="box shtrich inches-box">-->
<!--                                                <label class="temple-length"></label>-->
<!--                                                <div class="inches-inputs">-->
<!--                                                    <input type="number" placeholder="_ _" required>-->
<!--                                                    <input type="number" class="shares" placeholder="_ _" required>-->
<!--                                                    <label for="shares" class="label-shares">/16</label>-->
<!--                                                </div>-->
<!--                                            </div>-->
<!--                                        </div>-->
<!--                                        <div class="button-container">-->
<!--                                            <input type="submit" value="DONE">-->
<!--                                        </div>-->
<!--                                    </form>-->
<!--                                </div>-->
<!--                            </div>-->
<!---->
<!--                        </div>-->
<!--                    </div>-->

                </div>
            </div>
        </div>
<!--    </div>-->
<!--</div>-->

<?php
//if (is_user_logged_in()) {
//    echo '<h2>You are logged in</h2>';
//    return;
//};
    echo '
    <h2 id="sub-by-email">Subscribe by email</h2>
    <form action="" method="post" id="sub_form_subscriber" style="width: 100%;">
        <div class="large-12 columns text-left">
            <label for="form-name sub_name">
                Name
                <input type="text" id="form-name" required>
            </label>
        </div>
        <div class="large-12 columns text-left">
            <label for="form-email sub_email">
                Email
                <input type="email" id="form-email" required>
            </label>
            <div class="form-error-message">
            </div>
        </div>
        <div class="form--button-wrap text-left">
            <button class="button form--submit" type="submit" value="Submit" id="sub_submit">Subscribe</button>
        </div>
    </form>
';

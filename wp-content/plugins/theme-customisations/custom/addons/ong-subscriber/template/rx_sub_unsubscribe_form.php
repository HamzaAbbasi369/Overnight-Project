<?php

if (!isset($_GET['email'])) {
    echo '<br><br><h2 style="color: #aaaaaa;text-align: center;">You are not subscribed!</h2>';
} else {
    $email = !empty($_GET['email']) ? $_GET['email'] : '';
    $nonce = !empty($_GET['nonce']) ? $_GET['nonce'] : '';

    $email = htmlspecialchars($email);
    $nonce = htmlspecialchars($nonce);

    global $wpdb;
    $name = $wpdb->get_var($wpdb->prepare("SELECT `name` FROM rx_subscribe WHERE email = %s", $email));

    echo '
        <div class="row">
          <div class="small-2 large-4 columns">&nbsp</div>
          <div class="small-4 large-4 columns">
        <div id="unsub">
        <br>
        <h3 style="color: #aaaaaa;">Unsubscribe. '.$name.' Your Email: '.$email.'</h3>
        <br>
               <form action="" method="post" id="unsub_form_subscriber">
        
                        <textarea style="font-size: 20px;" placeholder="Why do you want to unsubscribe from our mailing list?" name="unsub_text" id="unsub_text" rows="3" cols="45" maxlength="1000"></textarea>
         
                    <input type="hidden" name="data" id="data" value="'.$nonce.'">
                    <input type="hidden" name="email" id="email" value="'.$email.'">
                    <br>
                    <button type="button" style="float:right;" class="btn btn-warning hollow button free--button watch--video" id="unsubsubmit" name="unsubsubmit">Unsubscribe</button>
                    <span id="loader" style="display: none;">
                    <img src='. plugins_url('..//img/loader.gif', __FILE__) .' alt=""> 
                </form>
                </span></p><div style="color: #aaaaaa;" id="unsub_res"></div>
        </div>
        </div>
          <div class="small-6 large-4 columns">&nbsp</div>
        </div>
        ';
}

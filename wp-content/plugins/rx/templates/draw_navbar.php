<!-- button footer-->
<div class="clearfix large-12 medium-12 small-12 clear-padding margin-top" id=<?=$id?> style="display:none">

    <div class="medium-5 small-5 large-5 columns clear-padding">
        <button type="button" class="btn btn-warning footer first_button <?php if($back_label==''){echo 'invisible';}?>"
                onclick=<?=$back_action?> ><?=$back_label?>
        </button>
    </div>

    <div class="large-2 medium-2 small-2 columns clear-padding">&nbsp;</div>

    <div class="medium-5 small-5 large-5 columns clear-padding">
        <button type="button" class="btn btn-warning footer last"
                onclick=<?=$next_action?>><?=$next_label?>
        </button>
    </div>

</div>
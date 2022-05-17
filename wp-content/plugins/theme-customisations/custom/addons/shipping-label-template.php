<?php
get_header();
?>

<div class='row'>
    <div class='medium-3 small-12 columns'>&nbsp;</div>

    <div class='medium-6 small-12 columns'><br><br><br><br><br><br>
        <?php
        if (isset($label_data['_carrier'])) {
            if ($label_data['_carrier']=='UPS') {
                ?>
                    <div class='small-12 medium-12 large-12' style="font-size: 1.4em">
                        <p style="font-weight: bold">Additional Instructions for mailing your package</p>
                        <p>
                            <span style="font-weight: bold">1.</span> Ensure that there are no other tracking labels
                            attached to your package. If you are shipping a non-hazardous item, completely remove or cover
                            any hazardous materials markings.<br>
                            <span style="font-weight: bold">2.</span> Affix the mailing label squarely onto the address side
                            of the parcel, covering up any previous delivery address and barcode without overlapping any
                            adjacent side.<br><br>
                            Take this package to a UPS location. To find your closest UPS location, visit the UPS Drop Off
                            Locator (linked https://www.ups.com/dropoff?loc=en_US) or go to www.ups.com.
                        </p>
                    </div>
                <?php
            }
                else {
                    ?>
                    <div class='small-12 medium-12 large-12' style="font-size: 1.4em">
                        <p style="font-weight: bold">Additional Instructions for mailing your package</p>
                        <p>
                            Securely pack the items in a box. <br>
                            Affix the mailing label squarely onto the address side of the parcel, covering up any previous delivery address and barcode without overlapping any adjacent side.<br>
                            Ship package from your nearest post office or shipping company of your choice
                        </p>
                    </div>
                    <?php
                }
        }
        echo $label_data['_label_message'];?>
    </div>

    <div class='medium-3 small-12 columns'>&nbsp;</div>
</div>

<?php
get_footer();

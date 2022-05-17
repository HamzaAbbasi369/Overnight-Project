
jQuery('#ltint_option').val('groupon');

/**
 * @deprecated
 */
function showProgTooltip() {
    jQuery('#checkthis').dialog().prev('.ui-dialog-titlebar').find('a').hide();
    //jQuery( '#checkthis' ).dialog({ dialogClass: 'no-close' });
//                       jQuery('#checkthis').load('./freeform_vs_standard.html').dialog({
    jQuery('#checkthis').load('/rx/lens.html').dialog({
        modal:true,
        height: 530,
        width: 800,
        position:[20,20],
        left: 20,
        buttons: {
            'Continue': function() {
                jQuery("#dummy_radio").hide();
                jQuery("#rdo_p_allday").show();
                jQuery("#rdo_p_allday").attr('checked', true).trigger('click');
                jQuery("#free_form").attr('checked', true);
                ShowEnhanceRxAccuracyFee();
                jQuery("#free_form").attr('disabled', true);
                jQuery('#checkthis').dialog('close');
                jQuery('.ui-dialog').remove();
            }

        }
    });
}

/**
 * @deprecated
 */
function showPDInfo() {
    jQuery('#mypd').load('/rx/pdinfo.php');
    jQuery('#mypd').show();
    /*
                              .dialog({
                            modal:true,
                            height: 550,
                            width: 800,
                            position:[20,20],
                            buttons: {
                                    'Finished': function() {
                                            jQuery(this).dialog('close');
                                    },
                            }
            });*/
}

/**
 * @deprecated
 */
function GetRX(){

    if (jQuery('#d_mode').val()== 'fashion') {
        content = '<div style="margin-bottom:5px; margin-top:0px;">Fashion Lenses</div>';
        return content;
    }
    var od_sign =jQuery("#od_sphere").val()>0 ? "+" : "";
    var os_sign =jQuery("#os_sphere").val()>0 ? "+" : "";

    content = '<div style="margin-bottom:5px; margin-top:10px;">OD Sphere: '+od_sign+jQuery("#od_sphere").val()+', OD Cylinder: '+jQuery("#od_cylinder").val()+', OD Axis: '+jQuery("#od_axis").val()+', OD Add: '+jQuery("#od_add").val()+'</div>';
    content = content + '<div style="margin-bottom:5px;">OS Sphere: '+os_sign+jQuery("#os_sphere").val()+', OS Cylinder: '+jQuery("#os_cylinder").val()+', OS Axis: '+jQuery("#os_axis").val()+', OS Add: '+jQuery("#os_add").val()+'</div>';
    content = content + '<div style="margin-bottom:5px;">PD 1: '+jQuery("#pd_1").val();
    if (jQuery("#chk_pd_2").is(':checked')) {
        content = content + ', PD 2: '+jQuery("#pd_2").val();
    }
    content = content + '</div>';

    if (jQuery("#prism").is(':checked')) {
        content = content + '<div style="margin-bottom:5px; margin-top:10px;">OD Vertical Prism: '+jQuery("#od_vp").val()+', OD Vertical Base Direction: '+jQuery("#od_vp_basedirection").val()+', OD Horizontal Prism: '+jQuery("#od_hp").val()+', OD Horizontal Base Direction: '+jQuery("#od_hp_basedirection").val()+'</div>';
        content = content + '<div style="margin-bottom:5px;">OS Vertical Prism: '+jQuery("#os_vp").val()+', OS Vertical Base Direction: '+jQuery("#os_vp_basedirection").val()+', OS Horizontal Prism: '+jQuery("#os_hp").val()+', OS Horizontal Base Direction: '+jQuery("#os_hp_basedirection").val()+'</div>';
    }
    return content;
}

/**
 * @deprecated
 */
function GetLenses(){




    content = '';
    content = content + '<div style="margin-bottom:5px;">Single Vision: Impact Resistant</div>';
    content = content + '<div style="margin-bottom:5px;">PC Advanced</div>';
    content = content + '<div style="margin-bottom:5px;">Premium Anti-Glare</div>';

    return content;

}

/**
 * @deprecated
 */
function ReviewOrder () {

    jQuery('#progress-step-name').hide();
    jQuery('#progress-step-name').hide();
    jQuery('.nav-progress').hide();
    jQuery('#package_image').hide();
    jQuery('#package_title').hide();
    jQuery('#package_data_header').html('<B>SELECTED OPTIONS</B>');
    //jQuery(".cart").show();


    jQuery('#step2').hide();
    jQuery('#nb_step6').show();



    jQuery("#rx_placeholder").html(GetRX());

    //jQuery("#charge_summary").html(jQuery('#package_data').html());

    rx.step.showStep6();
}

jQuery(document).ready(function($) {

    jQuery(document).on('rx-loaded', function () {
        var rx_form_container = $('.rx-form-container');
        rx_form_container.on('rx_navigation_set', function (event) {
            jQuery("[id^='nb_step']").hide();
            jQuery('#step2').show();
            jQuery('#nb_step3').show();
        });
    });

    var isource=jQuery('.wp-post-image').attr('src');
    var ftitle=jQuery('.product_title').text();
    if (jQuery('#pa_color').length >0) {
        ftitle=ftitle + ' - '+ jQuery('#ong_color_select').val();
    }
    window.scrollTo(0, 60);
    //jQuery('#order_fimage').attr('src',isource);
    jQuery('#order_ftype').text(ftitle);
    jQuery('#frame_title').text(ftitle);
    InitForm();

    var step_wrap = jQuery(".container.rx-form-container");
    // left_push_block = jQuery(".container.rx-form-container").find(".large-2.medium-8.small-12.columns"),
    // left_content_wrap = jQuery(".container.rx-form-container").find(".large-5.medium-8.small-12.columns");

    step_wrap.addClass("is-step-2");
    // left_push_block.removeClass("large-2").addClass("large-1");
    // left_content_wrap.removeClass("large-5").addClass("large-7");


    jQuery('.rx_link').css('cssText', 'background: none !important; font-size: 16px !important; color:#353648 !important; font-weight: bold');

    jQuery('span[rel=tooltip]').mouseover(function(e) {
        //Grab the title attribute's value and assign it to a variable
        var tip = jQuery(this).attr('title');

        //Remove the title attribute's to avoid the native tooltip from the browser
        jQuery(this).attr('title','');

        //Append the tooltip template and its value
        jQuery(this).append('<div id="tooltip"><div class="tipHeader"></div><div class="tipBody">' + tip + '</div></div>');

        //Show the tooltip with faceIn effect
        jQuery('#tooltip').show();
        //jQuery('#tooltip').css('top', e.pageY + 0 );
        jQuery('#tooltip').css('left', 0);
        //jQuery('#tooltip').fadeTo('10',0.9);

    }).mousemove(function(e) {

        //Keep changing the X and Y axis for the tooltip, thus, the tooltip move along with the mouse
        //jQuery('#tooltip').css('top', e.pageY + 10 );
        //jQuery('#tooltip').css('left', e.pageX + 0 );

    }).mouseout(function() {

        //Put back the title attribute's value
        jQuery(this).attr('title',jQuery('.tipBody').html());

        //Remove the appended tooltip template
        jQuery(this).children('div#tooltip').remove();

    });
});

function ValidateRx() {
    console.info(arguments.callee.name);
    var pd_1 = parseFloat(jQuery("#pd_1").val());
    var od_cylinder = parseFloat(jQuery("#od_cylinder").val());
    var os_cylinder = parseFloat(jQuery("#os_cylinder").val());
    var od_axis = parseFloat(jQuery("#od_axis").val());
    var os_axis = parseFloat(jQuery("#os_axis").val());
    var od_sphere = parseFloat(jQuery("#od_sphere").val());
    var os_sphere = parseFloat(jQuery("#os_sphere").val());
    //var material_and_thickness = jQuery("#material_and_thickness").val();
    var coating_treatment = jQuery("#coating_treatment").val();
    var tint_options = jQuery("#tint_options").val();
    var comments = jQuery("#comments").val();

    var ptype_value = jQuery("#lens_type").text() ;
    var od_add = parseFloat(jQuery("#od_add").val());
    var os_add = parseFloat(jQuery("#os_add").val());


    if (pd_1 == 0) {
        rx.alert("Prescription data must have PD value"); return false;
    }
    if(od_sphere==0 && os_sphere==0) {
        rx.alert("Prescription data must have OD Sphere and OS Sphere values"); return false;
    }
    if (ptype_value == 'Reading' || ptype_value == 'All-Day Lens') {
        if (od_add == 0 && os_add == 0) {
            rx.alert("Prescription data must have OD ADD and OS ADD value"); return false;
        }
        if (od_add == 0) {
            rx.alert("Prescription data must have OD ADD value"); return false;
        }
        if (os_add == 0) {
            rx.alert("Prescription data must have OS ADD value"); return false;
        }
    }

    if (od_cylinder != 0) {
        if (od_axis == 0) {
            rx.alert("Prescription data must have an Axis value if Cylinder value is different then 0.00"); return false;
        }
    }
    if (os_cylinder != 0) {
        if (os_axis == 0) {
            rx.alert("Prescription data must have an Axis value if Cylinder value is different then 0.00"); return false;
        }
    }

    if (od_sphere < 0) {
        if (os_sphere > 0) {
            var return_sphere = confirm('Please Note! Most prescription have either (-) or (+) value for both eyes. Would you like to change that?');
            if (return_sphere) {
                jQuery("#os_sphere").val("0").attr('selected', true); return false;
            }
        }
    }
    if (od_sphere > 0) {
        if (os_sphere < 0) {
            var return_sphere = confirm('Please Note! Most prescription have either (-) or (+) value for both eyes. Would you like to change that?');
            if (return_sphere) {
                jQuery("#os_sphere").val("0").attr('selected', true); return false;
            }
        }
    }

    if (jQuery("#pd_1").val() == "none") {
        rx.alert("PD cannot be empty."); return false;
    }
    else {
        if(jQuery("#chk_pd_2").is(':checked')) {
            if (jQuery("#pd_2").val() == "none") {
                rx.alert("PD cannot be empty."); return false;
            }
        }
    }


    return true;

}

function Reset_Review() {
    console.info(arguments.callee.name);
    jQuery('#package_data_header').show();
    jQuery('#package_image').show();
    jQuery('#package_title').show();
}

function showTooltip (idVar) {
    console.info(arguments.callee.name);
    //Grab the title attribute's value and assign it to a variable
    var tip = jQuery('#' + idVar).attr('title');

    //Remove the title attribute's to avoid the native tooltip from the browser
    jQuery('#' + idVar).attr('title','');

    //Append the tooltip template and its value
    jQuery('#' + idVar).append('<div id="tooltip"><div class="tipHeader"></div><div class="tipBody">' + tip + '</div></div>');

    //Show the tooltip with faceIn effect
    jQuery('#tooltip').show();
    //jQuery('#tooltip').css('top', e.pageY + 0 );
    jQuery('#tooltip').css('left', e.pageX + -30 );
    //jQuery('#tooltip').fadeTo('10',0.9);
}

function hideTooltip (idVar) {
    console.info(arguments.callee.name);
    //Put back the title attribute's value
    jQuery('#' + idVar).attr('title',jQuery('.tipBody').html());

    //Remove the appended tooltip template
    jQuery('#' + idVar).children('div#tooltip').remove();
}

function ToCart() {
    console.info(arguments.callee.name);
    if (!ValidateRx()) {
        return false;
    }
    SubmitRX();
    jQuery('.ong_cart').submit();
}

function ChangeAxis(container_axis, id_cyl, select_name) {
    console.info(arguments.callee.name);
    var cyl_val = parseFloat(jQuery('#' + id_cyl).val());
    if (cyl_val != 0) {
        var content = '<select name="'+select_name+'" id="'+select_name+'">';
        for (var i=0; i<=180; i++) {
            if (i == 0) {
                content = content + "<option value='"+i+"' selected>"+i+"</option>";
            }
            else {
                content = content + "<option value='"+i+"'>"+i+"</option>";
            }
        }
        content = content + '</select>';
        jQuery('#' + container_axis).html(content);
    }
    else {
        jQuery('#' + container_axis).html('<select name="'+select_name+'" id="'+select_name+'"><option value="0">0</option></select>');
    }

}

function AnotherPD() {
    console.info(arguments.callee.name);
    if (jQuery("#chk_pd_2").is(':checked')) {
        jQuery("#pd_spare").html(jQuery("#pd_1").html());
        jQuery("#pd_1").html(jQuery("#pd_2").html());
        jQuery("#div_pd_2").show();
    }
    else {
        jQuery("#div_pd_2").hide();
        jQuery("#pd_1").html(jQuery("#pd_spare").html());
    }
}

function ShowPrism() {
    console.info(arguments.callee.name);
    if(jQuery("#prism").is(':checked')) {

        jQuery("#container_prism").show();
    }
    else {

        jQuery("#container_prism").hide();
    }
}

//********************** End Easy Ckean **************************************************
//*****************************  Rush Service  *******************************************
// function HideRushFee() {
// 	jQuery("#c_rush").html("");
// 	jQuery("#rush_price").val(0);
//
// }

function ShowRushFee() {
    console.info(arguments.callee.name);
    var single_rush_fee = rx.fee.getSingleRush();
    jQuery("#rush_price").val(single_rush_fee);
    var content=FormatLineItem('Next Day Rush Service:',single_rush_fee);
    jQuery("#c_rush").html(content);
}

function RushClicked() {
    console.info(arguments.callee.name);
    if (jQuery('#lrush').val()==0) {
        ShowRushFee();
        jQuery('#lrush').val(1);
    }
    else {
        rx.fee.hideRush();
        jQuery('#lrush').val(0);
    }
    rx.price.calculate();
}
//********************** End Rush Service **************************************************

function InitForm(){
    console.info(arguments.callee.name);
    jQuery("#c_material").html(FormatLineItem('Premium Lenses',0.00));
    jQuery("#c_impact_resistant").html(FormatLineItem('Impact Resistant Lenses',0.00));
    //jQuery("#c_easy_clean").html(FormatLineItem('Easy Clean Lenses:',single_easy_clean_fee));
    jQuery("#c_coating").html(FormatLineItem('Premium Anti-Glare',0.00));
    //jQuery("#c_tint").html(FormatLineItem(value,fee));
    jQuery("#c_ptype").html(FormatLineItem('Single Vision',0.00));
    var frame_subtotal = parseFloat(jQuery("#frame_subtotal").val());
    jQuery('#total_price').val(frame_subtotal);
}

function FormatPrice(p){
    console.info(arguments.callee.name);
    if (p==0) {
        return "Free";
    }
    var reg="$" + p.toFixed(2).toString().replace(",", ".");
    var result='';
    if (jQuery("#pfactor").val()>0 && jQuery("#pfactor").val()<1 ) {
        var d =p*jQuery('#pfactor').val();
        var dp="$" + d.toFixed(2).toString().replace(",", ".");
        //result="<strike style='color:red'><b>" + reg + "</b></strike>  :  "+dp;
        result=dp;
    }
    else
    {
        result=reg;
    }
    return result;
}

function FormatLineItem(item_desc,item_price) {
    console.info(arguments.callee.name);
    return '<li style="float:left;">'+item_desc+':</li><li style="float:right;">'+FormatPrice(parseFloat(item_price))+'</li><li style="clear:both;"></li>';
}

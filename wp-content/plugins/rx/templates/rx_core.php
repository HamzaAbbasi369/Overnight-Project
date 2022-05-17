<div id="step2" style="display:none">
    <div class="" style="background-color:#eaeaea;color:#000000;padding:20px;font-family:'kelson';margin-bottom:50px!important;">
        <h3 class="Enter-prescription_heading" style="font-family:'Kelson';">Enter Your Prescription And Our Form Will Match You With The Best Possible Lenses. Thin Lenses Guaranteed.</h3>
        <a class="custom_learn_more_step_2" href="#" style="color:#92844d;font-size:18px;">Learn More</a>
        <p class="custom_text_learn_more" style="color:#333333;font-size:18px;line-height: 1.6;font-weight:normal;">Your eyeglass prescription is unique. Therefore, we match your prescription value with a unique set of lens packages and materials. These combinations will best serve your need and the lenses are guaranteed to be thin. Upload your prescription image and our opticians will review it. We will make sure all values are correct and match. We will contact you if any discrepancies or issues found.</p>
    
    <script>
//    jQuery("#custom_learn_more_step_2").click(function(){
//  jQuery('#custom_text_learn_more').show();
//});
        jQuery(".custom_text_learn_more").hide();
        jQuery(".custom_learn_more_step_2").on("click", function () {
            var txt = jQuery(".custom_text_learn_more").is(':visible') ? 'Learn More' : 'Read Less';
            jQuery(".custom_learn_more_step_2").text(txt);
            jQuery(this).next('.custom_text_learn_more').slideToggle(200);
        });
    </script>
    </div>

    <div class="clearfix">
        <div class="medium-6 small-12 columns text-center right-eye rx-eye-name"><p>Right Eye (OD)</p>

            <div class="clearfix prescription-step-too">
                <div class="float-left rx-product-name"><?= print_rx_label('SPHERE') ?></div>
                <div class="float-right"><?php print_rx_select_plus('SPHERE', 'od_sphere', 'od_sphere', '', -12, 12, 0.25) ?></div>
            </div>

            <div class="clearfix prescription-step-too">
                <div class="float-left rx-product-name"><?= print_rx_label('CYLINDER') ?></div>
                <div class="float-right"><?php print_rx_select_plus('CYLINDER', 'od_cylinder', 'od_cylinder', 'ChangeAxis(this)', -6, 6, 0.25) ?></div>
            </div>
            <div class="clearfix prescription-step-too">
                <div class="float-left rx-product-name"><?= print_rx_label('AXIS') ?></div>
                <div class="float-right"><?php print_rx_select('AXIS', 'od_axis', 'od_axis', '', 0, 180, 1) ?></div>
            </div>

            <div class="clearfix prescription-step-too">
                <div class="float-left rx-product-name"><?= print_rx_label('ADD') ?></div>
                <div class="float-right"><?php print_rx_select_plus('ADD', 'od_add', 'od_add', '', 0, 3, 0.25) ?></div>
            </div>

        </div>
        <div class="medium-6 small-12 columns text-center left-eye rx-eye-name"><p>Left Eye (OS)</p>

            <div class="clearfix prescription-step-too">
                <div class="float-left rx-product-name"><?= print_rx_label('SPHERE') ?></div>
                <div class="float-right"><?php print_rx_select_plus('SPHERE', 'os_sphere', 'os_sphere', '', -12, 12, 0.25) ?></div>
            </div>
            <div class="clearfix prescription-step-too">
                <div class="float-left rx-product-name"><?= print_rx_label('CYLINDER') ?></div>
                <div class="float-right"><?php print_rx_select_plus('CYLINDER', 'os_cylinder', 'os_cylinder', 'ChangeAxis(this)', -6, 6, 0.25) ?></div>
            </div>
            <div class="clearfix prescription-step-too">
                <div class="float-left rx-product-name"><?= print_rx_label('AXIS') ?></div>
                <div class="float-right"><?php print_rx_select('AXIS', 'os_axis', 'os_axis', '', 0, 180, 1) ?></div>
            </div>
            <div class="clearfix prescription-step-too">
                <div class="float-left rx-product-name"><?= print_rx_label('ADD') ?></div>
                <div class="float-right"><?php print_rx_select_plus('ADD', 'os_add', 'os_add', '', 0, 3, 0.25) ?></div>
            </div>

        </div>

    </div>


    <div class="clearfix" id="SegOC">
        <div class="rx-mini-box">
            <div class="checkboxes">
                <input type="checkbox" style="margin-bottom: 0px;" id="chk_segoc" onchange="rx.prescription.segOC_sub_toggle();"/>
                <label for="chk_segoc">
                    I Have Seg/O.C. HT'
                </label>
            </div>
        </div>
        <div class="clearfix" id="substep_segoc">

            <?php /*
			<div class="prescription-step-too-wrap small-3 large-3 columns">
				<div class="clearfix prescription-step-too">
					<?php print_rx_select('Seg/O.C. HT (PD)', 'od_segoc', 'od_segoc', '', 8, 40, 0.5, 'Select') ?>
				</div>
			</div>
			<div class="prescription-step-too-post-wrap small-3 large-3 columns">
				<div  class="row prescription-step-too">
					<?php print_rx_select('Seg/O.C. HT (PD)', 'os_segoc', 'os_segoc', '', 8, 40, 0.5, 'Select') ?>
				</div>
			</div>
		 */ ?>
            <div class="medium-6 small-12 columns text-center right-eye rx-eye-name">
                <div class="clearfix prescription-step-too">
                    <div class="float-left rx-product-name">Seg/O.C.</div>
                    <div class="float-right"><?php print_rx_select('Seg/O.C. HT (PD)', 'od_segoc', 'od_segoc', '', 8, 40, 0.5, 'Select') ?></div>
                </div>
            </div>
            <div class="medium-6 small-12 columns text-center left-eye rx-eye-name">
                <div class="clearfix prescription-step-too">
                    <div class="float-left rx-product-name">Seg/O.C.</div>
                    <div class="float-right"><?php print_rx_select('Seg/O.C. HT (PD)', 'os_segoc', 'os_segoc', '', 8, 40, 0.5, 'Select') ?></div>
                </div>
            </div>
        </div>
    </div>


    <div class="row">
        <div class="text-center rx-eye-name">
            <p><?= print_rx_label('Pupillary Distance (PD)') ?></p>
        </div>
    </div>

    <div class="clearfix" id="framePD">
        <div class="small-12 large-12 columns">
            <div class="rx-mini-box">
                <div class="checkboxes">
                    <input type="checkbox" style="margin-bottom: 0px;" id="chk_pd_frame" onchange="rx.prescription.pd_toggle();"/>
                    <label for="chk_pd_frame">
                        Copy PD From Frame
                        <a href="#" class="popup-rx-tooltip" data-open="usepdfromframe" aria-controls="usepdfromframe" aria-haspopup="true" tabindex="0"></a>
                    </label>
                </div>
            </div>
        </div>
    </div>
    <div class="clearfix" id="pd">
        <div class="prescription-step-too-wrap small-3 large-3 columns">
            <div class="clearfix prescription-step-too">
                <?php print_rx_select('Pupillary Distance (PD)', 'pd_1', 'pd_1', '', 50, 74, 0.5, 'Select', WC_RX_DEFAULT_PD) ?>
            </div>
        </div>
        <div class="small-5 large-5 columns">
            <div class="rx-mini-box">
                <div class="checkboxes">
                    <input type="checkbox" style="margin-bottom: 0px;" id="chk_pd_2" onchange="rx.prescription.anotherPD();"/>
                    <label for="chk_pd_2">
                        Two PD Numbers
                    </label>
                </div>
            </div>
        </div>
        <div class="prescription-step-too-post-wrap small-3 large-3 columns">
            <div id="div_pd_2" style="display:none" class="row prescription-step-too">
                <?php print_rx_select('Pupillary Distance 2', 'pd_2', 'pd_2', '', 25, 37, 0.5, 'Select') ?>
                <select id="pd_spare" style="display:none"></select>
            </div>
        </div>
         <div class="small-3 large-3 columns">
         	<div id="div_pd_606" class="rx-mini-box sixoversixbox" style="display: block">
            <div  onclick="measurePD_cus()" class="pd-measure-custom-btn" id="measurePdButton">Measure PD</div>
            </div>
        </div>
       
    </div>
	<div id="glasseson" class="glasseson-global glasseson webapp"></div>
<script src="https://web.cdn.glasseson.com/glasseson-2.7.9.js"></script>
<script>
  
function measurePD_cus() {
	glasseson.open("pd");
	glasseson.setResultCallback(resultCallback);
        var options = {
profileName:"web",//will be provided by 6over6
flow: "pd",
fullPage: true,
width: 835,
themeColor: "#92844D",
fontFamily: "Kelson"
};
glasseson.init('1b937087-83ef-4f03-a8c2-1b6fb98a8ffa', 'https://api.glasseson.com/prod/', options).then(
response => {
handleSuccess();
},
error => {
handleError(error);
}
);
}
    
function handleSuccess() {
glasseson.open("pd"); // default tag will be sent
}
	function resultCallback(result) {
        // alert("Done");
        // glasseson.close();
		var sel_pd = result.data.pd;
        console.log(sel_pd + '0');
		  console.log(sel_pd);
		if(sel_pd == 50.0){
			 $("#pd_1").val(~~sel_pd);
		} else if(sel_pd == 50.5){
			$("#pd_1").val(sel_pd + '0');
		} else if(sel_pd == 51.0){
			$("#pd_1").val(~~sel_pd);
		} else if(sel_pd == 51.5){
			$("#pd_1").val(sel_pd + '0');
		} else if(sel_pd == 52.0){
			$("#pd_1").val(~~sel_pd);
		} else if(sel_pd == 52.5){
			$("#pd_1").val(sel_pd + '0');
		} else if(sel_pd == 53.0){
			$("#pd_1").val(~~sel_pd);
		} else if(sel_pd == 53.5){
			$("#pd_1").val(sel_pd + '0');
		}  else if(sel_pd == 54.0){
			 $("#pd_1").val(~~sel_pd);
		} else if(sel_pd == 54.5){
			$("#pd_1").val(sel_pd + '0');
		} else if(sel_pd == 55.0){
			$("#pd_1").val(~~sel_pd);
		} else if(sel_pd == 55.5){
			$("#pd_1").val(sel_pd + '0');
		} else if(sel_pd == 56.0){
			$("#pd_1").val(~~sel_pd);
		} else if(sel_pd == 56.5){
			$("#pd_1").val(sel_pd + '0');
		} else if(sel_pd == 57.0){
			$("#pd_1").val(~~sel_pd);
		} else if(sel_pd == 57.5){
			$("#pd_1").val(sel_pd + '0');
		} else if(sel_pd == 58.0){
			$("#pd_1").val(~~sel_pd);
		} else if(sel_pd == 58.5){
			$("#pd_1").val(sel_pd + '0');
		} else if(sel_pd == 59.0){
			$("#pd_1").val(~~sel_pd);
		} else if(sel_pd == 59.5){
			$("#pd_1").val(sel_pd + '0');
		} else if(sel_pd == 60.0){
			$("#pd_1").val(~~sel_pd);
		} else if(sel_pd == 60.5){
			$("#pd_1").val(sel_pd + '0');
		} else if(sel_pd == 61.0){
			$("#pd_1").val(~~sel_pd);
		} else if(sel_pd == 61.5){
			$("#pd_1").val(sel_pd + '0');
		} else if(sel_pd == 62.0){
			$("#pd_1").val(~~sel_pd);
		} else if(sel_pd == 62.5){
			$("#pd_1").val(sel_pd + '0');
		} else if(sel_pd == 63.0){
			$("#pd_1").val(~~sel_pd);
		} else if(sel_pd == 63.5){
			$("#pd_1").val(sel_pd + '0');
		} else if(sel_pd == 64.0){
			$("#pd_1").val(~~sel_pd);
		} else if(sel_pd == 64.5){
			$("#pd_1").val(sel_pd + '0');
		} else if(sel_pd == 65.0){
			$("#pd_1").val(~~sel_pd);
		} else if(sel_pd == 65.5){
			$("#pd_1").val(sel_pd + '0');
		} else if(sel_pd == 66.0){
			$("#pd_1").val(~~sel_pd);
		} else if(sel_pd == 66.5){
			$("#pd_1").val(sel_pd + '0');
		} else if(sel_pd == 67.0){
			$("#pd_1").val(~~sel_pd);
		} else if(sel_pd == 67.5){
			$("#pd_1").val(sel_pd + '0');
		} else if(sel_pd == 68.0){
			$("#pd_1").val(~~sel_pd);
		} else if(sel_pd == 68.5){
			$("#pd_1").val(sel_pd + '0');
		} else if(sel_pd == 69.0){
			$("#pd_1").val(~~sel_pd);
		} else if(sel_pd == 69.5){
			$("#pd_1").val(sel_pd + '0');
		} else if(sel_pd == 70.0){
			$("#pd_1").val(~~sel_pd);
		} else if(sel_pd == 70.5){
			$("#pd_1").val(sel_pd + '0');
		} else if(sel_pd == 71.0){
			$("#pd_1").val(~~sel_pd);
		} else if(sel_pd == 71.5){
			$("#pd_1").val(sel_pd + '0');
		} else if(sel_pd == 72.0){
			$("#pd_1").val(~~sel_pd);
		} else if(sel_pd == 72.5){
			$("#pd_1").val(sel_pd + '0');
		} else if(sel_pd == 73.0){
			$("#pd_1").val(~~sel_pd);
		} else if(sel_pd == 73.5){
			$("#pd_1").val(sel_pd + '0');
		} else if(sel_pd == 74.0){
			$("#pd_1").val(~~sel_pd);
		}
        

        $(".rx-core-select").onchange();
//				$("#pd_1 option[value=sel_pd + '0']").prop('selected',true);
        savePdMeasureLogs('COMPLETE', result.data.pd);
//		glasseson.close();
    }

//    glasseson.setResultCallback(resultCallback);
</script>
<style type="text/css">
.pd-measure-custom-btn{ 
border: 1px solid #92844d;
padding: 10px 20px;
width: max-content;
font-size: 16px;
color: #333333;
margin-top: -10px;
cursor: pointer;
font-family: 'kelson'
}
#glasseson.webapp.glasseson.go-fullpage{
margin-top: unset!important;
}
@media screen and (max-width: 480px) {
.pd-measure-custom-btn{ 
padding: 12px 19px;
font-size: 14px;
}
.small-5.large-5.columns {
    padding: 0;
}
	.rx-mini-box .checkboxes{
		padding-left: 7px;
	}
	.rx-mini-box{
		margin: 10px 0px;
	}
}
	@media screen and (max-width: 380px) {
		.pd-measure-custom-btn{ 
padding: 12px 13px;
		}
	}
	@media screen and (max-width: 350px) {
		.container.rx-form-container.is-step-2 .small-3.large-3.columns .clearfix.prescription-step-too,.container.rx-form-container.is-step-2 .small-3.large-3.columns .prescription-step-too{
			padding: 14px 4px;
		}
		.container.rx-form-container.is-step-2 .checkboxes label{
			padding-left: 5px;
		}
		
		}
</style>
    <div class="row">
        <div class="text-center rx-eye-name">
            <p><?= print_rx_label('Prism Value') ?></p>
        </div>
    </div>


    <div class="row">
        <div class="text-center">
            <input type="checkbox" id="prism" name="prism" onchange="rx.prescription.showPrism();"/>
            <label for="prism">
                <?= print_rx_label('Add Prism') ?>
            </label>
        </div>
    </div>

    <!-- Right Eye (OD)-->
    <div class="row" id="container_prism" style="display:none">
        <div class="medium-6 columns small-12 text-center right-eye rx-eye-name"><p>Right Eye (OD)</p>

            <div class="clearfix prescription-step-too">
                <div class="float-left rx-product-name"><?= print_rx_label('Vertical') ?></div>
                <div class="float-right"><?php print_rx_select_plus('Vertical', 'od_vp', 'od_vp', '', 0, 7, 0.25) ?></div>
            </div>

            <div class="clearfix prescription-step-too">
                <div class="float-left rx-product-name"><?= print_rx_label('Base Direction') ?></div>
                <div class="float-right">

                    <select name="od_vp_basedirection" id="od_vp_basedirection" class="rx-core-select" onchange="ChangeAxis()">
                        <option value='n/a' selected>n/a</option>
                        <option value='in'>Up</option>
                        <option value='out'>Down</option>
						 </select>

                            <!--                    --><?php //print_rx_select('Base Direction', 'od_vp_basedirection', 'od_vp_basedirection', 'ChangeAxis(this)', -6, 6, 0.25) ?><!--</div>-->
                </div>
            </div>

            <div class="clearfix prescription-step-too">
                <div class="float-left rx-product-name"><?= print_rx_label('Horizontal') ?></div>
                <div class="float-right"><?php print_rx_select_plus('Horizontal', 'od_hp', 'od_hp', '', 0, 7, 0.25) ?></div>
            </div>

            <div class="clearfix prescription-step-too">
                <div class="float-left rx-product-name"><?= print_rx_label('Base Direction') ?></div>
                <div class="float-right">

                    <select name="od_hp_basedirection" id="od_hp_basedirection" class="rx-core-select">
                        <option value='n/a' selected>n/a</option>
                        <option value='in'>IN</option>
                        <option value='out'>OUT</option>
                    </select>
                    <!--                    --><?php //print_rx_select('Base Direction', 'od_hp_basedirection', 'od_hp_basedirection', '', 0, 3, 0.25) ?>

                </div>
            </div>

        </div>
        <div class="medium-6 columns small-12 text-center left-eye  rx-eye-name"><p>Left Eye (OS)</p>

            <div class="clearfix prescription-step-too">
                <div class="float-left rx-product-name"><?= print_rx_label('Vertical') ?></div>
                <div class="float-right"><?php print_rx_select_plus('Vertical', 'os_vp', 'os_vp', '', 0, 7, 0.25) ?></div>
            </div>
            <div class="clearfix prescription-step-too">
                <div class="float-left rx-product-name"><?= print_rx_label('Base Direction') ?></div>
                <div class="float-right">

                    <select name="os_vp_basedirection" id="os_vp_basedirection" class="rx-core-select" onchange="ChangeAxis()">
                        <option value='n/a' selected>n/a</option>
                        <option value='in'>Up</option>
                        <option value='out'>Down</option>
                    </select>

                    <!--                    --><?php //print_rx_select('Base Direction', 'os_vp_basedirection', 'os_vp_basedirection', 'ChangeAxis(this)', -6, 6, 0.25) ?>
                </div>
            </div>
            <div class="clearfix prescription-step-too">
                <div class="float-left rx-product-name"><?= print_rx_label('Horizontal') ?></div>
                <div class="float-right"><?php print_rx_select_plus('Horizontal', 'os_hp', 'os_hp', '', 0, 7, 0.25) ?></div>
            </div>
            <div class="clearfix prescription-step-too">
                <div class="float-left rx-product-name"><?= print_rx_label('Base Direction') ?></div>
                <div class="float-right">
                    <!--                    --><?php //print_rx_select('Base Direction', 'os_hp_basedirection', 'os_hp_basedirection', '', 0, 3, 0.25) ?>
                    <select name="os_hp_basedirection" id="os_hp_basedirection" class="rx-core-select">
                        <option value='n/a' selected>n/a</option>
                        <option value='in'>IN</option>
                        <option value='out'>OUT</option>
                    </select>
                </div>
            </div>

        </div>
    </div>

    <!--Prescription Image -->
    <div class="clearfix container_prescriptionimage">
        <div class="text-center rx-eye-name">
            <p><?= print_rx_label('Prescription Image (recommended)') ?></p>
        </div>
        <link rel='stylesheet' id='alg-wc-checkout-files-upload-ajax-css' href='/content/plugins/rx/assets/css/alg-wc-checkout-files-upload-ajax.css?ver=1.3.0' type='text/css' media='all'/>
        <script type='text/javascript' src='/content/plugins/rx/assets/js/alg-wc-checkout-files-upload-ajax.js?ver=1.3.0'></script>
        <?php do_action('prescription_step_2'); ?>
    </div>

</div>

<div class="reveal-overlay">
    <div class="reveal" id="usepdfromframe" data-reveal="l8362w-reveal" role="dialog" aria-hidden="true" data-yeti-box="usepdfromframe" data-resize="usepdfromframe">
        <button class="close-button" data-close="" aria-label="Close modal" type="button">
            <span aria-hidden="true">×</span>
        </button>
        <p class="popup-rx-title">Copy Your PD From The Frame You Send.</p>
        <hr class="gold-line">
        <div class="popup-rx-text">Don't know your Pupil Distance? We will copy your Pupil Distance (PD) from the frame that you send us.
        </div>
    </div>
</div>



<!--Step2-->

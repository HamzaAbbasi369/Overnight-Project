/** global rx_params */
/** global rx_cart_params */
/** global default_pd */
/** global rx_popup_material */
/** global rx_i_popup_next_day_rush_service */
/** global rx_i_popup_3_days_rush_service */

/* steps
** NOTE if use progressive number for steps is difficult modify the flow , better use step with 3 digits 100 200 300 and keep the order in an array

1 usage
150 purpose (if progressive)
        170 distance (if progressive && office)
2 prescription
    -if progressige show seg/oc
    -if frame is YOUR FRAME show use pd from frame
    250 premium
7 Lens Package // Material
6 Review Order
....
*/

String.prototype.includes = function (str) {
    var returnValue = false;

    if (this.indexOf(str) !== -1) {
        returnValue = true;
    }

    return returnValue;
}

var Rx = function () {
};

String.prototype.pluralize = function (count, plural) {
    if (plural == null) {
        plural = this + 's';
    }
    return (count === 1 ? this : plural);
};

var Warranty = function (value, unit) {
    this.value = value;
    this.unit = unit;

    this.format = function () {
        return this.value + ' ' + this.unit.pluralize(this.value);
    };
    return this;
};

var Price = function (value) {
    this.sale_price = 0;
    this.value = value;

    if (Array.isArray(value)) {
        this.price = parseFloat(value[0]);
        this.sale_price = parseFloat(value[1]);
        if (this.price <= this.sale_price) {
            this.sale_price = 0;
        }
    } else if (!isNaN(parseFloat(value))) {
        this.price = parseFloat(value);
    }

    this.setPrice = function (value) {
        this.price = value;
        return this;
    };

    this.setSalePrice = function (value) {
        this.sale_price = value;
        return this;
    };

    this.format = function () {

        if (typeof (this.price) === 'string') {
            this.price = value;
        }

        if (this.price === '') {
            return 'Included';
        }

        if (this.sale_price > 0) {
            return '<span class="old-price">'
                + rx.price.format(parseFloat(this.price))
                + '</span>&nbsp;<span class="new-price">'
                + rx.price.format(parseFloat(this.sale_price))
                + '</span>';
        } else if (this.price !== undefined) {
            return rx.price.format(parseFloat(this.price));
        }


        return this.value;
    };
    return this;
};


Rx.prototype.resultElement = {
    "set": function (name, title, value) {
        jQuery("#c_" + name).html(this.formatLineItem(title, value));
    },
    "value": function (name) {
        jQuery("#c_" + name).html();
    },
    "getTitle": function (name) {
        console.log(name);
        var _elements = {
            'diamond': 'Diamond Anti-Glare Coating',
            'blue_diamond': 'Blue Diamond Anti-Glare Coating',
            'diamond_warranty': 'Lens package warranty',
            'easy_clean': 'Easy Clean Lenses',
            'uv': 'U.V Protection',
            'scratch': 'Scratch Protection',
            'impact_resistant': 'Impact Resistant Lenses',
            'rush': 'Next Day Rush Service'
        };

        return rx.label(name, _elements);
    },
    "formatLineItem": function (item_desc, item_value) {
        var desc_class = '';

        // add logotype for packages
        $('.option_select_rx').removeClass('active_mirror');
        $('.option_select_rx').removeClass('not_active_mirror');
        $('.mirror_box').removeClass('active_mirror');
        $('.mirror_box').removeClass('not_active_mirror');
        console.log('SEC: ' + rx.get('tint'));

        if (rx.get('tint') == '' && rx.prescription.get('d', 'mode', false) === 'fashion') {
            $('#impact_resistant').prev('h3').hide();
            $('#impact_resistant').hide();
            $("#div_impact_resistant_check").removeClass("option_selected");
            rx.fee.hideImpactResistant();
            $('#include').hide();
            $('.wrap-free-packages').hide();
        } else {
            $('#impact_resistant').show();
            $('#impact_resistant').prev('h3').show();
            $('#include').show();
            $('.wrap-free-packages').show();
        }

        if (item_desc.match('^Clear')) {
            jQuery('#include').addClass('brand-icon clear-icon');
            desc_class = 'brand-icon clear-icon';
        }

        if (item_desc.match('^Transitions') && !rx.get('tint').match('DriveWear') && !rx.get('tint').match('Vantage')) {
            jQuery('#include').removeClass('brand-icon blue-armor-icon');
            jQuery('#include').removeClass('brand-icon clear-icon'); // doesn't work without it, horseradish knows why
            desc_class = 'brand-icon transition-icon';
        }

        if (rx.get('tint').match('DriveWear')) {
            jQuery('#include').removeClass('brand-icon blue-armor-icon');
            jQuery('#include').removeClass('brand-icon clear-icon');
            desc_class = 'brand-icon drivewear-icon';
        }

        if (rx.get('tint').match('Vantage')) {
            jQuery('#include').removeClass('brand-icon blue-armor-icon');
            jQuery('#include').removeClass('brand-icon clear-icon');
            desc_class = 'brand-icon vantage-icon';
        }
		 if (rx.get('tint').match('polarized')) {
            jQuery('#include').removeClass('brand-icon blue-armor-icon');
            jQuery('#include').removeClass('brand-icon clear-icon');
            desc_class = 'brand-icon vantage-icon';
        }

        if (rx.get('tint').match('Infinite')) {
            jQuery('#include').removeClass('brand-icon blue-armor-icon');
            jQuery('#include').removeClass('brand-icon clear-icon');
            desc_class = 'brand-icon infinite-icon';
        }

        // if (item_desc.match('^Blue')) {
        //     desc_class =  'brand-icon blue-armor-icon';
        //     // jQuery('#include').addClass('brand-icon blue-armor-icon');
        // }

        if (item_desc.match('^Blue ')) {
            // desc_class = 'brand-icon blue-armor-icon';
            jQuery('#include').removeClass('brand-icon clear-icon');
            jQuery('#include').addClass('brand-icon blue-armor-icon'); // doesn't work without it, horseradish knows why
        }

        if (item_desc.match('^polarized')) {
            jQuery('#include').removeClass('brand-icon clear-icon');
            jQuery('#include').removeClass('brand-icon blue-armor-icon'); // doesn't work without it, horseradish knows why
            desc_class = 'brand-icon polarized-icon';
        }

        if (!isNaN(parseFloat(item_value))) {
            item_value = new Price(item_value);
        }

        var is_rx_package = jQuery('#rx_package').val();

        if (is_rx_package === 'ok' && item_value instanceof Price && !item_value.price && rx.get('tint') != '') {
            console.log('show included premium features');
            jQuery('#include').html('<p class="' + desc_class + '"><span class="include-span">Included Premium Features:</span></p>');
            jQuery('.wrap-free-packages').show();

            return '<li class="rx-package-free" style="float:left;">' + item_desc + '</li><br>';
        }

        return '<li style="float:left;">' + item_desc + ':</li><li style="float:right;">' + item_value.format() + '</li><li style="clear:both;"></li>';

    }
};

Rx.prototype.sourceElement = {
    'rx_form': '.rx-form-container',
    "get": function (name) {
        //console.info('Rx.sourceElement.'+arguments.callee.name,arguments);
        return jQuery('#l' + name).val();
    },
    "insertValue": function (name, value) {
        //console.info('Rx.insertValue.'+arguments.callee.name,arguments);
        var that = this;
        var create_handler = function () {
            var event_name = jQuery.Event('rx_source_change');
            event_name.element_name = name;
            event_name.element_value = value;
            return function () {
                jQuery(that.rx_form).trigger(event_name);
            };
        }();
        var handler = create_handler();
        jQuery('#l' + name).off('change', handler).on('change', handler).val(value);

    }
};

/**step-1 LENS TYPE */
Rx.prototype.usage = {
    "checked": function () {
        //console.info(arguments.callee.name);
        if (rx.get('usage') != '') {
            return rx.get('usage');
        }
        return false;
    },
    "set": function (value) {
        //reset flow
        rx.purpose.set('');
        //console.info(arguments.callee.name, arguments);
        rx.set('usage', value);

        if (rx.sourcePage.get() !== 'landing_progressive') {
            rx.navigation.showNavControl(1);
        }
    },
    "getPtypeValue": function (ptype_value) {
        //console.info(arguments.callee.name);
        var ptype_desc = "";
        if (ptype_value === this.labels('singleVisionReading') || ptype_value === this.labels('singleVisionDistance')) {
            ptype_desc = this.labels('singleVision');
        }
        if (ptype_value === this.labels('bifocal')) {
            ptype_desc = this.labels('bifocal');
        }
        if (ptype_value === this.labels('nolinebifocal')) {
            ptype_desc = this.labels('nolinebifocal');
        }

        if (ptype_value === this.labels('progressive')) {
            ptype_desc = this.labels('progressive');
        }
        if (rx.purpose.checked != false) {
            ptype_desc += ' ' + rx.purpose;
        }
        if (rx.distance.checked != false) {
            ptype_desc += ' ' + rx.distance;
        }
        return ptype_desc;
    },
    "step_init": function (rx_form_container) {
        rx_form_container.on('click', '.selector.prescription_type-selector', function (event) {
            var usage = jQuery(this).data('usage');
            // console.log('usage',usage);
            rx.usage.set(usage);
            event.stopPropagation();
        });

        rx_form_container.on('rx_source_change', function (event) {
            //console.info(arguments.callee.name);
            var element_name = event.element_name;
            var element_value = event.element_value;
            console.log('shi: '+element_value);
            if ('usage' === element_name) {
                if (element_value === rx.usage.labels('bifocal') || element_value === rx.usage.labels('nolinebifocal') || element_value === rx.usage.labels('progressive')) {
                    rx.fee.hideEnhanceRxAccuracy();
                    jQuery("#enhance_rx_accuracy").hide();
                    if (jQuery("#order_ftype").text() == 'Your Frames') {
                        jQuery("#div_rush_check_label span").text("4 days, Two-way rush service. ");
                        jQuery("#your_frame_rush_message").text("Replacement Rush Service, includes two-way UPS Next Day Air shipping and faster processing. Get your new lenses in 4 days.");
                    } else {
                        jQuery("#div_rush_check_label span").text("Next Day Rush Service ");
		        jQuery('#div_rush_check_label span').prepend('<img src="/content/plugins/rx/assets/image/ic-delivery.svg" alt="" class="rush-logo-order">');	    
                    	jQuery('#div_rush3day_check_label span').prepend('<img src="/content/plugins/rx/assets/image/glasses-in-3-days.svg" alt="" class="rush-logo-order">');
			jQuery("#rush").show();
			jQuery("#3day_rush").hide();
		    }
                    var stitle = rx_params.rx_i_popup_next_day_rush_service;
                    jQuery('.IR_rush').html(stitle);
                    jQuery('#3_days_rush_message').show();
                } else {
                    jQuery("#enhance_rx_accuracy").show();
                    if (jQuery("#order_ftype").text() == 'Your Frames') {
                        jQuery("#div_rush_check_label span").text("2 days,Two-way rush service. ");
                        jQuery("#your_frame_rush_message").text("Replacement Rush Service, includes two-way UPS Next Day Air shipping and faster processing. Get your new lenses in 2 days.");
                    } else {
                        jQuery("#div_rush_check_label span").text("Next Day Rush Service ");
                        jQuery('#3_days_rush_message').hide();
			jQuery("#rush").show();
			jQuery("#3day_rush").show();
                    }
                    jQuery('#div_rush_check_label span').prepend('<img src="/content/plugins/rx/assets/image/ic-delivery.svg" alt="" class="rush-logo-order">');
		    jQuery('#div_rush3day_check_label span').prepend('<img src="/content/plugins/rx/assets/image/glasses-in-3-days.svg" alt="" class="rush-logo-order">');

                    stitle = rx_params.rx_i_popup_next_day_rush_service;
		    var stitle2 = rx_params.rx_i_popup_3_days_rush_service;
                    jQuery('.IR_rush').html(stitle);
		    jQuery('.IR_rush3day').html(stitle2);
                    jQuery('#3_days_rush_message').hide();
                }

            }
        });

        //    UI 12/01/18 hide yotpo reviews for RX
        jQuery('.yotpo.yotpo-main-widget').hide();
        jQuery('.secure-icons-wrap').addClass('icons-RX');

        var rxData = localStorage.getItem('rx_data') ? JSON.parse(localStorage.getItem('rx_data')) : {};
        if (typeof rxData.usage !== 'undefined' && typeof rxData.usage.type !== 'undefined') {
            var type = rxData.usage.type;
            setTimeout(function () {
                jQuery('.selector.prescription_type-selector[data-usage="' + type + '"] .selector_check').click();
            }, 0);
        }

        // if (typeof rxData.usage !== 'undefined' && typeof rxData.usage.impact_resistant !== 'undefined') {
        //     var impact_resistant = rxData.usage.impact_resistant;
        //     console.log('impact_resistant - ', impact_resistant);
        // }
    },
    "labels": function (name) {
        var _elements = {
            'singleVisionReading': 'Single Vision Reading',
            'singleVisionDistance': 'Single Vision Distance',
            'singleVision': 'Single Vision',
            'bifocal': 'Bifocal',
            'nolinebifocal': 'No Line Bifocal',
            'progressive': 'Progressive'
        };
        return rx.label(name, _elements);
    }
};


/**step-150 PURPOSE - PROGRESSIVE TYPE SELECTOR*/
Rx.prototype.purpose = {
    "checked": function () {
        if (rx.get('purpose') !== '') {
            return rx.get('purpose');
        }
        return false;
    },
    "set": function (value) {
        //reset flow
        rx.distance.set('');
        rx.premium.set('');

        rx.set('purpose', value);

        if (value !== '' && value !== undefined) {
            //DYNAMIC NAVI if progressive is selected swtich navi
            /*
            if(jQuery('#step150').is(":visible")){
                if ( value === rx.purpose.labels('office')) {
                    jQuery("#nb_step150_o").show();
                    jQuery("#nb_step150").hide();

                }else{
                    jQuery("#nb_step150_o").hide();
                    jQuery("#nb_step150").show();
                }
            }*/
            rx.navigation.showNavControl(150);
            //show on right collumn
            //rx.show('purpose', this.toString() , 0);
            rx.fee.showType('Progressive');
            rx.price.set('purpose', 0);
            return rx.get('purpose');
        } else {
            //remove checked
            jQuery('#step150').find('.option_selected').removeClass('option_selected');
            //rx.fee.hidePurpose();
            return false;
        }
    },
    "labels": function (name) {
        var _elements = {
            'driving': 'driving',
            'general': 'general',
            'office': 'office',
            'sports': 'sports'
        };
        return rx.label(name, _elements);
    },
    // get readable text
    "toString": function () {
        var _tmp = rx.purpose.checked();
        if (_tmp != false) {
            switch (_tmp) {
                case "general":
                    _tmp = "General Use";
                    break;
                case "sports":
                    _tmp = "Active";
                    break;
                default:
                    _tmp = _tmp.charAt(0).toUpperCase() + _tmp.slice(1);
            }
            return _tmp;
        } else {
            return '';
        }
    }
};

/**step-170 DISTANCE - PROGRESSIVE OFFICE*/
Rx.prototype.distance = {
    "checked": function () {
        if (rx.get('distance') != '') {
            return rx.get('distance');
        }
        return false;
    },
    "set": function (value) {
        rx.set('distance', value);
        //update right view
        if (value != '' && value !== undefined) {
            //rx.show('distance', this.toString() , 0);
            rx.price.set('distance', 0);
            rx.fee.showType('Progressive');
            return rx.get('distance');
        } else {
            //remove checked
            jQuery('#step170').find('.option_selected').removeClass('option_selected');
            //rx.fee.hideDistance();
            return false;
        }
    },
    "labels": function (name) {
        var _elements = {
            '4ft': '4gt',
            '6_5f': '6_5ft',
            '13f': '13ft',
            '19f': '19ft'
        };
        return rx.label(name, _elements);
    },
    // get readable text
    "toString": function () {
        var _tmp = rx.distance.checked();
        if (_tmp != false) {
            _tmp = _tmp.replace("_", ".");
            return 'up to ' + _tmp;
        } else {
            return '';
        }
    }
};

/**step-2 ENTER YOUR PRESCRIPTION */
Rx.prototype.prescription = {
    "step_init": function () {
        var rxData = localStorage.getItem('rx_data') ? JSON.parse(localStorage.getItem('rx_data')) : {};
        if (typeof rxData.prescription !== 'undefined') {
            var oldData = rxData.prescription;
            if (oldData.chk_pd_2) {
                rx.prescription.set('chk_pd_2');
            }
            if (oldData.prism) {
                rx.prescription.set('prism');
            }
            for (var i in oldData) {
                if (!oldData.hasOwnProperty(i)) {
                    continue;
                }
                // console.log(i + ': ' + oldData[i]);
                rx.prescription.set(i, oldData[i]);
            }
        }
    },
    "set": function () {
        // console.info('Rx.prescription.'+arguments.callee.name,arguments);
        if (arguments.length === 1) {
            var name = arguments[0];
            jQuery('#' + name).click();
        } else {
            name = arguments[0];
            var value = arguments[1];
            jQuery('#' + name).val(value);
        }
    },
    "get": function () {
        //console.info('Rx.prescription.'+arguments.callee.name,arguments);
        var name = arguments[0];
        var element = arguments[1];
        var needParse = arguments[2];

        if (needParse === undefined) {
            needParse = true;
        }
        needParse = !!needParse;
        var result = jQuery('#' + name + '_' + element).val();
        return needParse ? parseFloat(result) : result;
    },
    "checkSphereSigns": function () {
        var validation_prompt = 'Please Note! Most prescription have either (-) or (+) value for both eyes. Would you like to change that? Choose [Cancel] to stay on this screen and change values. Choose[OK] to continue the process.';
        if (this.get('od', 'sphere') < 0) {
            if (this.get('os', 'sphere') > 0) {
                if (!confirm(validation_prompt)) {
                    jQuery("#os_sphere").focus()
                    return false;
                }
            }
        }
        if (this.get('od', 'sphere') > 0) {
            if (this.get('os', 'sphere') < 0) {
                if (!confirm(validation_prompt)) {
                    jQuery("#os_sphere").focus()
                    return false;
                }
            }
        }
        return true;
    },
    "strength": function () {
        var strength_prism = 0;

        if (rx.package.isPrismInUse()) {
            strength_prism = Math.max(
                Math.abs(this.get('od', 'vp')),
                Math.abs(this.get('od', 'hp')),
                Math.abs(this.get('os', 'vp')),
                Math.abs(this.get('os', 'hp'))
            );
        }

        var strength = Math.max(
            Math.abs(this.get('od', 'sphere')) + Math.abs(this.get('od', 'cylinder')),
            Math.abs(this.get('os', 'sphere')) + Math.abs(this.get('os', 'cylinder'))
        );

        return strength + strength_prism;
    },

    "validate": function () {
        var coating_treatment = jQuery("#coating_treatment").val();
        var tint_options = jQuery("#tint_options").val();
        var comments = jQuery("#comments").val();
        var logList = [];

        if (rx.get('impact') === 1) {
            if (this.strength() > 6) {
                if (!confirm('Please note, if this prescription belongs to a person 18 year and older, we recommend unchecking the impact resistant option. Impact resistant lenses at this prescription strength tend to be thicker when compared to recommended high index lenses. Do you want to continue?')) {
                    //throw new Error('Please note, if this prescription belongs to a person 18 year and older, we recommend unchecking the impact resistant option. Impact resistant lenses at this prescription strength tend to be thicker when compared to recommended high index lenses');
                    return false;
                }
            }
        }
        if (jQuery("#chk_pd_frame").is(':checked')) {

        } else {
            if (this.get('pd', '1') === 0) {
                logList.push('Prescription data must have PD value.');
            }
        }

        if (rx.get('usage') === this.labels('bifocal') || rx.get('usage') === this.labels('nolinebifocal') || rx.get('usage') === this.labels('progressive')) {
            if (this.get('od', 'add') === 0 && this.get('os', 'add') === 0) {
                logList.push("Prescription data must have OD ADD and OS ADD value");
            }

            if (this.get('od', 'add') === 0) {
                logList.push("Prescription data must have OD ADD value");
            }

            if (this.get('os', 'add') === 0) {
                logList.push("Prescription data must have OS ADD value");
            }
        }

        if (this.get('od', 'cylinder') !== 0) {
            if (this.get('od', 'axis') === 0) {
                logList.push('Prescription data must have an Axis value if Cylinder value is different then 0.00');
            }
        }
        if (this.get('os', 'cylinder') !== 0) {
            if (this.get('os', 'axis') === 0) {
                logList.push('Prescription data must have an Axis value if Cylinder value is different then 0.00');
            }
        }

        if (jQuery("#chk_pd_frame").is(':checked')) {

        } else {
            if (this.get('pd', '1', false) === "none") {
                logList.push('PD cannot be empty.');
            }

            if (this.get('pd', '1', false) !== "none" && jQuery("#chk_pd_2").is(':checked') && this.get('pd', '2') === 0) {
                logList.push('Prescription data must have PD value (Left Eye)');
            }
        }

        if (rx.package.isPrismInUse()) {
            if ((this.get('od', 'vp') === 0) && (this.get('os', 'vp') === 0) && (this.get('od', 'hp') === 0) && (this.get('os', 'hp') === 0)) {
                logList.push('Prism option is selected, but no prism values are specified.');
            }

            if
            (
                this.get('od', 'vp') !== 0 && this.get('od_vp', 'basedirection', false) === 'n/a'
                ||
                this.get('od', 'vp') === 0 && this.get('od_vp', 'basedirection', false) !== 'n/a'
                ||
                this.get('os', 'vp') !== 0 && this.get('os_vp', 'basedirection', false) === 'n/a'
                ||
                this.get('os', 'vp') === 0 && this.get('os_vp', 'basedirection', false) !== 'n/a'
                ||
                this.get('od', 'hp') !== 0 && this.get('od_hp', 'basedirection', false) === 'n/a'
                ||
                this.get('od', 'hp') === 0 && this.get('od_hp', 'basedirection', false) !== 'n/a'
                ||
                this.get('os', 'hp') !== 0 && this.get('os_hp', 'basedirection', false) === 'n/a'
                ||
                this.get('os', 'hp') === 0 && this.get('os_hp', 'basedirection', false) !== 'n/a'
            ) {
                logList.push('OD / OS Prism values are not complete. Both Prism Value and Direction are required.');
            }
        }

        logList.map(function (value, index) {
            logList[index] = '<li>' + value + '</li>';
        });

        if (logList.length !== 0) {
            throw new Error('<ul>' + logList.join('') + '</ul>');
        }

        return true;
    },
    /* update view of seg oc div */
    "segOC_toggle": function () {
        if (rx.get('usage') === rx.usage.labels('progressive')) {
            jQuery("#SegOC").slideDown(); //show
        } else {
            jQuery("#SegOC").slideUp(); //hide
        }
        this.segOC_sub_toggle();
    },
    /* update view of seg oc substep div */
    "segOC_sub_toggle": function () {
        if (jQuery("#chk_segoc").is(':checked')) {
            jQuery("#substep_segoc").slideDown();
        } else {
            jQuery("#substep_segoc").slideUp();
        }
    },
    "default_PD_values": function () {

        /* if single vision set pd to 63 */
        if (rx.get('usage') === rx.usage.labels('singleVisionReading') ||
            rx.get('usage') === rx.usage.labels('singleVisionDistance')) {
            if (jQuery('#pd_1 option:selected').val() == 0) {
                jQuery('#pd_1').val('63').change();
            }
            if (jQuery('#pd_2 option:selected').val() == 0) {
                jQuery('#pd_2').val('63').change();
            }
        } else {
            /*
            if ( jQuery('#pd_1 option:selected').val() == 63 ){
                jQuery('#pd_1').val('0').change();
            }
            if ( jQuery('#pd_2 option:selected').val() == 63 ){
                jQuery('#pd_2').val('0').change();
            }*/
        }
    },
    /* update view of pd from frame */
    "framePD_toggle": function () {
        if (jQuery("#order_ftype").text() == 'Your Frames') {
            jQuery("#framePD").slideDown(); //show
        } else {
            jQuery("#framePD").slideUp(); //hide
            $("#chk_pd_frame").prop('checked', false);
        }
        this.pd_toggle();
    },
    /* update view of pd from frame */
    "pd_toggle": function () {
        if (jQuery("#chk_pd_frame").is(':checked')) {
            jQuery("#pd").slideUp();
        } else {
            jQuery("#pd").slideDown();
        }
    },
    "anotherPD": function () {
        //console.info(arguments.callee.name);
        if (jQuery("#chk_pd_2").is(':checked')) {
			  jQuery('#pd_2').val(0);
            jQuery("#pd_spare").html(jQuery("#pd_1").html());
            jQuery("#pd_1").html(jQuery("#pd_2").html());
            jQuery("#div_pd_2").show();
            jQuery("#div_pd_606").hide();
        } else {
            jQuery("#div_pd_2").hide();
            jQuery("#div_pd_606").show();
            jQuery("#pd_1").html(jQuery("#pd_spare").html());
        }
    },
    "showPrism": function () {
        //console.info(arguments.callee.name);
        if (jQuery("#prism").is(':checked')) {
            jQuery("#container_prism").show();
            rx.show('prism', 'Prism', new Price(rx_params.fees.prism_fee));
            rx.price.set('prism', parseFloat(rx_params.fees.prism_fee));
        } else {
            jQuery("#container_prism").hide();
            rx.reset('prism');
            rx.price.set('prism', 0);
        }
        rx.fee.hideCoating();
        rx.fee.hideMaterial();
    },
    "parseRx": function () {
        //console.info(arguments.callee.name);
        var parseResult = {};

        if (rx.prescription.get('d', 'mode', false) === 'fashion') {
            parseResult.fashion = 'Fashion Lenses';
        }
        parseResult.od_sphere = rx.prescription.get('od', 'sphere', false);
        parseResult.od_cylinder = rx.prescription.get('od', 'cylinder', false);
        parseResult.od_axis = rx.prescription.get('od', 'axis', false);
        parseResult.od_add = rx.prescription.get('od', 'add', false);
        parseResult.os_sphere = rx.prescription.get('os', 'sphere', false);
        parseResult.os_cylinder = rx.prescription.get('os', 'cylinder', false);
        parseResult.os_axis = rx.prescription.get('os', 'axis', false);
        parseResult.os_add = rx.prescription.get('os', 'add', false);
        parseResult.checked_segoc = !!jQuery("#chk_segoc").is(':checked');
        parseResult.od_segoc = rx.prescription.get('od', 'segoc', false);
        parseResult.os_segoc = rx.prescription.get('os', 'segoc', false);
        parseResult.use_frame_pd = !!jQuery("#chk_pd_frame").is(':checked');
        parseResult.pd_1 = rx.prescription.get('pd', '1', false);
        parseResult.chk_pd_2 = !!jQuery("#chk_pd_2").is(':checked');
        parseResult.pd_2 = rx.prescription.get('pd', '2', false);
        parseResult.prism = !!rx.package.isPrismInUse();
        parseResult.od_vp = rx.prescription.get('od', 'vp', false);

        parseResult.od_vp_basedirection = rx.prescription.get('od', 'vp_basedirection', false);
        parseResult.od_hp = rx.prescription.get('od', 'hp', false);
        parseResult.od_hp_basedirection = rx.prescription.get('od', 'hp_basedirection', false);
        parseResult.os_vp = rx.prescription.get('os', 'vp', false);
        parseResult.os_vp_basedirection = rx.prescription.get('os', 'vp_basedirection', false);
        parseResult.os_hp = rx.prescription.get('os', 'hp', false);
        parseResult.os_hp_basedirection = rx.prescription.get('os', 'hp_basedirection', false);

        return parseResult;
    },
    "getPdHtml": function (rxData) {
        if (!rxData) {
            rxData = this.parseRx();
        }
        var content = '';

        if (!rxData.chk_pd_2) {
            var pd_val = rxData.pd_1;
            if (rxData.use_frame_pd) {
                pd_val = 'From Frame';
            }
            content += '<div class="info-title-pd">' +
                '<span class="info-cart-title">PD: </span>' +
                '<span class="info-cart-value">' + pd_val + '</span>' +
                '</div>';
        }

        return content;
    },
    "getRightEyeHtml": function (rxData) {
        if (!rxData) {
            rxData = this.parseRx();
        }
        var content = '';
        content = content + '<div class="right-eye-od"><span class="info-title">Right Eye (OD)</span>';
        content = content + '<div class="info-cart">';
        content = content + '<div data-name="od_sphere">' +
            '<span class="info-cart-title">Sphere:</span>' +
            '<span class="info-cart-value">' + rxData.od_sphere + '</span>' +
            '</div>';
        content = content + '<div data-name="od_cylinder">' +
            '<span class="info-cart-title">Cylinder:</span>' +
            '<span class="info-cart-value">' + rxData.od_cylinder + '</span>' +
            '</div>';
        content = content + '<div data-name="od_axis">' +
            '<span class="info-cart-title">Axis:</span>' +
            '<span class="info-cart-value">' + rxData.od_axis + '</span>' +
            '</div>';
        content = content + '<div data-name="od_add">' +
            '<span class="info-cart-title">Add:</span>' +
            '<span class="info-cart-value">' + rxData.od_add + '</span>' +
            '</div>';
        if (rxData.chk_pd_2) {
            var pd_val = rxData.pd_1;
            if (rxData.use_frame_pd) {
                pd_val = 'From Frame';
            }
            content += '<div class="info-title-pd1">' +
                '<span class="info-cart-title">PD: </span>' +
                '<span class="info-cart-value">' + pd_val + '</span>' +
                '</div>';
        }

        if (rxData.checked_segoc && rxData.od_segoc != false) {
            content += '<div class="info-title-segoc_1">' +
                '<span class="info-cart-title">Seg/OC: </span>' +
                '<span class="info-cart-value">' + rxData.od_segoc + '</span>' +
                '</div>';
        }


        if (rxData.prism) {
            content = content + '<span class="rx-product-header-name">Prism</span>';

            content = content + '<div data-name="od_prism">' +
                '<span class="info-cart-title">Vertical:</span>' +
                '<span class="info-cart-value">' + rxData.od_vp + '</span>' +
                '</div>';
            content = content + '<div data-name="od_vertical_base_direction">' +
                '<span class="info-cart-title">Base Direction:</span>' +
                '<span class="info-cart-value">' + rxData.od_vp_basedirection + '</span>' +
                '</div>';
            content = content + '<div data-name="od_horizontal_prism">' +
                '<span class="info-cart-title">Horizontal:</span>' +
                '<span class="info-cart-value">' + rxData.od_hp + '</span>' +
                '</div>';
            content = content + '<div data-name="od_horizontal_base_dDirection">' +
                '<span class="info-cart-title">Base Direction:</span>' +
                '<span class="info-cart-value">' + rxData.od_hp_basedirection + '</span>' +
                '</div>';
        }
        content = content + '</div>';
        content = content + '</div><!--info-cart-->';
        return content;
    },
    "getLeftEyeHtml": function (rxData) {
        if (!rxData) {
            rxData = this.parseRx();
        }
        var content = '';
        content = content + '<div class="left-eye-os"><span class="info-title">Left Eye (OS)</span>';
        content = content + '<div class="info-cart">';
        content = content + '<div data-name="os_sphere">' +
            '<span class="info-cart-title">Sphere:</span>' +
            '<span class="info-cart-value">' + rxData.os_sphere + '</span>' +
            '</div>';
        content = content + '<div data-name="os_cylinder">' +
            '<span class="info-cart-title">Cylinder:</span>' +
            '<span class="info-cart-value">' + rxData.os_cylinder + '</span>' +
            '</div>';
        content = content + '<div data-name="os_axis">' +
            '<span class="info-cart-title">Axis:</span>' +
            '<span class="info-cart-value">' + rxData.os_axis + '</span>' +
            '</div>';
        content = content + '<div data-name="os_add">' +
            '<span class="info-cart-title">Add:</span>' +
            '<span class="info-cart-value">' + rxData.os_add + '</span>' +
            '</div>';
        if (rxData.chk_pd_2) {
            var pd_val = rxData.pd_2;
            if (rxData.use_frame_pd) {
                pd_val = 'From Frame';
            }
            content += '<div class="info-title-pd2">' +
                '<span class="info-cart-title">PD: </span>' +
                '<span class="info-cart-value">' + pd_val + '</span>' +
                '</div>';
        }

        if (rxData.checked_segoc && rxData.os_segoc !== false) {
            content += '<div class="info-title-segoc_2">' +
                '<span class="info-cart-title">Seg/OC: </span>' +
                '<span class="info-cart-value">' + rxData.os_segoc + '</span>' +
                '</div>';
        }


        if (rxData.prism) {
            content = content + '<span class="rx-product-header-name">Prism</span>';
            content = content + '<div data-name="os_prism">' +
                '<span class="info-cart-title">Vertical:</span>' +
                '<span class="info-cart-value">' + rxData.os_vp + '</span>' +
                '</div>';
            content = content + '<div data-name="os_vertical_base_direction">' +
                '<span class="info-cart-title">Base Direction:</span>' +
                '<span class="info-cart-value">' + rxData.os_vp_basedirection + '</span>' +
                '</div>';
            content = content + '<div data-name="os_od_horizontal_prism">' +
                '<span class="info-cart-title">Horizontal:</span>' +
                '<span class="info-cart-value">' + rxData.os_hp + '</span>' +
                '</div>';
            content = content + '<div data-name="os_horizontal_base_dDirection">' +
                '<span class="info-cart-title">Base Direction:</span>' +
                '<span class="info-cart-value">' + rxData.os_hp_basedirection + '</span>' +
                '</div>';
        }
        content = content + '</div>';


        content = content + '</div>';
        return content;
    },
    "getRX": function () {
        var rxData = rx.prescription.parseRx();
        var content = '';
        if (rx.prescription.get('d', 'mode', false) === 'fashion') {
            content = '<span class="info-title">Fashion Lenses</span>';
            return content;
        }

        content += rx.prescription.getPdHtml(rxData);
        content += rx.prescription.getRightEyeHtml(rxData);
        content += rx.prescription.getLeftEyeHtml(rxData);

        return content;
    },
    "parseLenses": function () {
        //console.info(arguments.callee.name);
        var parseResult = {};

        parseResult.ltype = rx.prescription.parseLensesPtype();
        parseResult.lpurpose = rx.purpose;
        parseResult.ldistance = rx.distance;
        parseResult.lpremium = rx.premium;
        parseResult.ltint = rx.get('tint');
        parseResult.ltint_option = rx.get('tint_option');
        parseResult.lpackage = rx.get('package');
        parseResult.lcoating = rx.get('coating');
        parseResult.enhanceAccuracy = !!rx.get('easyclean');
        parseResult.lrush = !!rx.get('rush');
        parseResult.lrush_3day = !!rx.get('rush_3day');
        parseResult.diamond = !!rx.get('diamond');
        parseResult.comment = rx.get('comment');

        return parseResult;
    },
    "getLenses": function () {
        var lenses = rx.prescription.parseLenses();

        var contentLenses = '';
        var divPackage = '';

        if (jQuery('#rx_package').val() === 'ok') {
            contentLenses = contentLenses + '<div class="info-title rp_title" style="font-weight: 600;font-size: 1.2em;">Recommended Package</div>';
        } else {
            contentLenses = contentLenses + '<div class="info-title cbc_title" style="font-weight: 600;font-size: 1.2em;">Customized By customer</div>';
        }

        if (lenses.ltint_option === 'groupon') {
            contentLenses = contentLenses + '<div class="info-title rp_details">Single Vision: Impact Resistant</div>';
            contentLenses = contentLenses + '<div class="info-title rp_details">PC Advanced</div>';
            contentLenses = contentLenses + '<div class="info-title rp_details">Premium Anti-Glare</div>';
        } else {

            if (lenses.ltype != 'Fashion Lenses' && lenses.ltype != '') {
                contentLenses = contentLenses + '<div class="info-title">' + lenses.ltype + '&nbsp;&nbsp;<a href="#" class="rx_a_toggle" onClick="$(this).children(\'div\').toggle(); return false;"   style="color: #aaa; font-size: 0.8em;">RX Details &gt;&gt;<div style="display: none; border-left: 1px solid #ccc; padding-left: 20px;"><br/>' + this.getRX() + '<br /></div><br /></a>';
            } else {
                contentLenses = contentLenses + '<div class="info-title">' + lenses.ltype + '</div>';
                contentLenses = contentLenses + '<div class="info-title">' + lenses.lpurpose + '</div>';
                contentLenses = contentLenses + '<div class="info-title">' + lenses.ldistance + '</div>';
                contentLenses = contentLenses + '<div class="info-title">' + lenses.lpremium + '</div>';
                contentLenses = contentLenses + '<div class="info-title">' + lenses.ltint + ' ' + lenses.ltint_option + '</div>';
            }

            divPackage = divPackage + '<div class="info-title">' + lenses.lpurpose + '</div>';
            divPackage = divPackage + '<div class="info-title">' + lenses.ldistance + '</div>';
            divPackage = divPackage + '<div class="info-title">' + lenses.lpremium + '</div>';
	    if (lenses.ltint.match('^Xtra Active')) {
            	divPackage = divPackage + '<div class="info-title">' + lenses.ltint + ' Gray</div>';
	    } else {
            	divPackage = divPackage + '<div class="info-title">' + lenses.ltint + ' ' + lenses.ltint_option + '</div>';
	    }

            // check additional icons
            var trans_icon = '';
            if (rx.get('tint').match('^Transitions') && !rx.get('tint').match('DriveWear') && !rx.get('tint').match('Vantage')) {
                trans_icon = '<img src="/content/plugins/rx/assets/image/Logo_Transition.jpg" style="margin:0; padding:0; margin-top: 5px;"/>';
            }

            if (rx.get('tint').match('DriveWear')) {
                trans_icon = '<img src="/content/plugins/rx/assets/image/Transitions_DRIVEWEAR_color.png" style="margin:0; padding:0; margin-top: 5px;"/ >';
            }

            if (rx.get('tint').match('polarized')) {
                trans_icon = '<img src="/content/plugins/rx/assets/image/transitions-xtraactive-polarized.jpg" style="margin:0; padding:0; margin-top: 5px;"/>';
            }
			  if (rx.get('tint').match('Vantage')) {
                trans_icon = '<img src="/content/plugins/rx/assets/image/transitions_vantage.png" style="margin:0; padding:0; margin-top: 5px;"/>';
            }

            if (rx.get('tint').match('Infinite')) {
                trans_icon = '<img src="/content/plugins/rx/assets/image/infinite_gray.png" style="margin:0; padding:0; margin-top: 5px;"/>';
            }

            if (rx.get('tint').match('^Blue ')) {
                trans_icon = '<img src="/content/plugins/rx/assets/image/BlueArmor.jpg" style="margin:0; padding:0; margin-top: 5px;"/>';
            }

            if (rx.get('tint').match('^polarized')) {
                trans_icon = '<img src="/content/plugins/rx/assets/image/NuPolar.jpg" style="margin:0; padding:0; margin-top: 5px;"/>';
            }


            divPackage = divPackage + '<div class="info-title"><br/><span><b>Included Premium Features:</b></span><br/>' + trans_icon + '<ul id="c_cart_f" style="margin: 0; padding: 0; list-style-type: none; line-height: 18px;"><li id="c_anti_glare_cart">Premium Anti-Glare</li><li id="c_easy_clean_cart">Easy Clean Lenses</li><li id="c_uv_cart">U.V. Protection</li><li id="c_scratch_cart">Scratch Protection</li></ul></div><br/>';


            var premiumPackage = '<div class="info-title"><br/><span><b>Included Premium Features:</b></span><br/>' + trans_icon + '<ul id="c_cart_f" style="margin: 0; padding: 0; list-style-type: none; line-height: 18px;"><li id="c_anti_glare_cart">Premium Anti-Glare</li><li id="c_easy_clean_cart">Easy Clean Lenses</li><li id="c_uv_cart">U.V. Protection</li><li id="c_scratch_cart">Scratch Protection</li></ul></div><br/>';

            if (rx.diamond.titleDiamond() !== undefined) {
                divPackage = divPackage + '<div class="info-title" id="c_diamond_cart">' + rx.diamond.titleDiamond() + '</div>';
                divPackage = divPackage + '<div class="info-title">' + rx.resultElement.getTitle('diamond_warranty') + ': ' + new Warranty(2, 'year').format() + '</div>';
            }

            var lens_price = jQuery('#lens_price').val();

            if (lenses.ltype != 'Fashion Lenses' && lenses.ltype != '' && lenses.lpackage != 'Demo Lenses' && (lens_price != 0.00)) {
                contentLenses = contentLenses + '<div class="info-title">' + lenses.lpackage + '&nbsp;&nbsp;<a href="#" class="package_a_toggle" onClick="$(this).children(\'div\').toggle(); return false;" style="color: #aaa; font-size: 0.8em;">Packages Details &gt;&gt;<div style="color: #222 !important; padding-left: 20px; border-left: 1px solid #ccc;"><br/>' + divPackage + '<br/></div></a></div>';
            } else if (lenses.lpackage == 'Demo Lenses') {
                contentLenses = contentLenses + '<div class="info-title">' + lenses.lpackage + '</div><br/>';
            } else {
                if (lens_price == '0.00') {
                    contentLenses = contentLenses + '<div class="info-title">' + lenses.lpackage + '</div><br/>';
                } else {
                    contentLenses = contentLenses + '<div class="info-title">' + lenses.lpackage + '</div><br/>' + premiumPackage + '<br/>';
                }
            }
        }

        var lcoating = lenses.lcoating;
        if (lenses.enhanceAccuracy) {
            lcoating = lcoating + this.labels('easyClean');
        }

        contentLenses = contentLenses + '<div class="info-title lcoating">' + lcoating + '</div>';

        //fix 2019
        //contentLenses = contentLenses + '</div> <!-- div close Fix -->';

        if (lenses.lrush) {
            var rush_info_title = 'NEXT DAY RUSH SERVICE';
            if (jQuery("#order_ftype").text() == 'Your Frames') {
                var rush_info_title = 'TWO-WAY RUSH SERVICE';
            }
            if (lenses.ltype == 'Bifocal' || lenses.ltype == 'No Line Bifocal') {
                var rush_info_title = 'NEXT DAY RUSH SERVICE';
            }

            contentLenses = contentLenses + '<br/><div class="info-title c_rush_cart" id="c_rush_cart">' + rush_info_title + '</div>';
        }
        if (lenses.lrush_3day) {
	    console.log("rushes 3 days selected");
            var rush_info_title = '3-4 DAYS GUARANTEED';
            contentLenses = contentLenses + '<br/><div class="info-title c_rush_cart" id="c_rush_cart">' + rush_info_title + '</div>';
        }


        if (lenses.comment != "") {
            var comm = wordwrap(lenses.comment, 50, '<br/>');
            contentLenses = contentLenses + '<br/><div class="info-title rx_comments" id="comment">Comments: ' + comm + '</div>';
        }

        return contentLenses;
    },
    "parseLensesPtype": function () {

        //console.info(arguments.callee.name);
        var ltype = rx.get('usage');
        if (ltype === this.labels('near') || ltype === this.labels('distance')) {
            ltype = this.labels('singleVision');
        }
        if (rx.get('freeform') === 1) {
            ltype += this.labels('freeForm');
        }
        if (rx.get('impact') === 1) {
            ltype += this.labels('impactResistant');
        }
        return ltype;
    },
    "labels": function (name) {
        var _elements = {
            'near': 'Near',
            'easyClean': '; Easy Clean',
            'singleVision': 'Single Vision',
            'freeForm': '; Free Form',
            'impactResistant': ': Impact Resistant',
            'distance': 'Distance',
            'bifocal': 'Bifocal',
            'nolinebifocal': 'No Line Bifocal',
            'progressive': 'Progressive'
        };
        return rx.label(name, _elements)
    }
};


/**step-250 PREMIUM*/
Rx.prototype.premium = {
    "checked": function () {
        if (rx.get('premium') != '') {
            return rx.get('premium');
        }
        return false;
    },
    "set": function (value) {
        if (value !== "" && value !== undefined) {
            var premiuminfo = value.split('#');
            var premium_value = premiuminfo[0];
            var price = premiuminfo[1];
            var sale_price = false;
            if (premiuminfo.length > 2) {
                sale_price = premiuminfo[2];
                price = [price, sale_price];
            }
            //set data
            rx.set('premium', premium_value);
            //right collumn display
            rx.show('premium', this.toString(), new Price(price));
            //var frame_subtotal = parseFloat(jQuery("#frame_subtotal").val());
            //rx.price.set('total', frame_subtotal);
            rx.price.set('premium', price);
            return true;
        } else {
            rx.set('premium', '');
            //remove checked
            jQuery('#step250').find('.option_selected').removeClass('option_selected');
            rx.fee.hidePremium();
            return false;
        }
    },
    "labels": function (name) {
        var _elements = {
            'premium': 'Premium',
            'premium_plus': 'Premium plus',
            'custom': '-- to be defined 3 --'
        };
        return rx.label(name, _elements);
    },
    // get readable text
    "toString": function () {
        var _tmp = rx.premium.checked();
        if (_tmp != false)
            return _tmp.charAt(0).toUpperCase() + _tmp.slice(1);
        //return rx.premium.labels(_tmp);
        else {
            return '';
        }
    }
};

/**step-3 LENS TINT OPTION */
Rx.prototype.lensColor = {
    "changeTintColor": function (tintcolor, variety) {
        //console.info(arguments.callee.name);
        var tintlens = rx.get('tint');
        rx.set('tint_option', tintcolor);
        rx.fee.showTint(tintlens + ' ' + tintcolor, variety);

        if (variety === 'custom') {
            rx.package.init();
        }

	console.log("TINT: "+tintcolor);
        if ((tintcolor === 'Silver Mirror' || tintcolor === 'Gold Mirror' || tintcolor === 'Blue Mirror') && variety === 'package') {
	    
            rx.show('tint', "Polarized "+tintcolor, new Price(rx_params.fees.polarized_mirror_fee));
            //console.log('Set fee ' + parseFloat(rx_params.fees.polarized_mirror_fee));
            rx.price.set('tint', parseFloat(rx_params.fees.polarized_mirror_fee));
        }

        if ((tintcolor === 'Gray Xtra Active' || tintcolor === 'Brown Xtra Active') && variety === 'package') {
            rx.show('tint', 'Transitions ® ' + tintcolor, new Price(rx_params.fees.xtra_active_color_fee));
            console.log('Set fee ' + parseFloat(rx_params.fees.xtra_active_color_fee));
            rx.price.set('tint', parseFloat(rx_params.fees.xtra_active_color_fee));
        } else if ((tintcolor === 'Gray Xtra Active' || tintcolor === 'Brown Xtra Active') && variety === 'custom') {
            rx.show('tint', 'Transitions ® ' + tintcolor,
                new Price(rx_params.fees.xtra_active_color_fee + rx_params.fees.tint_lr_t[rx_params.opt_ind]));
            rx.price.set('tint',
                parseFloat(rx_params.fees.xtra_active_color_fee + rx_params.fees.tint_lr_t[rx_params.opt_ind]));
        }
    },
    "changeValue": function (value) {
        //console.info(arguments.callee.name, arguments);
        jQuery('#step4').hide();

        jQuery("#ltint").val(value);
        rx.set('tint_option', this.labels('noTint'));

        if (value === this.labels('clearLens')) {
            rx.reset('tint');
            rx.zero('tint_price');
            jQuery("#tint_tr").hide();
            jQuery("#container_lens_tint_color").hide();
            jQuery('#hr_tint_color').hide();
            rx.package.init();
        }
        if (value !== this.labels('lightResponsive')) {
            rx.fee.showTint(value);
        }

        jQuery('.lens_tint_color').hide();

        if (value.match('^Transitions')) {
            jQuery('.rx-extra-section_div_rx_ps2').show();
            jQuery('.rx-extra-section_div_rx_ps21').show();
            jQuery('.rx-extra-section_div_rx_ps40').show();
        }
    },
    "setColor": function (e) {
        //console.info(arguments.callee.name);
        var obj = e.target;
        var $this = jQuery(obj);
        var tint_color = $this.data('colorName');
        var tintlens = rx.get('tint');

        rx.set('tint_option', tint_color);
        rx.show('tint', tintlens + ' ' + tint_color);

        var og = jQuery(obj).data('group');
        jQuery("div[data-group='" + og + "']")
            .removeClass('border_tint_color')
            .addClass('selector_option');
        var chk_id = jQuery(obj).data('id');
        jQuery("div[data-id='" + chk_id + "']")
            .removeClass('selector_option')
            .addClass('border_tint_color');
    },
    "validate": function () {
        //console.info(arguments.callee.name);
        /*
        var tint = rx.get('tint');
        if (tint === "") {
            rx.alert('Please select lens color option');
            return false;
        }
        return true;
        /**/
    },
    "validateOptions": function () {
        //console.info(arguments.callee.name);
        /**/
        var tint = rx.get('tint');
        var tint_options = rx.get('tint_option');
        if (tint !== '' && tint !== this.labels('clearLens') && tint_options != 'No Tint') {
            if (tint_options === this.labels('noTint')) {
                rx.alert('Please select lens color option');
                return false;
            }
        }
        return true;
        /**/
    },
    "reset": function () {
        //console.info(arguments.callee.name);
        jQuery('#container_lens_tint_color')
            .hide()
            .html("");
        jQuery('#hr_tint_color').hide();
        jQuery("div[data-group='check_rdo_tint']")
            .removeClass('option_selected')
            .addClass('selector_option')
            .html("");
        rx.fee.hideTint();
    },
    "setLR": function (lr_option, color, variety) {
        //console.info(arguments.callee.name);
        rx.lensColor.changeValue(lr_option);
        rx.lensColor.changeTintColor(color, variety);
        jQuery('.rx-extra-section_div_rdo_t_lres').show();
    },
    "labels": function (name) {
        var _elements = {
            'noTint': 'No Tint',
            'clearLens': 'Clear Lens',
            'lightResponsive': 'Light Responsive'
        };
        return rx.label(name, _elements)
    }
};

/**step-4 LENS MATERIAL & THICKNESS */
Rx.prototype.lensPackage = {
    "setMaterial": function (value) {
        //console.info(arguments.callee.name);
        rx.fee.hideMaterial();
        if (value !== "") {
            var packageinfo = value.split('#');
        } else {
            rx.fee.hideMaterial();
            return false;
        }
        var single_material_fee = parseFloat(packageinfo[1]).toFixed(2);
        var package_name = packageinfo[0];
        rx.set('package', package_name);
        rx.price.set('material', single_material_fee);

        // Material(true, package_name, single_material_fee);
        rx.show('material', package_name, new Price(single_material_fee));
        rx.set('package', package_name);
    }
};

/**step-5 LENS COATING */
Rx.prototype.coatingOptions = {
    "setCoating": function (coatinginfo1) {
        //console.info(arguments.callee.name);
        if (coatinginfo1 !== "") {
            var coatinginfo = coatinginfo1.split('#');
        } else {
            rx.fee.hideCoating();
            return false;
        }
        var coating = coatinginfo[0];
        var fee = coatinginfo[1];
        rx.set('coating', coating);
        rx.price.set('coating', fee);


        jQuery('.wrap-free-packages').show().addClass('extras-on');
        rx.show('coating', coating, new Price(fee));

    },
    "easyClean": function () {
        //console.info(arguments.callee.name);
        if (rx.get('easyclean') == 0) {
            rx.set('easyclean', 1);
            jQuery('.wrap-free-packages').show().addClass('extras-on');
        } else {
            rx.fee.hideEasyClean();
            rx.set('easyclean', 0);
            //jQuery('.wrap-free-packages').hide();
            // jQuery('.wrap-free-packages').removeClass('extras-on');
        }
    }
};

/**step-6 REVIEW ORDER */
Rx.prototype.previewOrder = {
//    "rushClicked": function () {
//		if (rx.get('rush') === '') {
//            //hide rush
//            if (rx.get('rush_3day') != '') {
//                jQuery("#div_rush3day_check").click();
//            }
//            rx.set('rush', 1);
//        } else {
//            rx.fee.hideRush();
//            rx.set('rush', '');
//        }
        
        /*
 		jQuery("#div_notrush20_check").removeClass('option_selected')
		  if ( jQuery("#div_rush_check").hasClass('option_selected') !== true ){
				 rx.set('rush', 1);
				 jQuery('#div_rush_check').addClass('option_selected')
			 } else {
				 rx.set('rush', '');
				 rx.fee.hideRush();
				 jQuery("#div_rush_check").removeClass('option_selected')
				 jQuery('#div_notrush20_check').addClass('option_selected')
			 }*/
//        if (rx.get('rush') === '') {
//            //hide rush
//            if (rx.get('rush_3day') != '') {
//                jQuery("#div_rush3day_check").click();
//            }
//            rx.set('rush', 1);
//        } else {
//            rx.fee.hideRush();
//            rx.set('rush', '');
//        }

        /*
        //console.info(arguments.callee.name);
        if (rx.get('rush') === '') {
            //hide rush
            if (rx.get('rush_3day') != '') {
                rx.fee.hideRush3day();
                rx.set('rush_3day', '');
            }
            rx.set('rush', 1);
            jQuery("#div_rush3day_check").removeClass('option_selected');
            jQuery("#div_rush_check").addClass('option_selected');
        } else {
            rx.fee.hideRush();
            rx.set('rush', '');
            jQuery("#div_rush_check").removeClass('option_selected');
            jQuery("#div_rush3day_check").removeClass('option_selected');
        }
        */
    },
    "rush3dayClicked": function () {


//        if (rx.get('rush_3day') === '') {
//            //hide rush
//            if (rx.get('rush') != '') {
//                jQuery("#div_rush_check").click();
//            }
//            rx.set('rush_3day', 1);
//        } else {
//            rx.fee.hideRush();
//            rx.set('rush_3day', '');
//        }

        /*
        //console.info(arguments.callee.name);
        if (rx.get('rush_3day') === '') {

            //hide rush
            if (rx.get('rush') != '') {
                rx.fee.hideRush();
                rx.set('rush', '');
            }

            rx.set('rush_3day', 1);
            jQuery("#div_rush_check").removeClass('option_selected');
            jQuery("#div_rush3day_check").addClass('option_selected');
        } else {
            rx.fee.hideRush3day();
            rx.set('rush_3day', '');
            jQuery("#div_rush_check").removeClass('option_selected');
            jQuery("#div_rush3day_check").removeClass('option_selected');
        }
        */
    },
    "reviewOrder": function () {
        //console.info(arguments.callee.name);
        console.log('revieworder');

        if (jQuery("#frame_title").text() == 'Your Frames') {
	    jQuery("#rush3day").slideUp();
            jQuery("#rush3day").css("display", "none");
        } else {
	    console.log("show 3 days");
	    //jQuery("#rush3day").slideDown();
            //jQuery("#rush3day").css("display", "block");
        }


        if ((false === rx.lensColor.validate() || false === rx.lensColor.validateOptions())) {
            //if (rx.prescription.get('d', 'mode', false) === 'fashion' && (false === rx.lensColor.validate() || false === rx.lensColor.validateOptions())) {
            return false;
        }

        rx.fashion.reviewOrderHide();

        if (rx.prescription.get('d', 'mode', false) != 'fashion') {
            rx.step.cShowStep6();
            var check_package = rx.prescription.get('rx', 'package', false);
            if (check_package != '') {
                jQuery(".first_button:visible").attr("onClick", "rx.navigation.reset(6)");
            }
        } else {
            rx.fashion.reviewOrderHideShowNot();
        }

        var rxData = rx.prescription.parseRx();
        jQuery("#rx_placeholder").html(rx.prescription.getPdHtml(rxData));
        jQuery(".right-eye-review").html(rx.prescription.getRightEyeHtml(rxData));
        jQuery(".left-eye-review").html(rx.prescription.getLeftEyeHtml(rxData));

        rx.step.showStep6();
        $('html, body').animate({scrollTop: $('#step6').offset().top}, 400);
        $("#step6").get(0).scrollIntoView();
    },
    "showOrderSummary": function () {
        jQuery('#nav-progress').show();
        jQuery('#package_image').show();
        jQuery('#package_title').show();
        jQuery('#progress-step-name').show();
        jQuery('#package_data_header').html('<B>YOUR EYEGLASSES</B>');
        jQuery('.coupon_block').remove();
        /** for plugin promo-rx */
    }
};

/**step-7 */
Rx.prototype.package = {
    "hideRdoMaterial": function () {
        jQuery("div[data-group='rdo_material']").hide();
        jQuery("div[data-group='rdo_material_tile']").hide();
    },
    "getVal": function () {
        var rxtype = rx.get('usage');
        var lens = rx.get('tint');
        if (lens === this.labels('transitions_@')) {
            if (rx.get('tint_option').indexOf('Xtra') > 0) {
                lens = this.labels('transitionsExtraDark');
            } else {
                lens = this.labels('transitions');
            }
        }
        var od_sphere = rx.prescription.get('od', 'sphere', false);
        var od_cylinder = rx.prescription.get('od', 'cylinder', false);
        var os_sphere = rx.prescription.get('os', 'sphere', false);
        var os_cylinder = rx.prescription.get('os', 'cylinder', false);
        var impact = rx.get('impact');
        var premium = rx.get('premium');
        var rx_progressive_type = rx.get('purpose');
        var pricetoremove = 0;


        // if (rx.get('impact') == 1) {
        // 		pricetoremove += rx.price.get('impact');
        // }
        if (rx.get('premium') != '') {
            pricetoremove += rx.price.get('premium');
        }
        // var pfactor = jQuery('#pfactor').val();
        var trackingdis = 0;

        if (rxtype === this.labels('near')) {
            rxtype = this.labels('readingFocal');
        }

        return {
            rxtype: rxtype,
            lens: lens,
            od_sphere: od_sphere,
            od_cylinder: od_cylinder,
            os_sphere: os_sphere,
            os_cylinder: os_cylinder,
            impact: impact,
            premium: premium,
            pricetoremove: pricetoremove,
            trackingdis: trackingdis,
            rx_progressive_type: rx_progressive_type,
            strength: rx.prescription.strength()
        };
    },

    // 'selectedMaterial' : false,
    // "initMaterialSelectPopup": function (cb) {
    //     jQuery("#materialModalRx .button_true").off('click').on('click', function(){
    //         cb(jQuery(this).data('material'));
    //     });
    //     this.showMaterialSelectPopup();
    // },
    // "showMaterialSelectPopup": function () {
    //     jQuery(document).foundation();
    //     jQuery('#materialModalRx').foundation('open');
    // },

    "init": function (ptype) {
        var material = '';
        if (arguments.length == 2) {
            var cb = arguments[1];
        }
        if (arguments.length == 3) {
            cb = arguments[1];
            material = arguments[2];
        }
        rx.set('packagetype', ptype);
        this.hideRdoMaterial();

        var __ret = this.getVal();
        var rxtype = __ret.rxtype;
        var lens = __ret.lens;
        var od_sphere = __ret.od_sphere;
        var od_cylinder = __ret.od_cylinder;
        var os_sphere = __ret.os_sphere;
        var os_cylinder = __ret.os_cylinder;
        var impact = __ret.impact;
        var trackingdis = __ret.trackingdis;
        var premium = __ret.premium;
        var pricetoremove = __ret.pricetoremove;
        var rx_progressive_type = __ret.rx_progressive_type;
        if (ptype === 'preset') {
            var urlAction = 'rx_get_preset_packages';
            var targetDiv = "#step7";
        } else {
            urlAction = 'rx_get_packages';
            targetDiv = "#step4";
        }
        window.loader.start();
        jQuery.ajax({
            url: rx_params.url,
            type: 'GET',
            dataType: 'text',
            data: {
                action: urlAction,
                rxtype: rxtype,
                lens: lens,
                od_sphere: od_sphere,
                od_cylinder: od_cylinder,
                os_sphere: os_sphere,
                os_cylinder: os_cylinder,
                impact_resistant: impact,
                tracking: trackingdis,
                material: material,
                premium: premium,
                pricetoremove: pricetoremove,
                rx_progressive_type: rx_progressive_type,
                strength: __ret.strength
            },
            success: function (data) {
                jQuery(targetDiv).html(data);
                jQuery(targetDiv).foundation();
            },
            complete: function (data) {
                if (typeof cb === "function") {
                    cb();
                }
                window.loader.stop();
            }
        })
    },
    "reset": function () {
        jQuery("div[data-group='check_rdo_material']")
            .removeClass('option_selected')
            .addClass('selector_option')
            .html("");

        rx.fee.hideMaterial();
    },
    "clear": function () {
        rx.reset('easy_clean');
        rx.reset('uv');
        rx.reset('scratch');
        rx.fee.hideCoating();
        rx.fee.hideEasyClean();
        rx.fee.hideMaterial();
        rx.fee.hideTint();
    },
    "customizeClear": function () {
        jQuery('#include').html('');
        jQuery('.wrap-free-packages').hide();
        // jQuery('#include').removeClass('after-icon');
        jQuery('#rx_package').val('');
        jQuery('#step7').hide();
        jQuery('#rx_right_packages').hide();
        jQuery('.rx-packages').hide();
        jQuery('#rx_right').show();
        jQuery('#step4').hide(); //todo sometimes does not work
        jQuery('#nb_step7').hide();
        jQuery('#package_step6').hide();
        rx.diamond.hideDiamond();
    },
    "customize": function () {
        this.customizeClear();
        this.clear();
        rx.navigation.reset(3);
    },
    "initPackage": function (event) {
        var $this = jQuery(event.target).closest('.selector');
        var l2id = $this.data('l2id');
        var rxtype = $this.data('rxtype');
        var material = $this.data('material');
        var price = $this.data('price');
        var sale_price = $this.data('sale_price');
        var tint = $this.data('tint');
        var coating = $this.data('coating');
        var easy_clean = $this.data('easy_clean');
        var uv_protection = $this.data('uv_protection');
        var scratch_protection = $this.data('scratch_protection');
        var package_name = material;
        rx.package.clear();
        jQuery("#rx_package").val('ok');

        jQuery("#ltint").val(tint);

        var package_price = new Price(price);
        if (sale_price) {
            package_price.setSalePrice(sale_price);
        }

        rx.show('material', package_name, package_price);
        price = (sale_price ? [price, sale_price] : price);
        rx.price.set('material', price);

        rx.show('coating', coating);
        //rx.show('tint', tint);
        rx.show('uv');
        rx.show('scratch');
        rx.show('easy_clean');

        var frame_subtotal = parseFloat(jQuery("#frame_subtotal").val());
        rx.price.set('total', frame_subtotal);
        rx.set('package', package_name);

        if (tint.substr(0, 5) !== rx.package.labels('clear') && tint.substr(0, 5) !== rx.package.labels('blue')) {
            var tintlens = rx.get('tint');
            var tint_color = rx.get('tint_option');
            if (tint_color === rx.package.labels('noTint')) {
                tint_color = rx.package.labels('brown');
                //rx.set('tint', '');
                //rx.set('tint_option', '');
                //rx.set('tint_option', tint_color);
            }
            //rx.show('tint', tintlens + ' ' + tint_color);
            jQuery('.l2_select').hide();
            jQuery(l2id).show();
        } else {
            jQuery('.l2_select').hide();
        }
    },
    "reviewPreset": function () {
        //console.info(arguments.callee.name)
        if (rx.get('package') === "") {
            rx.alert('Please Select A Lens Package');
            return false;
        }
        console.log('Transition: ' + rx.get('tint'));
        if (rx.get('tint') == 'Transitions &reg;' || rx.get('tint') == 'Transitions®' || rx.get('tint') == 'Transitions ®') {
            if (rx.get('tint_option') == "No Tint") {
                rx.alert('Please Select a Tint Color');
                return false;
            }
        }
        if ((rx.get('tint')).includes('Polarized')) {
            if (rx.get('tint_option') == "No Tint") {
                rx.alert('Please Select a Tint Color');
                return false;
            }
        }

        if (rx.get('tint') == 'FL-41 Tint (Rose Color)') {
            rx.set('tint_option', '');
        }

        jQuery('#step7').hide();
        jQuery('#nb_step7').hide();

        rx.previewOrder.reviewOrder('preset');
    },
    "isPrismInUse": function () {
        return jQuery("#prism").is(':checked') ? 1 : 0;
    },
    "labels": function (name) {
        var _elements = {
            'transitions_@': 'Transitions®',
            'brown': 'Brown',
            'clear': 'Clear',
            'blue': 'Blue ',
            'near': 'Near',
            'readingFocal': 'Reading / Focal',
            'transitions': 'Transitions',
            'transitionsExtraDark': 'Transitions Extra Dark',
            'noTint': 'No Tint'
        };
        return rx.label(name, _elements)
    },
    "getStrength": function () {
        var __ret = this.getVal();
        return Math.max(
            Math.abs(__ret.od_sphere) + Math.abs(__ret.od_cylinder),
            Math.abs(__ret.os_sphere) + Math.abs(__ret.os_cylinder)
        );
    },
    "isSingleVision": function () {
        var rxtype = this.getVal().rxtype;
        return (rxtype === 'Single Vision Reading' || rxtype === 'Single Vision Distance');
    },
    "getAllData": function () {

        var parsedLenses = rx.prescription.parseLenses();
        var ParsedRx = rx.prescription.parseRx();
        var type = parsedLenses['ltype'];

        return {
            'product_id': jQuery("input[name='add-to-cart']").val(),
            'type': type,
            'lenses': parsedLenses,
            'rush': (!!parsedLenses['lrush']),
            'rush_price': rx.price.get('rush'),
            'rush_3day': (!!parsedLenses['lrush_3day']),
            'rush_3day_price': rx.price.get('rush_3day'),
            'lens_price': rx.price.get('lens'),
            'lens_price': rx.price.get('lens'),
            'total_price': jQuery('#total_price').val(),
            'date': (new Date()).toISOString(),
            'prescription': rx.prescription.parseRx(),
            'commment': rx.get('comment')
        };
    }
};

Rx.prototype.price = {
    'rx_form': '.rx-form-container',
    "get": function (element) {
        //console.info('Rx.price.' + arguments.callee.name);
        return parseFloat(jQuery('#' + element + '_price').val());
    },
    "getSave": function (element) {
        //console.info('Rx.price.' + arguments.callee.name);
        var sale_price = jQuery('#' + element + '_price').data('sale_price');
        var price = jQuery('#' + element + '_price').data('price');
        return parseFloat(sale_price > 0 ? price - sale_price : 0);
    },
    "set": function (element, value) {
        $element = jQuery('#' + element + '_price');
        var new_event = jQuery.Event('rx_price_set');

        new_event.element = element;
        new_event.jqueryElement = $element;
        new_event.old_price = $element.data('price');
        new_event.old_sale_price = $element.data('sale_price');

        if (Array.isArray(value)) {
            $element.val(parseFloat(value[1]).toFixed(2));
            $element.data('price', parseFloat(value[0]).toFixed(2));
            $element.data('sale_price', parseFloat(value[1]).toFixed(2));
        } else {
            $element.val(parseFloat(value).toFixed(2));
            $element.data('price', parseFloat(value).toFixed(2));
            $element.data('sale_price', 0);
        }

        new_event.new_price = $element.data('price');
        new_event.new_sale_price = $element.data('sale_price');
        jQuery(this.rx_form).trigger(new_event);
    },
    "getFactor": function (element) {
        return parseFloat(jQuery('#' + element + 'factor').val());
    },
    "getDiscount": function (lens_subtotal_final, frame_subtotal, rush) {
        var ldiscount = 0;
        var fdiscount = 0;

        if (this.getFactor('p') > 0 && this.getFactor('p') < 1) {
            ldiscount = lens_subtotal_final * (1 - this.getFactor('p'));
            lens_subtotal_final = lens_subtotal_final * this.getFactor('p');
        } else {
            ldiscount =
                this.getSave('type') +
                this.getSave('premium') +
                this.getSave('material') +
                this.getSave('coating') +
                this.getSave('tint') +
                this.getSave('easy_clean') +
                this.getSave('impact') +
                this.getSave('diamond') +
                this.getSave('enhance_rx_accuracy');
        }

        var rush_discount = this.getSave('rush');
        if (this.getFactor('f') > 0 && this.getFactor('f') < 1) {
            fdiscount = frame_subtotal / this.getFactor('f') * (1 - this.getFactor('f'));
        } else if (rx_cart_params.frame_regular_price) {
            fdiscount = parseFloat(rx_cart_params.frame_regular_price) - frame_subtotal;
        }
        var subtotal = lens_subtotal_final + frame_subtotal + rush;
        var discount = ldiscount + fdiscount + rush_discount;

        return {
            lens_subtotal_final: lens_subtotal_final,
            subtotal: subtotal,
            discount: discount
        };
    },
    "calculate": function () {
        var frame_subtotal = parseFloat(jQuery("#frame_subtotal").val());
        var lens_subtotal_final =
            this.get('prism') +
            this.get('type') +
            this.get('premium') +
            this.get('material') +
            this.get('coating') +
            this.get('tint') +
            this.get('easy_clean') +
            this.get('impact') +
            this.get('diamond') +
            this.get('enhance_rx_accuracy');

        var rush = this.get('rush');

        var __ret = this.getDiscount(lens_subtotal_final, frame_subtotal, rush);
        lens_subtotal_final = __ret.lens_subtotal_final;
        var subtotal = __ret.subtotal;
        var discount = __ret.discount;

        jQuery("#price_subtotal").html("$" + subtotal.toFixed(2).toString().replace(",", "."));
        if (discount > 0) {
            jQuery('#c_discount').show();
            jQuery("#price_discount").html("<span class='new-price'>$" + discount.toFixed(2).toString().replace(",", ".") + "</span>");
        } else {
            jQuery('#c_discount').hide();
            jQuery("#price_discount").html('');
        }
        this.set('lens', lens_subtotal_final);
        jQuery("#c_lens_subtotal").html("$" + lens_subtotal_final.toFixed(2).toString().replace(",", "."));
        jQuery('#total_price').val(subtotal);

        jQuery(this.rx_form).trigger('rx_calculate');
        return subtotal;
    },
    "format": function (p) {
        // console.info(arguments.callee.name);

        if (p === 0) {
            return "Free";
        }
        var reg = "$" + p.toFixed(2).toString().replace(",", ".");
        var result = '';
        if (jQuery("#pfactor").val() > 0 && jQuery("#pfactor").val() < 1) {
            var d = p * jQuery('#pfactor').val();
            result = "$" + d.toFixed(2).toString().replace(",", ".");
        } else {
            result = reg;
        }
        return result;
    }
};

Rx.prototype.show = function () {
    var element = arguments[0];
    if (arguments.length === 2) {
        if (arguments[1] === undefined) {
            var price = new Price(0.00);
        }
        var title = arguments[1];
    } else if (arguments.length === 1) {
        title = this.resultElement.getTitle(element);
        price = arguments[2];
    } else {
        title = arguments[1];
        price = arguments[2];
    }
    if (price === undefined) {
        price = new Price(0.00);
    }
    if (arguments[1] === false) {
        title = this.resultElement.getTitle(element);
    }
    this.resultElement.set(element, title, price);
};

Rx.prototype.reset = function (element) {
    jQuery("#c_" + element).html("");
};

Rx.prototype.zero = function (element) {
    jQuery("#" + element).val(0);
};

Rx.prototype.get = function (element) {
    return this.sourceElement.get(element);
};

Rx.prototype.set = function (name, value) {
    this.sourceElement.insertValue(name, value);
};

Rx.prototype.step = {
    'rx_form': '.rx-form-container',
    "set": function (number) {
        var new_event = jQuery.Event('rx_step_set');
        new_event.step_number = number;
        jQuery(this.rx_form).trigger(new_event);
    },
    "showStep6": function () {
        jQuery("#step6").show();
        var rx_form_container = jQuery('.rx-form-container');
        rx_form_container.trigger('rx_step_6_showed');

        // jQuery('#lpackage').val().match('^1.74') && rx.get('tint') === 'Blue Armor &reg;'

        if (jQuery('#lpackage').val().match('^Trivex') && rx.get('tint') === 'Blue Armor &reg;') {
            jQuery("#diamond").show();
            if (rx.get('tint') === 'Blue Armor &reg;') {
                jQuery("#div_diamond_check_label span").text("Upgrade to Blue Diamond Anti-Glare Coating and receive two years warranty on your lenses");
            } else {
                jQuery("#div_diamond_check_label span").text("Upgrade to Diamond Anti-Glare Coating and receive two years warranty on your lenses");
            }
            jQuery('#div_diamond_check_label span').prepend('<img src="/content/plugins/rx/assets/image/diamond-coatings-ito-coatings.png" alt="" class="diamond-logo-order">');
        } else {
            jQuery("#diamond").show();

            if (rx.get('tint') === 'Blue Armor &reg;') {
                jQuery("#div_diamond_check_label span").text("Upgrade to Blue Diamond Anti-Glare Coating and receive two years warranty on your lenses");
            } else {
                jQuery("#div_diamond_check_label span").text("Upgrade to Diamond Anti-Glare Coating and receive two years warranty on your lenses");
            }
            jQuery('#div_diamond_check_label span').prepend('<img src="/content/plugins/rx/assets/image/diamond-coatings-ito-coatings.png" alt="" class="diamond-logo-order">');
        }
    },
    "cShowStep6": function () {
        //console.info(arguments.callee.name);
        jQuery('#step5').hide();
        jQuery('#nb_step5').hide();
        jQuery('.rx-add-lens-package-top').hide();
        jQuery('#rx_right_packages').hide();
        jQuery('#package_step6').hide();
        jQuery('#step6').show();
        jQuery('#rx_right').show();
        rx.navigation.showNavControl(6);
        jQuery('.rush_placeholder').show();

        if(  rx.get('usage') === rx.usage.labels('singleVisionReading') ||
             rx.get('usage') === rx.usage.labels('singleVisionDistance')  
	      ){
             jQuery('#rush3day').show();
        }else{
            jQuery('#rush3day').hide();
        }

    }

};

/*
values
''
'landing_progressive'
*/
Rx.prototype.sourcePage = {
    "set": function (value) {
        if (value !== "" && value !== undefined) {
            rx.set('sourcePage', value);
        } else {
            rx.set('sourcePage', '');
            return false;
        }
        return true;
    },
    "get": function (value) {
        return rx.get('sourcePage');
    }
};

/* manage right collumn with price */
Rx.prototype.fee = {
    "getSingleEnhanceRxAccuracy": function () {
        //console.info(arguments.callee.name);
        return parseFloat(20.0000);
    },
    "getSingleImpactResistant": function () {
        return parseFloat(rx_params.fees.impact_resistant_fee);
    },
    "getSingleDiamond": function () {
        return parseFloat(rx_params.fees.diamond_fee);
    },
    "getSingleEasyClean": function () {
        return parseFloat(rx_params.fees.easy_clean_fee);
    },
    "getSingleRush": function () {
        var frame_subtotal = parseFloat(jQuery("#frame_subtotal").val());
        var frame_title = jQuery("#order_ftype").text();
        if (frame_title == 'Your Frames') {
            return parseFloat(rx_params.fees.your_frame_rush_fee);
        }
        return (frame_subtotal >= 150
                ? parseFloat(rx_params.fees.rush_service_ge150_fee)
                : parseFloat(rx_params.fees.rush_service_fee)
        );
    },
    "getSingleTint": function () {
        //console.info(arguments.callee.name);
        var tint = rx.get('tint');
        switch (tint) {
            case this.labels('clearLens'):
                var tint_fee = parseFloat(0.00);
                break;
            case this.labels('sunLensTint'):
                tint_fee = parseFloat(rx_params.fees.tint_sun_tint[rx_params.opt_ind]);
                break;
            case this.labels('photochromic'):
                tint_fee = parseFloat(rx_params.fees.tint_lr_p[rx_params.opt_ind]);
                break;
            case this.labels('transitions_@'):
                if (rx.get('tint_option').indexOf('Xtra') > 0) {
                    tint_fee = parseFloat(rx_params.fees.tint_lr_xtr_a[rx_params.opt_ind]);
                } else {
                    tint_fee = parseFloat(rx_params.fees.tint_lr_t[rx_params.opt_ind]);
                }
                break;
            case this.labels('Polarized'):
                tint_fee = parseFloat(rx_params.fees.tint_polarized[rx_params.opt_ind]);
                break;
            default:

                tint_fee = parseFloat(0.00);
        }
        return tint_fee;
    },
    "getSingleType": function (ptype) {
        var ptype_fee = 0;
        if (ptype === this.labels('singleVisionReading') || ptype === this.labels('singleVisionDistance')) {
            ptype_fee = rx_params.fees.usage_reading;
        } else if (ptype === "Bifocal") {
            ptype_fee = rx_params.fees.usage_bifocal;
        } else if (ptype == 'No Line Bifocal') {
	    ptype_fee = rx_params.fees.usage_nolinebifocal;	
        } else if (ptype === "Progressive") {
            ptype_fee = rx_params.fees.usage_progressive;
        }
        return ptype_fee;
    },

    /**
     * @deprecated
     */
    "hideEnhanceRxAccuracy": function () {
        clearCheck('enhance_rx_accuracy');
        rx.set('freeform', 0);
        rx.zero('enhance_rx_accuracy_price');
        rx.reset('enhance_rx_accuracy');
    },
    "hideImpactResistant": function () {
        //console.info(arguments.callee.name);
        rx.reset('impact_resistant');
        rx.zero('impact_price');

        jQuery("#impact_resistant").removeAttr('checked');
        rx.set('impact', 0);
    },
    "hideEasyClean": function () {
        rx.reset('easy_clean');
        rx.zero('easy_clean_price');
    },
    "hideRush": function () {
        rx.reset('rush');
        rx.zero('rush_price');
    },
    "hideRush3day": function () {
        rx.reset('rush');
        rx.zero('rush_price');
    },
    "hideTint": function () {
        //console.info(arguments.callee.name);
        rx.reset('tint');
        rx.zero('tint_price');
        rx.set('tint', '');
        rx.set('tint_option', 'No Tint');
    },
    "hideMaterial": function () {
        //console.info(arguments.callee.name);
        rx.reset('material');
        rx.zero('material_price');
    },
    "hidePremium": function () {
        rx.reset('premium');
        rx.zero('premium_price');
        rx.set('premium', '');
    },
    "hidePurpose": function () {
        rx.reset('purpose');
        rx.zero('purpose_price');
        rx.set('purpose', '');
    },
    "hideDistance": function () {
        rx.reset('distance');
        rx.zero('distance_price');
        rx.set('distance', '');
    },
    "hideCoating": function () {
        rx.reset('coating');
        rx.zero('coating_price');
        rx.set('coating', '');
    },
    "showEnhanceRxAccuracy": function (value) {
        var content = rx.resultElement.formatLineItem(this.labels('enhanceRXAccuracy'), new Price(value));
        jQuery("#c_enhance_rx_accuracy").html(content);
    },
    "showImpactResistant": function () {
        //console.info(arguments.callee.name);
        var fee = rx.fee.getSingleImpactResistant();
        rx.price.set('impact', fee);
        rx.show('impact_resistant', false, new Price(fee));
    },
    "showEasyClean": function () {
        var single_easy_clean_fee = this.getSingleEasyClean();
        rx.price.set('easy_clean', single_easy_clean_fee);
        rx.show('easy_clean', false, new Price(single_easy_clean_fee));
    },
    "showRush": function (value) {
        //console.info(arguments.callee.name);

        var single_rush_fee = this.getSingleRush();

        rx.price.set('rush', single_rush_fee);
        rx.show('rush', false, new Price(single_rush_fee));
    },
    "showRush3day": function (value) {
        //console.info(arguments.callee.name);

        var fee = rx_params.fees.rush_3day_service_fee;

        rx.price.set('rush', fee);
        rx.show('rush', "3-4 Days Guaranteed", new Price(fee));
    },
    "showDiamond": function (value) {
        var single_diamond_fee = this.getSingleDiamond();
        rx.price.set('diamond', single_diamond_fee);

        if (rx.get('tint') === 'Blue Armor &reg;') {
            rx.show('blue_diamond', false, new Price(single_diamond_fee));
        } else {
            rx.show('diamond', false, new Price(single_diamond_fee));
        }
        rx.show('diamond_warranty', rx.resultElement.getTitle('diamond_warranty'), new Warranty(2, 'year'));
    },
    "showTint": function (value, variety) {
        //console.info(arguments.callee.name);

        if (variety === 'custom') {
            var fee = this.getSingleTint(); // price custom
        } else {
            fee = 0;// price PACKAGE
        }
        rx.show('tint', value, new Price(fee));
        rx.price.set('tint', fee);
    },
    "showType": function (ptype_value) {
        //console.info(arguments.callee.name, arguments);
        var single_type_fee = this.getSingleType(ptype_value);
        var ptype_desc = rx.usage.getPtypeValue(ptype_value);
        /*
        if (rx.purpose.checked != false){
            ptype_desc +=  ' '+rx.purpose;
        }
        if (rx.distance.checked != false){
            ptype_desc +=  ' '+rx.distance;
        }
        */
        rx.price.set('type', single_type_fee);
        rx.show('ptype', ptype_desc, new Price(single_type_fee));
    },
    "showPurpose": function (ptype_value) {
        /*TO-DO  show purpose on right panel if needed */
    },
    "init": function (rx_form_container) {
        rx_form_container.on('rx_source_change', function (event) {
            var element_name = event.element_name;
            var element_value = event.element_value;
            if (element_value) {
                switch (element_name) {
                    case 'usage' :
                        rx.fee.showType(element_value);
                        break;
                    case 'purpose' :
                        /*rx.fee.showPurpose(element_value);*/
                        break;
                    case 'impact' :
                        rx.fee.showImpactResistant(element_value);
                        break;
                    case 'rush' :
                        rx.fee.showRush(element_value);
                        break;
                    case 'rush_3day' :
                        rx.fee.showRush3day(element_value);
                        break;
                    case 'diamond' :
                        rx.fee.showDiamond(element_value);
                        break;
                    case 'easyclean' :
                        rx.fee.showEasyClean(element_value);
                        break;
                    case 'freeform' :
                        rx.fee.showEnhanceRxAccuracy(element_value);
                        break;
                }
            } else {
                switch (element_name) {
                    // case 'usage' :
                    // rx.fee.hide(element_value);
                    // break;
                }
            }
        });
    },
    "labels": function (name) {
        var _elements = {
            'clearLens': 'Clear Lens',
            'sunLensTint': 'Sun Lens Tint',
            'photochromic': 'Photochromic',
            'transitions_@': 'Transitions®',
            'polarized': 'Polarized',
            'singleVisionReading': 'Single Vision Reading',
            'singleVisionDistance': 'Single Vision Distance',
            'enhanceRXAccuracy': 'Enhance RX Accuracy'
        };
        return rx.label(name, _elements)
    }
};

Rx.prototype.impactResistant = {
    "change": function () {
        //console.info(arguments.callee.name);
        if (rx.get('impact') == 0) {
            var fee = rx.fee.getSingleImpactResistant();
            rx.price.set('impact', fee);
            rx.set('impact', 1);
        } else {
            rx.fee.hideImpactResistant();
        }
    }
};

Rx.prototype.diamond = {
    "init": function (rx_form_container) {
        rx_form_container.on('click', '.selector', function () {
            if (jQuery('#step7').is(':visible')) {
                jQuery('.wrapper-diamond').show();
                rx.diamond.hideDiamond();
            } else {
                jQuery('.wrapper-diamond').hide();
            }
        });
    },
    "change": function () {
        if (rx.get('diamond') === '') {

            var fee = rx.fee.getSingleDiamond();

            jQuery(".content-diamond").addClass('active-diamond');
            jQuery(".title-upgrade i.fa-check ").css('display', "inline-block");
            rx.price.set('diamond', fee);
            rx.set('diamond', 1);
        } else {
            rx.diamond.hideDiamond();
        }
    },
    "hideDiamond": function () {
        jQuery(".content-diamond").removeClass('active-diamond');
        jQuery(".title-upgrade i.fa-check ").css('display', "none");
        rx.zero('diamond_price');
        rx.set('diamond', '');
        rx.reset('diamond');
        rx.reset('blue_diamond');
        rx.reset('diamond_warranty');
    },
    "titleDiamond": function () {
        if (rx.get('diamond')) {
            var title = '';
            if (rx.get('tint') === 'Blue Armor &reg;') {
                title = rx.resultElement.getTitle('blue_diamond');
            } else {
                title = rx.resultElement.getTitle('diamond');
            }
            return title;
        }
    }
};


Rx.prototype.comment = {
    "togle": function () {
        if (jQuery("#lcomment").is(":hidden")) {
            jQuery("#lcomment").show();
            jQuery("#commenttitle").addClass('active');
        } else {
            jQuery("#lcomment").hide();
            jQuery("#commenttitle").removeClass('active');
        }
    },
    "set": function () {
        rx.set('comment', wordwrap(jQuery("#comment").val(), 50, '<br/>\n'));
    },
};

Rx.prototype.fashion = {
    "reset": function () {
        //console.info(arguments.callee.name);
        jQuery('#step6').slideToggle();
        jQuery('#step3').slideToggle();
        jQuery('#nb_step6').hide();
        jQuery('#nb_step3').show();
        jQuery('#package_image').show();
        jQuery('#package_title').show();
        jQuery('#package_data_header').html('<B>YOUR EYEGLASSES</B>');
        jQuery('#progress-step-name').show();
        jQuery('#nav-progress').show();
        jQuery('.wrap-free-packages').hide();
    },
    "reviewOrderHide": function () {
        //console.info(arguments.callee.name);
        jQuery('#progress-step-name').hide();
        jQuery('.nav-progress').hide();
        jQuery('#package_image').hide();
        jQuery('#package_title').hide();
        jQuery('#package_data_header').html('<B>SELECTED OPTIONS</B>');
    },
    "reviewOrderHideShowNot": function () {
        //console.info(arguments.callee.name);
        jQuery('#step3').hide();
        jQuery('#nb_step3').hide();
        jQuery('.rush_placeholder').hide();
        jQuery('.description-review-order').hide();
        /*jQuery('#nb_step6').show();*/
        rx.navigation.showNavControl(6);
    }
};

Rx.prototype.navigation = {
    'rx_form': '.rx-form-container',
    'map': {
        '1': 'usage',
        '2': 'prescription'
    },
    "set": function (n) {
        rx.navigation.move(n, n + 1);
    },
    "move": function (n, nxt) {
        var _nxt = nxt;
        if (jQuery('#step' + nxt).is(':visible')) {
            _nxt = n;
        }
        //hide all steps
        jQuery("[id^='step']").slideUp();
        /* all visible steps get hidden */
        jQuery('#step' + _nxt).slideDown();
        this.setProgress(_nxt);

        this.showNavControl(_nxt);
    },
    /* display the correct nav controll refering to the step number and flow */
    "showNavControl": function (nxt) {//FIX FLOW

        // hide all menu (starting with nb_step
        jQuery("[id^='nb_step']").hide();
        var _nav = '#nb_step';

        switch (nxt) {
            case 1:
                if (rx.get('usage') === rx.usage.labels('progressive')) {
                    _nav = "#nb_step1_progressive";
                }
                break;
            case 150:
                if (rx.get('purpose') === rx.purpose.labels('office')) {
                    _nav = "#nb_step150_office";
                }
                break;
            case 2:
                if (rx.sourcePage.get() === 'landing_progressive') {
                    if (rx.get('purpose') === rx.purpose.labels('general')) {
                        _nav = "#nb_step2_landingProgressive_premium";
                    } else {
                        _nav = "#nb_step2_landingProgressive";
                    }
                } else if (rx.get('usage') === rx.usage.labels('progressive')) {
                    if (rx.get('purpose') === rx.purpose.labels('general')) {
                        _nav = "#nb_step2_progressive_premium";
                    } else if (rx.get('purpose') === rx.purpose.labels('office')) {
                        _nav = "#nb_step2_distance";
                    } else {
                        _nav = "#nb_step2_progressive";
                    }
                }
                break;
            case 6:
                if (rx.sourcePage.get() === 'landing_progressive') {
                    _nav = "#nb_step6_landingProgressive";
                }
                break;
            case 7:
                if (rx.get('purpose') === rx.purpose.labels('general')) {
                    _nav = "#nb_step7_premium";
                }
                break;
        }

        /* steps */
        switch (nxt) {
            case 1:
            case 150:
            case 170:
                jQuery('#rx_stepnavigator_1').addClass('activated');
                jQuery('#rx_stepnavigator_2').removeClass('activated');
                jQuery('#rx_stepnavigator_3').removeClass('activated');
                jQuery('#rx_stepnavigator_4').removeClass('activated');
                jQuery('#rx_stepnavigator_bar').width('25%');
                break;
            case 2:
                jQuery('#rx_stepnavigator_1').addClass('activated');
                jQuery('#rx_stepnavigator_2').addClass('activated');
                jQuery('#rx_stepnavigator_3').removeClass('activated');
                jQuery('#rx_stepnavigator_4').removeClass('activated');
                jQuery('#rx_stepnavigator_bar').width('50%');
                break;
            case 3:
            case 5:
            case 250:
            case 7:
                jQuery('#rx_stepnavigator_1').addClass('activated');
                jQuery('#rx_stepnavigator_2').addClass('activated');
                jQuery('#rx_stepnavigator_3').addClass('activated');
                jQuery('#rx_stepnavigator_4').removeClass('activated');
                jQuery('#rx_stepnavigator_bar').width('75%');
                break;
            case 6:
                jQuery('#rx_stepnavigator_1').addClass('activated');
                jQuery('#rx_stepnavigator_2').addClass('activated');
                jQuery('#rx_stepnavigator_3').addClass('activated');
                jQuery('#rx_stepnavigator_4').addClass('activated');
                jQuery('#rx_stepnavigator_bar').width('100%');
                break;
        }

        if (_nav == '#nb_step') {
            _nav += nxt;
        }

        var new_event = jQuery.Event('rx_navigation_set');
        new_event.step_number = nxt;
        new_event.step_object = rx[this.map[nxt]];
        new_event.step_naw = _nav;
        jQuery(this.rx_form).trigger(new_event);

        /*
        if(nxt === 1 && rx.get('usage') === rx.usage.labels('progressive') ){
            jQuery("#nb_step1_p").show();
        }else if(nxt === 2 && jQuery.inArray( jQuery("input[name='add-to-cart']").val() , lens_packages_id ) !== -1  ){
            jQuery("#nb_step2_landingProgressive").show();
        }else if(nxt === 2 && rx.get('usage') === rx.usage.labels('progressive') && rx.get('purpose') === rx.purpose.labels('general')   ){
            jQuery("#nb_step2_p_lt").show();
        }else if(nxt === 2 && rx.get('usage') === rx.usage.labels('progressive') && rx.get('purpose') === rx.purpose.labels('office')   ){
            jQuery("#nb_step2_d").show();
        }else if(nxt === 2 && rx.get('usage') === rx.usage.labels('progressive') ){
            jQuery("#nb_step2_p").show();
        }else if(nxt === 3 && ){
            jQuery("#nb_step3_p").show();
        }else{
            //default step menu
            jQuery('#nb_step' + nxt).show();
        }*/
    },
    "setProgress": function (Step) {

        switch (Step) {
            case 250:
                jQuery('#step250 .onsale').show();
                break;
        }

        //console.info(arguments.callee.name);
        var snames = [
            "LENS TYPE",
            "ENTER YOUR PRESCRIPTION",
            "LENS TINT OPTION",
            "LENS MATERIAL & THICKNESS",
            "LENS COATING",
            "REVIEW ORDER",
            ""
        ];

        switch (Step) {
            case 150:
                jQuery('#progress-step-name').show().text('I need lenses for:'); // PURPOSE -> changed to I need lenses for:
                break;
            case 170:
                jQuery('#progress-step-name').show().html("Please select your office size or the required distance at which you wish the lenses to have the best performance.<br>IMPORTANT: Increased distance will narrow the reading area.<br><br>");
                break;
            case 250:
                jQuery('#progress-step-name').show().text('LENS TYPE');
                break;
            default:
                break;
        }

        jQuery('#progress-step-name').show().text(snames[Step - 1]);
        var step_wrap = jQuery(".container.rx-form-container");

        function scrollToTop() {
            jQuery('html, body').animate({scrollTop: 0}, 500);
        }

        if (Step === 2 || Step === 3 || Step === 4 || Step === 5 || Step === 6 || Step === 7) {
            scrollToTop();
            jQuery("#div_rush_check").on("click", function () {
                if (jQuery("#div_rush_check").hasClass("option_selected")) {
                    setTimeout(function () {
                        jQuery("#div_rush_check").addClass("option_selected");
                    }, 0);

                } else if (!jQuery("#div_rush_check").hasClass("option_selected")) {
                    setTimeout(function () {
                        jQuery("#div_rush_check").removeClass("option_selected");
                    }, 0);
                }
            });
        }

        if (Step === 2) {
            rx.set('package', '');
            step_wrap.addClass("is-step-2");
            jQuery("#nb_step2").on('click', "button.last", function (e) {
                e.preventDefault();
                if (jQuery('#pd_1 option:selected').val() === 0) {
                    rx.alert('Please Select A Pupillary Distance');
                }
            });
            rx.prescription.segOC_toggle(); // show hide SEG/OC section
            rx.prescription.framePD_toggle(); // show hide PD from Frame
            rx.prescription.default_PD_values(); //set default pd values
        } else {
            step_wrap.removeClass("is-step-2");
        }
        if (Step === 3) {
            // jQuery(".selector_check").css({"top": 50 + "px"}); // perhaps this piece is not needed at all, nothing breaks
        }

        if (Step === 7) {
            jQuery('.rx-form-container').on('click', '.selector_check', function (e, Step) {
            });

            jQuery('#progress-step-name')
                .css('margin-bottom', 0)
                .css('margin', 0);

            jQuery('.rx-form-container').on('click', '.selector_option', function (e) {
                var $self = jQuery(e.target);
                jQuery("[data-group='tint_color']").removeClass("color_button_border");
                $self.removeClass("color_button_border");
                $self.addClass("color_button_border");
            });
        } else {
            jQuery('#progress-step-name')
                .css('margin-bottom', 20)
                .css('margin', 0.67 + 'em');
            jQuery(".container.rx-form-container").removeClass("is-step-7");
        }


        var new_event = jQuery.Event('rx_step_setprogress');
        new_event.step_number = Step;
        jQuery(this.rx_form).trigger(new_event);
    },
    "reset": function (n, source) {
        source = source || 0;
        var _n = n;
        var _source = source;
        if (_source === 0) {  //hack because sometime is passed step to go sometime is passed step-1 to go
            _source = n + 1;
        }

        switch (n) {
            case 7 :
                _source = 7;
                _n = 2;
                jQuery('#rx_package').val('');
                //this.move(_source,_n);
                break;
            case 8 :
                _source = 6;
                _n = 7;
                //this.move(_source,_n);
                break;
        }

        this.move(_source, _n);


        switch (n) {
            case 2:
                rx.lensColor.reset();
                jQuery('#rx_package').val('');
                jQuery('.wrap-free-packages').removeClass('extras-on').hide();

                break;
            case 3:
                rx.package.reset();
                break;
            case 5:
                rx.previewOrder.showOrderSummary();
                break;
            case 6:
                /*
                var check_package = rx.prescription.get('rx', 'package', false);
                if(check_package !== '') {
                    jQuery('#nb_step6').hide();
                    jQuery('#package_step6').show();
                }
				*/
                //jQuery('#rush3day').hide();
                break;
            case 250:
                jQuery('.onsale').show();
            case 7:
                rx.reset('uv');
                rx.reset('scratch');
                jQuery('#include').html('');
                jQuery('.wrap-free-packages').hide();
                jQuery('#package_step6').hide();
                jQuery('.onsale').hide(); // red sale triangle hide on back step
                jQuery("#rx_right").show();
                jQuery(".rx-add-lens-package-top").hide();
                jQuery("#rx_right_packages").hide();
                jQuery('#progress-step-name').show();

                rx.fee.hideMaterial();
                rx.fee.hideTint();
                rx.fee.hideCoating();
                rx.fee.hideEasyClean();
                rx.diamond.hideDiamond();
                //this.move(7, 2);
                break;
            case 8:
                rx.previewOrder.showOrderSummary();
                jQuery('#nb_step8').hide();
                //this.move(6, 7);
                break;
            /*default:
                if(source === 0){
                    this.move(n+1,n);
                }else{
                    this.move(source,n);
                };*/
        }

        /*
        switch (n) {
            case 7 :
                _source = 7;
                _n = 2;
                this.move(_source,_n);
                break;
            case 8 :
                _source = 6;
                _n = 7;
                this.move(_source,_n);
                break;
        }
        */
    },
    // "toCart": function () {
    //     //console.info(arguments.callee.name);
    //     SubmitRX();
    //     jQuery('.ong_cart').submit();
    // },
    "hideRx": function () {
        //console.info(arguments.callee.name);
        jQuery('.product--right-content').show();
        jQuery('.product-description-block').show();
        jQuery('.product--related-products').show();
        jQuery('.product--shipping-returns').show();
        jQuery('.onsale').show();
        jQuery('.images').show();
        jQuery('.summary').show();
        jQuery('#mirror_placeholder').show();
        jQuery('#order_type').show();
        jQuery('.related').show();
        jQuery("#get_lenses").html('');
        //    UI 12/01/18 show yotpo reviews for product page
        jQuery('.yotpo.yotpo-main-widget').show();
        jQuery('.secure-icons-wrap').removeClass('icons-RX');

        if (typeof checkFrameFitsMe === 'function') {
            checkFrameFitsMe();
        }
        var rx_form_container = jQuery('.rx-form-container');
        rx_form_container.trigger('rx-hidden');
    },
    "toPrescription": function () {
        jQuery("#rx_right").show();
        jQuery('#progress-step-name').hide();
        jQuery(".rx-packages").show();
        jQuery(".button-hide-container").show();
        jQuery(".rx-add-lens-package-top").show();
    }
};

Rx.prototype.alert = function (message) {
    var popup = jQuery("#alertError");
    if (popup.length) {
        popup.find(".popup-rx-text").html(message);
        popup.foundation("open");
    } else {
        alert(message);
    }
};

Rx.prototype.label = function (name, _elements) {
    if (!(name in _elements)) {
        console.error('_elements has no index - ', name);
        return;
    }
    if (jQuery("#order_ftype").text() == 'Your Frames' && name == "rush") {
        return "Two-Way Rush Service"
    }
    return _elements[name];
};


var rx = new Rx();

/** ------------ **/


function ToCart() {
    //console.info(arguments.callee.name);
    SubmitRX();
    jQuery('.ong_cart').submit();

    /* ajax add to cart if is lens to combine
    if(rx.sourcePage.get() == 'landing_progressive' ){
        //send lens to cart with ajax and redirect to homepage
        jQuery.ajax({
            type     : "POST",
            cache    : false,
            url      : jQuery('.ong_cart').attr('action'),
            data     : jQuery('.ong_cart').serialize(),
            success  : function(data) {
                //window.location.replace('/');
                window.location.href = "/";
            }
        });
    }else{
        jQuery('.ong_cart').submit();
    }
    */
}


/**
 * Combine frame with lenses
 * @author: Stefano Gattuso
 */
function CombineCart(e) {

    event.preventDefault();
    var $this = jQuery(e.target);
    var ltype = $this.data('ltype');
    var ong_color = rx.prescription.get('ong', 'color_select', false);

    if ((
        jQuery('#pa_color').length > 0) && (ong_color === null ||
        ong_color === '')) {
        rx.alert('Please select Frame color lens2');
        return false;
    }

    if (jQuery('div#woosvimain img').length) {
        var pimage = jQuery('div#woosvimain img').attr('src');
    } else if (jQuery(".attachment-shop_single").length) {
        pimage = jQuery(".attachment-shop_single:visible").attr('src');
    } else {
        pimage = jQuery('li[class=flex-active-slide]').children('img').attr('src');
    }

    var product_id = jQuery("input[name='add-to-cart']").val();
    var package_price = jQuery('#total_price').val();

    window.loader.start();
    jQuery.ajax({
        url: rx_params.url,
        method: 'post',
        async: false,
        data: {
            'action': 'combine_frame_lenses',
            'total_price': package_price,
            price: rx_cart_params.price,
            fprice: rx_cart_params.fprice,
            frame_regular_price: rx_cart_params.frame_regular_price,
            d_mode: ltype,
            description: jQuery('#p_desc').html(),
            product_id: product_id,
            pimage: pimage
        }
    }).done(function (response) {
        jQuery('.ong_cart').submit();
        window.loader.stop();
    });
}

/**
 *
 * @param e
 * @returns {boolean}
 */
function load_lens_options(e) {
    e.preventDefault();
    var $this = jQuery(e.target);
    var ltype = $this.data('ltype');
    var ong_color = rx.prescription.get('ong', 'color_select', false);
    if (ong_color == '') {
        var ong_color = $("#pa_color :selected").val();
    }
    console.log("Ong selected color: " + ong_color);
    if ((jQuery('#pa_color').length > 0) && (ong_color === null || ong_color === '')) {
        rx.alert('Please select Frame color lens');
        return false;
    }

    var pimage = '';

    if (jQuery('div#woosvimain img').length) {
        pimage = jQuery('div#woosvimain img').attr('src');
    } else if (jQuery(".attachment-shop_single").length) {
        pimage = jQuery(".attachment-shop_single:visible").attr('src');
    } else {
        pimage = jQuery('li[class=flex-active-slide]').children('img').attr('src');
    }

    var product_id = jQuery("input[name='add-to-cart']").val();

    window.loader.start();
    jQuery("#get_lenses").load(rx_params.url, {
        action: 'rx_form',
        type: 'GET',
        async: true,
        price: rx_cart_params.price,
        fprice: rx_cart_params.fprice,
        frame_regular_price: rx_cart_params.frame_regular_price,
        d_mode: ltype,
        description: jQuery('#p_desc').html(),
        product_id: product_id,
        pimage: pimage
    }, function () {
        jQuery('.yotpo-main-widget').hide();
        jQuery('.product--right-content.large-8.columns').hide();
        jQuery('.product-description-block').hide();
        jQuery('.product--related-products').hide();
        jQuery('.product--shipping-returns').hide();
        jQuery('.onsale').hide();

        jQuery('.images').hide();
        jQuery('.summary').hide();
        jQuery('.related').hide();
        jQuery('#order_type').hide();
        jQuery('#mirror_placeholder').hide();

        rx.navigation.showNavControl(1);
        window.loader.stop();
    });
}


jQuery(function ($, Step) {

    $("#order_rx, #order_fashion").on('click', load_lens_options);
    $("#combine").on('click', CombineCart);

    var $variation_form = $('body.single-product .summary.entry-summary .variations_form');
    $variation_form.on('show_variation', function (event, variation) {
        // set the variation description if the value is set
        rx_cart_params.price = variation.display_price;
        rx_cart_params.fprice = variation.display_price;
        rx_cart_params.frame_regular_price = variation.display_regular_price;
    });

    $variation_form
        /*.on('found_variation', function (event, variation) {
            $.each(variation.attributes, function (attribute, value) {
                if ( attribute === 'attribute_pa_color' ) {
                    jQuery('#ong_color_select').val(value);
                }
            });
        })*/
        .on('reset_data', function () {
            jQuery('#ong_color_select').val('');
        })
    ;

    jQuery(document).on('rx-loaded', function () {

        var rx_form_container = $('.rx-form-container');

        rx.diamond.init(rx_form_container);
        rx.fee.init(rx_form_container);
        setTimeout(function () {
            rx.price.calculate();
        }, 0);

        rx_form_container.on('click', '.rx-color-selector', rx.lensColor.setColor);
        rx_form_container.on('click', '.material-selector', rx.package.initPackage);

        var count_of_changes = 0;

        rx_form_container.on('rx_source_change', function (event) {
            count_of_changes++;
            if (count_of_changes === 1) {
                setTimeout(function () {
                    rx.price.calculate();
                    count_of_changes = 0;
                });
            }
        });

        // Modal window for packages in RX
        rx_form_container.on('click', '.rx-product-icon>img, .rx-product-icon>p', function (e) {
            e.preventDefault();
            // jQuery('#modalRXpackages').foundation('open');
            // console.log('click');

            var that = jQuery(this);
            var description = that.data('content');
            // console.log('description', description);
            var modal = jQuery('#modalRXpackages');
            var imgIcon = that.attr('src');
            modal.find('.icon-packages').html('<img src="' + imgIcon + '">');
            modal.find('.description-packages').html('<p>' + description + '</p>');
            modal.foundation('open');
        }); // open modal

        jQuery('#modalRXpackages a.close').on('click', function () {
            jQuery(this).parent().foundation('close');
        }); // close modal

        /** step 1,2,3,4 , 150 validation */
        rx_form_container.on('rx_step_set', function (event) {
            var step_number = event.step_number;
            switch (step_number) {
                case 1:
                    if (rx.usage.checked() === false) {
                        rx.alert('Please select your prescription type first.');
                        event.stopImmediatePropagation();
                    } else if (rx.get('usage') === rx.usage.labels('progressive') && rx.purpose.checked() === false) {
                        rx.alert('Please select a purpose first.');
                        event.stopImmediatePropagation();
                    } else if (rx.get('usage') === rx.usage.labels('progressive') && rx.get('purpose') === rx.purpose.labels('office') && rx.distance.checked() === false) {
                        rx.alert('Please select a distance first.');
                        event.stopImmediatePropagation();
                    }

                    break;
                case 150:
                    if (rx.usage.checked() === false && rx.get('usage') === rx.usage.labels('progressive')) {
                        rx.alert('Please select your prescription type first.');
                        event.stopImmediatePropagation();
                    }
                    break;
                case 170:
                    if (rx.purpose.checked() === false && rx.get('purpose') === rx.purpose.labels('office')) {
                        rx.alert('Please select a distance first.');
                        event.stopImmediatePropagation();
                    }
                    break;
                case 2 :
                    try {
                        if (!rx.prescription.validate()) {
                            event.stopImmediatePropagation();
                        }

                    } catch (err) {
                        rx.alert(err.message);
                        event.stopImmediatePropagation();
                    }
                    break;
                case 250:
                    try {
                        if (!rx.prescription.validate()) {
                            event.stopImmediatePropagation();
                        }
                    } catch (err) {
                        rx.alert(err.message);
                        event.stopImmediatePropagation();
                    }
                    break;
                case 3:
                    jQuery('#step4').hide();
                    if (false === rx.lensColor.validate() || false === rx.lensColor.validateOptions()) {
                        event.stopImmediatePropagation();
                    }
                    break;
                case 4 :
                    var material_and_thickness = rx.get('package');
                    if (material_and_thickness === '') {
                        rx.alert('Please select lens package');
                        event.stopImmediatePropagation();
                    }
                    break;
                /*case 6 :
                    if(  rx.get('usage') === rx.usage.labels('singleVisionReading') ||
                        rx.get('usage') === rx.usage.labels('singleVisionDistance') ){
                        jQuery('#rush3day').show();
                    }else{
                        jQuery('#rush3day').hide();
                    }
                    break;*/
            }


            event.validated = true;

        });


        rx_form_container.on('rx_step_set', function (event) {

            if (event.step_number === 1 && event.validated) {
                // console.log('event.validated - ', event.validated);

                var rxData = localStorage.getItem('rx_data') ? JSON.parse(localStorage.getItem('rx_data')) : {};

                rxData.usage = rxData.usage || {};
                rxData.usage.type = rx.get('usage');
                rxData.usage.impact_resistant = rx.get('impact');

                if (rxData) {
                    localStorage.setItem('rx_data', JSON.stringify(rxData));
                }
            }

            if (event.step_number === 2 && event.validated) {
                rxData = localStorage.getItem('rx_data') ? JSON.parse(localStorage.getItem('rx_data')) : {};
                rxData.prescription = rxData.prescription || {};
                rxData.prescription = rx.prescription.parseRx();

                if (rxData) {
                    localStorage.setItem('rx_data', JSON.stringify(rxData));

                }
            }
        });


        /** step 1,2,3,4,150 transitions*/
        rx_form_container.on('rx_step_set', function (event) {
            var step_number = event.step_number;

            switch (step_number) {
                case 1://qua
                case 3:
                case 4:
                    rx.navigation.set(step_number);
                    break;
                case 2:
                    if (rx.prescription.checkSphereSigns()) {
                        rx.navigation.toPrescription();

                        rx.package.init('preset', function () {
                            rx.navigation.move(2, 7);
                        });


                        // var package_init_move = function(material){
                        //     rx.package.init('preset', function () {
                        //         rx.navigation.move(2, 7);
                        //     },
                        //     material);
                        // };
                        //
                        // if(rx_params.rx_popup_material){
                        //     if (rx.package.getStrength() <= 4 && rx.package.isSingleVision()){
                        //         rx.package.initMaterialSelectPopup(package_init_move);
                        //     } else {
                        //         package_init_move(false);
                        //     }
                        // } else {
                        //     package_init_move(false);
                        // }
                    }
                    break;
                case 150:
                case 170:
                case 250:
                    rx.navigation.set(step_number - 1); // -_-" better not ask why -1
                    break;
            }


        });


        rx_form_container.on('rx_navigation_set', function (event) {
            // console.log(event);
            var obj = event.step_object;
            var $nav = jQuery(event.step_naw);
            var $step = jQuery("#step" + event.step_number);
            if (!$step.data('initialized') &&
                typeof obj !== 'undefined' &&
                typeof obj.step_init !== 'undefined') {
                obj.step_init(jQuery(this));
                $step.data('initialized', true);
            }
            $nav.show();
        });


        setTimeout(function () {

            var isource = jQuery('.wp-post-image').attr('src');
            var ftitle = jQuery('.product--name-title').text();
            if (jQuery('#pa_color').length > 0) {

                var size = jQuery('[data-attribute_name="attribute_pa_size"] > span.selected').attr('title');

                if (size) {
                    size = ' (' + size + ')';
                } else {
                    size = '';
                }

                ftitle = ftitle
                    + ' - '
                    + jQuery('[data-attribute_name="attribute_pa_color"] > span.selected').attr('title')
                    + size;
            }

            window.scrollTo(0, 60);
            // jQuery('#order_fimage').attr('src', isource);
            jQuery('#order_ftype').text(ftitle);
            jQuery('#frame_title').text(ftitle);


            if (rx.prescription.get('d', 'mode', false) !== 'fashion') {
                jQuery("#enhance_rx_accuracy").hide();
                /*jQuery('#nb_step1').show();*/
                // jQuery("#div_coat_hard_check")
                //     .removeClass('selector_check_passive')
                //     .addClass('option_selected');

                jQuery("#div_coat_hard_tile")
                    .removeClass('selector_tile')
                    .addClass('border_orange');

            } else {
                jQuery('#nb_step3').show();
                jQuery('#step3').show();
                jQuery('#rx_progress').hide();
            }

            rx_form_container.on('click', '.selector', function () {

                var og = jQuery(this).data('group');
                jQuery("div[data-group='" + og + "_tile']")
                    .removeClass('border_orange')
                    .addClass('selector_tile');

                jQuery("div[data-group='check_" + og + "']")
                    .removeClass('option_selected')
                    .html('')
                    .addClass('selector_check_passive');

                var chk_id = '#' + jQuery(this).attr('id') + '_check';
                var tile_id = '#' + jQuery(this).attr('id') + '_tile';

                jQuery(tile_id)
                    .removeClass('selector_tile')
                    .addClass('border_orange');

                jQuery(chk_id)
                    .removeClass('selector_check_passive')
                    .addClass('option_selected');

                var color_template_active = jQuery(this).attr('id');
                jQuery('.rx-extra-section_' + color_template_active).show();

            });

            var countBlock = 0;

            jQuery('.ong_checkbox').click(function () {
                var state = '#' + jQuery(this).attr('id') + '_state';
                if (jQuery(state).val() === '0') {

                    jQuery(state).val('1');
                    jQuery(this)
                        .removeClass('selector_check_passive')
                        .addClass('option_selected');
                } else {
                    jQuery(this).html('');
                    jQuery(state).val('0');

                    jQuery(this).removeClass('option_selected');
                    jQuery(this).addClass('selector_check_passive');
                }

            });

            jQuery('.rx_link').css('cssText', 'background: none !important; font-size: 16px !important; color:#353648 !important; font-weight: bold');

            jQuery('span[rel=tooltip]').mouseover(function (e) {
                //Grab the title attribute's value and assign it to a variable
                var tip = jQuery(this).attr('title');

                //Remove the title attribute's to avoid the native tooltip from the browser
                jQuery(this).attr('title', '');

                //Append the tooltip template and its value
                jQuery(this).append('<div id="tooltip"><div class="tipHeader"></div><div class="tipBody">' + tip + '</div></div>');

                //Show the tooltip with faceIn effect
                jQuery('#tooltip')
                    .show()
                    .css('left', -120);

            }).mousemove(function (e) {


            }).mouseout(function () {

                //Put back the title attribute's value
                jQuery(this).attr('title', jQuery('.tipBody').html());

                //Remove the appended tooltip template
                jQuery(this).children('div#tooltip').remove();

            });


            /**
             * ong_info Change the picture after selecting LENS TYPE in step # 1
             */
            jQuery('.rx-form-container').on('click', '.selector_check', function (e, Step) {
                    var $self = jQuery(e.target);
                    var url_img = $self.data('orderLensDesignImg');
                    if (url_img) {
                        var img = jQuery("<img src='" + url_img + "' style='width: 100%;'>");
                        jQuery("#order_lens_design")
                            .empty()
                            .append(img);
                    }
                }
            );

            // jQuery('.view-packages').on('click', function(){
            //     jQuery('#rx_right_packages').hide();
            //     jQuery('#rx_right').show();
            //     jQuery('.button-hide-container').show();
            // });
            //
            // jQuery('.hide-packages').on('click', function(){
            //     jQuery('#rx_right').hide();
            //     jQuery('.button-hide-container').hide();
            //     jQuery('#rx_right_packages').show();
            // });


        }, 0);
        jQuery(rx_form_container).foundation();
    });
});

/** *******  deprecated  ******* **/

/**
 * @deprecated
 */
function showProgTooltip() {
    jQuery('#checkthis').dialog().prev('.ui-dialog-titlebar').find('a').hide();

    jQuery('#checkthis').load('/rx/lens.html').dialog({
        modal: true,
        height: 530,
        width: 800,
        position: [20, 20],
        left: 20,
        buttons: {
            "Continue": function () {
                jQuery("#dummy_radio").hide();
                jQuery("#rdo_p_allday").show();
                jQuery("#rdo_p_allday").attr('checked', true).trigger('click');
                jQuery("#free_form").attr('checked', true);
                rx.fee.showEnhanceRxAccuracy();
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
    jQuery('#mypd').load(rx_params.url, {
        action: 'pdinfo',
        limit: 25
    }).show();
}

/**
 * @deprecated
 */
function Reset_Review() {
    //console.info(arguments.callee.name);
    jQuery('#package_data_header').show();
    jQuery('#package_image').show();
    jQuery('#package_title').show();
}

/**
 * @deprecated
 */
function showTooltip(idVar) {
    //console.info(arguments.callee.name);
    //Grab the title attribute's value and assign it to a variable
    var tip = jQuery('#' + idVar).attr('title');

    //Remove the title attribute's to avoid the native tooltip from the browser
    jQuery('#' + idVar).attr('title', '');

    //Append the tooltip template and its value
    jQuery('#' + idVar).append('<div id="tooltip"><div class="tipHeader"></div><div class="tipBody">' + tip + '</div></div>');

    //Show the tooltip with faceIn effect
    jQuery('#tooltip').show();
    jQuery('#tooltip').css('left', e.pageX + -30);
}

/**
 * @deprecated
 */
function hideTooltip(idVar) {
    //console.info(arguments.callee.name);
    //Put back the title attribute's value
    jQuery('#' + idVar).attr('title', jQuery('.tipBody').html());

    //Remove the appended tooltip template
    jQuery('#' + idVar).children('div#tooltip').remove();
}

/**
 * @deprecated
 */
function ChangeAxis(obj) { //tod
    //console.info(arguments.callee.name);
}

/**
 * @deprecated
 */
function ShowLROptions() {
    //console.info(arguments.callee.name);
    jQuery('#trans_options').show();
}

/**
 * @deprecated
 */
function EnhanceRxAccuracyClicked() {
    //console.info(arguments.callee.name);
    if (rx.get('freeform') === 0) {
        var single_enhance_rx_fee = rx.fee.getSingleEnhanceRxAccuracy();
        rx.price.set('enhance_rx_accuracy', single_enhance_rx_fee);
        rx.set('freeform', 1);
    } else {
        rx.fee.hideEnhanceRxAccuracy();
    }
}

/**
 * @deprecated
 */
function clearCheck(cid) {
    //console.info(arguments.callee.name);
    var did = '#div_' + cid + '_check';
    jQuery(did).html('');
    jQuery(did).removeClass('option_selected');
    jQuery(did).addClass('selector_check_passive')
}

function SetColor() {
    jQuery('#pa_color').val(jQuery('#ong_color_select').val()).change();
}

function SubmitRX() {
    var parseLenses = rx.prescription.parseLenses;
    var parseRx = rx.prescription.parseRx;
    var html_data = rx.prescription.getLenses() + rx.prescription.getRX();
    var html_data_get_lenses = rx.prescription.getLenses();
    var html_data_get_prescription = rx.prescription.getRX();


    //code to add validation, if any
    //If all values are proper, then send AJAX request
    // window.loader.start();
    var package_price = jQuery('#total_price').val();
    var product_id = jQuery("input[name='add-to-cart']").val();
    var all_data = JSON.stringify(rx.package.getAllData());
    var parsedLenses = parseLenses();
    var parsedRx = parseRx();
    window.loader.start();
    jQuery.ajax({
        url: rx_params.url,
        type: "POST",
        data: wp.hooks.applyFilters('wdm_add_user_custom_data_options_data', {
            action: 'wdm_add_user_custom_data_options',
            user_data: html_data,//send request data
            user_data_lenses: html_data_get_lenses,
            user_data_prescription: html_data_get_prescription,
            lenses: parsedLenses,
            rx: parsedRx,
            total_price: package_price,
            lens_price: rx.price.get('lens'),
            rush_price: rx.price.get('rush'),
            //rush_3day_price: rx.price.get('rush_3day'),
            product_id: product_id,
            all_data: all_data
        }),
        async: false,
        success: function (data) {
            //Code, that need to be executed when data arrives after
            // successful AJAX request execution
            window.loader.stop();
        }
    });
}


function SubmitToCart() {
    var urlData = 'Sunglasses';
    //code to add validation, if any
    //If all values are proper, then send AJAX request
    var package_price = jQuery('#total_price').val();
    window.loader.start();
    jQuery.ajax({
        url: rx_params.url,
        type: "POST",
        data: {
            //action name
            action: 'wdm_add_user_custom_data_options',
            user_data: urlData,//send request data
            total_price: package_price
        },
        async: false,
        success: function (data) {
            //Code, that need to be executed when data arrives after
            // successful AJAX request execution
            window.loader.stop();
        }
    });
    jQuery('#ong_cart').submit();
}

//Function for preloader
(function (window, $) {
    'use strict';
    var SpinnerManager = function () {
    };

    SpinnerManager.prototype.start = function () {
        console.log('loader start');
        $(".wrap-loader").show();
    };
    SpinnerManager.prototype.stop = function () {
        console.log('loader stop');
        $(".wrap-loader").hide();
    };

    window.loader = window.loader || new SpinnerManager;

})(window, jQuery);

jQuery(document).ready(function () {


    // jQuery("#woof_widget-3").before('<a class="filter_hidden" href="#">x</a>');

    // ============== More RX Jscript============================
    var pathname = window.location.pathname;
    if (pathname.indexOf('product_category') > 0) {
        jQuery(".variations_button").hide();
    }
    if (jQuery('#pa_color').length > 0) {
        options = jQuery('#pa_color').html();
        jQuery('#ong_color_select').html(options);
        jQuery('#ong_color').show();
    }
});


function wordwrap(str, intWidth, strBreak, cut) {

    if (str.indexOf(' ') === -1) {
        // no space in the string
        var newstr = str.match(/.{1,20}/g);
        return newstr.join(' ');
    }

    intWidth = arguments.length >= 2 ? +intWidth : 75
    strBreak = arguments.length >= 3 ? '' + strBreak : '\n'
    cut = arguments.length >= 4 ? !!cut : false

    var i, j, line

    str += ''

    if (intWidth < 1) {
        return str
    }

    var reLineBreaks = /\r\n|\n|\r/
    var reBeginningUntilFirstWhitespace = /^\S*/
    var reLastCharsWithOptionalTrailingWhitespace = /\S*(\s)?$/

    var lines = str.split(reLineBreaks)
    var l = lines.length
    var match

    // for each line of text
    for (i = 0; i < l; lines[i++] += line) {
        line = lines[i]
        lines[i] = ''

        while (line.length > intWidth) {
            // get slice of length one char above limit
            var slice = line.slice(0, intWidth + 1)

            // remove leading whitespace from rest of line to parse
            var ltrim = 0
            // remove trailing whitespace from new line content
            var rtrim = 0

            match = slice.match(reLastCharsWithOptionalTrailingWhitespace)

            // if the slice ends with whitespace
            if (match[1]) {
                // then perfect moment to cut the line
                j = intWidth
                ltrim = 1
            } else {
                // otherwise cut at previous whitespace
                j = slice.length - match[0].length

                if (j) {
                    rtrim = 1
                }

                // but if there is no previous whitespace
                // and cut is forced
                // cut just at the defined limit
                if (!j && cut && intWidth) {
                    j = intWidth
                }

                // if cut wasn't forced
                // cut at next possible whitespace after the limit
                if (!j) {
                    var charsUntilNextWhitespace = (line.slice(intWidth).match(reBeginningUntilFirstWhitespace) || [''])[0]

                    j = slice.length + charsUntilNextWhitespace.length
                }
            }

            lines[i] += line.slice(0, j - rtrim)
            line = line.slice(j + ltrim)
            lines[i] += line.length ? strBreak : ''
        }
    }

    return lines.join('\n')
}

function option_select(e) {
    console.log('option select');
    $('.option_select_rx').removeClass('active_mirror');
    $('.option_select_rx').removeClass('not_active_mirror');
    if ($('.active_mirror').length) {
        $('.active_mirror').not($(e)).removeClass('mirror_option').addClass('active_mirror');
    }
    $(e).removeClass('mirror_option').addClass('active_mirror');
    $('.option_select_rx').not($('.active_mirror')).addClass('not_active_mirror')
}

//step packages switch between
function mirror_select(e) {
    $('.mirror_box').removeClass('active_mirror');
    $('.mirror_box').removeClass('not_active_mirror');
    console.log("mirror option clicked");
    if ($('.active_mirror').length) {
        $('.active_mirror').not($(e)).removeClass('mirror_option').addClass('active_mirror');
    }
    $(e).removeClass('mirror_option').addClass('active_mirror');
    $('.mirror_box').not($('.active_mirror')).addClass('not_active_mirror');
}
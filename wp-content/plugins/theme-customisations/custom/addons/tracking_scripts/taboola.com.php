<?php
/**
 * theme-customisations
 *
 * @author     Eugene Odokiienko <eugene.odokienko@agilefuel.com>
 * @copyright  Copyright (c) 2018 Overnightglasses LLC. (http://www.overnightglasses.com)
 */
function tfa_script_footer() {
    ?><script data-obct type="text/javascript">
        /** DO NOT MODIFY THIS CODE**/
        !function(_window, _document) {
            var OB_ADV_ID='00f36a399c87642d692b729d01c2e7b2ed';
            if (_window.obApi) {var toArray = function(object) {return Object.prototype.toString.call(object) === '[object Array]' ? object : [object];};_window.obApi.marketerId = toArray(_window.obApi.marketerId).concat(toArray(OB_ADV_ID));return;}
            var api = _window.obApi = function() {api.dispatch ? api.dispatch.apply(api, arguments) : api.queue.push(arguments);};api.version = '1.1';api.loaded = true;api.marketerId = OB_ADV_ID;api.queue = [];var tag = _document.createElement('script');tag.async = true;tag.src = '//amplify.outbrain.com/cp/obtp.js';tag.type = 'text/javascript';var script = _document.getElementsByTagName('script')[0];script.parentNode.insertBefore(tag, script);}(window, document);
        obApi('track', 'PAGE_VIEW');
    </script>
    <script type="text/javascript">
        window._tfa = window._tfa || [];
        _tfa.push({ notify: 'action',name: 'page_view' });
    </script>
    <script src="//cdn.taboola.com/libtrc/taboolaaccount-michaelovernightglassescom/tfa.js"></script>
<?php
}
add_action('wp_footer', 'tfa_script_footer');

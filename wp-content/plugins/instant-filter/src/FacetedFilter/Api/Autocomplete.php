<?php
/**
 * ONG Store
 *
 * Licence: MIT https://opensource.org/licenses/MIT
 * Copyright: odokienko
 */
namespace OngStore\FacetedFilter\Api;

class Autocomplete
{

    public function __construct(
        \OngStore\FacetedFilter\Api\Config $config
    ) {
        $this->config = $config;
    }

    public function setStoreId($storeId)
    {
        $this->storeId = $storeId;
    }

    /**
     * @return string
     */
    public function toHtml()
    {
        return <<<HTML
<script>'' +
    (function () {
        var protocol= ("https:" === document.location.protocol ? "https://" : "http://");
        var script = document.createElement('script');
        script.type = 'text/javascript';
        script.async = true;
        var s = document.getElementsByTagName('script')[0];
        s.parentNode.insertBefore(script, s);

        window.ONG_CONFIG = {}
    })();
</script>
HTML;
    }
}

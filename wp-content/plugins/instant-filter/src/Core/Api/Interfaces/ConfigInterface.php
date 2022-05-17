<?php
/**
 * ONG Store
 *
 * Licence: MIT https://opensource.org/licenses/MIT
 * Copyright: odokienko
 */

namespace OngStore\Core\Api\Interfaces;


interface ConfigInterface
{
    /**
     * @param string $path
     *
     * @return string
     */
    function getValue($path);

    /**
     * @param string $path
     * @param string $value
     *
     * @return void
     */
    function saveValue($path, $value);


    /**
     * @param string $path
     *
     * @return string
     */
    function getEncryptedValue($path);

    /**
     * @param string $path
     * @param string $value
     *
     * @return void
     */
    function saveValueEncrypted($path, $value);
}
<?php
/**
 * ONG Store
 *
 * Licence: MIT https://opensource.org/licenses/MIT
 * Copyright: odokienko
 */
namespace OngStore\Core\Api;

class ClientOld
{
    const METHOD_GET = 'GET';
    const METHOD_POST = 'POST';
    const METHOD_PUT = 'PUT';
    const METHOD_DELETE = 'DELETE';

    const BASE_URL = '/store/api/v1';

    const STATUS_OK = 200;
    const STATUS_UNATHORIZED = 401;

    public function __construct(
        \OngStore\Core\Api\Config $config
    ) {
        $this->config    = $config;
        $this->extID     = $config->getExtID();
        $this->secretKey = $config->getSecretKey();
        $this->host      = $config->getBaseApiUrl();
    }

    /**
     * @param string $extID
     * @param string $secret
     * @param array  $data
     *
     * @return string
     */
    public function getAuthUrl($extID, $secret, $data = [])
    {
        $data['timestamp'] = time();
        $data['extID']     = $extID;
        ksort($data);
        $url = [];
        foreach ($data as $k => $v) {
            $url[] = "$k=$v";
        }
        $url  = implode("&", $url);
        $hmac = hash_hmac('sha256', $url, $secret);

        return "/auth/token?$url&hmac=$hmac";
    }

    /**
     * @param array $data
     *
     * @return string
     * @throws Exception
     */
    public function getToken($data = [])
    {
        //echo $this->getAuthUrl($this->extID, $this->secretKey, $data);
        try {
            $res = $this->request(
                null,
                Client::METHOD_POST,
                $this->getAuthUrl($this->extID, $this->secretKey, $data),
                [],
                []
            );
        } catch (Exception $e) {
            if ($e->getCode() == self::STATUS_UNATHORIZED) {
                $this->config->disconnect();

                return false;
            }
            throw $e;
        }

        if (isset($res['IUID']) && (!$this->config->getIUid() || $res['IUID'] != $this->config->getIUid())) {
            $this->config->setIUid($res['IUID']);
        }
        if (isset($data['product'])) {
            $product = $data['product'];
            if (isset($res['AUID']) &&
                (!$this->config->getAUid($product) || $res['AUID'] != $this->config->getAUid($product))
            ) {
                $this->config->setAUid($product, $res['AUID']);
            }
        }
        $token = $res['token'];

        return $token;
    }

    /**
     * @param string $token
     * @param string $method
     * @param string $action
     * @param array  $params
     * @param array  $data
     *
     * @return array|false
     * @throws Exception
     */
    public function request($token, $method, $action, $params = [], $data = [])
    {
        $url = $this->host . self::BASE_URL . $action;
        $url = rtrim($url, '/');
        if (is_array($params) && count($params)) {
            $url .= '?' . http_build_query($params);
        }

        $curlHandle = curl_init();

        $headers = [
            'Content-type: application/json',
        ];

        if ($token) { //we don't need token for registration/autorization/token actions
            $headers[] = 'Authorization: Bearer ' . $token;
        }

        curl_setopt($curlHandle, CURLOPT_HTTPHEADER, $headers);

        //Return the output instead of printing it
        curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curlHandle, CURLOPT_FAILONERROR, true);
        curl_setopt($curlHandle, CURLOPT_ENCODING, '');

        curl_setopt($curlHandle, CURLOPT_URL, $url);
        curl_setopt($curlHandle, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($curlHandle, CURLOPT_SSL_VERIFYHOST, 2);

        curl_setopt($curlHandle, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($curlHandle, CURLOPT_TIMEOUT, 5);
        curl_setopt($curlHandle, CURLOPT_NOSIGNAL, 1);
        curl_setopt($curlHandle, CURLOPT_FAILONERROR, false);

        if ($method === self::METHOD_GET) {
            curl_setopt($curlHandle, CURLOPT_CUSTOMREQUEST, 'GET');
            curl_setopt($curlHandle, CURLOPT_HTTPGET, true);
            curl_setopt($curlHandle, CURLOPT_POST, false);
        } elseif ($method === self::METHOD_POST) {
            $body = ($data) ? json_encode($data) : '';
            curl_setopt($curlHandle, CURLOPT_CUSTOMREQUEST, 'POST');
            curl_setopt($curlHandle, CURLOPT_POST, true);
            curl_setopt($curlHandle, CURLOPT_POSTFIELDS, $body);
        } elseif ($method === self::METHOD_DELETE) {
            curl_setopt($curlHandle, CURLOPT_CUSTOMREQUEST, 'DELETE');
            curl_setopt($curlHandle, CURLOPT_POST, false);
        } elseif ($method === self::METHOD_PUT) {
            $body = ($data) ? json_encode($data) : '';
            curl_setopt($curlHandle, CURLOPT_CUSTOMREQUEST, 'PUT');
            curl_setopt($curlHandle, CURLOPT_POSTFIELDS, $body);
            curl_setopt($curlHandle, CURLOPT_POST, true);
        }
        curl_exec($curlHandle);


        $httpStatus = (int) curl_getinfo($curlHandle, CURLINFO_HTTP_CODE);

        $response = curl_multi_getcontent($curlHandle);
        $error    = curl_error($curlHandle);

        if ($httpStatus != self::STATUS_OK) {
            curl_close($curlHandle);
            throw new Exception("[$httpStatus] " . $error, $httpStatus);
        }

        $result = json_decode($response, true);

        curl_close($curlHandle);

        switch (json_last_error()) {
            case JSON_ERROR_DEPTH:
                $errorMsg = 'JSON parsing error: maximum stack depth exceeded';
                break;
            case JSON_ERROR_CTRL_CHAR:
                $errorMsg = 'JSON parsing error: unexpected control character found';
                break;
            case JSON_ERROR_SYNTAX:
                $errorMsg = 'JSON parsing error: syntax error, malformed JSON';
                break;
            case JSON_ERROR_STATE_MISMATCH:
                $errorMsg = 'JSON parsing error: underflow or the modes mismatch';
                break;
            case defined('JSON_ERROR_UTF8') ? JSON_ERROR_UTF8 : - 1:
                $errorMsg = 'JSON parsing error: malformed UTF-8 characters, possibly incorrectly encoded';
                break;
            case JSON_ERROR_NONE:
            default:
                $errorMsg = null;
                break;
        }
        if ($errorMsg !== null) {
            throw new Exception(__($url . ' ' . $errorMsg . ' ' . $response));
        }

        return $result;
    }
}

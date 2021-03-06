<?php
/**
 * Solwin Infotech
 * Solwin Instagram Extension
 *
 * @category   Solwin
 * @package    Solwin_Instagram
 * @copyright  Copyright © 2006-2020 Solwin (https://www.solwininfotech.com)
 * @license    https://www.solwininfotech.com/magento-extension-license/
 */
namespace Solwin\Instagram\Helper;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
     /**
      * Return brand config value by key and store
      *
      * @param string $key
      * @param \Magento\Store\Model\Store|int|string $store
      * @return string|null
      */
    public function getConfig($key)
    {
        $result = $this->scopeConfig->getValue(
            $key,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
        return $result;
    }

    public function getInstangramData($url)
    {
        $ch = curl_init();
        $timeout = 2500;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, 1);
        curl_setopt($ch, CURLOPT_PROXY, '192.168.10.1:808');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $response = curl_exec($ch);
        $errmsg = curl_error($ch);
        $cInfo = curl_getinfo($ch);
        curl_close($ch);

        if ($errmsg == '') {
            return $response;
        } else {
            return $errmsg;
        }
    }

    public function rudrInstagramApiCurlConnect($api_url)
    {
        $connection_c = curl_init(); // initializing
        curl_setopt($connection_c, CURLOPT_URL, $api_url); // API URL to connect
        curl_setopt($connection_c, CURLOPT_RETURNTRANSFER, 1); // return the result, do not print
        curl_setopt($connection_c, CURLOPT_TIMEOUT, 20);
        $json_return = curl_exec($connection_c); // connect and get json data
        curl_close($connection_c); // close connection
        return json_decode($json_return); // decode and return
    }
}

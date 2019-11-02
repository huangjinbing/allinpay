<?php
/**
 * Created by PhpStorm.
 * User: chunmeng_jiang
 * Date: 19-2-25
 * Time: 上午9:21
 */

namespace Tonglian\Allinpay\Common;

use Tonglian\Allinpay\Helper\Tools;
use Tonglian\Allinpay\SDK\Crypt\Crypt_RSA;
use Tonglian\Allinpay\SDK\File\File_X509;

class AllinpayClient
{
    public $serverUrl;
    public $path;
    public $pwd;
    public $alias;
    public $version;
    public $tlCertPath;
    public $sysid;

    public function __construct()
    {
        $this->serverUrl = config('allinpay.server_url');
        $this->path = config('allinpay.private_key_path');
        $this->pwd = config('allinpay.private_password');
        $this->alias = config('allinpay.alias');
        $this->version = config('allinpay.version');
        $this->tlCertPath = config('allinpay.tl_cert_path');
        $this->sysid = config('allinpay.sysid');
    }

    /**
     *请求封装
     *
     * @param string $service 服务名称
     * @param string $method 方法名称
     * @param array $param 其他的参数
     * @return array|mixed
     */
    public function AllinpayCurl($service, $method, $param)
    {
        $param = $this->requestParamFilter($param);
        $ssoid = $this->sysid;
        $request["service"] = $service;
        $request["method"] = $method;
        $request["param"] = $param;
        $strRequest = json_encode($request);
        $strRequest = str_replace("\r\n", "", $strRequest);
        $req['req'] = $strRequest;
        $req['sysid'] = $ssoid;
        $timestamp = date("Y-m-d H:i:s", time());
        $sign = $this->sign($ssoid, $strRequest, $timestamp);
        $req['timestamp'] = $timestamp;
        $req['sign'] = $sign;
        $req['v'] = $this->version;
        $serverAddress = $this->serverUrl;

        Tools::logInfo($req, '通联请求参数', 'tonglian');

        $result = $this->requestYSTAPI($serverAddress, $req);
        $response = json_decode($result, true);
        if (!$this->checkResult($result)) {
            return Tools::setData($response);
        }

        if ($response['signedValue'] ?? '') {
            $decodeSignedValue = json_decode($response['signedValue'], true);
            if ($decodeSignedValue) {
                $response['signedValue'] = $decodeSignedValue;
            }
        }
        return Tools::setData($response);
    }


    /**
     *支付密码请求封装
     *
     * @param string $service 服务名称
     * @param string $method 方法名称
     * @param array $param 其他的参数
     * @return array|mixed
     */
    public function getPaymentCodeParams($service, $method, $param)
    {
        $param = $this->requestParamFilter($param);
        $ssoid = $this->sysid;
        $request["service"] = $service;
        $request["method"] = $method;
        $request["param"] = $param;
        $strRequest = json_encode($request);
        $strRequest = str_replace("\r\n", "", $strRequest);
        $req['req'] = $strRequest;
        $req['sysid'] = $ssoid;
        $timestamp = date("Y-m-d H:i:s", time());
        $sign = $this->sign($ssoid, $strRequest, $timestamp);
        $req['timestamp'] = $timestamp;
        $req['sign'] = $sign;
        $req['v'] = $this->version;
        return Tools::setData($req);
    }

    /**
     *对数据进行加密
     *
     * @param $ssoid
     * @param $strRequest
     * @param $timestamp
     * @return 返回类型
     * @author  trendpower
     */
    public function sign($ssoid, $strRequest, $timestamp)
    {
        $dataStr = $ssoid . $strRequest . $timestamp;
        $text = base64_encode(hash('md5', $dataStr, true));

        $privateKey = $this->getPrivateKey();
        openssl_sign($text, $sign, $privateKey);
        $res = openssl_get_privatekey($privateKey);
        openssl_free_key($res);
        $sign = base64_encode($sign);

        return $sign;
    }

    /**
     * 获取私匙的绝对路径;
     * @date 2019/9/5 16:29
     * @return string 私钥
     */
    public function getPrivateKey()
    {
        return $this->loadPrivateKey($this->path, $this->pwd);
    }

    /**
     * 从证书文件中装入私钥 pem格式
     * @date 2019/9/5 18:19
     * @param string $path 证书路径的绝对路径
     * @param string $pwd 证书密码
     * @return bool|resource|私钥
     */
    private function loadPrivateKey($path, $pwd)
    {
        //判断文件的格式
        //使用pem格式私钥
        $str = explode('.', $path);
        $houzuiName = $str[count($str) - 1];
        if ($houzuiName == "pfx") {
            return $this->loadPrivateKeyByPfx($path, $pwd);
            //$priKey = file_get_contents($path);
            //$res = openssl_get_privatekey($priKey, $pwd);
            //return $res;
        }

        if ($houzuiName == "pem") {
            $priKey = file_get_contents($path);
            $res = openssl_get_privatekey($priKey, $pwd);
            //echo "<br/>pem===".$res. "<br/>";
            if (!$res) {
                exit('您使用的私钥格式错误，请检查私钥配置');
            }
            return $res;
        }
    }

    /**
     * 从证书文件中装入私钥 Pfx 文件格式
     *
     * @param string path 证书路径
     * @param string password 证书密码
     * @return 私钥
     * @throws Exception
     */
    private function loadPrivateKeyByPfx($path, $pwd)
    {
        if (file_exists($path)) {
            $priKey = file_get_contents($path);
            if (openssl_pkcs12_read($priKey, $certs, $pwd)) {
                $privateKey = $certs['pkey'];
                return $privateKey;
            }
            die("私钥文件格式错误");

        }
        die('私钥文件不存在');
    }

    /**
     * 请求云商通URL
     * @date 2019/9/5 18:18
     * @param $serverUrl
     * @param $args
     * @return bool|string
     */
    private function requestYSTAPI($serverUrl, $args)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $serverUrl);

        $sb = '';
        $reqbody = array();
        foreach ($args as $entry_key => $entry_value) {
            $sb .= $entry_key . '=' . urlencode($entry_value) . '&';
        }

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $sb);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_AUTOREFERER, 0);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-length', count($reqbody)));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        $result = curl_exec($ch);
        curl_getinfo($ch);
        curl_close($ch);
        return $result;
    }

    /**
     *检查返回的结果是否合法;
     *
     * @param $result 需要检测的返回结果
     * @return bool
     * @throws Exception
     * @author  trendpower
     */
    private function checkResult($result)
    {
        $arr = json_decode($result, true);
        $sign = $arr['sign'];
        $signedValue = $arr['signedValue'];
        $success = false;

        if ($sign != null) $success = $this->verify2($this->getPublicKeyPath(), $signedValue, $sign);
        if ($success) return $arr;
        return $success;
    }

    /**
     *验证的返回结果的合法性 2.0版本
     *
     * @param $publicKeyPath 公匙所在绝对路径
     * @param $signedValue   返回的数据
     * @param $sign          返回的加密数据
     * @return bool
     * @author  trendpower
     */
    private function verify2($publicKeyPath, $signedValue, $sign)
    {
        $certfile = file_get_contents($publicKeyPath);
        if (!$certfile) {
            return null;
        }
        $x509 = new File_X509();//请自行去github下载;
        $cert = $x509->loadX509($certfile);
        $publicKey = $x509->getPublicKey();

        $rsa = new Crypt_RSA();
        $rsa->loadKey($publicKey); // public key
        $rsa->setSignatureMode(CRYPT_RSA_SIGNATURE_PKCS1);


        $signedValue = base64_encode(hash('md5', $signedValue, true));
        $verifyResult = $rsa->verify($signedValue, base64_decode(trim($sign)));

        return $verifyResult;
    }


    /**
     *验证返回的数据的合法性
     *
     * @param $publicKeyPath 公匙整数所在的绝对路径
     * @param $text
     * @param $sign
     * @return bool
     * @throws Exception
     * @author  trendpower
     */
    private function verify($publicKeyPath, $text, $sign)
    {
        $publicKey = $this->loadPublicKey($publicKeyPath);
        $result = (bool)openssl_verify($text, base64_decode($sign), $publicKey, OPENSSL_ALGO_SHA1);
        openssl_free_key($publicKey);
        return $result;
    }

    /**
     * [RsaEncode 隐私数据加密]
     *
     * @param [type] $data [隐私数据]
     */
    public function RsaEncode($data)
    {
        $publicKeyPath = $this->getPublicKeyPath();
        $certfile = file_get_contents($publicKeyPath);
        if (!$certfile) {
            return null;
        }
        $x509 = new File_X509();
        $x509->loadX509($certfile);
        $publicKey = $x509->getPublicKey();
        $encrypted = "";
        openssl_public_encrypt($data, $encrypted, $publicKey);
        return strtoupper(bin2hex($encrypted));
    }

    /**
     * [RsaDecode 隐私数据解密]
     *
     * @param [type] $data [description]
     */
    public function RsaDecode($data)
    {
        $data = $this->Hex2String($data);
        $prikey = $this->getPrivateKey();
        if (!openssl_private_decrypt($data, $decrypt_data, $prikey, OPENSSL_PKCS1_PADDING)) {
            echo "<br/>ErrorMsg====" . openssl_error_string() . "<br/>";
        }
        return $decrypt_data;
    }

    public function Hex2String($hex)
    {
        $string = '';
        for ($i = 0; $i < strlen($hex) - 1; $i += 2) {
            $string .= chr(hexdec($hex[$i] . $hex[$i + 1]));
        }
        return $string;
    }
    /*
    public function decrypt($data, $code = 'base64', $padding = OPENSSL_PKCS1_PADDING, $rev = false)
    {
        $ret = false;
        $data = $this->_decode($data, $code);
        $path = $this->config->getConf('path');
        $pwd = $this->config->getConf('pwd');
        $this->_getPrivateKey($path,$pwd);
        if (!$this->_checkPadding($padding, 'de')) $this->_error('padding error');
        if ($data !== false)
        {
            if (openssl_private_decrypt($data, $result, $this->priKey, $padding))
            {
                $ret = $rev ? rtrim(strrev($result), "\0") : ''.$result;
            }
        }
        return $ret;
    }

    private function _getPrivateKey($path,$pwd)
    {
        $key_content = $this->_readFile($path);
        if ($key_content)
        {
            $this->priKey = openssl_get_privatekey($path,$pwd);
            var_dump($this->priKey);
        }
    }

    private function _readFile($file){
        $ret = false;
        if (!file_exists($file))
        {
            $this->_error("The file {$file} is not exists");
        }else
        {
            $ret = file_get_contents($file);
        }
        return $ret;
    }

    private function _checkPadding($padding, $type){
        if ($type == 'en')
        {
            switch ($padding){
                case OPENSSL_PKCS1_PADDING:
                    $ret = true;
                    break;
                default:
                    $ret = false;
            }
        }else
        {
            switch ($padding){
            case OPENSSL_PKCS1_PADDING:
            case OPENSSL_NO_PADDING:
                $ret = true;
                break;
            default:
                $ret = false;
            }
        }
        //var_dump($ret);
        return $ret;
    }
    private function _decode($data, $code){
        switch (strtolower($code)){
        case 'base64':
            $data = base64_decode($data);
            break;
        case 'hex':
            $data = $this->_hex2bin($data);
        break;
            case 'bin':
        default:
        }
        return $data;
    }

    private function _hex2bin($hex = false){
        $ret = $hex !== false && preg_match('/^[0-9a-fA-F]+$/i', $hex) ? pack("H*", $hex) : false;
        return $ret;
    }
    */
    /**
     *获取公匙的绝对路径
     *
     * @param 参数1
     * @param 参数2
     * @return 返回类型
     * @author  trendpower
     */
    public function getPublicKeyPath()
    {
        return $this->tlCertPath;
    }

    /**
     * [curPageURL 获取当前URL]
     *
     * @return [type] [description]
     */
    public function curPageURL()
    {
        $pageURL = 'http';

        if ($_SERVER["HTTPS"] == "on") {
            $pageURL .= "s";
        }
        $pageURL .= "://";

        if ($_SERVER["SERVER_PORT"] != "80") {
            $pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
        } else {
            $pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
        }
        return $pageURL;
    }

    /**
     * 过滤请求参数
     *
     * @param $param
     * @return array
     */
    private function requestParamFilter($param): array
    {
        foreach ($param as $key => $item) {
            if (is_null($item)) {
                unset($param[$key]);
            }
        }
        return $param;
    }
}

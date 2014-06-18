<?php
/**
 * ================================================================================
 * Yahoo!ウォレットFastPay - サンプルコード利用規約
 * ================================================================================
 * Yahoo! JAPANの提供するサンプルコードをご利用いただくにあたっては、
 * 以下のガイドラインの内容をお読みいただき、同意していただくことが必要です。
 * サンプルコードを利用することによって、ガイドラインの内容に同意いただいたものとみなします。
 * ガイドラインに同意いただけない場合は、サンプルコードを使用するライセンスは許諾されません。
 * ガイドライン：http://developer.yahoo.co.jp/terms/
 */

class FastPay
{
    private static $secret = null;
    private static $url = "https://fastpay.yahooapis.jp";
    private static $version = "v1";

    const VERSION = 0.2;

    public static function setUrl($_url)
    {
        static::$url = $_url;
    }

    public static function getUrl()
    {
        return static::$url;
    }

    public static function setSecret($_secret)
    {
        static::$secret = $_secret;
    }

    public static function getSecret()
    {
        if (static::$secret === null) {
            throw new Exception("Check your secret here: http://fastpay.yahoo.co.jp/account");
        }
        return static::$secret;
    }

    public static function setVersion($_version)
    {
        static::$version = $_version;
    }

    public static function getVersion()
    {
        return static::$version;
    }
}

require_once dirname(__FILE__) . '/FastPay/Object.php';
require_once dirname(__FILE__) . '/FastPay/Util.php';
require_once dirname(__FILE__) . '/FastPay/Charge.php';
require_once dirname(__FILE__) . '/FastPay/Card.php';
require_once dirname(__FILE__) . '/FastPay/Request.php';
require_once dirname(__FILE__) . '/FastPay/Error.php';
require_once dirname(__FILE__) . '/FastPay/Errors/ApiError.php';
require_once dirname(__FILE__) . '/FastPay/Errors/CardError.php';
require_once dirname(__FILE__) . '/FastPay/Errors/ConnectionError.php';
require_once dirname(__FILE__) . '/FastPay/Errors/InvalidRequestError.php';

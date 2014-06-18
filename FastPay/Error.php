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

class FastPay_Error extends Exception
{
    public function __construct($http_status=null, $http_body=null)
    {
        $message = sprintf("Status:%s, Body:%s", $http_status, $http_body);
        parent::__construct($message);
        $this->http_status = $http_status;
        $this->http_body = json_decode($http_body);
    }

    public function getHttpStatus()
    {
        return $this->http_status;
    }

    public function getHttpBody()
    {
        return $this->http_body;
    }

    public function __toString()
    {
        return get_class($this) . " (Status " . $this->http_status . ") " . json_encode($this->http_body);
    }

}


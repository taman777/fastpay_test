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

class FastPay_Charge extends FastPay_Object
{
    public static function create($fields)
    {
        $instance = new self;
        return $instance->executeRequest("post", $instance->url(get_class()), $fields);
    }

    public static function retrieve($id)
    {
        $instance = new self;
        return $instance->executeRequest("get", $instance->url(get_class(), array($id)));
    }

    public static function all($fields)
    {
        $instance = new self;
        return $instance->executeRequest("get", $instance->url(get_class()), $fields);
    }

    public function refund()
    {
        return $this->executeRequest("post", $this->url(get_class(), array($this->id, "refund")));
    }

    public function capture()
    {
        return $this->executeRequest("post", $this->url(get_class(), array($this->id, "capture")));
    }
}

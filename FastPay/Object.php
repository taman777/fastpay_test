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

class FastPay_Object implements ArrayAccess
{
    private $values;

    public function __construct($values = array())
    {
        unset($values["object"]);
        $this->values = $values;
    }

    public function executeRequest($method = "get", $url, $fields=array())
    {
        $options = array(
            "httpheader" => FastPay_Util::createHeader(),
            "userpwd" => FastPay_Util::basicAuth(),
            "ssl_verifypeer" => true,
            "timeout" => 80,
            "connecttimeout" => 30,
        );

        $fields = FastPay_Util::encode($fields);

        switch ($method) {
            case 'get':
                $url .= "?" . $fields;
                break;
            case 'post':
                $options["post"] = true;
                $options["postFields"] = $fields;
                break;
        }

        $request = new FastPay_Request($url, $options);
        list($body, $info) = $request->send();

        return FastPay_Util::parser($body, $info);
    }

    public static function url($class, $custom_url=null)
    {
        $url = array(
            FastPay::getUrl(),
            FastPay::getVersion(),
            static::getClassName($class),
        );

        if( ! is_null($custom_url)) {
            $url = array_merge($url, $custom_url);
        }
        return implode("/", $url);
    }

    public static function getClassName($name)
    {
        return strtolower(substr($name, strlen("FastPay_")) . "s");
    }

    public function __get ($key)
    {
        return isset($this->values[$key]) ? $this->values[$key] : null;
    }

    public function __set($key,$value)
    {
        $this->values[$key] = $value;
    }

    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->values[] = $value;
        } else {
            $this->values[$offset] = $value;
        }
    }

    public function offsetExists($offset)
    {
        return isset($this->values[$offset]);
    }

    public function offsetUnset($offset)
    {
        unset($this->values[$offset]);
    }

    public function offsetGet($offset)
    {
        return isset($this->values[$offset]) ? $this->values[$offset] : null;
    }

}


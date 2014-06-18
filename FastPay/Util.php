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

class FastPay_Util
{
    public static function encode(array $fields, $innerkey = null)
    {
        $ret = array();
        foreach ($fields as $key => $value) {
            if (is_string($value) || is_integer($value)) {
                if ( ! is_null($innerkey)) {
                    $key = $innerkey . "[" . $key . "]";
                }
                $ret[] = urlencode($key) . "=" . urlencode($value);
            } elseif (is_array($value)) {
                $ret[] = static::encode($value, $key);
            } elseif (is_bool($value)) {
                if ( ! is_null($innerkey)) {
                    $key = $innerkey . "[" . $key . "]";
                }
                $value = $value ? "true" : "false";
                $ret[] = urlencode($key) . "=" . $value;
            }
        }

        return implode("&", $ret);
    }

    public static function createHeader(array $header = array())
    {
        $base = array(
            "content-type: application/x-www-form-urlencoded",
        );

        $base[] = "user-agent: FastPay-php v" . FastPay::VERSION;

        return array_merge($base, $header);
    }

    public static function parser($body, $info)
    {
        $jsonData = json_decode($body, true);

        if (isset($jsonData["error"]["type"])) {
            switch ($jsonData["error"]["type"]) {
                case 'card_error':
                    throw new FastPay_CardError($info["http_code"], $body, $jsonData["error"]["code"]);
                    break;
                case 'api_error':
                    throw new FastPay_ApiError($info["http_code"], $body);
                    break;
                case 'invalid_request_error':
                    throw new FastPay_InvalidRequestError($info["http_code"], $body);
                    break;
            }
        }

        return static::convertFastPayObject($jsonData);
    }

    public static function convertFastPayObject($jsonData)
    {
        if ($jsonData["object"] === "list") {
            $responseList = array();
            for ($i = 0; $i < $jsonData["count"]; $i++) {
                if (isset($jsonData["data"][$i])) array_push($responseList, static::returnFastPayObject($jsonData["data"][$i]));
            }
            return $responseList;
        } else {
            return static::returnFastPayObject($jsonData);
        }
    }

    public static function basicAuth()
    {
        return FastPay::getSecret() . ":";
    }

    private static function returnFastPayObject($object)
    {
        switch (gettype($object)) {
            case "array":
                foreach ($object as $key => $value) {
                    if ( ! is_null(static::checkFastPayObject($key))) {
                        $object[$key] = static::returnFastPayObject($value);
                    }
                }
                if(isset($object["object"])) {
                    $className = static::checkFastPayObject($object["object"]);
                    return new $className($object);
                } else {
                    return $object;
                }
                break;
            default:
                return $object;
        }
    }

    private static function checkFastPayObject($name)
    {
        $classes = array(
            "charge" => "FastPay_Charge",
            "card" => "FastPay_Card",
        );

        return array_key_exists($name, $classes) ?  $classes[$name] : "FastPay_Object";
    }
}

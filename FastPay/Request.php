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

class FastPay_Request
{
    protected $handle;

    protected $options = array(
        'returnTransfer' => true,
        'verbose' => false,
    );

    public function __construct($url = null, array $options = array())
    {
        if (is_string($url)) {
            $this->handle = curl_init($url);
            $options['url'] = $url;
        } else {
            $this->handle = curl_init();
        }

        $this->options += $options;
        $this->setOptions($this->options);
    }

    public function __destruct()
    {
        curl_close($this->handle);
    }

    public function __clone()
    {
        $this->handle = curl_copy_handle($this->handle);
    }

    public function setOptions(array $options)
    {
        $this->options = $options + $this->options;
        curl_setopt_array($this->handle, $this->_toCurlSetopt($options));
        return $this;
    }

    public function setOption($label, $val)
    {
        $this->options[$label] = $val;
        curl_setopt($this->handle, $this->_toCurlOption($label), $val);
        return $this;
    }

    public function getOption($label)
    {
        return $this->options[$label];
    }

    public function send()
    {
        $body = curl_exec($this->handle);
        $info = curl_getinfo($this->handle);

        if ( ! $body ) {
            if ($info["http_code"] === 0) {
                throw new FastPay_ConnectionError(sprintf("Status: %s, Message: %s", $info["http_code"], curl_error($this->handle)));
            } else {
                throw new FastPay_ApiError($info["http_code"], "");
            }
        }
        return array($body, $info);
    }

    protected function _toCurlSetopt(array $optionList)
    {
        $fixedOptionList = array();
        foreach ($optionList as $opt => $value) {
            $label = $this->_toCurlOption($opt);
            $fixedOptionList[$label] = $value;
        }
        return $fixedOptionList;
    }

    protected function _toCurlOption($label)
    {
        if (is_int($label)) {
            return $label;
        }

        if (is_string($label)) {
            $const = 'CURLOPT_' . strtoupper($label);
            if (defined($const)) {
                $curlopt = constant($const);
            } else {
                throw new InvalidArgumentException("$label does not exist in CURLOPT_* constants.");
            }
            return $curlopt;
        }

        throw new InvalidArgumentException('label is invalid');
    }
}

<?php
require_once(dirname(__FILE__) . '/FastPay.php');
require_once(dirname(__FILE__) . '/config.php');
$error_msg = '';
$error_flg = FALSE;
try {
    // FastPayによって生成されたトークン
    $token = htmlspecialchars($_POST['fastpayToken']);
    // 送信された金額項目
    $price = htmlspecialchars($_POST['amount']);
    if (is_nan($price)) {
        $error_msg = "不正な文字列を入力されています。";
        $error_flg = TRUE;
    }

    if ( !$error_flg ) {
        FastPay::setSecret(SECRET_KEY);
        $response = FastPay_Charge::create(array(
            "amount" => $price,
            "card" => $token,
            "description" => CHARGE_DESCRIPTION,
        ));
        // 決済成功
        $amount = $response->amount;
        $last4 = $response->card->last4;
        $currency = $response->currency;
    }
} catch (Exception $e) {
    // ステータス
    $e_status = $e->http_status;
    // ボディ
    $e_body = $e->http_body;
    // エラー
    $e_error = $e_body->error;
    // タイプ
    $e_type = $e_error->type;
    // コード
    $e_code = $e_error->code;
    // パラメータ
    $e_param = $e_error->param;

    // エラー
    echo "HTTP STATUS:".$e_status."<br />";


    if ( $e_status != "200" ) {
        $error_flg = TRUE;
    }
    switch ($e_status) {

    /**
    200 OK - リクエストは成功しました。
    400 Bad Request - 必要なパラメータがありません。
    401 Unauthorized - API keyの認証に失敗しました。
    402 Request Failed - パラメータは有効でしたが、現在のステータスではこのリクエストは実行できません。
    404 Not Found - リクエストされた内容が存在しません。
    500, 502, 503, 504 Server errors - Yahoo!ウォレット FastPay側のシステムエラーです。
    */
        case "400" :
            $error_msg = "必要なパラメータがありません。";
            break;
        case "401" :
            $error_msg = "APIキーの認証に失敗しました。";
            break;
        case "402" :
            $error_msg = "支払い処理が完了していません。";
            break;
        case "404" :
            $error_msg = "リクエストされた内容が存在しません。";
            break;
        case "500":
        case "502":
        case "503":
        case "504":
            $error_msg = "Yahoo!ウォレット FastPay側のシステムでエラーになっています。";
            break;

//      case "200" :
//          break;
    }
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="Content-Style-Type" content="text/css">
<meta http-equiv="Content-Script-Type" content="text/javascript">
<title>FastPayテスト結果</title>
</head>
<body>
<?php
if ( !$error_flg ) { ?>

#####ここをサンキュー画面にして下さい#####<br />
<br />
決済が完了しました。<br />
決済金額：<?php echo $amount; ?>円<br />
通貨：<?php echo $currency; ?><br />
カード番号（下４桁のみ表示）<br />
************<?php echo $last4; ?>

<!-- ここでサンキュー画面または入力内容確認画面の表示を行う！ -->


<?php
} else {
?>
<form action="index.html">
<?php echo $error_msg."<br />".$e_code.":".$e_type;; ?><br />
<input type="submit" value="戻る" />
</form>
<?php
}
?>
</body>
</html>
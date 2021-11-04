<?php

require '../inc.php';
require './inc.php';

@header('Content-Type: text/html; charset=UTF-8');
$trade_no = daddslashes($_GET['trade_no']);
$type     = daddslashes($_GET['type']);
if (!is_numeric($trade_no)) {
    exit('订单号不符合要求!');
}
if (empty($conf['codepay_key'])) {
    exit('当前支付接口未开启');
}
$row = $DB->getRow("SELECT * FROM pre_pay WHERE trade_no='{$trade_no}' LIMIT 1");
if (!$row) {
    exit('该订单号不存在，请返回来源地重新发起请求！');
}

$valid = (strtotime($row['addtime']) + $USDT_VALID_TIME) * 1000;
$lock  = sys_get_temp_dir() . '/usdt-trc20_pay_' . $trade_no . '.dat';
if (file_exists($lock)) {
    $usdt = file_get_contents($lock);
} else {
    $rate    = getLatestRate();
    $usdt    = round($row['money'] / $rate, 4);
    $addTime = date('Y-m-d H:i:s', strtotime($row['addtime']) - $USDT_VALID_TIME);
    $exist   = $DB->getRow("select * from pre_pay where trade_no != '{$row['trade_no']}' and money = '{$row['money']}' and status = 0 and addtime >= '$addTime' order by trade_no desc limit 1");
    if ($exist) {
        $dat  = sys_get_temp_dir() . '/usdt-trc20_pay_' . $exist['trade_no'] . '.dat';
        $usdt = bcadd(file_get_contents($dat), 0.0001, 4);
    }

    file_put_contents($lock, $usdt);
}

include_once SYSTEM_ROOT . 'usdt-trc20/themes.php';
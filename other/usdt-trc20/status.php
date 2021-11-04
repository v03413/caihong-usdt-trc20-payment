<?php
require '../inc.php';
require './inc.php';


checkResult();

$trade_no = isset($_GET['trade_no']) ? daddslashes($_GET['trade_no']) : exit('No trade_no!');

@header('Content-Type: text/html; charset=UTF-8');

$row = $DB->getRow("SELECT * FROM pre_pay WHERE trade_no='{$trade_no}' LIMIT 1");
if ($row['domain'] && $row['domain'] != $_SERVER['HTTP_HOST'] && strpos($row['domain'], '.') !== false) {
    $baseurl = 'http://' . $row['domain'] . '/';
} else {
    $baseurl = '../';
}
if ($row['tid'] == -1) $link = $baseurl . 'user/';
elseif ($row['tid'] == -2) $link = $baseurl . 'user/regok.php?orderid=' . $trade_no;
else $link = $baseurl . '?buyok=1';

if ($row['status'] >= 1) {
    exit('{"code":1,"msg":"付款成功","backurl":"' . $link . '"}');
} else {
    exit('{"code":-1,"msg":"未付款"}');
}
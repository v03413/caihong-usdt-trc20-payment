<?php
/**
 * 充值余额
 **/
include("../includes/common.php");
$title = '充值余额';
include './head.php';
if ($islogin2 == 1) {
} else exit("<script language='javascript'>window.location.href='./login.php?back=recharge';</script>");
?>
<style>
    img.logo {
        width: 20px;
        margin: -2px 5px 0 5px;
    }
</style>
<div class="wrapper">
    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading font-bold" style="background-color: #9999CC;color: white;">
                充值余额
            </div>
            <div class="panel-body text-center">
                <?php if (!empty($conf['fenzhan_gift'])) {
                    $rules = explode('|', $conf['fenzhan_gift']);
                    $i     = 1;
                    ?>
                    <div class="alert alert-success text-left">本平台回馈新老用户推出多充多返活动！<br/>
                        <?php foreach ($rules as $rule) {
                            $arr = explode(':', $rule);
                            echo $i++ . '.一次性充值 <font color="red"><strong>' . $arr[0] . '</strong></font> 元以上，平台赠送您 <font color="red"><strong>' . $arr[1] . '</strong>%</font> 的可消费余额<br/>';
                        }
                        ?>
                    </div>
                <?php } ?>
                <b>我当前的账户余额：<span style="font-size:16px; color:#FF6133;"><?php echo $userrow['rmb'] ?></span> 元</b>
                <hr>
                <input type="text" class="form-control" name="value" autocomplete="off" placeholder="输入要充值的余额"><br>
                <?php
                if ($conf['codepay_key']) echo '<button type="submit" class="btn btn-default" id="buy_usdt"><img src="../other/usdt-trc20/static/img/tether.svg" class="logo">USDT - TRC20</button>&nbsp;';
                if ($conf['alipay_api']) echo '<button type="submit" class="btn btn-default" id="buy_alipay"><img src="../assets/img/alipay.png" class="logo">支付宝</button>&nbsp;';
                if ($conf['qqpay_api']) echo '<button type="submit" class="btn btn-default" id="buy_qqpay"><img src="../assets/img/qqpay.png" class="logo">QQ钱包</button>&nbsp;';
                if ($conf['wxpay_api']) echo '<button type="submit" class="btn btn-default" id="buy_wxpay"><img src="../assets/img/wxpay.png" class="logo">微信支付</button>&nbsp;';

                ?>
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModa4" id="alink"
                        style="visibility: hidden;"></button>
                <hr>
                <?php if ($conf['tixian_limit'] == 1) { ?><small
                        style="color:red;">提示：充值的余额只能用于消费，无法提现</small><?php } ?><?php if ($conf['recharge_min'] > 0) { ?>
                    <small style="color:red;">，最低充值<?php echo $conf['recharge_min'] ?>元</small><?php } ?>

            </div>
        </div>
        <?php if ($conf['fenzhan_jiakuanka']) { ?>
            <div class="panel panel-default">
                <div class="panel-heading font-bold" style="background-color: #9999CC;color: white;">
                    使用加款卡充值余额
                </div>
                <div class="panel-body text-center">
                    <input type="text" class="form-control" name="km" autocomplete="off" placeholder="请输入加款卡密"><br>
                    <button type="submit" class="btn btn-primary" id="usekm">立即使用</button>
                </div>
            </div>
        <?php } ?>
    </div>
    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading font-bold" style="background-color: #9999CC;color: white;">充值记录</div>
            <div class="panel-body">

                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th>充值金额</th>
                            <th>充值时间</th>
                            <th>状态</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $flag = false;
                        $rs   = $DB->query("SELECT * FROM pre_points WHERE zid='{$userrow['zid']}' AND action='充值' ORDER BY id DESC LIMIT 10");
                        while ($res = $rs->fetch()) {
                            $flag = true;
                            echo '<tr><td><b>' . $res['point'] . '</b></td><td>' . $res['addtime'] . '</td><td><font color="green">已完成</font></td></tr>';
                        }
                        if (!$flag) echo '<tr class="no-records-found"><td colspan="99">暂无充值记录</td></tr>';
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include './foot.php'; ?>
<script>
    function dopay(type) {
        var value = $("input[name='value']").val();
        if (value == '' || value == 0) {
            layer.alert('充值金额不能为空');
            return false;
        }
        $.get("ajax_user.php?act=recharge&type=" + type + "&value=" + value, function (data) {
            if (data.code == 0) {
                window.location.href = '../other/submit.php?type=' + type + '&orderid=' + data.trade_no;
            } else {
                layer.alert(data.msg);
            }
        }, 'json');
    }

    $(document).ready(function () {
        $("#buy_usdt").click(function () {
            dopay('usdt_pay')
        });
        $("#buy_alipay").click(function () {
            dopay('alipay')
        });
        $("#buy_qqpay").click(function () {
            dopay('qqpay')
        });
        $("#buy_wxpay").click(function () {
            dopay('wxpay')
        });
        $("#usekm").click(function () {
            var km = $("input[name='km']").val();
            $.ajax({
                type: "POST",
                url: "ajax_user.php?act=usekm",
                data: {km: km},
                dataType: 'json',
                async: true,
                success: function (data) {
                    if (data.code == 0) {
                        layer.alert(data.msg, {icon: 1}, function () {
                            window.location.reload()
                        });
                    } else {
                        layer.alert(data.msg, {icon: 2});
                    }
                }
            });
        });

    })
</script>
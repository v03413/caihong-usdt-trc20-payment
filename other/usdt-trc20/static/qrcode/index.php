<?php
/**
 * 二维码生成接口
 *
 * 请求方式   http get
 *
 * 请求字段描述
 * 参数    说明                  类型                                 是否必需
 * c	content             二维码内容 如：http://www.itxe.net	 	 是
 * s	size                大小，每像素几个点 1,2,3,4,5,6....	     否 默认7
 * bc	backgroud_color     背景色&透明度 rgba 逗号分开	 	     否 默认白色 255,255,255,0
 * fc	froreground_color   前景色&透明度 rgba 逗号分开	 	     否 默认黑色 0,0,0,0
 *
 * 返回字段描述
 * 返回示例
 * 直接 返回图片
 *
 * Date: 16/5/19
 * Time: 10:52
 * By Nyarime
 */
require 'QRcode.php';

// 接收参数
$c = $_GET['c'] ? $_GET['c'] : '';

// 图片输出
Toplib_Lib_QRcode::png($c,false,QR_ECLEVEL_L,6,1,false,array(255, 255, 255, 0),array(0, 0, 0, 0));

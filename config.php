<?php
/**
 * Created by PhpStorm.
 * User: laiguanhui
 * Date: 2019-06-18
 * Time: 17:33
 */

$mysql_conf = array(
    'host'    => '127.0.0.1:3306',
    'db'      => 'test',
    'db_user' => 'root',
    'db_pwd'  => 'root',
);

// 大华平台的ip地址
//$ip = "http://192.168.1.190:80";    //  二小
//$ip = "http://192.168.1.96:80";    //  中堂实验中学
//$ip = "http://10.158.50.90:80";    //  中堂焦利小学
//$ip = "http://10.157.119.90:80";    // 中堂第四小学
//$ip = "http://10.157.249.211:80";    // 中堂第三小学
$ip = "http://10.157.251.114:80";    // 中堂槎滘小学
$userName = "system";
$interval = 3600; //获取过车信息的时间间隔（秒）

// 公安平台

//中堂第二小学的正式环境，tv：1222438283
/*$gateId = '441906001650000001';
$initKey = 'bpA2w1pu';
$ftpIP = "59.39.179.76";
$ftpUser = "tccftp00777";
$ftpPwd = "B7V1G2c0";*/

//中堂实验中学的正式环境，tv：1478636902
/*$gateId = '441956001650000001'; //停车场编号
$initKey = 'z50SX7uf';
$ftpIP = "59.39.179.76";
$ftpUser = "tccftp00862";
$ftpPwd = "v1V5B7g1";*/

//中堂焦利小学的正式环境，tv：1227636321
/*$gateId = '441956001650000002'; //停车场编号
$initKey = 'VqJ6y32p';
$ftpIP = "59.39.179.76";
$ftpUser = "tccftp00919";
$ftpPwd = "l6I0c2P4";*/

//中堂第四小学的正式环境，tv：1211334443
/*$gateId = '441956001650000003'; //停车场编号
$initKey = 'vNAmq871';
$ftpIP = "59.39.179.76";
$ftpUser = "tccftp00918";
$ftpPwd = "A8H4z7z4";*/

//中堂第三小学的正式环境，tv：1225360833
/*$gateId = '441956001650000004'; //停车场编号
$initKey = 'a5959Z1m';
$ftpIP = "59.39.179.76";
$ftpUser = "tccftp00945";
$ftpPwd = "q2u8g7Q0";*/

//中堂槎滘小学的正式环境，tv：1227410788
/*$gateId = '441956001650000005'; //停车场编号
$initKey = 'Mp6o0A74';
$ftpIP = "59.39.179.76";
$ftpUser = "tccftp00944";
$ftpPwd = "Z4o2c8I3";*/

//测试环境
$gateId = '44190000000000test';
$initKey = '127.0.0.1';
$ftpIP = "59.39.179.76";
$ftpUser = "tcctest";
$ftpPwd = "password";

$webservice_url = "http://59.39.179.74:9070/services/hole?wsdl";//webservice地址
$directArr = array(
    "1" => "1",
    "2" => "1"
);

//日志文件
$log = fopen("car.log", "a");

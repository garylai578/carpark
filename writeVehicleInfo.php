<?php
/**
 * Created by PhpStorm.
 * User: laiguanhui
 * Date: 2019-06-13
 * Time: 17:14
 */

header("Content-type: text/html; charset=utf-8");
date_default_timezone_set("PRC");
require_once 'Car.class.php';
require_once 'config.php';

$time_ago = time();
set_time_limit(0);
$suc = false;

//连接ftp
$conn = ftp_connect($ftpIP) or die("Could not connect the ftp");    //连接标识ftp_connect("ftp地址")
ftp_login($conn, $ftpUser, $ftpPwd);  //进行FTP连接ftp_login($conn,"用户名",“登录密码")

while($suc == false) { //第一次连接可能会超时或者失败，所以需要重连直至成功
    try {
        libxml_disable_entity_loader(false); //解决webservice间歇性调用失败的问题：failed to load external entity
        $client = new SoapClient($webservice_url);
        //连接数据库
        $mysqli = @new mysqli($mysql_conf['host'], $mysql_conf['db_user'], $mysql_conf['db_pwd']);
        if ($mysqli->connect_errno) {
            fwrite($log, date("Y-m-d H:i:s", time())."    could not connect to the database:\r\n" . $mysqli->connect_error . "\r\n");
            die("could not connect to the database:\r\n" . $mysqli->connect_error);//诊断连接错误
        }
        $mysqli->query("set names 'utf8';");//编码转化
        $select_db = $mysqli->select_db($mysql_conf['db']);
        if (!$select_db) {
            fwrite($log, date("Y-m-d H:i:s", time())."    could not connect to the db:\r\n" .  $mysqli->error . "\r\n");
            die("could not connect to the db:\r\n" .  $mysqli->error);
        }

        $num=0;
        foreach ($directArr as $directType => $directWayNo) {
            //该传参已做优化，参数都为必需值
            $param = array(
                'gateId' => $gateId,
                'directType' => $directType,
                'driverWayNo' => $directWayNo,
                'initKey' => $initKey,
            );
            $arr = $client->initTrans($param);//调用其中initTrans方法
            $xmlData = simplexml_load_string($arr->String);
            echo "<br>" . date("Y-m-d H:i:s", time()). '  init: ';
            print_r($xmlData);
            if ($xmlData->code == 1) { // 如果连接成功
                // 获取directType相同的数据
                $sql = "select * from carpass WHERE directType=".$directType." and sendTime=0";
                $res = $mysqli->query($sql);
                $token = $xmlData->token;
                while($carPass = $res->fetch_assoc()){ //对于每一条数据，查询其对应的car信息
                    $sql2 = "select * from car where id=".$carPass['cid'];
                    $res2 = $mysqli->query($sql2);
                    $car = $res2->fetch_assoc();

                    // 上传照片
                    $list = ftp_nlist($conn, '.');
                    if (sizeof($list) == 0)
                        ftp_mkdir($conn, $gateId);
                    $picPath1 = $gateId . '/' . date("Ymd", time()) . '_' . mt_rand() . '.jpg'; //命名规则：停车场编号/20190323+序号.jpg。图片名称不要有中文，序号不重复即可。
                    ftp_pasv($conn, true);  // 设置为被动模式，否则上传会失败
                    echo ftp_put($conn, $picPath1, $carPass['picPath1'], FTP_BINARY);

                    // 上传过车信息
                    $cc = new Car();
                    $cc->setPassTime($carPass['passTime']);
                    $cc->setLicense($car['license']);
                    $cc->setLicenseColor($car['licenseColor']);
                    $cc->setCarType($car['type']);
                    $cc->setPicPath1($picPath1);
                    $writeParam = $cc->toCarArray();
                    $writeParam['gateId'] = $gateId;
                    $writeParam['directType'] = $directType;
                    $writeParam['driverWayNo'] = $directWayNo;
                    $writeParam['token'] = $token;

                    $writeArr = $client->writeVehicleInfo($writeParam);//调用其中writeVehicleInfo方法写入车辆信息
                    echo "<br>writeVehicleInfo: ";
                    print_r($writeArr);
                    // 如果上传成功，则写入更新时间
                    $xmlData = simplexml_load_string($writeArr->String);
                    if($xmlData->code == 1){
                        $updateSql = "update carpass set sendTime='". date("Y-m-d H:i:s", time()). "' where id=".$carPass['id'];
                        $mysqli->query($updateSql);
                        $num++;
                    }
                    mysqli_free_result($res2);
                }
                mysqli_free_result($res);
            }
        }
        //如果全部上传成功，则退出循环
        $sql = "select * from carpass WHERE  sendTime=0";
        $res = $mysqli->query($sql);
        if($res->num_rows == 0) {
            $suc = true;
        }
    } catch (Exception $e) {
        echo "<br>".$e."<br>writeParam: ";
        print_r($writeParam);
        sleep(10);
        $sql = "select * from carpass WHERE  sendTime=0";
        $res = $mysqli->query($sql);
        if($res->num_rows != 0) {
            echo "<br>异常重新连接";
            $suc = false;
        }
    }
}

ftp_close($conn);
$time_end = time();
fwrite($log, date("Y-m-d H:i:s", time()).'    webservice执行时间差为：'.($time_end-$time_ago).'s , 上传成功：'.$num."条\r\n");
fclose($log);
exit;
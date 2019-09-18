<?php
/**
 * 从大华平台获取车辆和过车信息分别存放到car和carPass表中
 * Created by PhpStorm.
 * User: laiguanhui
 * Date: 2019-06-13
 * Time: 9:53
 */
date_default_timezone_set("PRC");
header("Content-type:text/html;charset=utf-8");
require_once 'Car.class.php';
require_once 'config.php';
set_time_limit(0);

$mysqli = @new mysqli($mysql_conf['host'], $mysql_conf['db_user'], $mysql_conf['db_pwd']);
if ($mysqli->connect_errno) {
    fwrite($log, date("Y-m-d H:i:s", time())."  could not connect to the database:\r\n" . $mysqli->connect_error ."\r\n");
    die("could not connect to the database:\r\n" . $mysqli->connect_error);//诊断连接错误
}
$mysqli->query("set names 'utf8';");//编码转化
$select_db = $mysqli->select_db($mysql_conf['db']);
if (!$select_db) {
    fwrite($log, date("Y-m-d H:i:s", time())."  could not connect to the db:\r\n" .  $mysqli->error . "\r\n");
    die("could not connect to the db:\r\n" .  $mysqli->error);
}

$url = $ip."/ipms/subSystem/generate/token?userName=".$userName; // 获取access token
$res = file_get_contents($url);
$arr = json_decode($res, true);
if($arr['success'] == 'true') {
    $accessToken = $arr['data']['accessToken'];
    //获取闸道信息
    $channels = $ip."/ipms/device/sluice/channel";
    $ch = curl_init();
    $header = array('charset:utf-8', 'accessToken:'.$accessToken);
    curl_setopt($ch, CURLOPT_URL, $channels);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    $file_contents = curl_exec($ch);
    $arr = json_decode($file_contents, true);
    curl_close($ch);
    if($arr['success'] == 'true'){
        $entranceChannelIds='';
        $exitusChannelIds='';
        foreach ($arr['data'] as $data) {
            if(strpos($data['channelName'], "入口"))
                $entranceChannelIds .= $data['channelId'] . ',';
            elseif (strpos($data['channelName'], "出口"))
                $exitusChannelIds .= $data['channelId'] . ',';
        }
        $entranceChannelIds = substr($entranceChannelIds, 0, strlen($entranceChannelIds)-1);
        $exitusChannelIds = substr($exitusChannelIds, 0, strlen($exitusChannelIds)-1);
        // 每次查询interval秒前的数据
        $queryTimeBegin = urlencode(date("Y-m-d H:i:s", (time() - $interval)));
//        $queryTimeBegin = urlencode("2019-06-11 00:30:53");
        $queryTimeEnd = urlencode(date("Y-m-d H:i:s", time()));
//        $queryTimeEnd = urlencode("2019-06-13 24:00:00");
        $pageNum = 1;
        $pageSize = 1000;
        //获取每个道闸的车辆出入信息
        $carAccess = $ip."/ipms/caraccess/find/his?queryTimeBegin=".$queryTimeBegin.'&queryTimeEnd='.$queryTimeEnd.'&pageNum='.$pageNum.'&pageSize='.$pageSize;
        echo $carAccess.'<br>';
        fwrite($log, date("Y-m-d H:i:s", time())."  获取车辆出入信息：".$carAccess.":\r\n");
        $ch = curl_init();
        $header = array('Content-Type: application/json; charset=utf-8', 'accessToken:'.$accessToken);
        curl_setopt($ch, CURLOPT_URL, $carAccess);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        $file_contents = curl_exec($ch);
        $arr = json_decode($file_contents, true);

        // 对于每一条信息，包含了进场和出场时间，分别插入到carPass表中
        $num = 0;
        fwrite($log,"  信息数量：".sizeof($arr['data']['pageData']).":\r\n");
        foreach ($arr['data']['pageData'] as $data){
            // 首先检查车辆信息是否在car表中
//            print_r($data);
            $carNum; $licenseColor=""; $carType="";
            //如果是入场或者已经出场
            if($data['carStatus'] == 0 || $data['carStatus'] == 1){
                $carNum = $data['carNum'];
            }elseif($data['carStatus'] == 2){ //如果只有出场信息
                $carNum = $data['exitCarNum'];
            }
            $sql = "select * from car where license='".$carNum."'";
            $res = $mysqli->query($sql);
            if (!$res) {
                die("sql error:\r\n" . $mysqli->error);
            }
            if ($row = $res->fetch_assoc()) {   //如果车牌已经存储在car表中，则获取车辆的id
                $cid = $row['id'];
            }else {     //否则，插入到car表中，并获取插入后的id
                $carInfo =  $ip."/ipms/car/list?pageNum=1&pageSize=10&carNumLikeStr=".urlencode($carNum);
                curl_setopt($ch, CURLOPT_URL, $carInfo);
                $carJson = json_decode(curl_exec($ch), true);
                /*                if (!curl_errno($ch)) {
                                    print_r(curl_getinfo($ch));
                                }
                                echo "<br>carjson:";
                                print_r($carJson);*/
                if(isset($carJson['data']['pageData'][0])){
                    $carData = $carJson['data']['pageData'][0];
                    $licenseColor = $carData['carNumColorStr'];
                    $carType = $carData['carTypeStr'];
                }
                $sql = 'insert into car(`license`, `licenseColor`, `type`) VALUES ("' . $carNum . '",'. getLicenseColorCode($licenseColor). ', "'.getCarTypeCode($carType).'" )';
                $res = $mysqli->query($sql);
                if (!$res) {
                    die("sql error:\r\n" . $mysqli->error);
                }
                $cid = $mysqli->insert_id;
//                    echo "<br>sql:". $sql.", cid:".$cid;
            }

            // 将过车信息插入到carPass表中
            // 下载图片到本地
            $picPath1 = "default.jpg";

            // 如果有进入时间，一般都有的。但是carStatus==2的是只有出场信息，起入场时间和出场时间一样，要排除
            if(isset($data['enterTimeStr']) && $data['carStatus'] != 2){
                if(isset($data['realCapturePicPathEnter']) && $data['realCapturePicPathEnter'] != '')
                    $picPath1 = crabImage($ip.$data['realCapturePicPathEnter']);

                $sql = 'insert into carPass(`cid`, `directType`, `passTime`, `picPath1`) VALUES (' . $cid . ', 1, "'. $data['enterTimeStr']. '", "' . $picPath1 .'" )';
                $res = $mysqli->query($sql);
                if (!$res) {
                    die("sql error:\r\n" . $mysqli->error);
                }
                $num++;
            }
            // 如果有出场时间，carStatus==1或carStatus==2时才有
            if(isset($data['exitTimeStr'])){
                if(isset($data['realCapturePicPathExit']) && $data['realCapturePicPathExit'] != '')
                    $picPath1 = crabImage($ip.$data['realCapturePicPathExit']);

                $sql = 'insert into carPass(`cid`, `directType`, `passTime`, `picPath1`) VALUES (' . $cid . ', 2, "'. $data['exitTimeStr']. '", "' . $picPath1 .'" )';
                $res = $mysqli->query($sql);
                if (!$res) {
                    die("sql error:\r\n" . $mysqli->error);
                }
                $num++;
            }
        }
        fwrite($log,"  插入数据库数量：". $num . ":\r\n");
        curl_close($ch);
    }
}

$mysqli->close();
fclose($log);

/** 获取车牌颜色代号，参见市公安局社会停车场上传规范
 * @param $color
 * @return int
 */
function getLicenseColorCode($color){
    if(strpos($color, "白"))
        return 0;
    if(strpos($color, "黄"))
        return 1;
    if(strpos($color, "蓝"))
        return 2;
    if(strpos($color, "黑"))
        return 3;
    if(strpos($color, "绿"))
        return 4;
    return 9;
}

/** 获取车辆类型代码，参见市公安局社会停车场上传规范
 * @param $type
 * @return int
 */
function getCarTypeCode($type){
    if($type == "小型汽车")
        return "K33";

    return "K33";
}

/**
 * PHP将网页上的图片攫取到本地存储
 * @param $imgUrl  图片url地址
 * @param string $saveDir 本地存储路径 默认存储在当前路径+images/
 * @param null $fileName 图片存储到本地的文件名
 * @return mix 图片的相对路径
 */

function crabImage($imgUrl, $saveDir='images/', $fileName=null){
    if(empty($imgUrl)){
        return false;
    }

    //获取图片信息大小
    $imgSize = getImageSize($imgUrl);
    if(!in_array($imgSize['mime'],array('image/jpg', 'image/gif', 'image/png', 'image/jpeg'),true)){
        return false;
    }

    //获取后缀名
    $_mime = explode('/', $imgSize['mime']);
    $_ext = '.'.end($_mime);

    if(empty($fileName)){  //生成唯一的文件名
        $fileName = uniqid(time(),true).$_ext;
    }

    //开始攫取
    ob_start();
    readfile($imgUrl);
    $imgInfo = ob_get_contents();
    ob_end_clean();
    if(!file_exists($saveDir)){
        mkdir($saveDir,0777,true);
    }

    $fp = fopen($saveDir.$fileName, 'a');
    $imgLen = strlen($imgInfo);    //计算图片源码大小
    $_inx = 1024;   //每次写入1k
    $_time = ceil($imgLen/$_inx);
    for($i=0; $i<$_time; $i++){
        fwrite($fp,substr($imgInfo, $i*$_inx, $_inx));
    }
    fclose($fp);
    return $saveDir.$fileName;
}
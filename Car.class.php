<?php

/**
 * 通过停车场的车辆.
 * User: laiguanhui
 * Date: 2019-06-17
 * Time: 8:57
 */
class Car
{
    private $passTime, $carType='K33', $license='', $licenseColor='2', $picPath1='',  $driverWayType='', $backLicense='', $backLicenseColor='', $identical='', $carColor='', $carBrand='', $carShape='', $picPath2='', $picPath3='',  $featurePic='', $driverPic='', $copilotPic='', $sendFlag='', $inputTime= '';

    /**
     * @return string 车道类型
     */
    public function getDriverWayType()
    {
        return $this->driverWayType;
    }

    /**
     * @param string $driverWayType 车道类型
     */
    public function setDriverWayType($driverWayType)
    {
        $this->driverWayType = $driverWayType;
    }

    /**
     * @return string 尾部号牌号码
     */
    public function getBackLicense()
    {
        return $this->backLicense;
    }

    /**
     * @param string $backLicense 尾部号牌号码
     */
    public function setBackLicense($backLicense)
    {
        $this->backLicense = $backLicense;
    }

    /**
     * @return string 尾部车牌颜色
     */
    public function getBackLicenseColor()
    {
        return $this->backLicenseColor;
    }

    /**
     * @param string $backLicenseColor 尾部车牌颜色
     */
    public function setBackLicenseColor($backLicenseColor)
    {
        $this->backLicenseColor = $backLicenseColor;
    }

    /**
     * @return string 号牌一致
     */
    public function getIdentical()
    {
        return $this->identical;
    }

    /**
     * @param string $identical 号牌一致
     */
    public function setIdentical($identical)
    {
        $this->identical = $identical;
    }

    /**
     * @return string 车身颜色
     */
    public function getCarColor()
    {
        return $this->carColor;
    }

    /**
     * @param string $carColor 车身颜色
     */
    public function setCarColor($carColor)
    {
        $this->carColor = $carColor;
    }

    /**
     * @return string 车辆品牌
     */
    public function getCarBrand()
    {
        return $this->carBrand;
    }

    /**
     * @param string $carBrand 车辆品牌
     */
    public function setCarBrand($carBrand)
    {
        $this->carBrand = $carBrand;
    }

    /**
     * @return string 车辆外形
     */
    public function getCarShape()
    {
        return $this->carShape;
    }

    /**
     * @param string $carShape 车辆外形
     */
    public function setCarShape($carShape)
    {
        $this->carShape = $carShape;
    }

    /**
     * @return string 过车图片路径
     */
    public function getPicPath2()
    {
        return $this->picPath2;
    }

    /**
     * @param string $picPath2 过车图片路径
     */
    public function setPicPath2($picPath2)
    {
        $this->picPath2 = $picPath2;
    }

    /**
     * @return string 过车图片路径
     */
    public function getPicPath3()
    {
        return $this->picPath3;
    }

    /**
     * @param string $picPath3 过车图片路径
     */
    public function setPicPath3($picPath3)
    {
        $this->picPath3 = $picPath3;
    }

    /**
     * @return string 特征图片
     */
    public function getFeaturePic()
    {
        return $this->featurePic;
    }

    /**
     * @param string $featurePic 特征图片
     */
    public function setFeaturePic($featurePic)
    {
        $this->featurePic = $featurePic;
    }

    /**
     * @return string 驾驶员图片定位信息
     */
    public function getDriverPic()
    {
        return $this->driverPic;
    }

    /**
     * @param string $driverPic 驾驶员图片定位信息
     */
    public function setDriverPic($driverPic)
    {
        $this->driverPic = $driverPic;
    }

    /**
     * @return string 副驾驶座位图片定位信息
     */
    public function getCopilotPic()
    {
        return $this->copilotPic;
    }

    /**
     * @param string $copilotPic 副驾驶座位图片定位信息
     */
    public function setCopilotPic($copilotPic)
    {
        $this->copilotPic = $copilotPic;
    }

    /**
     * @return string 发送标志（1位字符, 0-正常，1-滞后发送）
     */
    public function getSendFlag()
    {
        return $this->sendFlag;
    }

    /**
     * @param string $sendFlag 发送标志（1位字符, 0-正常，1-滞后发送）
     */
    public function setSendFlag($sendFlag)
    {
        $this->sendFlag = $sendFlag;
    }

    /**
     * @return string 入库时间（格式如“2011-11-11 11:11:11.233”）
     */
    public function getInputTime()
    {
        return $this->inputTime;
    }

    /**
     * @param string $inputTime  入库时间（格式如“2011-11-11 11:11:11.233”）
     */
    public function setInputTime($inputTime)
    {
        $this->inputTime = $inputTime;
    }

    /**
     * @return mixed 通过时间
     */
    public function getPassTime()
    {
        return $this->passTime;
    }

    /**
     * 设置通过时间,格式：2019-06-17 08:20:25
     * @param mixed $passTime
     */
    public function setPassTime($passTime)
    {
        $this->passTime = $passTime;
    }

    /**
     * 获取车辆类型
     * @return string
     */
    public function getCarType()
    {
        return $this->carType;
    }

    /**
     * 设置车辆类型
     * @param string $carType
     */
    public function setCarType($carType)
    {
        $this->carType = $carType;
    }

    /**
     * 获取车牌类别
     * @return mixed
     */
    public function getLicense()
    {
        return $this->license;
    }

    /**
     * 设置车牌类别
     * @param mixed $license
     */
    public function setLicense($license)
    {
        $this->license = $license;
    }

    /**
     * 获取车牌颜色
     * @return string
     */
    public function getLicenseColor()
    {
        return $this->licenseColor;
    }

    /**
     * 设置车牌颜色
     * @param string $licenseColor
     */
    public function setLicenseColor($licenseColor)
    {
        $this->licenseColor = $licenseColor;
    }

    /**
     * 获取抓拍图片的相对路径
     * @return mixed
     */
    public function getPicPath1()
    {
        return $this->picPath1;
    }

    /**
     * @param mixed $picPath1 设置抓拍图片的相对路径
     */
    public function setPicPath1($picPath1)
    {
        $this->picPath1 = $picPath1;
    }

    /**
     * 将通过车辆的5个属性转化为Array
     */
    public function toCarArray(){
        if(!isset($this->passTime))
            $this->passTime = date("Y-m-d H:i:s");
        return array(
            'passTime' => $this->passTime,  //  通过时间
            'carType' => $this->carType,     //车辆类型
            'license' => $this->license,    //车牌号码
            'licenseColor' => $this->licenseColor,  // 车牌颜色
            'driverWayType' => $this->driverWayType,
            'backLicense' => $this->backLicense,
            'backLicenseColor' => $this->backLicenseColor,
            'identical' => $this->identical,
            'carColor' => $this->carColor,
            'carBrand' => $this->carBrand,
            'carShape' => $this->carShape,
            'picPath1' => $this->picPath1,   //过车图片路径
            'picPath2' => $this->picPath2,
            'picPath3' => $this->picPath3,
            'featurePic' => $this->featurePic,
            'driverPic' => $this->driverPic,
            'copilotPic' => $this->copilotPic,
            'sendFlag' => $this->sendFlag,
            'inputTime' => $this->inputTime,
        );
    }
}
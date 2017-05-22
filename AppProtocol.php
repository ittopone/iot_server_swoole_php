<?php
/**
 * Created by PhpStorm.
 * User: yjw
 * Date: 17-3-24
 * Time: 下午6:11
 */

namespace ITTOPONE;

//包含头文件
include_once("debug.php");
include_once("AppClient.php");

/**
 * Class AppProtocol
 * @package ITTOPONE
 */
class AppProtocol
{
    private $mObj_json;
    private $mJsonError;

    /**
     * AppProtocol constructor.
     * @param $data
     */
    function __construct(& $data)
    {
        $this->mObj_json = json_decode($data);
        $this->mJsonError = json_last_error();

        if(DEBUG_APPPROTOCOL)
        {
            if(JSON_ERROR_NONE != $this->mJsonError)
                echo 'AppProtocol:json_decode error.'.PHP_EOL;
            var_dump($this->mObj_json);
        }
    }

    /**
     * 返回json字符串解析结果
     * @return int
     */
    function getJsonError()
    {
        return $this->mJsonError;
    }

    /**
     * 获取指令cmd值
     * @return mixed
     */
    function getCmdValue()
    {
        if(DEBUG_APPPROTOCOL)
        {
            echo 'AppProtocol:getCmdValue is '.$this->mObj_json->{"cmd"}.PHP_EOL;
        }

        return $this->mObj_json->{"cmd"};
    }

    /**
     * 获取用户昵称
     * @return mixed
     */
    function getUname()
    {
        if(DEBUG_APPPROTOCOL)
        {
            echo 'AppProtocol:getUname is '.$this->mObj_json->{"data"}->{"uname"}.PHP_EOL;
        }

        return $this->mObj_json->{"data"}->{"uname"};
    }

    /**
     * 获取手机密码
     * @return mixed
     */
    function getPhoneNum()
    {
        if(DEBUG_APPPROTOCOL)
        {
            echo 'AppProtocol:getPhoneNum is '.$this->mObj_json->{"data"}->{"phone"}.PHP_EOL;
        }

        return $this->mObj_json->{"data"}->{"phone"};
    }

    /**
     * 获取密码
     * @return mixed
     */
    function getPswd()
    {
        if(DEBUG_APPPROTOCOL)
        {
            echo 'AppProtocol:getPswd is '.$this->mObj_json->{"data"}->{"pswd"}.PHP_EOL;
        }

        return $this->mObj_json->{"data"}->{"pswd"};
    }

    /**
     * 获取设备类型
     * @return mixed
     */
    function getDevType()
    {
        if(DEBUG_APPPROTOCOL)
        {
            echo 'AppProtocol:getDevType is '.$this->mObj_json->{"data"}->{"devType"}.PHP_EOL;
        }

        return $this->mObj_json->{"data"}->{"devType"};
    }

    /**
     * 获取设备系列
     * @return mixed
     */
    function getDevSeries()
    {
        if(DEBUG_APPPROTOCOL)
        {
            echo 'AppProtocol:getDevSeries is '.$this->mObj_json->{"data"}->{"devSeries"}.PHP_EOL;
        }

        return $this->mObj_json->{"data"}->{"devSeries"};
    }

    /**
     * 获取设备ID
     * @return mixed
     */
    function getDevID()
    {
        if(DEBUG_APPPROTOCOL)
        {
            echo 'AppProtocol:getDevID is '.$this->mObj_json->{"data"}->{"devID"}.PHP_EOL;
        }

        return $this->mObj_json->{"data"}->{"devID"};
    }

    /**
     * 获取设备名称
     * @return mixed
     */
    function getDevName()
    {
        if(DEBUG_APPPROTOCOL)
        {
            echo 'AppProtocol:getDevName is '.$this->mObj_json->{"data"}->{"devName"}.PHP_EOL;
        }

        return $this->mObj_json->{"data"}->{"devName"};
    }

    /**
     * 获取手机系统类型
     * @return mixed
     */
    function getSystem()
    {
        if(DEBUG_APPPROTOCOL)
        {
            echo 'AppProtocol:getSystem is '.$this->mObj_json->{"data"}->{"sys"}.PHP_EOL;
        }

        return $this->mObj_json->{"data"}->{"sys"};
    }

    /**
     * 获取app名称
     * @return mixed
     */
    function getAppName()
    {
        if(DEBUG_APPPROTOCOL)
        {
            echo 'AppProtocol:getAppName is '.$this->mObj_json->{"data"}->{"appName"}.PHP_EOL;
        }

        return $this->mObj_json->{"data"}->{"appName"};
    }

    /**
     * 获取需要透传的数据
     * @return mixed
     */
    function getDirData()
    {
        if(DEBUG_APPPROTOCOL)
        {
            echo 'AppProtocol:getDirData is '.$this->mObj_json->{"data"}->{"dirData"}.PHP_EOL;
        }

        return $this->mObj_json->{"data"}->{"dirData"};
    }

    /**
     * 获取校验码
     * @return mixed
     */
    function getCode()
    {
        if(DEBUG_APPPROTOCOL)
        {
            echo 'AppProtocol:getCode is '.$this->mObj_json->{"data"}->{"code"}.PHP_EOL;
        }

        return $this->mObj_json->{"data"}->{"code"};
    }

    /**
     * 获取验证码用途
     * @return mixed
     */
    function getUse()
    {
        if(DEBUG_APPPROTOCOL)
        {
            echo 'AppProtocol:getUse is '.$this->mObj_json->{"data"}->{"use"}.PHP_EOL;
        }

        return $this->mObj_json->{"data"}->{"use"};
    }

    /**
     * 获取推送消息类型
     * @return mixed
     */
    function getMsgType()
    {
        if(DEBUG_APPPROTOCOL)
        {
            echo 'AppProtocol:getMsgType is '.$this->mObj_json->{"data"}->{"msgType"}.PHP_EOL;
        }

        return $this->mObj_json->{"data"}->{"msgType"};
    }

    /**
     * 获取推送消息序号
     * @return mixed
     */
    function getMsgNum()
    {
        if(DEBUG_APPPROTOCOL)
        {
            echo 'AppProtocol:getMsgNum is '.$this->mObj_json->{"data"}->{"num"}.PHP_EOL;
        }

        return $this->mObj_json->{"data"}->{"num"};
    }
}

function test_AppProtocol()
{
    //手机端注册
    $register = '{"cmd":1,"data":{"uname":"zhangsan","phone":"18825221627","pswd":"123456"}}';
    $appProtocol = new AppProtocol($register);
    $appProtocol->getCmdValue();
    $appProtocol->getUname();
    $appProtocol->getPhoneNum();
    $appProtocol->getPswd();

    $respond1 = array('cmd' => 2, 'data' => array('ret' => 2));
    echo 'respond1:'.json_encode($respond1).PHP_EOL;

    $respond2 = array('cmd' => 2,
        'data' => array('ret' => 2),
        'devList' => array("123456", "devname", 1, 1)
    );
    echo 'respond2:'.json_encode($respond2).PHP_EOL;
}

if(DEBUG_APPPROTOCOL)
{
    test_AppProtocol();
}

?>

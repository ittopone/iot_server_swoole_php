<?php
/**
 * Created by PhpStorm.
 * User: yjw
 * Date: 17-3-24
 * Time: 下午6:12
 */

namespace ITTOPONE;

//包含头文件
include_once("debug.php");
include_once("MyGlobal.php");

//常量定义
defined('YD_SOI') or define('YD_SOI', 0x7e);//起始位标志
defined('YD_EOI') or define('YD_EOI', 0x0d);//结束码
defined('YD_VER') or define('YD_VER', 0x20);//通信协议版本号

defined('MIN_LEN_PROTOCOL') or define('MIN_LEN_PROTOCOL', 18);//协议最小长度
defined('MAX_LEN_PROTOCOL') or define('MAX_LEN_PROTOCOL', 1024);//协议最大长度

defined('DEV_TYPE_SPC') or define('DEV_TYPE_SPC', 'spc');//spc设备类型
defined('CID1_SPC') or define('CID1_SPC', 0xD1);//spc设备类型值

/**
 * Class DevProtocol
 * @package ITTOPONE
 */
class DevProtocol
{
    private $mSOI;
    private $mVER;
    private $mADR;
    private $mCID1;
    private $mCID2;
    private $mLENGTH;
    private $mINFO;
    private $mCHKSUM;
    private $mEOI;

    /**
     * 协议打包
     * @param $ADR
     * @param $CID1
     * @param $CID2
     * @param $INFO
     * @return string
     */
    function packData()
    {
        $this->mSOI = YD_SOI;
        $this->mEOI = YD_EOI;
        $this->mVER = YD_VER;

        //填充LENGTH
        $this->setLENGTH(count($this->mINFO) * 2);
        //填充CHKSUM
        $this->setCHKSUM();

        //打包数据
        $protocol = '';
        $protocol .= chr($this->mSOI);
        $protocol .= sprintf('%02X', $this->mVER);
        $protocol .= sprintf('%02X', $this->mADR);
        $protocol .= sprintf('%02X', $this->mCID1);
        $protocol .= sprintf('%02X', $this->mCID2);
        for($i = 0; $i < 2; $i++)
        {
            $protocol .= sprintf('%02X', $this->mLENGTH[$i]);
        }
        for($i = 0; $i < count($this->mINFO); $i++)
        {
            $protocol .= sprintf('%02X', $this->mINFO[$i]);
        }
        for($i = 0; $i < 2; $i++)
        {
            $protocol .= sprintf('%02X', $this->mCHKSUM[$i]);
        }
        $protocol .= chr($this->mEOI);

        return $protocol;
    }

    /**
     * 设置协议中的LENGTH（包括LENID和LCHKSUM）
     * @param $LENID
     */
    function setLENGTH($LENID)
    {
        $D11_8 = ($LENID >> 8) & 0x0f;
        $D7_4 = ($LENID >> 4) & 0x0f;
        $D3_0 = $LENID & 0x0f;

        $LCHKSUM = ((~(($D11_8 + $D7_4 + $D3_0) % 16)) + 1) & 0x0f;

        $LENGTH = (($LENID & 0x0fff) + (($LCHKSUM << 12) & 0xf000)) & 0xffff;

        $this->mLENGTH[0] = ($LENGTH >> 8) & 0xff;
        $this->mLENGTH[1] = $LENGTH & 0xff;
    }

    /**
     * 设置协议中的CHKSUM
     */
    function setCHKSUM()
    {
        $sum = 0;

        $hexStr = sprintf('%02X', $this->mVER);
        $sum += ord($hexStr[0]) + ord($hexStr[1]);

        $hexStr = sprintf('%02X', $this->mADR);
        $sum += ord($hexStr[0]) + ord($hexStr[1]);

        $hexStr = sprintf('%02X', $this->mCID1);
        $sum += ord($hexStr[0]) + ord($hexStr[1]);

        $hexStr = sprintf('%02X', $this->mCID2);
        $sum += ord($hexStr[0]) + ord($hexStr[1]);

        for($i = 0; $i < 2; $i++)
        {
            $hexStr = sprintf('%02X', $this->mLENGTH[$i]);
            $sum += ord($hexStr[0]) + ord($hexStr[1]);
        }

        for ($i = 0; $i < count($this->mINFO); $i++)
        {
            $hexStr = sprintf('%02X', $this->mINFO[$i]);
            $sum += ord($hexStr[0]) + ord($hexStr[1]);
        }

        $sum = (~($sum % 65536)) + 1;

        $this->mCHKSUM[0] = ($sum >> 8) & 0xff;
        $this->mCHKSUM[1] = $sum & 0xff;
    }

    //协议解析
    function unpackData(& $protocol)
    {
        $len = strlen($protocol);

        //检测协议长度是否正确
        if(($len < MIN_LEN_PROTOCOL) || ($len > MAX_LEN_PROTOCOL))
        {
            if (DEBUG_DEVPROTOCOL)
            {
                echo 'DevProtocol:unpackData protocol len error.'.PHP_EOL;
            }

            return false;
        }

        //检测头尾标识是否正确
        if((YD_SOI != ord($protocol[0])) ||(YD_EOI != ord($protocol[$len - 1])))
        {
            if (DEBUG_DEVPROTOCOL)
            {
                echo 'DevProtocol:unpackData head or tail flag error.'.PHP_EOL;
            }

            return false;
        }

        //验证CHKSUM
        if($this->calculateCHKSUM($protocol) != $this->getCHKSUM($protocol))
        {
            if (DEBUG_DEVPROTOCOL)
            {
                echo 'DevProtocol:unpackData CHKSUM error.'.PHP_EOL;
            }

            return false;
        }

        //获取长度并验证校验码LCHKSUM
        $LENID = $this->getLENID($protocol);
        if($LENID < 0)
        {
            if (DEBUG_DEVPROTOCOL)
            {
                echo 'DevProtocol:unpackData LCHKSUM error.'.PHP_EOL;
            }

            return false;
        }

        //检测协议版本号是否正确
        if(YD_VER != hexdec($protocol[1].$protocol[2]) & 0xff)
        {
            if (DEBUG_DEVPROTOCOL)
            {
                echo 'DevProtocol:unpackData VER error.'.PHP_EOL;
            }

            return false;
        }

        //获取设备地址
        $this->mADR = hexdec($protocol[3].$protocol[4]) & 0xff;
        //获取设备类型
        $this->mCID1 = hexdec($protocol[5].$protocol[6]) & 0xff;
        //获取返回码RTN
        $this->mCID2 = hexdec($protocol[7].$protocol[8]) & 0xff;

        //获取INFO
        for($i = 0; $i < ($LENID / 2); $i++)
        {
            $this->mINFO[$i] = hexdec($protocol[13 + 2 * $i].$protocol[14 + 2 * $i]) & 0xff;
        }

        return true;
    }

    /**
     * 计算并返回校验码
     * @param $protocol
     * @return int
     */
    function calculateCHKSUM(& $protocol)
    {
        $sum = 0;
        for($i = 1; $i < (strlen($protocol) - 5); $i++)
        {
            $sum += ord($protocol[$i]);
        }

        if(DEBUG_DEVPROTOCOL)
        {
            echo 'DevProtocol:calculateCHKSUM '.sprintf('%04X', ((~($sum % 65536)) + 1) & 0xffff).PHP_EOL;
        }

        return ((~($sum % 65536)) + 1) & 0xffff;
    }

    /**
     * 获取协议中的校验码
     * @param $protocol
     * @return int
     */
    function getCHKSUM(& $protocol)
    {
        $len = strlen($protocol);

        $this->mCHKSUM[0] = hexdec($protocol[$len - 5].$protocol[$len - 4]) & 0xff;
        $this->mCHKSUM[1] = hexdec($protocol[$len - 3].$protocol[$len - 2]) & 0xff;

        if(DEBUG_DEVPROTOCOL)
        {
            echo 'DevProtocol:getCHKSUM '.sprintf('%02X%02X', $this->mCHKSUM[0], $this->mCHKSUM[1]).PHP_EOL;
        }

        return (($this->mCHKSUM[0] << 8) + $this->mCHKSUM[1]) & 0xffff;
    }

    /**
     * 获取长度并验证校验码LCHKSUM
     * @return int
     */
    function getLENID(& $protocol)
    {
        $this->mLENGTH[0] = hexdec($protocol[9].$protocol[10]) & 0xff;
        $this->mLENGTH[1] = hexdec($protocol[11].$protocol[12]) & 0xff;

        //取得LENID
        $LENID = (($this->mLENGTH[0] << 8) + $this->mLENGTH[1]) & 0x0fff;
        //取得LCHKSUM
        $LCHKSUM = ($this->mLENGTH[0] >> 4) & 0x0f;

        $D11_8 = ($LENID >> 8) & 0x0f;
        $D7_4 = ($LENID >> 4) & 0x0f;
        $D3_0 = $LENID & 0x0f;

        if($LCHKSUM != (((~(($D11_8 + $D7_4 + $D3_0) % 16)) + 1) & 0x0f))
        {
            if (DEBUG_DEVPROTOCOL)
            {
                echo 'DevProtocol:getLENID LCHKSUM error'.PHP_EOL;
            }
            return -1;
        }

        if (DEBUG_DEVPROTOCOL)
        {
            echo 'DevProtocol:getLENID '.sprintf('%04X', $LENID).PHP_EOL;
        }

        return $LENID;
    }

    /**
     * 获取设备地址
     * @return mixed
     */
    function getADR()
    {
        if (DEBUG_DEVPROTOCOL)
        {
            echo 'DevProtocol:getADR '.sprintf('%02X', $this->mADR).PHP_EOL;
        }

        return $this->mADR;
    }

    /**
     * 设置设备地址
     * @param $ADR
     */
    function setADR($ADR)
    {
        $this->mADR = $ADR;
    }

    /**
     * 获取INFO[0]
     * @return mixed
     */
    function getINFO0()
    {
        if (DEBUG_DEVPROTOCOL)
        {
            echo 'DevProtocol:getINFO0 '.sprintf('%02X', $this->mINFO[0]).PHP_EOL;
        }

        return $this->mINFO[0];
    }

    /**
     * 获取设置的新设备地址
     * @return mixed
     */
    function getSetADR()
    {
        if (DEBUG_DEVPROTOCOL)
        {
            echo 'DevProtocol:getSetADR '.sprintf('%02X', $this->mINFO[0]).PHP_EOL;
        }

        return $this->mINFO[1];
    }

    /**
     * 获取CID1值
     * @return mixed
     */
    function getCID1()
    {
        if (DEBUG_DEVPROTOCOL)
        {
            echo 'DevProtocol:getCID1 '.sprintf('%02X', $this->mCID2).PHP_EOL;
        }
        return $this->mCID1;
    }

    /**
     * 设置CID1值
     * @param $CID1
     */
    function setCID1($CID1)
    {
        $this->mCID1 = $CID1;
    }

    /**
     * 获取CID2值
     * @return mixed
     */
    function getCID2()
    {
        if (DEBUG_DEVPROTOCOL)
        {
            echo 'DevProtocol:getCID2 '.sprintf('%02X', $this->mCID2).PHP_EOL;
        }

        return $this->mCID2;
    }

    /**
     * 设置CID2值
     * @param $CID2
     */
    function setCID2($CID2)
    {
        $this->mCID2 = $CID2;
    }

    /**
     * 设置INFO内容
     * @param $INFO
     */
    function setINFO($INFO)
    {
        $this->mINFO = $INFO;
    }

    /**
     * 获取设备类型、设备系列、设备ID、固件版本
     * @param $dev_type
     * @param $dev_series
     * @param $dev_id
     * @param $version
     */
    function getDevTypeSeriesIDVer(& $dev_type, & $dev_series, & $dev_id, &$version)
    {
        $arr_info = explode(",", MyGlobal::bytes_to_string($this->mINFO));
        $dev_type = $arr_info[0];
        $dev_series = $arr_info[1];
        $dev_id = $arr_info[2];
        $version = $arr_info[3];
    }

    /**
     * 获取设备的实时报警信息
     * @param $alarmInfo
     * @return bool
     */
    function getRealTimeAlarmInfo(& $alarmInfo)
    {
        switch ($this->mCID1)
        {
            case CID1_SPC:
                return $this->getSpcAlarmInfo($alarmInfo);
            default:
                return false;
        }
    }

    /**
     * spc设备报警信息
     * @return string
     */
    function getSpcAlarmInfo(& $alarmInfo)
    {
        $flag = false;
        for ($i = 0; $i < 7; $i++)
        {
            if (($this->mINFO[0] >> $i) & 0x01)
            {//如果有告警
                switch ($i)
                {
                    case 0:
                        $flag = true;
                        $alarmInfo .= '过压报警 ';
                        break;
                    case 1:
                        $flag = true;
                        $alarmInfo .= '欠压报警 ';
                        break;
                    case 2:
                        $flag = true;
                        $alarmInfo .= '电流一级报警 ';
                        break;
                    case 3:
                        $flag = true;
                        $alarmInfo .= '电流二级报警 ';
                        break;
                    case 4:
                        $flag = true;
                        $alarmInfo .= '短路报警 ';
                        break;
                    case 5:
                        $flag = true;
                        $alarmInfo .= '漏电流报警 ';
                        break;
                    case 6:
                        $flag = true;
                        $alarmInfo .= '超温报警 ';
                        break;
                    default:
                        break;
                }
            }
        }

        return $flag;
    }
}

function test_DevProtocol()
{
    $arr_info = explode(",", 'dev type,dev series,123adb23,1.1.1');
    $dev_type = $arr_info[0];
    $dev_series = $arr_info[1];
    $dev_id = $arr_info[2];
    $version = $arr_info[3];
    echo 'dev_type:'.$dev_type.', '.'dev_series:'.$dev_series.', '.'dev_id:'.$dev_id.', '.'ver:'.$version.PHP_EOL;

    $protocol = new DevProtocol();
    $protocol->setINFO(array(0x04));
    $protocol->getSpcAlarmInfo($alarmInfo);
    echo $alarmInfo.PHP_EOL;
    return true;
}

if(DEBUG_DEVPROTOCOL)
{
    test_DevProtocol();
}

?>
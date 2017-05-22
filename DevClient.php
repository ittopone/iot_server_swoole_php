<?php
/**
 * Created by PhpStorm.
 * User: yjw
 * Date: 17-3-24
 * Time: 下午4:28
 */

namespace ITTOPONE;

//包含头文件
include_once("debug.php");
include_once("DevProtocol.php");
include_once("Database.php");

//常量定义
defined('CMD_DEV_LOGIN') or define('CMD_DEV_LOGIN', 0x88);//设备端登录指令

/**
 * Class DevClient
 * @package ITTOPONE
 */
class DevClient
{
    private $mServ;
    private $mFd;
    private $mProtocol;
    private $mData;

    /**
     * DevClient constructor.
     * @param $serv
     * @param $fd
     * @param $data
     */
    function __construct(& $serv, $fd, & $data)
    {
        $this->mServ = $serv;
        $this->mFd = $fd;
        $this->mProtocol = new DevProtocol();
        $this->mData = $data;

        if(chr(YD_SOI).chr(YD_EOI) == $data)
        {//心跳包
            $this->mServ->send($this->mFd, $data);
            return;
        }

        //数据解包
        if(! $this->mProtocol->unpackData($this->mData))
        {//释放请求，并给手机端返回
            /*解决设备返回错误指令无法及时释放锁的BUG*/
            if(Database::dbGetDevClientWithoutADR($devClients, $this->mFd))
            {
                foreach ($devClients as $index => $client)
                {
                    //删除请求
                    Database::dbDeleteAppResDev($client[0], $client[1], $client[2]);
                }
            }

            return;
        }

        //解析指令
        $this->parseCmd($this->mProtocol->getCID2());
    }

    /**
     * 解析指令
     * @param $cmd
     */
    function parseCmd($cmd)
    {
        switch ($cmd)
        {
            case CMD_DEV_LOGIN://设备端登录指令
                $this->handleDevLogin();
                break;
            default://透传数据给手机端
                $this->handleDirectTransport();
                break;
        }
    }

    /**
     * 处理设备端登录
     */
    function handleDevLogin()
    {
        $this->mProtocol->getDevTypeSeriesIDVer($dev_type, $dev_series, $dev_id, $version);
        $ADR = $this->mProtocol->getADR();

        //检测设备是否已经登录了
        if(Database::dbGetDevClientFd($dev_type, $dev_series, $dev_id, $fd))
        {//设备端已经登录
            if ($fd == $this->mFd)
            {//重复登录
                $this->mProtocol->setINFO(array(0x01));
                $this->mServ->send($this->mFd, $this->mProtocol->packData());

                if (DEBUG_DEVCLIENT)
                {
                    echo 'DevClient:handleDevLogin dev relogin success.'.PHP_EOL;
                }
            }
            else
            {//异地登录
                if (Database::dbUpdateDevClientAdrFd($dev_type, $dev_series, $dev_id, $ADR, $this->mFd))
                {//更新成功
                    $this->mProtocol->setINFO(array(0x01));
                    $this->mServ->send($this->mFd, $this->mProtocol->packData());

                    if (DEBUG_DEVCLIENT)
                    {
                        echo 'DevClient:handleDevLogin dev other login success.'.PHP_EOL;
                    }
                }
                else
                {//更新失败
                    $this->mProtocol->setINFO(array(0x00));
                    $this->mServ->send($this->mFd, $this->mProtocol->packData());

                    if (DEBUG_DEVCLIENT)
                    {
                        echo 'DevClient:handleDevLogin dev other login fail.'.PHP_EOL;
                    }
                }
            }
        }
        else
        {
            //设备端未登录
            if (Database::dbAddDevClient($dev_type, $dev_series, $dev_id, $ADR, $this->mFd))
            {//添加成功
                $this->mProtocol->setINFO(array(0x01));
                $this->mServ->send($this->mFd, $this->mProtocol->packData());
                if (DEBUG_DEVCLIENT)
                {
                    echo 'DevClient:handleDevLogin dev login success.'.PHP_EOL;
                }

                //更新软件版本号
                Database::dbUpdateDevVersion($dev_type, $dev_series, $dev_id, $version);
            }
            else
            {//添加失败
                $this->mProtocol->setINFO(array(0x00));
                $this->mServ->send($this->mFd, $this->mProtocol->packData());
                if (DEBUG_DEVCLIENT)
                {
                    echo 'DevClient:handleDevLogin dev login fail.'.PHP_EOL;
                }
            }
        }
    }

    /**
     * 处理设备端数据透传
     */
    function handleDirectTransport()
    {
        //获取设备地址
        $ADR = $this->mProtocol->getADR();
        //根据设备地址和fd获取设备类型、系列和ID
        if(Database::dbGetDevClient($dev_type, $dev_series, $dev_id, $ADR, $this->mFd))
        {//设备端已登录

            if(CMD_SET_DEV_ADR == $this->mProtocol->getCID2())
            {//设备端设置地址指令
                if(0 == $this->mProtocol->getINFO0())
                {
                    //获取新设备地址
                    $setADR = $this->mProtocol->getSetADR();
                    //更新到数据库中
                    Database::dbUpdateDevClientAdrFd($dev_type, $dev_series, $dev_id, $setADR, $this->mFd);
                }
            }
            else if(CMD_DEV_RECOVERY == $this->mProtocol->getCID2())
            {//设备恢复出厂设置
                $RTN = $this->mProtocol->getINFO0();
                if (RET_OK == $RTN)
                {
                    //删除设备绑定
                    $count = 6666;
                    while((! Database::dbDelDevice($dev_type, $dev_series, $dev_id)) and ($count > 0))
                    {
                        if (DEBUG_DEVCLIENT)
                        {
                            echo 'DevClient:handleDirectTransport dev recovery unbind fail.'.PHP_EOL;
                        }

                        $count--;
                    }
                    if ($count > 0)
                    {
                        //删除设备共享人
                        Database::dbDeleteShareDev($dev_type, $dev_series, $dev_id);
                        //删除消息推送（主要用在解绑设备时的操作）
                        Database::dbDeletePushMsgs($dev_type, $dev_series, $dev_id);
                    }
                }
            }
            else if (CMD_GET_DEV_ALARM == $this->mProtocol->getCID2())
            {//设备上报告警状态
                //是否使用极光推送发送报警信息
                if (ENABLE_JPUSH)
                {
                    //获取设备的实时报警信息
                    if($this->mProtocol->getRealTimeAlarmInfo($alarmInfo))
                    {//设备出现告警
                        //获取设备类型名称
                        $devTypeName = MyGlobal::getDevTypeName($dev_type);
                        //获取设备名称
                        Database::dbGetDevName($dev_type, $dev_series, $dev_id, $dev_name);

                        if (DEBUG_DEVCLIENT)
                        {
                            echo 'DevClient:device alarm:'.$alarmInfo.PHP_EOL;
                        }

                        //打包推送的消息内容
                        $jp_data = $dev_name.'：'.$alarmInfo;
                        //打包extra
                        $jp_extra = array(
                            'devType' => $dev_type,
                            'devSeries' => $dev_series,
                            'devID' => $dev_id,
                            'devName' => $dev_name,
                            'pushTime' => date("Y-m-d H:i:s",time()),
                            'content' => $alarmInfo,
                            'msgType' => 'devAlarm',
                        );
                        //推送给设备归属人
                        if (Database::dbGetPhoneNumBindDev($dev_type, $dev_series, $dev_id, $jp_phone_bind))
                        {
                            //添加推送消息到数据库
                            Database::dbInsertPushMsg($dev_type, $dev_series, $dev_id, $dev_name, 'devAlarm',
                                $jp_phone_bind, $alarmInfo);
                            //极光推送
                            MyGlobal::jpush_send($jp_phone_bind, $jp_data, $jp_extra);
                        }
                        //推送给共享该设备的人
                        if (Database::dbGetShareDevUsers($dev_type, $dev_series, $dev_id, $share_user_list))
                        {
                            foreach ($share_user_list as $key_phone => $value_name)
                            {
                                //添加推送消息到数据库
                                Database::dbInsertPushMsg($dev_type, $dev_series, $dev_id, $dev_name, 'devAlarm', $key_phone, $alarmInfo);
                                //极光推送
                                MyGlobal::jpush_send($key_phone, $jp_data, $jp_extra);
                            }
                        }
                    }
                    else
                    {//设备运行正常
                        if (DEBUG_DEVCLIENT)
                        {
                            echo 'DevClient:device is running normally'.PHP_EOL;
                        }
                    }
                }

                return;
            }

            //查看设备被哪个用户请求
            if (Database::dbGetAppResDevPhoneTime($dev_type, $dev_series, $dev_id, $CID2, $phone_res, $time))
            {//获取发出该请求用户
                //删除请求
                Database::dbDeleteAppResDev($dev_type, $dev_series, $dev_id);

                //检测是否超过了请求时间
                if ((time() - strtotime($time)) > VALID_RES_SEC)
                {
                    if (DEBUG_DEVCLIENT)
                    {
                        echo 'DevClient:handleDirectTransport over res time.'.PHP_EOL;
                    }

                    return;
                }
                if (ENABLE_SERV_POLL_ALARM)
                {
                    //检测是否为服务器轮询设备告警状态返回的数据
                    if (SERV_ID == $phone_res)
                    {
                        if (DEBUG_DEVCLIENT)
                        {
                            echo 'DevClient:serv poll alarm of '.$dev_type.','.$dev_series.','.$dev_id.PHP_EOL;
                            echo 'DevClient:serv get alarmInfo is '.$this->mData.PHP_EOL;
                        }

                        //是否使用极光推送发送报警信息
                        if (ENABLE_JPUSH)
                        {
                            //获取设备的实时报警信息
                            if($this->mProtocol->getRealTimeAlarmInfo($alarmInfo))
                            {
                                //获取设备类型名称
                                $devTypeName = MyGlobal::getDevTypeName($dev_type);
                                //获取设备名称
                                Database::dbGetDevName($dev_type, $dev_series, $dev_id, $dev_name);

                                if (DEBUG_DEVCLIENT)
                                {
                                    echo 'DevClient:device alarm:'.$alarmInfo.PHP_EOL;
                                }
                            }
                            else
                            {
                                if (DEBUG_DEVCLIENT)
                                {
                                    echo 'DevClient:device is running normally'.PHP_EOL;
                                }
                            }
                        }

                        return;
                    }
                }

                //查看该手机号是否在线
                if (Database::dbGetAppClientFd($phone_res, $fd))
                {//手机端已登录
                    //开始透传数据
                    $respond = array('cmd' => CMD_DIRECT_TRANSPORT,
                        'data' => array(
                            'ret' => RET_OK,
                            'CID2' => $CID2,
                            "dirData" => $this->mData
                        )
                    );
                    $this->mServ->push($fd, json_encode($respond));

                    if(DEBUG_DEVCLIENT)
                    {
                        echo 'DevClient:handleDirectTransport transport over.'.PHP_EOL;
                    }
                }
                else
                {//手机端未登录
                    if (DEBUG_DEVCLIENT)
                    {
                        echo 'DevClient:handleDirectTransport res phone unlogin.'.PHP_EOL;
                    }

                    return;
                }
            }
            else
            {//该设备未被任何用户请求
                if (DEBUG_DEVCLIENT)
                {
                    echo 'DevClient:handleDirectTransport null res.'.PHP_EOL;
                }

                return;
            }
        }
        else
        {//设备端未登录
            //断开连接unpackData
            $this->mServ->close($this->mFd);

            if(DEBUG_DEVCLIENT)
            {
                echo 'DevClient:handleDirectTransport dev not login.'.PHP_EOL;
            }
        }
    }
}

?>
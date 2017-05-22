<?php
/**
 * Created by PhpStorm.
 * User: yjw
 * Date: 17-3-6
 * Time: 下午6:44
 */

namespace ITTOPONE;

//包含头文件
include_once("debug.php");
include_once("AppProtocol.php");
include_once("Database.php");

//常量定义
defined('CMD_REGISTER') or define('CMD_REGISTER', 1);//手机端注册指令
defined('CMD_LOGIN') or define('CMD_LOGIN', 2);//手机端登录指令
defined('CMD_MODIFY_UNAME') or define('CMD_MODIFY_UNAME', 3);//手机端修改用户昵称指令
defined('CMD_MODIFY_PSWD') or define('CMD_MODIFY_PSWD', 4);//手机端修改密码指令
defined('CMD_BIND_DEV') or define('CMD_BIND_DEV', 5);//手机端绑定设备指令
defined('CMD_UNBIND_DEV') or define('CMD_UNBIND_DEV', 6);//手机端解绑设备指令
defined('CMD_GET_DEV_LIST') or define('CMD_GET_DEV_LIST', 7);//手机端获取设备列表指令
defined('CMD_MODIFY_DEV_NAME') or define('CMD_MODIFY_DEV_NAME', 8);//手机端修改设备名称指令
defined('CMD_UPDATE_APP') or define('CMD_UPDATE_APP', 9);//手机端APP升级指令
defined('CMD_GET_DEV_BELONG') or define('CMD_GET_DEV_BELONG', 11);//手机端查询设备归属人
defined('CMD_SHARE_DEVICE') or define('CMD_SHARE_DEVICE', 12);//手机端分享设备
defined('CMD_CANCEL_SHARE_DEV') or define('CMD_CANCEL_SHARE_DEV', 13);//手机端取消设备分享
defined('CMD_GET_SHARE_DEV_LIST') or define('CMD_GET_SHARE_DEV_LIST', 14);//手机端获取分享的设备列表
defined('CMD_GET_SHARE_USER_LIST') or define('CMD_GET_SHARE_USER_LIST', 15);//手机端获取分享的联系人列表
defined('CMD_GET_VERIFY_CODE') or define('CMD_GET_VERIFY_CODE', 16);//手机端获取手机验证码
defined('CMD_GET_DEV_ONLINE_STATE') or define('CMD_GET_DEV_ONLINE_STATE', 17);//手机端获取某个设备在线状态指令
defined('CMD_ACNT_LOGIN_ON_OTHER_DEV') or define('CMD_ACNT_LOGIN_ON_OTHER_DEV', 18);//账号已在其他设备上登录指令
defined('CMD_GET_PUSH_MSG') or define('CMD_GET_PUSH_MSG', 19);//获取推送消息指令

defined('RET_FAIL') or define('RET_FAIL', 1);//失败返回码
defined('RET_RE_REG') or define('RET_RE_REG', 2);//已注册返回码
defined('RET_UN_REG') or define('RET_UN_REG', 3);//未注册返回码
defined('RET_PSWD_ERROR') or define('RET_PSWD_ERROR', 4);//密码错误返回码
defined('RET_RE_BIND_DEV') or define('RET_RE_BIND_DEV', 5);//重复绑定设备返回码
defined('RET_DEV_EMPTY') or define('RET_DEV_EMPTY', 6);//设备为空返回码
defined('RET_DATA_ERROR') or define('RET_DATA_ERROR', 7);//数据错误返回码
defined('RET_RE_LOGIN') or define('RET_RE_LOGIN', 8);//重复登录返回码
defined('RET_OTHER_LOGIN') or define('RET_OTHER_LOGIN', 9);//该帐号已在其他移动设备登录返回码
defined('RET_UN_LOGIN') or define('RET_UN_LOGIN', 10);//未登录返回码
defined('RET_OTHER_BIND_DEV') or define('RET_OTHER_BIND_DEV', 11);//设备已被其他账号绑定返回码
defined('RET_DEV_NOT_EXIST') or define('RET_DEV_NOT_EXIST', 12);//设备不存在返回码
defined('RET_DEV_NOT_BIND') or define('RET_DEV_NOT_BIND', 13);//未绑定该设备返回码
defined('RET_DEV_OFF_LINE') or define('RET_DEV_OFF_LINE', 14);//设备离线返回码
defined('RET_APP_OFF_LINE') or define('RET_APP_OFF_LINE', 15);//APP离线返回码
defined('RET_DEV_NOT_SHARE_TO_YOU') or define('RET_DEV_NOT_SHARE_TO_YOU', 16);//设备没有分享给你
defined('RET_ADR_ERROR') or define('RET_ADR_ERROR', 17);//设备地址错误
defined('RET_NO_PERMISSION') or define('RET_NO_PERMISSION', 18);//没有权限
defined('RET_DEV_BUSY') or define('RET_DEV_BUSY', 19);//设备正忙着处理其他人的请求
defined('RET_CODE_OUT_OF_DATE') or define('RET_CODE_OUT_OF_DATE', 20);//验证码已过期
defined('RET_CODE_ERROR') or define('RET_CODE_ERROR', 21);//验证码错误
defined('RET_NO_MORE_PUSH_MSG') or define('RET_NO_MORE_PUSH_MSG', 22);//没有更多的推送消息
defined('RET_GET_CODE_TOO_FAST') or define('RET_GET_CODE_TOO_FAST', 23);//获取短信验证码太频繁

defined('MAX_LEN_UNAME') or define('MAX_LEN_UNAME', 30);//用户昵称最大长度
defined('MAX_LEN_PSWD') or define('MAX_LEN_PSWD', 20);//密码最大长度
defined('MIN_LEN_PSWD') or define('MIN_LEN_PSWD', 8);//密码最小长度
defined('MAX_LEN_DEV_TYPE') or define('MAX_LEN_DEV_TYPE', 10);//设备类型最大长度
defined('MAX_LEN_DEV_SERIES') or define('MAX_LEN_DEV_SERIES', 10);//设备系列最大长度
defined('MAX_LEN_DEV_ID') or define('MAX_LEN_DEV_ID', 30);//设备ID最大长度
defined('MAX_LEN_DEV_NAME') or define('MAX_LEN_DEV_NAME', 30);//设备名称最大长度
defined('LEN_SMS_CODE') or define('LEN_SMS_CODE', 6);//短信验证码的长度

defined('ON_LINE') or define('ON_LINE', 1);//在线
defined('OFF_LINE') or define('OFF_LINE', 0);//离线

/**
 * Class AppClient
 * @package ITTOPONE
 */
class AppClient
{
    private $mServ;
    private $mFrame;
    private $mProtocol;

    /**
     * AppClient constructor.
     * @param $serv
     * @param $frame
     */
    function __construct(& $serv, & $frame)
    {
        if ("~~" == $frame->data)
        {//心跳包
            //返回心跳包
            $serv->push($frame->fd, $frame->data);
            return;
        }

        //初始化成员变量
        $this->mServ = $serv;
        $this->mFrame = $frame;

        //数据处理
        $this->mProtocol = new AppProtocol($frame->data);

        if(JSON_ERROR_NONE != $this->mProtocol->getJsonError())
            return;

        //解析指令
        $this->parseCmd();
    }

    /**
     * 给手机端返回操作结果
     * @param $cmd
     * @param $ret
     */
    function sendGeneralRespond($cmd, $ret)
    {
        $respond = array('cmd' => $cmd, 'data' => array('ret' => $ret));

        $this->mServ->push($this->mFrame->fd, json_encode($respond));
    }

    /**
     * 给手机端返回透传结果
     * @param $cmd
     * @param $ret
     * @param $CID2
     */
    function sendDirTransportRespond($cmd, $ret, $CID2)
    {
        $respond = array('cmd' => $cmd, 'data' => array('ret' => $ret, 'CID2' => $CID2));

        $this->mServ->push($this->mFrame->fd, json_encode($respond));
    }

    /**
     * 指令解析
     */
    function parseCmd()
    {
        switch ($this->mProtocol->getCmdValue())
        {
            case CMD_REGISTER://手机端注册指令
                $this->handleRegister();
                break;
            case CMD_LOGIN://手机端登录指令
                $this->handleLogin();
                break;
            case CMD_MODIFY_UNAME://手机端修改用户昵称指令
                $this->handleModifyUname();
                break;
            case CMD_MODIFY_PSWD://手机端修改密码指令
                $this->handleModifyPswd();
                break;
            case CMD_BIND_DEV://手机端绑定设备指令
                $this->handleBindDev();
                break;
            case CMD_UNBIND_DEV://手机端解绑设备指令
                $this->handleUnbindDev();
                break;
            case CMD_GET_DEV_LIST://手机端获取设备列表指令
                $this->handleGetDevList();
                break;
            case CMD_MODIFY_DEV_NAME://手机端修改设备名称指令
                $this->handleModifyDevName();
                break;
            case CMD_UPDATE_APP://手机端APP升级指令
                $this->handleUpdateApp();
                break;
            case CMD_DIRECT_TRANSPORT://手机端透传数据指令
                $this->handleDirectTransport();
                break;
            case CMD_GET_DEV_BELONG://手机端查询设备归属人
                $this->handleGetDevBelong();
                break;
            case CMD_SHARE_DEVICE:
                $this->handleShareDevice();//手机端分享设备
                break;
            case CMD_CANCEL_SHARE_DEV://手机端取消设备分享
                $this->handleCancelShareDev();
                break;
            case CMD_GET_SHARE_DEV_LIST://手机端获取分享的设备列表
                $this->handleGetShareDevList();
                break;
            case CMD_GET_SHARE_USER_LIST://手机端获取分享的联系人列表
                $this->handleGetShareUserList();
                break;
            case CMD_GET_VERIFY_CODE://手机端获取手机验证码
                $this->handleGetVerifyCode();
                break;
            case CMD_GET_DEV_ONLINE_STATE://手机端获取某个设备在线状态指令
                $this->handleGetDevOnlineState();
                break;
            case CMD_GET_PUSH_MSG://手机端获取推送消息指令
                $this->handleGetPushMsg();
                break;
            default:
                break;
        }
    }

    /**
     * 处理手机端注册
     */
    function handleRegister()
    {
        //检查手机号码是否符合规定
        $phone_num = $this->mProtocol->getPhoneNum();
        if(!preg_match('/^1[34578]\d{9}$/',$phone_num))
        {
            $this->sendGeneralRespond(CMD_REGISTER, RET_DATA_ERROR);

            if (DEBUG_APPCLIENT)
            {
                echo 'AppClient:handleRegister phoneNum error.'.PHP_EOL;
            }

            return;
        }

        //查找数据库看看该账户是否已经注册
        if(Database::dbCheckUserPhoneNumExist($phone_num))
        {//已经注册
            $this->sendGeneralRespond(CMD_REGISTER, RET_RE_REG);

            if (DEBUG_APPCLIENT)
            {
                echo 'AppClient:handleRegister already register.'.PHP_EOL;
            }
        }
        else
        {//未注册
            //检查用户昵称长度是否符合规定
            $uname = $this->mProtocol->getUname();
            if ((strlen($uname) > MAX_LEN_UNAME) || empty($uname))
            {
                $this->sendGeneralRespond(CMD_REGISTER, RET_DATA_ERROR);

                if (DEBUG_APPCLIENT)
                {
                    echo 'AppClient:handleRegister uname len error.'.PHP_EOL;
                }

                return;
            }
            //检查密码长度是否符合规定
            $pswd = $this->mProtocol->getPswd();
            if((strlen($pswd) < MIN_LEN_PSWD) || (strlen($pswd) > MAX_LEN_PSWD))
            {
                $this->sendGeneralRespond(CMD_REGISTER, RET_DATA_ERROR);

                if (DEBUG_APPCLIENT)
                {
                    echo 'AppClient:handleRegister pswd len error.'.PHP_EOL;
                }

                return;
            }
            //检查校验码是否符合规范
            $new_code = $this->mProtocol->getCode();
            if((strlen($new_code) != LEN_SMS_CODE))
            {
                $this->sendGeneralRespond(CMD_REGISTER, RET_DATA_ERROR);

                if (DEBUG_APPCLIENT)
                {
                    echo 'AppClient:handleRegister new_code len error.'.PHP_EOL;
                }

                return;
            }
            //获取数据库中保存的验证码和时间
            if (! Database::dbGetAppCodeTime($phone_num, $old_code, $time))
            {
                $this->sendGeneralRespond(CMD_REGISTER, RET_FAIL);

                if (DEBUG_APPCLIENT)
                {
                    echo 'AppClient:handleRegister no code.'.PHP_EOL;
                }

                return;
            }
            //检测验证码是否过期
            if (time() > (strtotime($time) + VALID_CODE_SEC))
            {//验证码已过期
                $this->sendGeneralRespond(CMD_REGISTER, RET_CODE_OUT_OF_DATE);

                if (DEBUG_APPCLIENT)
                {
                    echo 'AppClient:handleRegister code out of date now:'.time().', old:'.strtotime($time).PHP_EOL;
                }
                return;
            }
            //检测验证码是否正确
            if ($new_code != $old_code)
            {//验证码错误
                $this->sendGeneralRespond(CMD_REGISTER, RET_CODE_ERROR);

                if (DEBUG_APPCLIENT)
                {
                    echo 'AppClient:handleRegister code error.'.PHP_EOL;
                }
                return;
            }
            //添加到数据库中
            if(Database::dbAddNewUser($phone_num, $uname, $pswd))
            {//添加成功
                $this->sendGeneralRespond(CMD_REGISTER, RET_OK);

                if (DEBUG_APPCLIENT)
                {
                    echo 'AppClient:handleRegister register success.'.PHP_EOL;
                }
            }
            else
            {//添加失败
                $this->sendGeneralRespond(CMD_REGISTER, RET_FAIL);

                if (DEBUG_APPCLIENT)
                {
                    echo 'AppClient:handleRegister database error.'.PHP_EOL;
                }
            }
        }
    }

    /**
     * 处理手机端登录
     */
    function handleLogin()
    {
        $phone_num = $this->mProtocol->getPhoneNum();
        $pswd = $this->mProtocol->getPswd();

        //检查手机号码是否符合规定
        if(! preg_match('/^1[34578]\d{9}$/', $phone_num))
        {
            $this->sendGeneralRespond(CMD_LOGIN, RET_DATA_ERROR);

            if (DEBUG_APPCLIENT)
            {
                echo 'AppClient:handleLogin phoneNum error.'.PHP_EOL;
            }

            return;
        }

        //检查密码长度是否符合规定
        if((strlen($pswd) < MIN_LEN_PSWD) || (strlen($pswd) > MAX_LEN_PSWD))
        {
            $this->sendGeneralRespond(CMD_LOGIN, RET_DATA_ERROR);

            if (DEBUG_APPCLIENT)
            {
                echo 'AppClient:handleLogin pswd len error.'.PHP_EOL;
            }

            return;
        }

        //查询数据库看看该手机号是否已经注册
        if(! Database::dbCheckUserPhoneNumExist($phone_num))
        {
            $this->sendGeneralRespond(CMD_LOGIN, RET_UN_REG);

            if (DEBUG_APPCLIENT)
            {
                echo 'AppClient:handleLogin user unregister.'.PHP_EOL;
            }

            return;
        }

        //处理登录
        if(! Database::dbGetUsername($phone_num, $uname))
        {
            $this->sendGeneralRespond(CMD_LOGIN, RET_FAIL);

            if (DEBUG_APPCLIENT)
            {
                echo 'AppClient:handleLogin database error.'.PHP_EOL;
            }

            return;
        }

        //查询数据库看看该账户密码是否正确
        if(Database::dbCheckUserPasswordExist($phone_num, $pswd))
        {//密码正确
            //检测是否重复登录
            $fd = 0;
            if(Database::dbGetAppClientFd($phone_num, $fd))
            {//已登录
                if($this->mFrame->fd == $fd)
                {//重复登录
                    $this->sendGeneralRespond(CMD_LOGIN, RET_RE_LOGIN);

                    if (DEBUG_APPCLIENT)
                    {
                        echo 'AppClient:handleLogin relogin.'.PHP_EOL;
                    }
                }
                else
                {//该账户已在另外一个设备登录
                    //更新登录信息到数据库中
                    if(Database::dbUpdateAppClientFd($phone_num, $this->mFrame->fd))
                    {
                        $respond = array(
                            'cmd' => CMD_LOGIN,
                            'data' => array('ret' => RET_OK, 'uname' => $uname)
                        );
                        $this->mServ->push($this->mFrame->fd, json_encode($respond));

                        //告诉原来的设备，账号已在其他设备登录
                        $other_respond = array(
                            'cmd' => CMD_ACNT_LOGIN_ON_OTHER_DEV,
                            'data' => array(
                                'ret' => RET_OTHER_LOGIN,
                            )
                        );
                        $this->mServ->push($fd, json_encode($other_respond));

                        if (DEBUG_APPCLIENT)
                        {
                            echo 'AppClient:handleLogin already login on other device.'.PHP_EOL;
                        }
                    }
                    else
                    {//数据库出错
                        $this->sendGeneralRespond(CMD_LOGIN, RET_FAIL);

                        if (DEBUG_APPCLIENT)
                        {
                            echo 'AppClient:handleLogin database error.'.PHP_EOL;
                        }
                    }
                }
            }
            else
            {//未登录
                //添加登录信息到数据库中
                if(Database::dbAddAppClient($phone_num, $this->mFrame->fd))
                {
                    $respond = array(
                        'cmd' => CMD_LOGIN,
                        'data' => array('ret' => RET_OK, 'uname' => $uname)
                    );

                    $this->mServ->push($this->mFrame->fd, json_encode($respond));

                    if (DEBUG_APPCLIENT)
                    {
                        echo 'AppClient:handleLogin login success.'.PHP_EOL;
                    }
                }
                else
                {//数据库错误
                    $this->sendGeneralRespond(CMD_LOGIN, RET_FAIL);

                    if (DEBUG_APPCLIENT)
                    {
                        echo 'AppClient:handleLogin database error.'.PHP_EOL;
                    }
                }

            }
        }
        else
        {//密码错误
            $this->sendGeneralRespond(CMD_LOGIN, RET_PSWD_ERROR);

            if (DEBUG_APPCLIENT)
            {
                echo 'AppClient:handleLogin password error.'.PHP_EOL;
            }
        }
    }

    /**
     * 处理手机端修改用户昵称
     */
    function handleModifyUname()
    {
        //检测用户是否已经登录
        if (! Database::dbGetAppClientPhoneNum($phone_num, $this->mFrame->fd))
        {//用户未登录
            $this->sendGeneralRespond(CMD_MODIFY_UNAME, RET_UN_LOGIN);

            if (DEBUG_APPCLIENT)
            {
                echo 'AppClient:handleModifyUname unlogin.'.PHP_EOL;
            }
            return;
        }

        //检查用户昵称长度是否符合规定
        $uname = $this->mProtocol->getUname();
        if ((strlen($uname) > MAX_LEN_UNAME) || empty($uname))
        {
            $this->sendGeneralRespond(CMD_MODIFY_UNAME, RET_DATA_ERROR);

            if (DEBUG_APPCLIENT)
            {
                echo 'AppClient:handleModifyUname uname len error.'.PHP_EOL;
            }

            return;
        }

        //更改用户昵称
        if(Database::dbModifyUsername($phone_num, $uname))
        {//更改成功
            $this->sendGeneralRespond(CMD_MODIFY_UNAME, RET_OK);

            if (DEBUG_APPCLIENT)
            {
                echo 'AppClient:handleModifyUname success.'.PHP_EOL;
            }
        }
        else
        {//更改失败
            $this->sendGeneralRespond(CMD_MODIFY_UNAME, RET_FAIL);

            if (DEBUG_APPCLIENT)
            {
                echo 'AppClient:handleModifyUname fail.'.PHP_EOL;
            }
        }
    }

    /**
     * 处理手机端修改密码
     */
    function handleModifyPswd()
    {
        //检查手机号码是否符合规定
        $phone_num = $this->mProtocol->getPhoneNum();
        if(! preg_match('/^1[34578]\d{9}$/', $phone_num))
        {
            $this->sendGeneralRespond(CMD_MODIFY_PSWD, RET_DATA_ERROR);

            if (DEBUG_APPCLIENT)
            {
                echo 'AppClient:handleModifyPswd phoneNum error.'.PHP_EOL;
            }

            return;
        }

        //查询数据库看看该手机号是否已经注册
        if(! Database::dbCheckUserPhoneNumExist($phone_num))
        {
            $this->sendGeneralRespond(CMD_MODIFY_PSWD, RET_UN_REG);

            if (DEBUG_APPCLIENT)
            {
                echo 'AppClient:handleModifyPswd user unregister.'.PHP_EOL;
            }

            return;
        }

        //检测密码是否符合规范
        $pswd = $this->mProtocol->getPswd();
        if((strlen($pswd) < MIN_LEN_PSWD) || (strlen($pswd) > MAX_LEN_PSWD))
        {
            $this->sendGeneralRespond(CMD_MODIFY_PSWD, RET_DATA_ERROR);

            if (DEBUG_APPCLIENT)
            {
                echo 'AppClient:handleModifyPswd pswd len error.'.PHP_EOL;
            }

            return;
        }

        //检查校验码是否符合规范
        $new_code = $this->mProtocol->getCode();
        if((strlen($new_code) != LEN_SMS_CODE))
        {
            $this->sendGeneralRespond(CMD_MODIFY_PSWD, RET_DATA_ERROR);

            if (DEBUG_APPCLIENT)
            {
                echo 'AppClient:handleModifyPswd new_code len error.'.PHP_EOL;
            }

            return;
        }
        //获取数据库中保存的验证码和时间
        if (! Database::dbGetAppCodeTime($phone_num, $old_code, $time))
        {
            $this->sendGeneralRespond(CMD_MODIFY_PSWD, RET_FAIL);

            if (DEBUG_APPCLIENT)
            {
                echo 'AppClient:handleModifyPswd no code.'.PHP_EOL;
            }

            return;
        }
        //检测验证码是否过期
        if (time() > (strtotime($time) + VALID_CODE_SEC))
        {//验证码已过期
            $this->sendGeneralRespond(CMD_MODIFY_PSWD, RET_CODE_OUT_OF_DATE);

            if (DEBUG_APPCLIENT)
            {
                echo 'AppClient:handleModifyPswd code out of date.'.PHP_EOL;
            }
            return;
        }
        //检测验证码是否正确
        if ($new_code != $old_code)
        {//验证码错误
            $this->sendGeneralRespond(CMD_MODIFY_PSWD, RET_CODE_ERROR);

            if (DEBUG_APPCLIENT)
            {
                echo 'AppClient:handleModifyPswd code error.'.PHP_EOL;
            }
            return;
        }

        //修改密码
        if(Database::dbModifyUserPassword($phone_num, $pswd))
        {//修改成功
            $this->sendGeneralRespond(CMD_MODIFY_PSWD, RET_OK);

            if (DEBUG_APPCLIENT)
            {
                echo 'AppClient:handleModifyPswd success.'.PHP_EOL;
            }
        }
        else
        {//修改失败
            $this->sendGeneralRespond(CMD_MODIFY_PSWD, RET_FAIL);

            if (DEBUG_APPCLIENT)
            {
                echo 'AppClient:handleModifyPswd fail.'.PHP_EOL;
            }
        }
    }

    /**
     * 处理手机端绑定设备
     */
    function handleBindDev()
    {
        //检测用户是否已经登录
        if (! Database::dbGetAppClientPhoneNum($phone_num, $this->mFrame->fd))
        {//用户未登录
            $this->sendGeneralRespond(CMD_BIND_DEV, RET_UN_LOGIN);

            if (DEBUG_APPCLIENT)
            {
                echo 'AppClient:handleBindDev unlogin.'.PHP_EOL;
            }
            return;
        }

        //检查设备类型是否符合规定
        $dev_type = $this->mProtocol->getDevType();
        if ((strlen($dev_type) > MAX_LEN_DEV_TYPE) || empty($dev_type))
        {
            $this->sendGeneralRespond(CMD_BIND_DEV, RET_DATA_ERROR);

            if (DEBUG_APPCLIENT)
            {
                echo 'AppClient:handleBindDev devType len error.'.PHP_EOL;
            }

            return;
        }

        //检查设备系列是否符合规定
        $dev_series = $this->mProtocol->getDevSeries();
        if ((strlen($dev_series) > MAX_LEN_DEV_SERIES) || empty($dev_series))
        {
            $this->sendGeneralRespond(CMD_BIND_DEV, RET_DATA_ERROR);

            if (DEBUG_APPCLIENT)
            {
                echo 'AppClient:handleBindDev devSeries len error.'.PHP_EOL;
            }

            return;
        }

        //检查设备ID是否符合规定
        $dev_id = $this->mProtocol->getDevID();
        if ((strlen($dev_id) > MAX_LEN_DEV_ID) || empty($dev_id))
        {
            $this->sendGeneralRespond(CMD_BIND_DEV, RET_DATA_ERROR);

            if (DEBUG_APPCLIENT)
            {
                echo 'AppClient:handleBindDev devID len error.'.PHP_EOL;
            }

            return;
        }

        //检查设备名称是否符合规定
        $dev_name = $this->mProtocol->getDevName();
        if ((strlen($dev_name) > MAX_LEN_DEV_NAME) || empty($dev_name))
        {
            $this->sendGeneralRespond(CMD_BIND_DEV, RET_DATA_ERROR);

            if (DEBUG_APPCLIENT)
            {
                echo 'AppClient:handleBindDev devName len error.'.PHP_EOL;
            }

            return;
        }

        //绑定设备
        if(Database::dbGetPhoneNumBindDev($dev_type, $dev_series, $dev_id, $phone_bind_dev))
        {//设备已被绑定
            //检测是否为重复绑定
            if ($phone_num == $phone_bind_dev)
            {//重复绑定设备
                $this->sendGeneralRespond(CMD_BIND_DEV, RET_RE_BIND_DEV);

                if (DEBUG_APPCLIENT)
                {
                    echo 'AppClient:handleBindDev rebind dev'.PHP_EOL;
                }
            }
            else
            {
                $this->sendGeneralRespond(CMD_BIND_DEV, RET_OTHER_BIND_DEV);

                if (DEBUG_APPCLIENT)
                {
                    echo 'AppClient:handleBindDev other phone_num bind dev.'.PHP_EOL;
                }
            }
        }
        else
        {//设备未被绑定
            //添加绑定设备
            if(Database::dbAddNewDevice($dev_type, $dev_series, $dev_id, $dev_name, $phone_num))
            {//添加成功
                $this->sendGeneralRespond(CMD_BIND_DEV, RET_OK);

                if (DEBUG_APPCLIENT)
                {
                    echo 'AppClient:handleBindDev success'.PHP_EOL;
                }
            }
            else
            {//添加失败
                $this->sendGeneralRespond(CMD_BIND_DEV, RET_FAIL);

                if (DEBUG_APPCLIENT)
                {
                    echo 'AppClient:handleBindDev fail'.PHP_EOL;
                }
            }
        }
    }

    /**
     * 解绑设备
     */
    function handleUnbindDev()
    {
        //检测用户是否已经登录
        if (! Database::dbGetAppClientPhoneNum($phone_num, $this->mFrame->fd))
        {//用户未登录
            $this->sendGeneralRespond(CMD_UNBIND_DEV, RET_UN_LOGIN);

            if (DEBUG_APPCLIENT)
            {
                echo 'AppClient:handleUnbindDev unlogin.'.PHP_EOL;
            }
            return;
        }

        //检查设备类型是否符合规定
        $dev_type = $this->mProtocol->getDevType();
        if ((strlen($dev_type) > MAX_LEN_DEV_TYPE) || empty($dev_type))
        {
            $this->sendGeneralRespond(CMD_UNBIND_DEV, RET_DATA_ERROR);

            if (DEBUG_APPCLIENT)
            {
                echo 'AppClient:handleUnbindDev devType len error.'.PHP_EOL;
            }

            return;
        }

        //检查设备系列是否符合规定
        $dev_series = $this->mProtocol->getDevSeries();
        if ((strlen($dev_series) > MAX_LEN_DEV_SERIES) || empty($dev_series))
        {
            $this->sendGeneralRespond(CMD_UNBIND_DEV, RET_DATA_ERROR);

            if (DEBUG_APPCLIENT)
            {
                echo 'AppClient:handleUnbindDev devSeries len error.'.PHP_EOL;
            }

            return;
        }

        //检查设备ID是否符合规定
        $dev_id = $this->mProtocol->getDevID();
        if ((strlen($dev_id) > MAX_LEN_DEV_ID) || empty($dev_id))
        {
            $this->sendGeneralRespond(CMD_UNBIND_DEV, RET_DATA_ERROR);

            if (DEBUG_APPCLIENT)
            {
                echo 'AppClient:handleUnbindDev devID len error.'.PHP_EOL;
            }

            return;
        }

        //解绑设备
        if(Database::dbGetPhoneNumBindDev($dev_type, $dev_series, $dev_id, $phone_bind_dev))
        {
            if($phone_num == $phone_bind_dev)
            {//账号一致，进行解绑
                //删除绑定用户
                if(Database::dbDelDevice($dev_type, $dev_series, $dev_id))
                {//解绑成功
                    $this->sendGeneralRespond(CMD_UNBIND_DEV, RET_OK);

                    //删除设备共享人
                    Database::dbDeleteShareDev($dev_type, $dev_series, $dev_id);
                    //删除消息推送（主要用在解绑设备时的操作）
                    Database::dbDeletePushMsgs($dev_type, $dev_series, $dev_id);

                    if (DEBUG_APPCLIENT)
                    {
                        echo 'AppClient:handleUnbindDev success'.PHP_EOL;
                    }
                }
                else
                {//解绑失败
                    $this->sendGeneralRespond(CMD_UNBIND_DEV, RET_FAIL);

                    if (DEBUG_APPCLIENT)
                    {
                        echo 'AppClient:handleUnbindDev database error'.PHP_EOL;
                    }
                }
            }
            else
            {//账号不一致，没有解绑权限
                $this->sendGeneralRespond(CMD_UNBIND_DEV, RET_NO_PERMISSION);

                if (DEBUG_APPCLIENT)
                {
                    echo 'AppClient:handleUnbindDev phone_num no permission.'.PHP_EOL;
                }
            }
        }
        else
        {
            $this->sendGeneralRespond(CMD_UNBIND_DEV, RET_DEV_NOT_EXIST);

            if (DEBUG_APPCLIENT)
            {
                echo 'AppClient:handleUnbindDev not exist dev '.$dev_id.PHP_EOL;
            }
        }
    }

    /**
     * 处理手机端获取设备列表
     */
    function handleGetDevList()
    {
        //检测用户是否已经登录
        if (! Database::dbGetAppClientPhoneNum($phone_num, $this->mFrame->fd))
        {//用户未登录
            $this->sendGeneralRespond(CMD_GET_DEV_LIST, RET_UN_LOGIN);

            if (DEBUG_APPCLIENT)
            {
                echo 'AppClient:handleGetDevList unlogin.'.PHP_EOL;
            }
            return;
        }

        //检查设备类型是否符合规定
        $dev_type = $this->mProtocol->getDevType();
        if ((strlen($dev_type) > MAX_LEN_DEV_TYPE) || empty($dev_type))
        {
            $this->sendGeneralRespond(CMD_GET_DEV_LIST, RET_DATA_ERROR);

            if (DEBUG_APPCLIENT)
            {
                echo 'AppClient:handleGetDevList devType len error.'.PHP_EOL;
            }

            return;
        }

        //检查设备系列是否符合规定
        $dev_series = $this->mProtocol->getDevSeries();
        if ((strlen($dev_series) > MAX_LEN_DEV_SERIES) || empty($dev_series))
        {
            $this->sendGeneralRespond(CMD_GET_DEV_LIST, RET_DATA_ERROR);

            if (DEBUG_APPCLIENT)
            {
                echo 'AppClient:handleGetDevList devSeries len error.'.PHP_EOL;
            }

            return;
        }

        //获取设备列表
        $json_dev_list = array();
        $index = 0;
        if(Database::dbGetDeviceList($dev_type, $dev_series, $phone_num, $dev_list))
        {
            if(count($dev_list))
            {//设备不为空
                foreach ($dev_list as $temp_dev_id=>$temp_dev_name)
                {
                    if(Database::dbGetDevClientAdr($dev_type, $dev_series, $temp_dev_id, $adr))
                    {//获取成功，设备在线
                        $json_dev_list[$index++] = $temp_dev_id;
                        $json_dev_list[$index++] = $temp_dev_name;
                        $json_dev_list[$index++] = $adr;
                        $json_dev_list[$index++] = ON_LINE;
                    }
                    else
                    {//获取失败，设备离线
                        $json_dev_list[$index++] = $temp_dev_id;
                        $json_dev_list[$index++] = $temp_dev_name;
                        $json_dev_list[$index++] = 0;
                        $json_dev_list[$index++] = OFF_LINE;
                    }
                }
            }
        }

        //获取分享的设备列表
        if (Database::dbGetShareDevs($dev_type, $dev_series, $phone_num, $share_dev_list))
        {
            foreach ($share_dev_list as $temp_dev_id=>$temp_dev_name)
            {
                if(Database::dbGetDevClientAdr($dev_type, $dev_series, $temp_dev_id, $adr))
                {//获取成功，设备在线
                    $json_dev_list[$index++] = $temp_dev_id;
                    $json_dev_list[$index++] = $temp_dev_name;
                    $json_dev_list[$index++] = $adr;
                    $json_dev_list[$index++] = ON_LINE;
                }
                else
                {//获取失败，设备离线
                    $json_dev_list[$index++] = $temp_dev_id;
                    $json_dev_list[$index++] = $temp_dev_name;
                    $json_dev_list[$index++] = 0;
                    $json_dev_list[$index++] = OFF_LINE;
                }
            }
        }

        if (count($json_dev_list))
        {
            //封装json数据发送给手机端
            $respond = array('cmd' => CMD_GET_DEV_LIST,
                'data' => array(
                    'ret' => RET_OK,
                    'devType' => $dev_type,
                    'devSeries' => $dev_series,
                    'devList' => $json_dev_list
                )
            );
            $this->mServ->push($this->mFrame->fd, json_encode($respond));
        }
        else
        {
            $this->sendGeneralRespond(CMD_GET_DEV_LIST, RET_DEV_EMPTY);

            if (DEBUG_APPCLIENT)
            {
                echo 'AppClient:handleGetDevList dev list is empty.'.PHP_EOL;
            }
        }
    }

    /**
     * 修改设备名称
     */
    function handleModifyDevName()
    {
        //检测用户是否已经登录
        if (! Database::dbGetAppClientPhoneNum($phone_num, $this->mFrame->fd))
        {//用户未登录
            $this->sendGeneralRespond(CMD_MODIFY_DEV_NAME, RET_UN_LOGIN);

            if (DEBUG_APPCLIENT)
            {
                echo 'AppClient:handleModifyDevName unlogin.'.PHP_EOL;
            }
            return;
        }

        //检查设备类型是否符合规定
        $dev_type = $this->mProtocol->getDevType();
        if ((strlen($dev_type) > MAX_LEN_DEV_TYPE) || empty($dev_type))
        {
            $this->sendGeneralRespond(CMD_MODIFY_DEV_NAME, RET_DATA_ERROR);

            if (DEBUG_APPCLIENT)
            {
                echo 'AppClient:handleModifyDevName devType len error.'.PHP_EOL;
            }

            return;
        }

        //检查设备系列是否符合规定
        $dev_series = $this->mProtocol->getDevSeries();
        if ((strlen($dev_series) > MAX_LEN_DEV_SERIES) || empty($dev_series))
        {
            $this->sendGeneralRespond(CMD_MODIFY_DEV_NAME, RET_DATA_ERROR);

            if (DEBUG_APPCLIENT)
            {
                echo 'AppClient:handleModifyDevName devSeries len error.'.PHP_EOL;
            }

            return;
        }

        //检查设备ID是否符合规定
        $dev_id = $this->mProtocol->getDevID();
        if ((strlen($dev_id) > MAX_LEN_DEV_ID) || empty($dev_id))
        {
            $this->sendGeneralRespond(CMD_MODIFY_DEV_NAME, RET_DATA_ERROR);

            if (DEBUG_APPCLIENT)
            {
                echo 'AppClient:handleModifyDevName devID len error.'.PHP_EOL;
            }

            return;
        }

        //检查设备名称是否符合规定
        $dev_name = $this->mProtocol->getDevName();
        if ((strlen($dev_name) > MAX_LEN_DEV_NAME) || empty($dev_name))
        {
            $this->sendGeneralRespond(CMD_MODIFY_DEV_NAME, RET_DATA_ERROR);

            if (DEBUG_APPCLIENT)
            {
                echo 'AppClient:handleModifyDevName devName len error.'.PHP_EOL;
            }

            return;
        }

        //检测设备归属于你，还是被你共享
        if (! Database::dbGetPhoneNumBindDev($dev_type, $dev_series, $dev_id, $phone_bind))
        {
            $this->sendGeneralRespond(CMD_MODIFY_DEV_NAME, RET_DEV_NOT_BIND);

            if (DEBUG_APPCLIENT)
            {
                echo 'AppClient:handleModifyDevName fail.'.PHP_EOL;
            }

            return;
        }
        if ($phone_num != $phone_bind)
        {
            if (Database::dbCheckShareDevPhone($dev_type, $dev_series, $dev_id, $phone_num))
            {//设备共享人
                $this->sendGeneralRespond(CMD_MODIFY_DEV_NAME, RET_NO_PERMISSION);

                if (DEBUG_APPCLIENT)
                {
                    echo 'AppClient:handleModifyDevName no permission.'.PHP_EOL;
                }
            }
            else
            {//不是设备共享人
                $this->sendGeneralRespond(CMD_MODIFY_DEV_NAME, RET_OTHER_BIND_DEV);

                if (DEBUG_APPCLIENT)
                {
                    echo 'AppClient:handleModifyDevName dev other bind.'.PHP_EOL;
                }
            }

            return;
        }

        //修改设备名称
        if (Database::dbModifyDevName($dev_type, $dev_series, $dev_id, $dev_name))
        {//修改成功
            $this->sendGeneralRespond(CMD_MODIFY_DEV_NAME, RET_OK);

            if (DEBUG_APPCLIENT)
            {
                echo 'AppClient:handleModifyDevName success.'.PHP_EOL;
            }
        }
        else
        {//修改失败
            $this->sendGeneralRespond(CMD_MODIFY_DEV_NAME, RET_FAIL);

            if (DEBUG_APPCLIENT)
            {
                echo 'AppClient:handleModifyDevName fail.'.PHP_EOL;
            }
        }
    }

    /**
     * 处理手机端APP升级
     */
    function handleUpdateApp()
    {
        $sys = $this->mProtocol->getSystem();
        $app_name = $this->mProtocol->getAppName();
        $key = $sys.'_'.$app_name;
        $path = VER_FILE_PATH;
        $fo = fopen($path, 'r');
        $arr_version = unserialize(fread($fo, filesize($path)));
        $version = $arr_version[$key];

        if(empty($version))
        {
            $this->sendGeneralRespond(CMD_UPDATE_APP, RET_FAIL);

            if (DEBUG_APPCLIENT)
            {
                echo 'AppClient:handleUpdateApp sys name error.'.PHP_EOL;
            }
        }
        else
        {
            $app_url = '';
            if ('ios' == $sys)
                $app_url = APP_IOS_URL;
            else if ('android' == $sys)
                $app_url = APP_ANDROID_URL;
            else
            {
                $this->sendGeneralRespond(CMD_UPDATE_APP, RET_FAIL);

                if (DEBUG_APPCLIENT)
                {
                    echo 'AppClient:handleUpdateApp sys name error.'.PHP_EOL;
                }
            }

            $respond = array(
                'cmd' => CMD_UPDATE_APP,
                'data' => array(
                    'ret' => RET_OK,
                    'ver' => $version,
                    'url' => $app_url,
                )
            );

            $this->mServ->push($this->mFrame->fd, json_encode($respond));

            if (DEBUG_APPCLIENT)
            {
                echo 'AppClient:handleUpdateApp success.'.PHP_EOL;
            }
        }
    }

    /**
     * 处理手机端透传数据
     */
    function handleDirectTransport()
    {
        //检测用户是否已经登录
        if (! Database::dbGetAppClientPhoneNum($phone_num, $this->mFrame->fd))
        {//用户未登录
            $this->sendDirTransportRespond(CMD_DIRECT_TRANSPORT, RET_UN_LOGIN, 0xFF);

            if (DEBUG_APPCLIENT)
            {
                echo 'AppClient:handleDirectTransport unlogin.'.PHP_EOL;
            }
            return;
        }

        //检查设备类型是否符合规定
        $dev_type = $this->mProtocol->getDevType();
        if ((strlen($dev_type) > MAX_LEN_DEV_TYPE) || empty($dev_type))
        {
            $this->sendDirTransportRespond(CMD_DIRECT_TRANSPORT, RET_DATA_ERROR, 0xFF);

            if (DEBUG_APPCLIENT)
            {
                echo 'AppClient:handleDirectTransport devType len error.'.PHP_EOL;
            }

            return;
        }

        //检查设备系列是否符合规定
        $dev_series = $this->mProtocol->getDevSeries();
        if ((strlen($dev_series) > MAX_LEN_DEV_SERIES) || empty($dev_series))
        {
            $this->sendDirTransportRespond(CMD_DIRECT_TRANSPORT, RET_DATA_ERROR, 0xFF);

            if (DEBUG_APPCLIENT)
            {
                echo 'AppClient:handleDirectTransport devSeries len error.'.PHP_EOL;
            }

            return;
        }

        //检查设备ID是否符合规定
        $dev_id = $this->mProtocol->getDevID();
        if ((strlen($dev_id) > MAX_LEN_DEV_ID) || empty($dev_id))
        {
            $this->sendDirTransportRespond(CMD_DIRECT_TRANSPORT, RET_DATA_ERROR, 0xFF);

            if (DEBUG_APPCLIENT)
            {
                echo 'AppClient:handleDirectTransport devID len error.'.PHP_EOL;
            }

            return;
        }

        //检测透传给设备的数据是否正确
        $dir_data = $this->mProtocol->getDirData();
        if (empty($dir_data))
        {
            $this->sendDirTransportRespond(CMD_DIRECT_TRANSPORT, RET_DATA_ERROR, 0xFF);

            if (DEBUG_APPCLIENT)
            {
                echo 'AppClient:handleDirectTransport dirData is null.'.PHP_EOL;
            }

            return;
        }
        $devProtocol = new DevProtocol();
        if (! $devProtocol->unpackData($dir_data))
        {
            $this->sendDirTransportRespond(CMD_DIRECT_TRANSPORT, RET_DATA_ERROR, 0xFF);

            if (DEBUG_APPCLIENT)
            {
                echo 'AppClient:handleDirectTransport unpackData error.'.PHP_EOL;
            }

            return;
        }
        $CID2 = $devProtocol->getCID2();

        //检测是否绑定该设备
        if(Database::dbGetPhoneNumBindDev($dev_type, $dev_series, $dev_id, $phone_bind))
        {//设备已被绑定
            if($phone_num != $phone_bind)
            {//设备被其他账号绑定了
                //检测用户是否共享该设备
                if(! Database::dbCheckShareDevPhone($dev_type, $dev_series, $dev_id, $phone_num))
                {
                    $this->sendDirTransportRespond(CMD_DIRECT_TRANSPORT, RET_DEV_NOT_SHARE_TO_YOU, $CID2);

                    if (DEBUG_APPCLIENT)
                    {
                        echo 'AppClient:handleDirectTransport dev not share to you.'.PHP_EOL;
                    }

                    return;
                }
                else
                {//共享设备者
                    switch ($devProtocol->getCID2())
                    {
                        case CMD_SET_DEV_ADR://设置设备地址指令
                        case CMD_DEV_RECOVERY://恢复出厂设置
                            $this->sendDirTransportRespond(CMD_DIRECT_TRANSPORT, RET_NO_PERMISSION, $CID2);
                            if (DEBUG_APPCLIENT)
                            {
                                echo 'AppClient:handleDirectTransport no permission.'.PHP_EOL;
                            }
                            return;
                        default:
                            break;
                    }
                }
            }
        }
        else
        {//该设备还未被绑定
            $this->sendDirTransportRespond(CMD_DIRECT_TRANSPORT, RET_DEV_NOT_BIND, $CID2);

            if (DEBUG_APPCLIENT)
            {
                echo 'AppClient:handleDirectTransport dev not bind.'.PHP_EOL;
            }

            return;
        }

        //检测设备地址是否正确
        if (! Database::dbGetDevClientAdr($dev_type, $dev_series, $dev_id, $cur_adr))
        {
            $this->sendDirTransportRespond(CMD_DIRECT_TRANSPORT, RET_DEV_OFF_LINE, $CID2);

            if (DEBUG_APPCLIENT)
            {
                echo 'AppClient:handleDirectTransport dev offline.'.PHP_EOL;
            }

            return;
        }

        if ($cur_adr != $devProtocol->getADR())
        {
            $respond = array(
                'cmd' => CMD_DIRECT_TRANSPORT,
                'data' => array(
                    'ret' => RET_ADR_ERROR,
                    'CID2' => $CID2,
                    'dirData' => sprintf('%02X', $cur_adr)
                )
            );
            $this->mServ->push($this->mFrame->fd, json_encode($respond));

            if (DEBUG_APPCLIENT)
            {
                echo 'AppClient:handleDirectTransport adr cur_adr not the same.'.PHP_EOL;
            }

            return;
        }

        //检测设备是否已经登录
        if(Database::dbGetDevClientFd($dev_type, $dev_series, $dev_id, $dev_fd))
        {//设备已经登录
            //检测该设备是否正忙
            if(Database::dbGetAppResDevPhoneTime($dev_type, $dev_series, $dev_id, $temp_CID2, $phone_res, $time))
            {
                if ((time() - strtotime($time)) < VALID_RES_SEC)
                {//设备正忙
                    $this->sendDirTransportRespond(CMD_DIRECT_TRANSPORT, RET_DEV_BUSY, $CID2);

                    if (DEBUG_APPCLIENT)
                    {
                        echo 'AppClient:handleDirectTransport dev busy.'.PHP_EOL;
                        echo 'AppClient:handleDirectTransport now:'.time().', strtotime:'.strtotime($time).PHP_EOL;
                    }

                    return;
                }
                else
                {//清除正忙，恢复设备空闲
                    if(Database::dbDeleteAppResDev($dev_type, $dev_series, $dev_id))
                    {
                        if (! Database::dbInsertAppResDev($dev_type, $dev_series, $dev_id, $CID2, $phone_num))
                        {
                            $this->sendDirTransportRespond(CMD_DIRECT_TRANSPORT, RET_FAIL, $CID2);

                            if (DEBUG_APPCLIENT)
                            {
                                echo 'AppClient:handleDirectTransport database error.'.PHP_EOL;
                            }

                            return;
                        }
                    }
                    else
                    {
                        $this->sendDirTransportRespond(CMD_DIRECT_TRANSPORT, RET_FAIL, $CID2);

                        if (DEBUG_APPCLIENT)
                        {
                            echo 'AppClient:handleDirectTransport database error.'.PHP_EOL;
                        }

                        return;
                    }
                }
            }
            else
            {//设备空闲
                if (! Database::dbInsertAppResDev($dev_type, $dev_series, $dev_id, $CID2, $phone_num))
                {
                    $this->sendDirTransportRespond(CMD_DIRECT_TRANSPORT, RET_FAIL, $CID2);

                    if (DEBUG_APPCLIENT)
                    {
                        echo 'AppClient:handleDirectTransport database error.'.PHP_EOL;
                    }

                    return;
                }
            }

            //开始透传数据
            $this->mServ->send($dev_fd, $dir_data);

            if (DEBUG_APPCLIENT)
            {
                echo 'AppClient:handleDirectTransport transport over.'.PHP_EOL;
            }
        }
        else
        {//设备未登录
            $this->sendDirTransportRespond(CMD_DIRECT_TRANSPORT, RET_DEV_OFF_LINE, $CID2);

            if (DEBUG_APPCLIENT)
            {
                echo 'AppClient:handleDirectTransport dev off line.'.PHP_EOL;
            }
        }
    }

    /**
     * 处理手机端查询设备归属人
     */
    function handleGetDevBelong()
    {
        //检测用户是否已经登录
        if (! Database::dbGetAppClientPhoneNum($phone_num, $this->mFrame->fd))
        {//用户未登录
            $this->sendGeneralRespond(CMD_GET_DEV_BELONG, RET_UN_LOGIN);

            if (DEBUG_APPCLIENT)
            {
                echo 'AppClient:handleGetDevBelong unlogin.'.PHP_EOL;
            }
            return;
        }

        //检查设备类型是否符合规定
        $dev_type = $this->mProtocol->getDevType();
        if ((strlen($dev_type) > MAX_LEN_DEV_TYPE) || empty($dev_type))
        {
            $this->sendGeneralRespond(CMD_GET_DEV_BELONG, RET_DATA_ERROR);

            if (DEBUG_APPCLIENT)
            {
                echo 'AppClient:handleGetDevBelong devType len error.'.PHP_EOL;
            }

            return;
        }

        //检查设备系列是否符合规定
        $dev_series = $this->mProtocol->getDevSeries();
        if ((strlen($dev_series) > MAX_LEN_DEV_SERIES) || empty($dev_series))
        {
            $this->sendGeneralRespond(CMD_GET_DEV_BELONG, RET_DATA_ERROR);

            if (DEBUG_APPCLIENT)
            {
                echo 'AppClient:handleGetDevBelong devSeries len error.'.PHP_EOL;
            }

            return;
        }

        //检查设备ID是否符合规定
        $dev_id = $this->mProtocol->getDevID();
        if ((strlen($dev_id) > MAX_LEN_DEV_ID) || empty($dev_id))
        {
            $this->sendGeneralRespond(CMD_GET_DEV_BELONG, RET_DATA_ERROR);

            if (DEBUG_APPCLIENT)
            {
                echo 'AppClient:handleGetDevBelong devID len error.'.PHP_EOL;
            }

            return;
        }

        //根据设备获取绑定该设备的手机号
        if(! Database::dbGetPhoneNumBindDev($dev_type, $dev_series, $dev_id, $phone_bind))
        {
            $this->sendGeneralRespond(CMD_GET_DEV_BELONG, RET_DEV_NOT_BIND);

            if (DEBUG_APPCLIENT)
            {
                echo 'AppClient:handleGetDevBelong dev not bind.'.PHP_EOL;
            }

            return;
        }

        //根据手机号获取用户昵称
        if(! Database::dbGetUsername($phone_bind, $uname))
        {
            $this->sendGeneralRespond(CMD_GET_DEV_BELONG, RET_FAIL);

            if (DEBUG_APPCLIENT)
            {
                echo 'AppClient:handleGetDevBelong database error.'.PHP_EOL;
            }

            return;
        }

        //给app返回数据
        $respond = array('cmd' => CMD_GET_DEV_BELONG,
            'data' => array('ret' => RET_OK,
                "uname" => $uname,
                "phone" => $phone_bind
                )
        );

        $this->mServ->push($this->mFrame->fd, json_encode($respond));
    }

    /**
     * 手机端分享设备
     */
    function handleShareDevice()
    {
        //检测用户是否已经登录
        if (! Database::dbGetAppClientPhoneNum($phone_num, $this->mFrame->fd))
        {//用户未登录
            $this->sendGeneralRespond(CMD_SHARE_DEVICE, RET_UN_LOGIN);

            if (DEBUG_APPCLIENT)
            {
                echo 'AppClient:handleShareDevice unlogin.'.PHP_EOL;
            }
            return;
        }

        //检查设备类型是否符合规定
        $dev_type = $this->mProtocol->getDevType();
        if ((strlen($dev_type) > MAX_LEN_DEV_TYPE) || empty($dev_type))
        {
            $this->sendGeneralRespond(CMD_SHARE_DEVICE, RET_DATA_ERROR);

            if (DEBUG_APPCLIENT)
            {
                echo 'AppClient:handleShareDevice devType len error.'.PHP_EOL;
            }

            return;
        }

        //检查设备系列是否符合规定
        $dev_series = $this->mProtocol->getDevSeries();
        if ((strlen($dev_series) > MAX_LEN_DEV_SERIES) || empty($dev_series))
        {
            $this->sendGeneralRespond(CMD_SHARE_DEVICE, RET_DATA_ERROR);

            if (DEBUG_APPCLIENT)
            {
                echo 'AppClient:handleShareDevice devSeries len error.'.PHP_EOL;
            }

            return;
        }

        //检查设备ID是否符合规定
        $dev_id = $this->mProtocol->getDevID();
        if ((strlen($dev_id) > MAX_LEN_DEV_ID) || empty($dev_id))
        {
            $this->sendGeneralRespond(CMD_SHARE_DEVICE, RET_DATA_ERROR);

            if (DEBUG_APPCLIENT)
            {
                echo 'AppClient:handleShareDevice devID len error.'.PHP_EOL;
            }

            return;
        }

        //检查手机号码是否符合规定
        $phone_share = $this->mProtocol->getPhoneNum();
        if(!preg_match('/^1[34578]\d{9}$/',$phone_share))
        {
            $this->sendGeneralRespond(CMD_SHARE_DEVICE, RET_DATA_ERROR);

            if (DEBUG_APPCLIENT)
            {
                echo 'AppClient:handleShareDevice phone_share error.'.PHP_EOL;
            }

            return;
        }

        //根据设备获取绑定该设备的手机号
        if(! Database::dbGetPhoneNumBindDev($dev_type, $dev_series, $dev_id, $phone_bind))
        {
            $this->sendGeneralRespond(CMD_SHARE_DEVICE, RET_DEV_NOT_BIND);

            if (DEBUG_APPCLIENT)
            {
                echo 'AppClient:handleShareDevice dev not bind.'.PHP_EOL;
            }

            return;
        }

        //检测该设备是否归属于进行分享的人
        if($phone_bind != $phone_num)
        {//不是
            $this->sendGeneralRespond(CMD_SHARE_DEVICE, RET_NO_PERMISSION);

            if (DEBUG_APPCLIENT)
            {
                echo 'AppClient:handleShareDevice no permission.'.PHP_EOL;
            }

            return;
        }

        //检测是否为本人分享给本人
        if($phone_num == $phone_share)
        {
            $this->sendGeneralRespond(CMD_SHARE_DEVICE, RET_OK);

            if (DEBUG_APPCLIENT)
            {
                echo 'AppClient:handleShareDevice self share to self.'.PHP_EOL;
            }

            return;
        }

        //检测分享给的人是否已经注册
        if(! Database::dbCheckUserPhoneNumExist($phone_share))
        {
            $this->sendGeneralRespond(CMD_SHARE_DEVICE, RET_UN_REG);

            if (DEBUG_APPCLIENT)
            {
                echo 'AppClient:handleShareDevice phone_share not register.'.PHP_EOL;
            }

            return;
        }

        //检测该设备是否已经分享给该用户
        if (Database::dbCheckShareDevPhone($dev_type, $dev_series, $dev_id, $phone_share))
        {//已经分享过了
            $this->sendGeneralRespond(CMD_SHARE_DEVICE, RET_OK);

            if (DEBUG_APPCLIENT)
            {
                echo 'AppClient:handleShareDevice already share the dev to the phone.'.PHP_EOL;
            }

            return;
        }

        //将分享信息插入到数据库中
        if (! Database::dbInsertShareDev($dev_type, $dev_series, $dev_id, $phone_share))
        {//插入失败
            $this->sendGeneralRespond(CMD_SHARE_DEVICE, RET_FAIL);

            if (DEBUG_APPCLIENT)
            {
                echo 'AppClient:handleShareDevice Insert share dev info error.'.PHP_EOL;
            }

            return;
        }

        $this->sendGeneralRespond(CMD_SHARE_DEVICE, RET_OK);

        if (DEBUG_APPCLIENT)
        {
            echo 'AppClient:handleShareDevice Insert share dev info success.'.PHP_EOL;
        }
    }

    /**
     * 手机端取消设备分享
     */
    function handleCancelShareDev()
    {
        //检测用户是否已经登录
        if (! Database::dbGetAppClientPhoneNum($phone_num, $this->mFrame->fd))
        {//用户未登录
            $this->sendGeneralRespond(CMD_CANCEL_SHARE_DEV, RET_UN_LOGIN);

            if (DEBUG_APPCLIENT)
            {
                echo 'AppClient:handleCancelShareDev unlogin.'.PHP_EOL;
            }
            return;
        }

        //检查设备类型是否符合规定
        $dev_type = $this->mProtocol->getDevType();
        if ((strlen($dev_type) > MAX_LEN_DEV_TYPE) || empty($dev_type))
        {
            $this->sendGeneralRespond(CMD_CANCEL_SHARE_DEV, RET_DATA_ERROR);

            if (DEBUG_APPCLIENT)
            {
                echo 'AppClient:handleCancelShareDev devType len error.'.PHP_EOL;
            }

            return;
        }

        //检查设备系列是否符合规定
        $dev_series = $this->mProtocol->getDevSeries();
        if ((strlen($dev_series) > MAX_LEN_DEV_SERIES) || empty($dev_series))
        {
            $this->sendGeneralRespond(CMD_CANCEL_SHARE_DEV, RET_DATA_ERROR);

            if (DEBUG_APPCLIENT)
            {
                echo 'AppClient:handleCancelShareDev devSeries len error.'.PHP_EOL;
            }

            return;
        }

        //检查设备ID是否符合规定
        $dev_id = $this->mProtocol->getDevID();
        if ((strlen($dev_id) > MAX_LEN_DEV_ID) || empty($dev_id))
        {
            $this->sendGeneralRespond(CMD_CANCEL_SHARE_DEV, RET_DATA_ERROR);

            if (DEBUG_APPCLIENT)
            {
                echo 'AppClient:handleCancelShareDev devID len error.'.PHP_EOL;
            }

            return;
        }

        //检查手机号码是否符合规定
        $phone_share = $this->mProtocol->getPhoneNum();
        if(!preg_match('/^1[34578]\d{9}$/',$phone_share))
        {
            $this->sendGeneralRespond(CMD_CANCEL_SHARE_DEV, RET_DATA_ERROR);

            if (DEBUG_APPCLIENT)
            {
                echo 'AppClient:handleCancelShareDev phone_share error.'.PHP_EOL;
            }

            return;
        }

        //根据设备获取绑定该设备的手机号
        if(! Database::dbGetPhoneNumBindDev($dev_type, $dev_series, $dev_id, $phone_bind))
        {
            $this->sendGeneralRespond(CMD_CANCEL_SHARE_DEV, RET_DEV_NOT_BIND);

            if (DEBUG_APPCLIENT)
            {
                echo 'AppClient:handleCancelShareDev dev not bind.'.PHP_EOL;
            }

            return;
        }

        //检测该设备是否归属于进行分享的人
        if($phone_bind != $phone_num)
        {//不是
            $this->sendGeneralRespond(CMD_CANCEL_SHARE_DEV, RET_NO_PERMISSION);

            if (DEBUG_APPCLIENT)
            {
                echo 'AppClient:handleCancelShareDev no permission to cancel share.'.PHP_EOL;
            }

            return;
        }

        //将相应的分享信息删除
        if(! Database::dbDeleteShareDevPhone($dev_type, $dev_series, $dev_id, $phone_share))
        {//删除失败
            $this->sendGeneralRespond(CMD_CANCEL_SHARE_DEV, RET_FAIL);

            if (DEBUG_APPCLIENT)
            {
                echo 'AppClient:handleCancelShareDev delete share dev info error.'.PHP_EOL;
            }

            return;
        }

        //删除消息推送（主要用在取消设备分享时的操作）
        Database::dbDeletePushMsgsByPhone($dev_type, $dev_series, $dev_id, $phone_share);

        $this->sendGeneralRespond(CMD_CANCEL_SHARE_DEV, RET_OK);

        if (DEBUG_APPCLIENT)
        {
            echo 'AppClient:handleCancelShareDev delete share dev info success.'.PHP_EOL;
        }
    }

    /**
     * 手机端获取分享的设备列表
     */
    function handleGetShareDevList()
    {
        //检测用户是否已经登录
        if (! Database::dbGetAppClientPhoneNum($phone_num, $this->mFrame->fd))
        {//用户未登录
            $this->sendGeneralRespond(CMD_GET_SHARE_DEV_LIST, RET_UN_LOGIN);

            if (DEBUG_APPCLIENT)
            {
                echo 'AppClient:handleGetShareDevList unlogin.'.PHP_EOL;
            }
            return;
        }

        //检查设备类型是否符合规定
        $dev_type = $this->mProtocol->getDevType();
        if ((strlen($dev_type) > MAX_LEN_DEV_TYPE) || empty($dev_type))
        {
            $this->sendGeneralRespond(CMD_GET_SHARE_DEV_LIST, RET_DATA_ERROR);

            if (DEBUG_APPCLIENT)
            {
                echo 'AppClient:handleGetShareDevList devType len error.'.PHP_EOL;
            }

            return;
        }

        //检查设备系列是否符合规定
        $dev_series = $this->mProtocol->getDevSeries();
        if ((strlen($dev_series) > MAX_LEN_DEV_SERIES) || empty($dev_series))
        {
            $this->sendGeneralRespond(CMD_GET_SHARE_DEV_LIST, RET_DATA_ERROR);

            if (DEBUG_APPCLIENT)
            {
                echo 'AppClient:handleGetShareDevList devSeries len error.'.PHP_EOL;
            }

            return;
        }

        //获取设备列表
        if(Database::dbGetDeviceList($dev_type, $dev_series, $phone_num, $dev_list))
        {
            if(0 == count($dev_list))
            {//设备为空
                $this->sendGeneralRespond(CMD_GET_SHARE_DEV_LIST, RET_DEV_EMPTY);

                if (DEBUG_APPCLIENT)
                {
                    echo 'AppClient:handleGetShareDevList no device bind.'.PHP_EOL;
                }
            }
            else
            {//设备不为空
                $json_dev_list = array();
                $index = 0;
                foreach ($dev_list as $temp_dev_id=>$temp_dev_name)
                {
                    //检测设备是否已被分享
                    if (Database::dbCheckShareDev($dev_type, $dev_series, $temp_dev_id))
                    {//是
                        $json_dev_list[$index++] = $temp_dev_id;
                        $json_dev_list[$index++] = $temp_dev_name;
                    }
                }

                if (0 != count($json_dev_list))
                {
                    //封装json数据发送给手机端
                    $respond = array(
                        'cmd' => CMD_GET_SHARE_DEV_LIST,
                        'data' => array(
                            'ret' => RET_OK,
                            'devType' => $dev_type,
                            'devSeries' => $dev_series,
                            'devList' => $json_dev_list
                        ),
                    );
                    $this->mServ->push($this->mFrame->fd, json_encode($respond));
                }
                else
                {
                    $this->sendGeneralRespond(CMD_GET_SHARE_DEV_LIST, RET_DEV_EMPTY);

                    if (DEBUG_APPCLIENT)
                    {
                        echo 'AppClient:handleGetShareDevList dev empty.'.PHP_EOL;
                    }
                }
            }
        }
        else
        {
            $this->sendGeneralRespond(CMD_GET_SHARE_DEV_LIST, RET_DEV_EMPTY);

            if (DEBUG_APPCLIENT)
            {
                echo 'AppClient:handleGetShareDevList dev empty.'.PHP_EOL;
            }
        }
    }

    /**
     * 手机端获取分享的联系人列表
     */
    function handleGetShareUserList()
    {
        //检测用户是否已经登录
        if (! Database::dbGetAppClientPhoneNum($phone_num, $this->mFrame->fd))
        {//用户未登录
            $this->sendGeneralRespond(CMD_GET_SHARE_USER_LIST, RET_UN_LOGIN);

            if (DEBUG_APPCLIENT)
            {
                echo 'AppClient:handleGetShareUserList unlogin.'.PHP_EOL;
            }
            return;
        }

        //检查设备类型是否符合规定
        $dev_type = $this->mProtocol->getDevType();
        if ((strlen($dev_type) > MAX_LEN_DEV_TYPE) || empty($dev_type))
        {
            $this->sendGeneralRespond(CMD_GET_SHARE_USER_LIST, RET_DATA_ERROR);

            if (DEBUG_APPCLIENT)
            {
                echo 'AppClient:handleGetShareUserList devType len error.'.PHP_EOL;
            }

            return;
        }

        //检查设备系列是否符合规定
        $dev_series = $this->mProtocol->getDevSeries();
        if ((strlen($dev_series) > MAX_LEN_DEV_SERIES) || empty($dev_series))
        {
            $this->sendGeneralRespond(CMD_GET_SHARE_USER_LIST, RET_DATA_ERROR);

            if (DEBUG_APPCLIENT)
            {
                echo 'AppClient:handleGetShareUserList devSeries len error.'.PHP_EOL;
            }

            return;
        }

        //检查设备ID是否符合规定
        $dev_id = $this->mProtocol->getDevID();
        if ((strlen($dev_id) > MAX_LEN_DEV_ID) || empty($dev_id))
        {
            $this->sendGeneralRespond(CMD_GET_SHARE_USER_LIST, RET_DATA_ERROR);

            if (DEBUG_APPCLIENT)
            {
                echo 'AppClient:handleGetShareUserList devID len error.'.PHP_EOL;
            }

            return;
        }

        //获取该设备的归属用户
        if(! Database::dbGetPhoneNumBindDev($dev_type, $dev_series, $dev_id, $phone_bind))
        {
            $this->sendGeneralRespond(CMD_GET_SHARE_USER_LIST, RET_DEV_NOT_BIND);

            if (DEBUG_APPCLIENT)
            {
                echo 'AppClient:handleGetShareUserList dev not bind.'.PHP_EOL;
            }

            return;
        }

        //检测该设备是否被该用户绑定
        if($phone_num != $phone_bind)
        {
            $this->sendGeneralRespond(CMD_GET_SHARE_USER_LIST, RET_NO_PERMISSION);

            if (DEBUG_APPCLIENT)
            {
                echo 'AppClient:handleGetShareUserList no permission.'.PHP_EOL;
            }

            return;
        }

        //获取该设备的所有共享者
        if (Database::dbGetShareDevUsers($dev_type, $dev_series, $dev_id, $share_user_list))
        {
            if(0 == count($share_user_list))
            {//设备为空
                $this->sendGeneralRespond(CMD_GET_SHARE_USER_LIST, RET_FAIL);

                if (DEBUG_APPCLIENT)
                {
                    echo 'AppClient:handleGetShareDevList share_user_list is null.'.PHP_EOL;
                }
            }
            else
            {//设备不为空
                $json_user_list = array();
                $index = 0;
                foreach ($share_user_list as $temp_phone=>$temp_uname)
                {
                    $json_user_list[$index++] = $temp_phone;
                    $json_user_list[$index++] = $temp_uname;
                }

                //封装json数据发送给手机端
                $respond = array(
                    'cmd' => CMD_GET_SHARE_USER_LIST,
                    'data' => array(
                        'ret' => RET_OK,
                        'userList' => $json_user_list
                    ),
                );
                $this->mServ->push($this->mFrame->fd, json_encode($respond));
            }
        }
        else
        {
            $this->sendGeneralRespond(CMD_GET_SHARE_USER_LIST, RET_FAIL);

            if (DEBUG_APPCLIENT)
            {
                echo 'AppClient:handleGetShareUserList database error.'.PHP_EOL;
            }
        }
    }


    function handleGetVerifyCode()
    {
        //检查手机号码是否符合规定
        $phone_num = $this->mProtocol->getPhoneNum();
        if(!preg_match('/^1[34578]\d{9}$/',$phone_num))
        {
            $this->sendGeneralRespond(CMD_GET_VERIFY_CODE, RET_DATA_ERROR);

            if (DEBUG_APPCLIENT)
            {
                echo 'AppClient:handleGetVerifyCode phoneNum error.'.PHP_EOL;
            }

            return;
        }

        //检查验证码的用途
        $use_cmd = $this->mProtocol->getUse();
        if (empty($use_cmd))
        {
            $this->sendGeneralRespond(CMD_GET_VERIFY_CODE, RET_DATA_ERROR);

            if (DEBUG_APPCLIENT)
            {
                echo 'AppClient:handleGetVerifyCode use cmd error.'.PHP_EOL;
            }

            return;
        }
        else
        {
            switch ($use_cmd)
            {
                case CMD_REGISTER:
                    //检测是否已经注册
                    if(Database::dbCheckUserPhoneNumExist($phone_num))
                    {
                        $this->sendGeneralRespond(CMD_GET_VERIFY_CODE, RET_RE_REG);

                        if (DEBUG_APPCLIENT)
                        {
                            echo 'AppClient:handleGetVerifyCode already register.'.PHP_EOL;
                        }

                        return;
                    }
                    break;
                case CMD_MODIFY_PSWD:
                    if(! Database::dbCheckUserPhoneNumExist($phone_num))
                    {
                        $this->sendGeneralRespond(CMD_GET_VERIFY_CODE, RET_UN_REG);

                        if (DEBUG_APPCLIENT)
                        {
                            echo 'AppClient:handleGetVerifyCode not register.'.PHP_EOL;
                        }

                        return;
                    }
                    break;
                default:
                    $this->sendGeneralRespond(CMD_GET_VERIFY_CODE, RET_DATA_ERROR);
                    if (DEBUG_APPCLIENT)
                    {
                        echo 'AppClient:handleGetVerifyCode use cmd not right.'.PHP_EOL;
                    }
                    return;
            }
        }
        //获取数据库中保存的验证码和时间
        if (Database::dbGetAppCodeTime($phone_num, $old_code, $time))
        {
            //检测验证码是否过期
            if (time() < (strtotime($time) + VALID_CODE_SEC))
            {//验证码未过期
                $this->sendGeneralRespond(CMD_GET_VERIFY_CODE, RET_GET_CODE_TOO_FAST);

                if (DEBUG_APPCLIENT)
                {
                    echo 'AppClient:handleGetVerifyCode code is not out of date.'.PHP_EOL;
                }

                return;
            }
            else
            {
                if(! Database::dbDeleteAppCode($phone_num))
                {
                    $this->sendGeneralRespond(CMD_GET_VERIFY_CODE, RET_FAIL);

                    if (DEBUG_APPCLIENT)
                    {
                        echo 'AppClient:handleGetVerifyCode database error.'.PHP_EOL;
                    }

                    return;
                }
            }
        }

        if ("000" != MyGlobal::send_sms($phone_num, $code))
        {
            $this->sendGeneralRespond(CMD_GET_VERIFY_CODE, RET_FAIL);

            if (DEBUG_APPCLIENT)
            {
                echo 'AppClient:handleGetVerifyCode app get sms fail.'.PHP_EOL;
            }

            return;
        }
        else
        {
            //保存验证码到数据库中
            if (Database::dbInsertAppCode($phone_num, $code))
            {
                $this->sendGeneralRespond(CMD_GET_VERIFY_CODE, RET_OK);

                if (DEBUG_APPCLIENT)
                {
                    echo 'AppClient:handleGetVerifyCode success.'.PHP_EOL;
                }
            }
            else
            {
                $this->sendGeneralRespond(CMD_GET_VERIFY_CODE, RET_FAIL);

                if (DEBUG_APPCLIENT)
                {
                    echo 'AppClient:handleGetVerifyCode database error.'.PHP_EOL;
                }
            }
        }
    }

    /**
     * 手机端获取某个设备在线状态指令
     */
    function handleGetDevOnlineState()
    {
        //检测用户是否已经登录
        if (! Database::dbGetAppClientPhoneNum($phone_num, $this->mFrame->fd))
        {//用户未登录
            $this->sendGeneralRespond(CMD_GET_DEV_ONLINE_STATE, RET_UN_LOGIN);

            if (DEBUG_APPCLIENT)
            {
                echo 'AppClient:handleGetDevOnlineState unlogin.'.PHP_EOL;
            }
            return;
        }

        //检查设备类型是否符合规定
        $dev_type = $this->mProtocol->getDevType();
        if ((strlen($dev_type) > MAX_LEN_DEV_TYPE) || empty($dev_type))
        {
            $this->sendGeneralRespond(CMD_GET_DEV_ONLINE_STATE, RET_DATA_ERROR);

            if (DEBUG_APPCLIENT)
            {
                echo 'AppClient:handleGetDevOnlineState devType len error.'.PHP_EOL;
            }

            return;
        }

        //检查设备系列是否符合规定
        $dev_series = $this->mProtocol->getDevSeries();
        if ((strlen($dev_series) > MAX_LEN_DEV_SERIES) || empty($dev_series))
        {
            $this->sendGeneralRespond(CMD_GET_DEV_ONLINE_STATE, RET_DATA_ERROR);

            if (DEBUG_APPCLIENT)
            {
                echo 'AppClient:handleGetDevOnlineState devSeries len error.'.PHP_EOL;
            }

            return;
        }

        //检查设备ID是否符合规定
        $dev_id = $this->mProtocol->getDevID();
        if ((strlen($dev_id) > MAX_LEN_DEV_ID) || empty($dev_id))
        {
            $this->sendGeneralRespond(CMD_GET_DEV_ONLINE_STATE, RET_DATA_ERROR);

            if (DEBUG_APPCLIENT)
            {
                echo 'AppClient:handleGetDevOnlineState devID len error.'.PHP_EOL;
            }

            return;
        }

        //检测设备归属于你，还是被你共享
        if (! Database::dbGetPhoneNumBindDev($dev_type, $dev_series, $dev_id, $phone_bind))
        {
            $this->sendGeneralRespond(CMD_GET_DEV_ONLINE_STATE, RET_DEV_NOT_BIND);

            if (DEBUG_APPCLIENT)
            {
                echo 'AppClient:handleGetDevOnlineState fail.'.PHP_EOL;
            }

            return;
        }
        if ($phone_num != $phone_bind)
        {
            if (! Database::dbCheckShareDevPhone($dev_type, $dev_series, $dev_id, $phone_num))
            {
                $this->sendGeneralRespond(CMD_GET_DEV_ONLINE_STATE, RET_NO_PERMISSION);

                if (DEBUG_APPCLIENT)
                {
                    echo 'AppClient:handleGetDevOnlineState no permission.'.PHP_EOL;
                }

                return;
            }
        }

        //获取设备的在线状态
        if (Database::dbGetDevClientAdr($dev_type, $dev_series, $dev_id, $adr))
        {//设备在线
            $respond = array(
                'cmd' => CMD_GET_DEV_ONLINE_STATE,
                'data' => array(
                    'ret' => RET_OK,
                    'netstate' => ON_LINE
                )
            );

            $this->mServ->push($this->mFrame->fd, json_encode($respond));

            if (DEBUG_APPCLIENT)
            {
                echo 'AppClient:handleGetDevOnlineState dev online.'.PHP_EOL;
            }

            return;
        }

        //设备不在线
        $respond = array(
            'cmd' => CMD_GET_DEV_ONLINE_STATE,
            'data' => array(
                'ret' => RET_OK,
                'netstate' => OFF_LINE
            )
        );

        $this->mServ->push($this->mFrame->fd, json_encode($respond));

        if (DEBUG_APPCLIENT)
        {
            echo 'AppClient:handleGetDevOnlineState dev offline.'.PHP_EOL;
        }
    }

    /**
     * 获取推送消息指令
     */
    function handleGetPushMsg()
    {
        //检测用户是否已经登录
        if (! Database::dbGetAppClientPhoneNum($phone_num, $this->mFrame->fd))
        {//用户未登录
            $this->sendGeneralRespond(CMD_GET_PUSH_MSG, RET_UN_LOGIN);

            if (DEBUG_APPCLIENT)
            {
                echo 'AppClient:handleGetPushMsg unlogin.'.PHP_EOL;
            }
            return;
        }

        //获取推送消息序号
        $msgNum = $this->mProtocol->getMsgNum();
        if ($msgNum <= 0)
        {
            $this->sendGeneralRespond(CMD_GET_PUSH_MSG, RET_DATA_ERROR);

            if (DEBUG_APPCLIENT)
            {
                echo 'AppClient:handleGetPushMsg data error.'.PHP_EOL;
            }
            return;
        }
        //获取推送消息类型
        $msgType = $this->mProtocol->getMsgType();
        if ($msgType == 'devAlarm')
        {
            if (Database::dbGetPushMsg($msgNum, $msgType, $phone_num, $extra))
            {
                $respond = array(
                    'cmd' => CMD_GET_PUSH_MSG,
                    'data' => array(
                        'ret' => RET_OK,
                        'devType' => $extra['devType'],
                        'devSeries' => $extra['devSeries'],
                        'devID' => $extra['devID'],
                        'devName' => $extra['devName'],
                        'content' => $extra['content'],
                        'pushTime' => $extra['pushTime']
                    )
                );

                $this->mServ->push($this->mFrame->fd, json_encode($respond));
            }
            else
            {
                $this->sendGeneralRespond(CMD_GET_PUSH_MSG, RET_NO_MORE_PUSH_MSG);

                if (DEBUG_APPCLIENT)
                {
                    echo 'AppClient:handleGetPushMsg no more push msg.'.PHP_EOL;
                }

                return;
            }
        }
        else
        {
            $this->sendGeneralRespond(CMD_GET_PUSH_MSG, RET_DATA_ERROR);

            if (DEBUG_APPCLIENT)
            {
                echo 'AppClient:handleGetPushMsg data error.'.PHP_EOL;
            }
            return;
        }
    }
}

?>
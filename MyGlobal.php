<?php
/**
 * Created by PhpStorm.
 * User: yjw
 * Date: 17-3-28
 * Time: 上午11:47
 */

namespace ITTOPONE;

//包含头文件
include_once("debug.php");
include_once("./jpush-api-php-client-3.5.12/autoload.php");

use JPush\Client as JPush;

//是否使能服务器轮询设备获取告警信息并推送给相应的用户
defined('ENABLE_SERV_POLL_ALARM') or define('ENABLE_SERV_POLL_ALARM', false);
//是否使能极光推送
defined('ENABLE_JPUSH') or define('ENABLE_JPUSH', false);

//存储app版本号的文件的路径
defined('VER_FILE_PATH') or define('VER_FILE_PATH', './appVersion.php');

//app请求dev的有效时间范围
defined('VALID_RES_SEC') or define('VALID_RES_SEC', 6);
//验证码的有效时间
defined('VALID_CODE_SEC') or define('VALID_CODE_SEC', 60);

//极光推送
defined('APP_KEY') or define('APP_KEY', 'd03ad51184914e7a758d11e3');
defined('MASTER_SECRET') or define('MASTER_SECRET', '812ac723bd82fea07bfcafb5');
defined('JPUSH_LOG_PATH') or define('JPUSH_LOG_PATH', './jpush.log');
defined('APP_NAME') or define('APP_NAME', '易通技术');

//APP下载url
defined('APP_ANDROID_URL') or define('APP_ANDROID_URL', 'https://www.yujunwu.top/downloadpage/index.html');
defined('APP_IOS_URL') or define('APP_IOS_URL', 'https://www.yujunwu.top/downloadpage/index.html');

//设备端设置地址指令
defined('CMD_SET_DEV_ADR') or define('CMD_SET_DEV_ADR', 0x84);
//获取设备实时告警指令
defined('CMD_GET_DEV_ALARM') or define('CMD_GET_DEV_ALARM', 0x85);
//恢复出厂设置
defined('CMD_DEV_RECOVERY') or define('CMD_DEV_RECOVERY', 0x92);

//手机端透传数据指令
defined('CMD_DIRECT_TRANSPORT') or define('CMD_DIRECT_TRANSPORT', 10);

//成功返回码
defined('RET_OK') or define('RET_OK', 0);

//服务器ID
defined('SERV_ID') or define('SERV_ID', '01234567890');
//轮询设备报警状态的定时器周期
defined('SEC_POLL_ALARM_TIMER') or define('SEC_POLL_ALARM_TIMER', (10 * 1000));

/**
 * Class MyGlobal
 * @package ITTOPONE
 */
class MyGlobal
{

    /**
     * 初始化APP软件版本
     */
    static function initAppVersion()
    {
        $version = array(
            'android_app' => '1.0.0',
            'ios_app' => '1.0.0',
        );
        //保存
        $path = VER_FILE_PATH;
        if (fopen($path, 'w'))
        {
            file_put_contents($path, serialize($version));
        }
        //还原
        $fo = fopen($path, 'r');
        $arr = unserialize(fread($fo, filesize($path)));

        if(DEBUG_MYGLOBAL)
        {
            print_r($arr);
            echo PHP_EOL;
        }
    }

    /**
     * 将byte数组转换为string类型
     * @param $bytes:要转换为string的byte数组
     * @return string
     */
    static function bytes_to_string($bytes)
    {
        $str = '';
        if(DEBUG_MYGLOBAL)
            echo 'bytes:';
        foreach($bytes as $ch)
        {
            //chr():从不同的ASCII值返回字符
            $str .= chr($ch);
            if (DEBUG_MYGLOBAL)
                printf('%02x ', $ch);
        }

        if(DEBUG_MYGLOBAL)
            echo 'toString:'.$str.PHP_EOL;
        return $str;
    }

    /**
     * 根据设备类型返回设备类型名称
     * @param $dev_type
     * @return string
     */
    static function getDevTypeName($dev_type)
    {
        switch ($dev_type)
        {
            case DEV_TYPE_SPC:
                return '智能插座';
            default:
                return '未知设备';
        }
    }

    /**
     * 根据设备类型获取CID1
     * @param $dev_type
     * @return int
     */
    static function getCID1ByDevType($dev_type)
    {
        switch ($dev_type)
        {
            case DEV_TYPE_SPC:
                return CID1_SPC;
            default:
                return 0xff;
        }
    }

    /**
     * 发送短信验证码
     * 短信服务提供商：中正云
     * @param $phone
     * @param $code
     * @return mixed|string
     */
    static function send_sms($phone, & $code)
    {
        $randStr = str_shuffle('1234567890');
        $code = substr($randStr,0,6);
        $msg="尊敬的用户，您本次的验证码为：".$code."，1分钟内有效。如非本人操作请忽略本信息。";

        $url="http://service.winic.org:8009/sys_port/gateway/index.asp?";
        $data = "id=%s&pwd=%s&to=%s&content=%s&time=";
        $id = 'ittopone';
        $pwd = 'yjw785116032493';
        $to = $phone;
        $content = iconv("UTF-8","GB2312",$msg);
        $rdata = sprintf($data, $id, $pwd, $to, $content);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_POST,1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,$rdata);
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
        $result = curl_exec($ch);
        curl_close($ch);
        $result = substr($result,0,3);
        if(DEBUG_MYGLOBAL)
        {
            echo 'send_sms result:'.$result.PHP_EOL;
        }
        return $result;
    }

    /**
     * 极光推送
     * @param $phone_num
     * @param $data
     * @param $extra
     * @return array
     */
    static function jpush_send($phone_num, $data, $extra)
    {
//        $app_key = getenv(APP_KEY);
//        $master_secret = getenv(MASTER_SECRET);
//        $registration_id = getenv('registration_id');
        $client = new JPush(APP_KEY, MASTER_SECRET, JPUSH_LOG_PATH);

        try
        {
            $response = $client->push()
                ->setPlatform(array('ios', 'android'))
                // 一般情况下，关于 audience 的设置只需要调用 addAlias、addTag、addTagAnd  或 addRegistrationId
                // 这四个方法中的某一个即可，这里仅作为示例，当然全部调用也可以，多项 audience 调用表示其结果的交集
                // 即是说一般情况下，下面三个方法和没有列出的 addTagAnd 一共四个，只适用一个便可满足大多数的场景需求

                ->addAlias("$phone_num")
                //->addTag(array('tag1', 'tag2'))
                // ->addRegistrationId($registration_id)

                ->setNotificationAlert(APP_NAME)
                ->iosNotification($data, array(
                    'sound' => 'sound.caf',
                    // 'badge' => '+1',
                    'content-available' => true,
                    'mutable-content' => true,
                    'category' => 'eTon-Tech',
                    'extras' => $extra,
                ))
                ->androidNotification($data, array(
                    'title' => APP_NAME,
                    // 'build_id' => 2,
                    'extras' => $extra,
                ))
                ->message($data, array(
                    'title' => APP_NAME,
                    'content_type' => 'text',
                    'extras' => $extra,
                ))
                ->options(array(
                    // sendno: 表示推送序号，纯粹用来作为 API 调用标识，
                    // API 返回时被原样返回，以方便 API 调用方匹配请求与返回
                    // 这里设置为 100 仅作为示例

                    // 'sendno' => 100,

                    // time_to_live: 表示离线消息保留时长(秒)，
                    // 推送当前用户不在线时，为该用户保留多长时间的离线消息，以便其上线时再次推送。
                    // 默认 86400 （1 天），最长 10 天。设置为 0 表示不保留离线消息，只有推送当前在线的用户可以收到
                    // 这里设置为 1 仅作为示例

                    // 'time_to_live' => 1,

                    // apns_production: 表示APNs是否生产环境，
                    // True 表示推送生产环境，False 表示要推送开发环境；如果不指定则默认为推送生产环境

                    'apns_production' => true,

                    // big_push_duration: 表示定速推送时长(分钟)，又名缓慢推送，把原本尽可能快的推送速度，降低下来，
                    // 给定的 n 分钟内，均匀地向这次推送的目标用户推送。最大值为1400.未设置则不是定速推送
                    // 这里设置为 1 仅作为示例

                    // 'big_push_duration' => 1
                ))
                ->send();

            if (DEBUG_MYGLOBAL)
            {
                print_r($response);
            }

            return $response;
        }
        catch (\JPush\Exceptions\APIConnectionException $e)
        {
            // try something here

            if (DEBUG_MYGLOBAL)
            {
                print $e;
            }
        }
        catch (\JPush\Exceptions\APIRequestException $e)
        {
            // try something here

            if (DEBUG_MYGLOBAL)
            {
                print $e;
            }
        }
    }
}

?>
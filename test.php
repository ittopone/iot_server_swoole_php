<?php
/**
 * Created by PhpStorm.
 * User: yjw
 * Date: 17-3-28
 * Time: 下午4:58
 */

include_once ("MyGlobal.php");

$a = 123;
$a = array(0x01, 0x02);
print_r($a);

function test_arg(& $test)
{
    $test = 'test chuan can.';
}

test_arg($test);
echo $test.PHP_EOL;

if(test_arg($test1))
{

}
echo $test1.PHP_EOL;

$arr = array();

if (empty($arr))
    echo 'arr is empty'.PHP_EOL;

if (0 == count($arr))
    echo 'arr is 0'.PHP_EOL;

$count = 6666;
while (true and ($count > 0))
{
    $count--;
    echo 'count == 0'.PHP_EOL;
}

while (false and 1)
    echo 'count == 1'.PHP_EOL;

$index = 0;
$devClients[$index++] = array('1', '2', '3');
$devClients[$index++] = array('4', '5', '6');
foreach ($devClients as $index => $client)
{
    echo 'client:'.$client[0].$client[1].$client[2].PHP_EOL;
}

//测试极光推送
//打包推送的消息内容
$jp_data = '空调插座'.'：'.'过压告警 过流告警';
//打包extra
$jp_extra = array(
    'devType' => 'SPC',
    'devSeries' => 'ET-SPC-001',
    'devID' => '6001940f0ef4',
    'devName' => '空调插座',
    'pushTime' => date("Y-m-d H:i:s",time()),
    'content' => '过压告警 过流告警',
    'msgType' => 'devAlarm',
);
$phone = '18825221627';
\ITTOPONE\MyGlobal::jpush_send($phone, $jp_data, $jp_extra);

//测试短信验证码
\ITTOPONE\MyGlobal::send_sms("18825221627", $smscode);
echo 'smscode is '.PHP_EOL;

$arr = array('123'=> '1232131',
    '456' => '324234');
foreach ($arr as $mykey => $myvalue)
{
    echo 'mykey v:'."$mykey".PHP_EOL;
}

?>
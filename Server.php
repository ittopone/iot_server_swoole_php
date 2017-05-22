<?php
/**
 * Created by PhpStorm.
 * User: yjw
 * Date: 16-12-28
 * Time: 下午2:05
 */

//定义命名空间
namespace ITTOPONE;

//包含头文件
include_once("debug.php");
include_once("DevClient.php");
include_once("AppClient.php");

/*常量定义*/

/**
 * Class Server
 * @package Server
 */
class Server
{
    private $mServ;
    private $mPort;
    /**
     * Server constructor.
     * @param $ipAddr
     * @param $port
     */
    function __construct($ipAddr, $portForApp, $portForDev)
    {
        //构建webSocket服务器
        $this->mServ = new \swoole_websocket_server($ipAddr, $portForApp);

        //构建TCP服务器
        $this->mPort = $this->mServ->listen($ipAddr, $portForDev, SWOOLE_SOCK_TCP);
        //需要重定义TCP协议，默认会跟webSocket一致
        $this->mPort->set(array(
            'package_max_length' => 8192,
            'open_eof_check'=> true,
            'package_eof' => "\r"
        ));

        //设置服务器运行参数
        $this->mServ->set(array(
            //通过此参数来调节poll线程的数量，以充分利用多核
            'reactor_num' => 2,
            /*
             * 设置启动的worker进程数量。swoole采用固定worker进程的模式。
             * PHP代码中是全异步非阻塞，worker_num配置为CPU核数的1-4倍即可。
             * 如果是同步阻塞，worker_num配置为100或者更高，具体要看每次请求处理的耗时和操作系统负载状况。
             * 当设定的worker进程数小于reactor线程数时，会自动调低reactor线程的数量
             */
            'worker_num' => 8,
            //daemonize => 1，加入此参数后，执行程序将转入后台作为守护进程运行
            'daemonize' => false,
            //此参数将决定最多同时有多少个待accept的连接，swoole本身accept效率是很高的，基本上不会出现大量排队情况。
            'backlog' => 128,
            //启用CPU亲和设置
            'open_cpu_affinity' => 1,
            //启用tcp_nodelay
//            'open_tcp_nodelay' => 1,
            //此参数设定一个秒数，当客户端连接连接到服务器时，在约定秒数内并不会触发accept，直到有数据发送，或者超时时才会触发。
//            'tcp_defer_accept' => 5,
            //指定swoole错误日志文件。在swoole运行期发生的异常信息会记录到这个文件中。默认会打印到屏幕。
            'log_file' => './swoole.log',
            /*
             * buffer主要是用于检测数据是否完整，如果不完整swoole会继续等待新的数据到来。
             * 直到收到完整的一个请求，才会一次性发送给worker进程。
             * 这时onReceive会收到一个超过SW_BUFFER_SIZE，小于$serv->setting['package_max_length']的数据。
             * 目前仅提供了EOF检测、固定包头长度检测2种buffer模式。
             */
//            'open_eof_check' => true,//打开buffer
//            'package_eof' => "\r\n\r\n",//设置EOF
            //每隔多少秒检测一次，单位秒，Swoole会轮询所有TCP连接，将超过心跳时间的连接关闭掉
            'heartbeat_check_interval' => 30,
            //TCP连接的最大闲置时间，单位s , 如果某fd最后一次发包距离现在的时间超过heartbeat_idle_time会把这个连接关闭。
            //建议 heartbeat_idle_time 为 heartbeat_check_interval 的两倍多一点。
            'heartbeat_idle_time' => 65,
            /*
             * 1平均分配，2按FD取摸固定分配，3抢占式分配，默认为取模(dispatch=2)
             * 抢占式分配，每次都是空闲的worker进程获得数据。很合适SOA/RPC类的内部服务框架
             * 当选择为dispatch=3抢占模式时，worker进程内发生onConnect/onReceive/onClose/onTimer会将worker进程标记为忙，
             * 不再接受新的请求。reactor会将新请求投递给其他状态为闲的worker进程
             * 如果希望每个连接的数据分配给固定的worker进程，dispatch_mode需要设置为2
             */
//            'dispatch_mode' => 1,

        ));

        //webSocket：注册事件回调函数
        $this->mServ->on("open", function($serv, $request){
            if(DEBUG_SERVER)
            {
                echo "server: handshake success with fd = {$request->fd}".PHP_EOL;
            }
        });
        $this->mServ->on("message", function($serv, $frame){
            if(DEBUG_SERVER)
            {
                echo 'server:receive from app fd = '.$frame->fd.', '.'data = '.$frame->data.PHP_EOL;
                if(0)
                {
                    if(0)
                        $this->mServ->push($frame->fd, "this msg came from webSocket server mServ.");
                    else
                        $serv->push($frame->fd, "this msg came from webSocket server serv.");
                }
            }

            //创建手机端对象，进行数据处理
            $appClient = new AppClient($serv, $frame);
            //释放对象
            unset($appClient);
        });
        $this->mServ->on("close", function($serv, $fd){
            //删除手机端登录信息
            Database::dbDelAppClient($fd);

            if(DEBUG_SERVER)
            {
                echo "server:app fd = {$fd} close.".PHP_EOL;
            }
        });


        //TCP：注册事件回调函数
        $this->mPort->on("connect", function($serv, $fd){
            if(DEBUG_SERVER)
            {
                echo "server:dev fd = {$fd} connected.".PHP_EOL;
            }
        });
        $this->mPort->on("receive", function($serv, $fd, $from_id, $data){
            if(DEBUG_SERVER)
            {
                echo "server:receive from dev fd = {$fd}, data = {$data}".PHP_EOL;
                if(0)
                {
                    if(0)
                        $this->mServ->send($fd, "this msg came from tcp server mServ.");
                    else
                        $serv->send($fd, "this msg came from tcp server serv.");
                }
            }

            //创建设备对象，进行数据处理
            $devClient = new DevClient($serv, $fd, $data);
            //释放对象
            unset($devClient);
        });
        $this->mPort->on("close", function($serv, $fd){
            //删除设备端登录信息
            Database::dbDelDevClient($fd);

            /*释放所有手机端对该设备的请求*/
            if(Database::dbGetDevClientWithoutADR($devClients, $fd))
            {
                foreach ($devClients as $index => $client)
                {
                    //删除请求
                    Database::dbDeleteAppResDev($client[0], $client[1], $client[2]);
                }
            }

            if(DEBUG_SERVER)
            {
                echo "server:dev fd = {$fd} disconnected.".PHP_EOL;
            }
        });

        //是否使能服务器轮询设备获取告警信息并推送给相应的用户
        if (ENABLE_SERV_POLL_ALARM)
        {
            //新创建一个进程，用于定时轮询设备报警信息，并推送给相应的用户
            $process = new \swoole_process(function($process){
                \swoole_timer_tick(SEC_POLL_ALARM_TIMER, function(){
                    if(DEBUG_SERVER)
                    {
                        echo "Server:timeout sec = ".(SEC_POLL_ALARM_TIMER / 1000).PHP_EOL;
                    }

                    if (Database::dbGetDevClientCount($count))
                    {
                        $index = 0;
                        while($index < $count)
                        {
                            //获取已登录的设备的登录记录
                            if (Database::dbGetDevClientOneByOne($index, $dev_type, $dev_series, $dev_id, $ADR, $fd))
                            {
                                //检测该设备是否正忙
                                if(Database::dbGetAppResDevPhoneTime($dev_type,$dev_series,$dev_id,$CID2,$phone_res,$time))
                                {
                                    if ((time() - strtotime($time)) <= VALID_RES_SEC)
                                    {//设备正忙

                                        if (DEBUG_SERVER)
                                        {
                                            echo 'Server:dev busy id is '.$dev_id.PHP_EOL;
                                        }

                                        continue;
                                    }
                                    else
                                    {//设备空闲
                                        if(Database::dbDeleteAppResDev($dev_type, $dev_series, $dev_id))
                                        {
                                            if (! Database::dbInsertAppResDev($dev_type,$dev_series,$dev_id,CMD_GET_DEV_ALARM,SERV_ID))
                                            {
                                                if (DEBUG_SERVER)
                                                {
                                                    echo 'Server:insert res database error.'.PHP_EOL;
                                                }

                                                continue;
                                            }
                                        }
                                        else
                                        {
                                            if (DEBUG_SERVER)
                                            {
                                                echo 'Server:delete res database error.'.PHP_EOL;
                                            }

                                            continue;
                                        }
                                    }
                                }
                                else
                                {
                                    if (! Database::dbInsertAppResDev($dev_type,$dev_series,$dev_id,CMD_GET_DEV_ALARM,SERV_ID))
                                    {
                                        if (DEBUG_SERVER)
                                        {
                                            echo 'Server:insert res database error.'.PHP_EOL;
                                        }

                                        continue;
                                    }
                                }

                                //填充协议内容
                                $devProtocol = new DevProtocol();
                                $devProtocol->setADR($ADR);
                                $devProtocol->setCID1(MyGlobal::getCID1ByDevType($dev_type));
                                $devProtocol->setCID2(CMD_GET_DEV_ALARM);
                                //打包协议并发送给相应的设备
                                $this->mServ->send($fd, $devProtocol->packData());
                            }
                            //获取下一条记录
                            $index++;
                            //更新记录总数
                            if (!Database::dbGetDevClientCount($count))
                                break;
                        }
                    }
                });
            });

            //添加子进程
            $this->mServ->addProcess($process);
        }

        //启动服务器
        $this->mServ->start();
    }
}

?>
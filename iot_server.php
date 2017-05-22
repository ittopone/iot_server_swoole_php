<?php
/**
 * Created by PhpStorm.
 * User: yjw
 * Date: 16-12-31
 * Time: 上午10:18
 */

//包含头文件
include_once("Server.php");
include_once("MyGlobal.php");

//使用命名空间
use ITTOPONE\Server;
use ITTOPONE\Database;
use ITTOPONE\MyGlobal;

//APP软件版本初始化
if(0)
{
    MyGlobal::initAppVersion();
}

//删除数据库表
if(1)
{
    //删除数据库表tb_appClients
    if(! Database::dropTableAppClients())
        die('drop table tb_appClients error, server shut down.');
    //删除数据库表tb_devClients
    if(! Database::dropTableDevClients())
        die('drop table tb_devClients error, server shut down.');
    //删除数据库表tb_appResDev
    if (! Database::dropTableAppResDev())
        die('drop table tb_appResDev error, server shut down.');
    //删除数据库表tb_appCode
    if (! Database::dropTableAppCode())
        die('drop table tb_appCode error, server shut down.');
}

//创建数据库表tb_users
if(! Database::createTableUsers())
    die('create table tb_users error, server shut down.');
//创建数据库表tb_devices
if(! Database::createTableDevices())
    die('create table tb_devices error, server shut down.');
//创建数据库表tb_shareDevs
if(! Database::createTableShareDevs())
    die('create table tb_shareDevs error, server shut down.');
//创建数据库表tb_appClients
if(! Database::createTableAppClients())
    die('create table tb_appClients error, server shut down.');
//创建数据库表tb_devClients
if(! Database::createTableDevClients())
    die('create table tb_devClients error, server shut down.');
//创建数据库表tb_appResDev
if(! Database::createTableAppResDev())
    die('create table tb_appResDev error, server shut down.');
//创建数据库表tb_appCode
if(! Database::createTableAppCode())
    die('create table tb_appCode error, server shut down.');
//创建数据库表tb_pushMsgs
if(! Database::createTablePushMsgs())
    die('create table tb_pushMsgs error, server shut down.');

//创建服务器对象
$gServ = new Server('192.168.1.219', 8900, 8901);

?>
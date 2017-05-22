<?php
/**
 * Created by PhpStorm.
 * User: yjw
 * Date: 17-1-4
 * Time: 上午10:57
 */

//定义命名空间
namespace ITTOPONE;

//包含头文件
include_once("debug.php");

//常量定义
defined('SERVER_NAME') or define('SERVER_NAME', 'localhost');
defined('USER_NAME') or define('USER_NAME', 'root');
defined('PASSWORD') or define('PASSWORD', '78511603');
defined('DB_NAME') or define('DB_NAME', 'yjw_db');

/**
 * Class Database
 * @package ITTOPONE
 */
class Database
{
    /**
     * 创建数据库连接
     * @param $conn 保存获取到的连接
     * @return bool
     */
    static function createDbConnection(& $conn)
    {
        $conn = new \mysqli(SERVER_NAME, USER_NAME, PASSWORD, DB_NAME);
        // 检测连接
        if ($conn->connect_error)
        {
            if (DEBUG_DATABASE)
            {
                echo 'Database:connect db error:'. $conn->connect_error.PHP_EOL;
            }
            return false;
        }

        if (DEBUG_DATABASE)
        {
            echo 'Database:connect db success'.PHP_EOL;
        }

        return true;
    }

    /**
     * 创建数据库表tb_users
     * @return bool
     */
    static function createTableUsers()
    {
        //获取数据库连接
        $conn = null;
        if(! self::createDbConnection($conn))
        {
            return false;
        }
        //创建数据库表tb_users
        $sql = 'create table if not exists tb_users(' .
            'id integer unsigned auto_increment not null,' .
            'phone_num char(11) not null,' .
            'user_name char(30) not null,' .
            'password char(20) not null,' .
            'register_time timestamp not null default CURRENT_TIMESTAMP,' .
            'primary key(id),' .
            'unique(phone_num))';
        if(true == $conn->query($sql))
        {
            if(DEBUG_DATABASE)
            {
                echo 'Database:succeed to create table tb_users' . PHP_EOL;
            }
        }
        else
        {
            if(DEBUG_DATABASE)
            {
                echo 'Database:fail to create table tb_users:';
                var_dump($conn->error, $conn->errno);
                echo PHP_EOL;
            }
            $conn->close();//释放数据库连接
            return false;
        }

        $conn->close();//释放数据库连接
        return true;
    }

    /**
     * 创建数据库表tb_devices
     * @return bool
     */
    static function createTableDevices()
    {
        //获取数据库连接
        $conn = null;
        if(! self::createDbConnection($conn))
        {
            return false;
        }

        //创建数据库表tb_devices
        $sql = 'create table if not exists tb_devices(' .
            'id integer unsigned auto_increment not null,' .
            'dev_type char(10) not null,' .
            'dev_series char(10) not null,' .
            'dev_id char(30) not null,' .
            'dev_name char(30),' .
            'soft_version char(5),' .
            'phone_num char(11),' .
            'bind_time timestamp not null default CURRENT_TIMESTAMP,' .
            'primary key(id),' .
            'unique(dev_id))';
        if(true == $conn->query($sql))
        {
            if(DEBUG_DATABASE)
            {
                echo 'Database:succeed to create table tb_devices' . PHP_EOL;
            }
        }
        else
        {
            if(DEBUG_DATABASE)
            {
                echo 'Database:fail to create table tb_devices:';
                var_dump($conn->error, $conn->errno);
                echo PHP_EOL;
            }
            $conn->close();//释放数据库连接
            return false;
        }

        $conn->close();//释放数据库连接
        return true;
    }

    /**
     * 创建数据库表tb_appClients
     * @return bool
     */
    static function createTableAppClients()
    {
        //获取数据库连接
        $conn = null;
        if(! self::createDbConnection($conn))
        {
            return false;
        }

        //创建数据库表tb_appClients
        $sql = 'create table if not exists tb_appClients(' .
            'id integer unsigned auto_increment not null,' .
            'phone_num char(11) not null,' .
            'fd integer unsigned not null,' .
            'login_time timestamp not null default CURRENT_TIMESTAMP,' .
            'primary key(id),' .
            'unique(phone_num))';
        if(true == $conn->query($sql))
        {
            if(DEBUG_DATABASE)
            {
                echo 'Database:succeed to create table tb_appClients' . PHP_EOL;
            }
        }
        else
        {
            if(DEBUG_DATABASE)
            {
                echo 'Database:fail to create table tb_appClients:';
                var_dump($conn->error, $conn->errno);
                echo PHP_EOL;
            }
            $conn->close();//释放数据库连接
            return false;
        }

        $conn->close();//释放数据库连接
        return true;
    }

    /**
     * 创建数据库表tb_devClients
     * @return bool
     */
    static function createTableDevClients()
    {
        //获取数据库连接
        $conn = null;
        if(! self::createDbConnection($conn))
        {
            return false;
        }

        //创建数据库表tb_appClients
        $sql = 'create table if not exists tb_devClients(' .
            'id integer unsigned auto_increment not null,' .
            'dev_type char(10) not null,' .
            'dev_series char(10) not null,' .
            'dev_id char(30) not null,' .
            'adr integer unsigned not null,' .
            'fd integer unsigned not null,' .
            'login_time timestamp not null default CURRENT_TIMESTAMP,' .
            'primary key(id)' .
            ')';
        if(true == $conn->query($sql))
        {
            if(DEBUG_DATABASE)
            {
                echo 'Database:succeed to create table tb_devClients' . PHP_EOL;
            }
        }
        else
        {
            if(DEBUG_DATABASE)
            {
                echo 'Database:fail to create table tb_devClients:';
                var_dump($conn->error, $conn->errno);
                echo PHP_EOL;
            }
            $conn->close();//释放数据库连接
            return false;
        }

        $conn->close();//释放数据库连接
        return true;
    }

    /**
     * 创建数据库表tb_shareDevs
     * @return bool
     */
    static function createTableShareDevs()
    {
        //获取数据库连接
        $conn = null;
        if(! self::createDbConnection($conn))
        {
            return false;
        }

        //创建数据库表tb_shareDevs
        $sql = 'create table if not exists tb_shareDevs(' .
            'id integer unsigned auto_increment not null,' .
            'dev_type char(10) not null,' .
            'dev_series char(10) not null,' .
            'dev_id char(30) not null,' .
            'phone_num char(11) not null,' .
            'share_time timestamp not null default CURRENT_TIMESTAMP,' .
            'primary key(id)' .
            ')';
        if(true == $conn->query($sql))
        {
            if(DEBUG_DATABASE)
            {
                echo 'Database:succeed to create table tb_shareDevs' . PHP_EOL;
            }
        }
        else
        {
            if(DEBUG_DATABASE)
            {
                echo 'Database:fail to create table tb_shareDevs:';
                var_dump($conn->error, $conn->errno);
                echo PHP_EOL;
            }
            $conn->close();//释放数据库连接
            return false;
        }

        $conn->close();//释放数据库连接
        return true;
    }

    /**
     * 创建数据库表tb_appResDev
     * @return bool
     */
    static function createTableAppResDev()
    {
        //获取数据库连接
        $conn = null;
        if(! self::createDbConnection($conn))
        {
            return false;
        }

        //创建数据库表tb_appResDev
        $sql = 'create table if not exists tb_appResDev(' .
            'id integer unsigned auto_increment not null,' .
            'dev_type char(10) not null,' .
            'dev_series char(10) not null,' .
            'dev_id char(30) not null,' .
            'CID2 integer unsigned not null,' .
            'phone_num char(11) not null,' .
            'res_time timestamp not null default CURRENT_TIMESTAMP,' .
            'primary key(id)' .
            ')';
        if(true == $conn->query($sql))
        {
            if(DEBUG_DATABASE)
            {
                echo 'Database:succeed to create table tb_appResDev' . PHP_EOL;
            }
        }
        else
        {
            if(DEBUG_DATABASE)
            {
                echo 'Database:fail to create table tb_appResDev:';
                var_dump($conn->error, $conn->errno);
                echo PHP_EOL;
            }
            $conn->close();//释放数据库连接
            return false;
        }

        $conn->close();//释放数据库连接
        return true;
    }

    /**
     * 创建数据库表tb_appCode
     * @return bool
     */
    static function createTableAppCode()
    {
        //获取数据库连接
        $conn = null;
        if(! self::createDbConnection($conn))
        {
            return false;
        }

        //创建数据库表tb_appCode
        $sql = 'create table if not exists tb_appCode(' .
            'id integer unsigned auto_increment not null,' .
            'phone_num char(11) not null,' .
            'code char(6) not null,' .
            'res_time timestamp not null default CURRENT_TIMESTAMP,' .
            'primary key(id)' .
            ')';
        if(true == $conn->query($sql))
        {
            if(DEBUG_DATABASE)
            {
                echo 'Database:succeed to create table tb_appCode' . PHP_EOL;
            }
        }
        else
        {
            if(DEBUG_DATABASE)
            {
                echo 'Database:fail to create table tb_appCode:';
                var_dump($conn->error, $conn->errno);
                echo PHP_EOL;
            }
            $conn->close();//释放数据库连接
            return false;
        }

        $conn->close();//释放数据库连接
        return true;
    }

    /**
     * 创建数据库表tb_pushMsgs
     * @return bool
     */
    static function createTablePushMsgs()
    {
        //获取数据库连接
        $conn = null;
        if(! self::createDbConnection($conn))
        {
            return false;
        }

        //创建数据库表tb_pushMsgs
        $sql = 'create table if not exists tb_pushMsgs(' .
            'id integer unsigned auto_increment not null,' .
            'dev_type char(10) not null,' .
            'dev_series char(10) not null,' .
            'dev_id char(30) not null,' .
            'dev_name char(30) not null,' .
            'msg_type char(30) not null,' .
            'phone_num char(11) not null,' .
            'content char(255) not null,' .
            'push_time timestamp not null default CURRENT_TIMESTAMP,' .
            'primary key(id)' .
            ')';
        if(true == $conn->query($sql))
        {
            if(DEBUG_DATABASE)
            {
                echo 'Database:createTablePushMsgs succeed to create table tb_pushMsgs' . PHP_EOL;
            }
        }
        else
        {
            if(DEBUG_DATABASE)
            {
                echo 'Database:createTablePushMsgs fail to create table tb_pushMsgs:';
                var_dump($conn->error, $conn->errno);
                echo PHP_EOL;
            }
            $conn->close();//释放数据库连接
            return false;
        }

        $conn->close();//释放数据库连接
        return true;
    }

    /**
     * 删除数据库表tb_appClients
     * @return bool
     */
    static function dropTableAppClients()
    {
        //获取数据库连接
        $conn = null;
        if(! self::createDbConnection($conn))
        {
            return false;
        }
        //删除数据库表tb_appClients
        $sql = 'drop table tb_appClients';
        if(true == $conn->query($sql))
        {
            if(DEBUG_DATABASE)
            {
                echo 'Database:succeed to drop table tb_appClients' . PHP_EOL;
            }
        }
        else
        {
            if(DEBUG_DATABASE)
            {
                echo 'Database:fail to drop table tb_appClients:';
                var_dump($conn->error, $conn->errno);
                echo PHP_EOL;
            }

            $conn->close();//释放数据库连接
            return false;
        }

        $conn->close();//释放数据库连接
        return true;
    }

    /**
     * 删除数据库表tb_devClients
     * @return bool
     */
    static function dropTableDevClients()
    {
        //获取数据库连接
        $conn = null;
        if(! self::createDbConnection($conn))
        {
            return false;
        }
        //删除数据库表tb_devClients
        $sql = 'drop table tb_devClients';
        if(true == $conn->query($sql))
        {
            if(DEBUG_DATABASE)
            {
                echo 'Database:succeed to drop table tb_devClients' . PHP_EOL;
            }
        }
        else
        {
            if(DEBUG_DATABASE)
            {
                echo 'Database:fail to drop table tb_devClients:';
                var_dump($conn->error, $conn->errno);
                echo PHP_EOL;
            }

            $conn->close();//释放数据库连接
            return false;
        }

        $conn->close();//释放数据库连接
        return true;
    }

    /**
     * 删除数据库表tb_appResDev
     * @return bool
     */
    static function dropTableAppResDev()
    {
        //获取数据库连接
        $conn = null;
        if(! self::createDbConnection($conn))
        {
            return false;
        }
        //删除数据库表tb_appResDev
        $sql = 'drop table tb_appResDev';
        if(true == $conn->query($sql))
        {
            if(DEBUG_DATABASE)
            {
                echo 'Database:succeed to drop table tb_appResDev' . PHP_EOL;
            }
        }
        else
        {
            if(DEBUG_DATABASE)
            {
                echo 'Database:fail to drop table tb_appResDev:';
                var_dump($conn->error, $conn->errno);
                echo PHP_EOL;
            }

            $conn->close();//释放数据库连接
            return false;
        }

        $conn->close();//释放数据库连接
        return true;
    }

    /**
     * 删除数据库表tb_appCode
     * @return bool
     */
    static function dropTableAppCode()
    {
        //获取数据库连接
        $conn = null;
        if(! self::createDbConnection($conn))
        {
            return false;
        }
        //删除数据库表tb_appCode
        $sql = 'drop table tb_appCode';
        if(true == $conn->query($sql))
        {
            if(DEBUG_DATABASE)
            {
                echo 'Database:succeed to drop table tb_appCode' . PHP_EOL;
            }
        }
        else
        {
            if(DEBUG_DATABASE)
            {
                echo 'Database:fail to drop table tb_appCode:';
                var_dump($conn->error, $conn->errno);
                echo PHP_EOL;
            }

            $conn->close();//释放数据库连接
            return false;
        }

        $conn->close();//释放数据库连接
        return true;
    }

    /**
     * 检测手机号在数据库表tb_users中是否存在
     * @param $phone_num
     * @return bool
     */
    static function dbCheckUserPhoneNumExist($phone_num)
    {
        //获取数据库连接
        $conn = null;
        if(! self::createDbConnection($conn))
        {
            return false;
        }
        $sql = "select * from tb_users where phone_num='$phone_num'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0)
        {//查找到记录
            if (DEBUG_DATABASE)
            {
                echo 'tb_users exists phone_num:'.$phone_num.PHP_EOL;
            }
        }
        else
        {
            if (DEBUG_DATABASE)
            {
                echo 'tb_users does not exist phone_num:'.$phone_num.PHP_EOL;
            }
            $conn->close();//释放数据库连接
            return false;
        }

        $conn->close();//释放数据库连接
        return true;
    }

    /**
     * 添加新用户
     * @param $phone_num
     * @param $uname
     * @param $password
     * @return bool
     */
    static function dbAddNewUser($phone_num, $uname, $password)
    {
        //获取数据库连接
        $conn = null;
        if(! self::createDbConnection($conn))
        {
            return false;
        }
        $sql = 'insert into tb_users(phone_num, user_name, password)'.
            " values('$phone_num', '$uname', '$password')";
        if(true == $conn->query($sql))
        {
            if(DEBUG_DATABASE)
            {
                echo 'succeed to insert user:'.$phone_num.','.$uname.','.$password.PHP_EOL;
            }
        }
        else
        {
            if(DEBUG_DATABASE)
            {
                echo 'fail to insert user:'.$phone_num.','.$uname.','.$password.':';
                var_dump($conn->error, $conn->errno);
                echo PHP_EOL;
            }
            $conn->close();//释放数据库连接
            return false;
        }

        $conn->close();//释放数据库连接
        return true;
    }

    /**
     * 验证用户密码
     * @param $phone_num
     * @param $password
     * @return bool
     */
    static function dbCheckUserPasswordExist($phone_num, $password)
    {
        //获取数据库连接
        $conn = null;
        if(! self::createDbConnection($conn))
        {
            return false;
        }
        $sql = "select * from tb_users where phone_num='$phone_num' and password='$password'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0)
        {//查找到记录
            if (DEBUG_DATABASE)
            {
                echo 'tb_users exists phone_num:'.$phone_num.' and password:'.$password.PHP_EOL;
            }
        }
        else
        {
            if (DEBUG_DATABASE)
            {
                echo 'tb_users does not exist phone_num:'.$phone_num.' and password:'.$password.PHP_EOL;
            }
            $conn->close();//释放数据库连接
            return false;
        }

        $conn->close();//释放数据库连接
        return true;
    }

    /**
     * 修改用户昵称
     * @param $phone_num
     * @param $uname
     * @return bool
     */
    static function dbModifyUsername($phone_num, $uname)
    {
        //获取数据库连接
        $conn = null;
        if(! self::createDbConnection($conn))
        {
            return false;
        }
        $sql = "update tb_users set user_name='$uname' where phone_num='$phone_num'";
        if(true == $conn->query($sql))
        {
            if(DEBUG_DATABASE)
            {
                echo 'Database:succeed to update uname:'.$uname.PHP_EOL;
            }
        }
        else
        {
            if(DEBUG_DATABASE)
            {
                echo 'Database:fail to update uname:'.$uname.':';
                var_dump($conn->error, $conn->errno);
                echo PHP_EOL;
            }
            $conn->close();//释放数据库连接
            return false;
        }

        $conn->close();//释放数据库连接
        return true;
    }

    /**
     * 修改设备名称
     * @param $dev_type
     * @param $dev_series
     * @param $dev_id
     * @param $dev_name
     * @return bool
     */
    static function dbModifyDevName($dev_type, $dev_series, $dev_id, $dev_name)
    {
        //获取数据库连接
        $conn = null;
        if(! self::createDbConnection($conn))
        {
            return false;
        }
        $sql = "update tb_devices set dev_name='$dev_name' ".
            "where dev_type='$dev_type' and dev_series='$dev_series' and dev_id='$dev_id'";
        if(true == $conn->query($sql))
        {
            if(DEBUG_DATABASE)
            {
                echo 'Database:succeed to update dev name:'.$dev_name.PHP_EOL;
            }
        }
        else
        {
            if(DEBUG_DATABASE)
            {
                echo 'Database:fail to update dev name:'.$dev_name.':';
                var_dump($conn->error, $conn->errno);
                echo PHP_EOL;
            }
            $conn->close();//释放数据库连接
            return false;
        }

        $conn->close();//释放数据库连接
        return true;
    }

    /**
     * 获取设备名称
     * @param $dev_type
     * @param $dev_series
     * @param $dev_id
     * @param $dev_name
     * @return bool
     */
    static function dbGetDevName($dev_type, $dev_series, $dev_id, & $dev_name)
    {
        //获取数据库连接
        $conn = null;
        if(! self::createDbConnection($conn))
        {
            return false;
        }
        $sql = "select dev_name from tb_devices ".
            "where dev_type='$dev_type' and dev_series='$dev_series' and dev_id='$dev_id'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0)
        {//查找到记录
            while($row = $result->fetch_assoc())
            {
                $dev_name = $row['dev_name'];
                break;
            }
            if (DEBUG_DATABASE)
            {
                echo 'Database:succeed to get dev_name:'.$dev_name.' of dev_id:'.$dev_id.PHP_EOL;
            }
        }
        else
        {
            if (DEBUG_DATABASE)
            {
                echo 'Database:fail to get dev_name of dev_id:'.$dev_id.PHP_EOL;
            }

            $conn->close();//释放数据库连接
            return false;
        }

        $conn->close();//释放数据库连接
        return true;
    }
    /**
     * 更新设备的固件版本号
     * @param $dev_type
     * @param $dev_series
     * @param $dev_id
     * @param $version
     * @return bool
     */
    static function dbUpdateDevVersion($dev_type, $dev_series, $dev_id, $version)
    {
        //获取数据库连接
        $conn = null;
        if(! self::createDbConnection($conn))
        {
            return false;
        }
        $sql = "update tb_devices set soft_version='$version' ".
            "where dev_type='$dev_type' and dev_series='$dev_series' and dev_id='$dev_id'";
        if(true == $conn->query($sql))
        {
            if(DEBUG_DATABASE)
            {
                echo 'Database:succeed to update dev version:'.$version.PHP_EOL;
            }
        }
        else
        {
            if(DEBUG_DATABASE)
            {
                echo 'Database:fail to update dev version:'.$version.':';
                var_dump($conn->error, $conn->errno);
                echo PHP_EOL;
            }
            $conn->close();//释放数据库连接
            return false;
        }

        $conn->close();//释放数据库连接
        return true;
    }
    /**
     * 获取用户昵称
     * @param $phone_num
     * @param $uname
     * @return bool
     */
    static function dbGetUsername($phone_num, & $uname)
    {
        //获取数据库连接
        $conn = null;
        if(! self::createDbConnection($conn))
        {
            return false;
        }
        $sql = "select user_name from tb_users where phone_num='$phone_num'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0)
        {//查找到记录
            while($row = $result->fetch_assoc())
            {
                $uname = $row['user_name'];
                break;
            }
            if (DEBUG_DATABASE)
            {
                echo 'Database:succeed to get uname:'.$uname.' of phone_num:'.$phone_num.PHP_EOL;
            }
        }
        else
        {
            if (DEBUG_DATABASE)
            {
                echo 'Database:fail to get uname of phone_num:'.$phone_num.PHP_EOL;
            }
            $conn->close();//释放数据库连接
            return false;
        }

        $conn->close();//释放数据库连接
        return true;
    }

    /**
     * 修改用户密码
     * @param $phone_num
     * @param $password
     * @return bool
     */
    static function dbModifyUserPassword($phone_num, $password)
    {
        //获取数据库连接
        $conn = null;
        if(! self::createDbConnection($conn))
        {
            return false;
        }
        $sql = "update tb_users set password='$password' where phone_num='$phone_num'";
        if(true == $conn->query($sql))
        {
            if(DEBUG_DATABASE)
            {
                echo 'Database:succeed to update password:'.$password.PHP_EOL;
            }
        }
        else
        {
            if(DEBUG_DATABASE)
            {
                echo 'Database:fail to update password:'.$password.':';
                var_dump($conn->error, $conn->errno);
                echo PHP_EOL;
            }
            $conn->close();//释放数据库连接
            return false;
        }

        $conn->close();//释放数据库连接
        return true;
    }

    /**
     * 获取设备绑定的手机号
     * @param $dev_type
     * @param $dev_series
     * @param $dev_id
     * @param $phone_num
     * @return bool
     */
    static function dbGetPhoneNumBindDev($dev_type, $dev_series, $dev_id, & $phone_num)
    {
        //获取数据库连接
        $conn = null;
        if(! self::createDbConnection($conn))
        {
            return false;
        }
        $sql = "select phone_num from tb_devices where dev_type='$dev_type' ".
            "and dev_series='$dev_series' and dev_id='$dev_id'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0)
        {//查找到记录
            while($row = $result->fetch_assoc())
            {
                $phone_num = $row['phone_num'];
                break;
            }
            if (DEBUG_DATABASE)
            {
                echo 'Database:get phone_num '.$phone_num.' bind dev '.$dev_id.PHP_EOL;
            }
        }
        else
        {
            if (DEBUG_DATABASE)
            {
                echo 'Database:no phone_num '.'bind dev '.$dev_id.PHP_EOL;
            }

            $conn->close();//释放数据库连接
            return false;
        }

        $conn->close();//释放数据库连接
        return true;
    }

    /**
     * 新增绑定设备
     * @param $dev_type
     * @param $dev_series
     * @param $dev_id
     * @param $dev_name
     * @param $phone_num
     * @return bool
     */
    static function dbAddNewDevice($dev_type, $dev_series, $dev_id, $dev_name, $phone_num)
    {
        //获取数据库连接
        $conn = null;
        if(! self::createDbConnection($conn))
        {
            return false;
        }
        $sql = 'insert into tb_devices(dev_type, dev_series, dev_id, dev_name, phone_num) '.
            "values('$dev_type', '$dev_series', '$dev_id', '$dev_name', '$phone_num')";
        if(true == $conn->query($sql))
        {
            if(DEBUG_DATABASE)
            {
                echo 'Database:succeed to insert device:'.$dev_type.','.$dev_series.','.$dev_id.PHP_EOL;
            }
        }
        else
        {
            if(DEBUG_DATABASE)
            {
                echo 'Database:fail to insert device:'.$dev_type.','.$dev_series.','.$dev_id.':';
                var_dump($conn->error, $conn->errno);
                echo PHP_EOL;
            }

            $conn->close();//释放数据库连接
            return false;
        }

        $conn->close();//释放数据库连接
        return true;
    }

    /**
     * 将设备从数据库中删除
     * @param $dev_type
     * @param $dev_series
     * @param $dev_id
     * @return bool
     */
    static function dbDelDevice($dev_type, $dev_series, $dev_id)
    {
        //获取数据库连接
        $conn = null;
        if(! self::createDbConnection($conn))
        {
            return false;
        }
        //删除数据库中已登录的设备端的fd
        $sql = 'delete from tb_devices'." where dev_type='$dev_type' and dev_series='$dev_series' and dev_id='$dev_id'";
        if(true == $conn->query($sql))
        {
            if(DEBUG_DATABASE)
            {
                echo 'Database:succeed to delete device:'.$dev_id.PHP_EOL;
            }
        }
        else
        {
            if(DEBUG_DATABASE)
            {
                echo 'Database:fail to delete device:'.$dev_id.':';
                var_dump($conn->error, $conn->errno);
                echo PHP_EOL;
            }

            $conn->close();//释放数据库连接
            return false;
        }

        $conn->close();//释放数据库连接
        return true;
    }

    /**
     * 获取设备列表
     * @param $dev_type
     * @param $dev_series
     * @param $phone_num
     * @param $dev_list
     * @return bool
     */
    static function dbGetDeviceList($dev_type, $dev_series, $phone_num, & $dev_list)
    {
        //获取数据库连接
        $conn = null;
        if(! self::createDbConnection($conn))
        {
            return false;
        }
        $sql = "select dev_id,dev_name from tb_devices where dev_type='$dev_type'".
            " and dev_series='$dev_series' and phone_num='$phone_num'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0)
        {//查找到记录
            if (DEBUG_DATABASE)
            {
                echo 'Database:succeed to get dev list:'.PHP_EOL;
            }

            while($row = $result->fetch_assoc())
            {
                $dev_list[$row['dev_id']] = $row['dev_name'];
                if(DEBUG_DATABASE)
                {
                    echo $row['dev_id'].','.$row['dev_name'].' '.PHP_EOL;
                }
            }
        }
        else
        {
            if (DEBUG_DATABASE)
            {
                echo 'Database:fail to get dev list'.PHP_EOL;
            }

            $conn->close();//释放数据库连接
            return false;
        }

        $conn->close();//释放数据库连接
        return true;
    }

    /**
     * 添加已登录的手机端用户到数据库中
     * @param $phone_num
     * @param $fd
     * @return bool
     */
    static function dbAddAppClient($phone_num, $fd)
    {
        //获取数据库连接
        $conn = null;
        if(! self::createDbConnection($conn))
        {
            return false;
        }
        //添加已登录的手机端用户到数据库中
        $sql = 'insert into tb_appClients(phone_num, fd)'." values('$phone_num', $fd)";
        if(true == $conn->query($sql))
        {
            if(DEBUG_DATABASE)
            {
                echo 'Database:succeed to insert app client: '.$phone_num.', '.$fd.PHP_EOL;
            }
        }
        else
        {
            if(DEBUG_DATABASE)
            {
                echo 'Database:fail to insert app client: '.$phone_num.', '.$fd.':';
                var_dump($conn->error, $conn->errno);
                echo PHP_EOL;
            }

            $conn->close();//释放数据库连接
            return false;
        }

        $conn->close();//释放数据库连接
        return true;
    }

    /**
     * 添加已登录的设备端用户到数据库中
     * @param $dev_type
     * @param $dev_series
     * @param $dev_id
     * @param $ADR
     * @param $fd
     * @return bool
     */
    static function dbAddDevClient($dev_type, $dev_series, $dev_id, $ADR, $fd)
    {
        //获取数据库连接
        $conn = null;
        if(! self::createDbConnection($conn))
        {
            return false;
        }
        //添加已登录的设备端用户到数据库中
        $sql = 'insert into tb_devClients(dev_type, dev_series, dev_id, adr, fd) '.
            "values('$dev_type', '$dev_series', '$dev_id', $ADR, $fd)";
        if(true == $conn->query($sql))
        {
            if(DEBUG_DATABASE)
            {
                echo 'Database:succeed to insert dev client: '.$dev_id.', '.$fd.PHP_EOL;
            }
        }
        else
        {
            if(DEBUG_DATABASE)
            {
                echo 'Database:fail to insert dev client: '.$dev_id.', '.$fd.':';
                var_dump($conn->error, $conn->errno);
                echo PHP_EOL;
            }

            $conn->close();//释放数据库连接
            return false;
        }

        $conn->close();//释放数据库连接
        return true;
    }

    /**
     * 获取数据库中已登录的手机端的fd
     * @param $phone_num
     * @param $fd
     * @return bool
     */
    static function dbGetAppClientFd($phone_num, & $fd)
    {
        //获取数据库连接
        $conn = null;
        if(! self::createDbConnection($conn))
        {
            return false;
        }
        //获取数据库中已登录的手机端的fd
        $sql = "select fd from tb_appClients where phone_num='$phone_num'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0)
        {//查找到记录
            while($row = $result->fetch_assoc())
            {
                $fd = $row['fd'];
                break;
            }
            if (DEBUG_DATABASE)
            {
                echo 'Database:succeed to get fd:'.$fd.' of app client:'.$phone_num.PHP_EOL;
            }
        }
        else
        {
            if (DEBUG_DATABASE)
            {
                echo 'Database:fail to get fd of app client:'.$phone_num.PHP_EOL;
            }

            $conn->close();//释放数据库连接
            return false;
        }

        $conn->close();//释放数据库连接
        return true;
    }

    /**
     * 获取数据库中已登录的设备端的fd
     * @param $dev_type
     * @param $dev_series
     * @param $dev_id
     * @param $fd
     * @return bool
     */
    static function dbGetDevClientFd($dev_type, $dev_series, $dev_id, & $fd)
    {
        //获取数据库连接
        $conn = null;
        if(! self::createDbConnection($conn))
        {
            return false;
        }
        //获取数据库中已登录的设备端的fd
        $sql = "select fd from tb_devClients where dev_type='$dev_type'".
        " and dev_series='$dev_series' and dev_id='$dev_id'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0)
        {//查找到记录
            while($row = $result->fetch_assoc())
            {
                $fd = $row['fd'];
                break;
            }
            if (DEBUG_DATABASE)
            {
                echo 'Database:succeed to get fd:'.$fd.' of dev client:'.$dev_id.PHP_EOL;
            }
        }
        else
        {
            if (DEBUG_DATABASE)
            {
                echo 'Database:fail to get fd of dev client:'.$dev_id.PHP_EOL;
            }

            $conn->close();//释放数据库连接
            return false;
        }

        $conn->close();//释放数据库连接
        return true;
    }

    /**
     * 获取设备通信地址
     * @param $dev_type
     * @param $dev_series
     * @param $dev_id
     * @param $adr
     * @return bool
     */
    static function dbGetDevClientAdr($dev_type, $dev_series, $dev_id, & $adr)
    {
        //获取数据库连接
        $conn = null;
        if(! self::createDbConnection($conn))
        {
            return false;
        }
        //获取数据库中已登录的设备端的fd
        $sql = "select adr from tb_devClients where dev_type='$dev_type'".
            " and dev_series='$dev_series' and dev_id='$dev_id'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0)
        {//查找到记录
            while($row = $result->fetch_assoc())
            {
                $adr = $row['adr'];
                break;
            }
            if (DEBUG_DATABASE)
            {
                echo 'Database:succeed to get adr:'.$adr.' of dev client:'.$dev_id.PHP_EOL;
            }
        }
        else
        {
            if (DEBUG_DATABASE)
            {
                echo 'Database:fail to get adr of dev client:'.$dev_id.PHP_EOL;
            }

            $conn->close();//释放数据库连接
            return false;
        }

        $conn->close();//释放数据库连接
        return true;
    }

    /**
     * 删除数据库中已登录的手机端的fd
     * @param $fd
     * @return bool
     */
    static function dbDelAppClient($fd)
    {
        //获取数据库连接
        $conn = null;
        if(! self::createDbConnection($conn))
        {
            return false;
        }
        //删除数据库中已登录的手机端的fd
        $sql = 'delete from tb_appClients'." where fd=$fd";
        if(true == $conn->query($sql))
        {
            if(DEBUG_DATABASE)
            {
                echo 'Database:succeed to delete app client:'.$fd.PHP_EOL;
            }
        }
        else
        {
            if(DEBUG_DATABASE)
            {
                echo 'Database:fail to delete app client:'.$fd.':';
                var_dump($conn->error, $conn->errno);
                echo PHP_EOL;
            }

            $conn->close();//释放数据库连接
            return false;
        }

        $conn->close();//释放数据库连接
        return true;
    }

    /**
     * 删除数据库中已登录的设备端的fd
     * @param $fd
     * @return bool
     */
    static function dbDelDevClient($fd)
    {
        //获取数据库连接
        $conn = null;
        if(! self::createDbConnection($conn))
        {
            return false;
        }
        //删除数据库中已登录的设备端的fd
        $sql = 'delete from tb_devClients'." where fd=$fd";
        if(true == $conn->query($sql))
        {
            if(DEBUG_DATABASE)
            {
                echo 'Database:succeed to delete dev client:'.$fd.PHP_EOL;
            }
        }
        else
        {
            if(DEBUG_DATABASE)
            {
                echo 'Database:fail to delete dev client:'.$fd.':';
                var_dump($conn->error, $conn->errno);
                echo PHP_EOL;
            }

            $conn->close();//释放数据库连接
            return false;
        }

        $conn->close();//释放数据库连接
        return true;
    }

    /**
     * 修改数据库中手机端的fd
     * @param $phone_num
     * @param $fd
     * @return bool
     */
    static function dbUpdateAppClientFd($phone_num, $fd)
    {
        //获取数据库连接
        $conn = null;
        if(! self::createDbConnection($conn))
        {
            return false;
        }
        //修改数据库中手机端的fd
        $sql = "update tb_appClients set fd=$fd where phone_num='$phone_num'";
        if(true == $conn->query($sql))
        {
            if(DEBUG_DATABASE)
            {
                echo 'Database:succeed to update app client:'.$phone_num.PHP_EOL;
            }
        }
        else
        {
            if(DEBUG_DATABASE)
            {
                echo 'Database:fail to update app client:'.$phone_num.':';
                var_dump($conn->error, $conn->errno);
                echo PHP_EOL;
            }

            $conn->close();//释放数据库连接
            return false;
        }

        $conn->close();//释放数据库连接
        return true;
    }

    /**
     * 修改数据库中设备端的ADR和fd
     * @param $dev_type
     * @param $dev_series
     * @param $dev_id
     * @param $ADR
     * @param $fd
     * @return bool
     */
    static function dbUpdateDevClientAdrFd($dev_type, $dev_series, $dev_id, $ADR, $fd)
    {
        //获取数据库连接
        $conn = null;
        if(! self::createDbConnection($conn))
        {
            return false;
        }
        //修改数据库中设备端的fd
        $sql = "update tb_devClients set fd=$fd,adr=$ADR where dev_type='$dev_type'".
            " and dev_series='$dev_series' and dev_id='$dev_id'";
        if(true == $conn->query($sql))
        {
            if(DEBUG_DATABASE)
            {
                echo 'Database:succeed to update dev client:'.$dev_id.PHP_EOL;
            }
        }
        else
        {
            if(DEBUG_DATABASE)
            {
                echo 'Database:fail to update dev client:'.$dev_id.':';
                var_dump($conn->error, $conn->errno);
                echo PHP_EOL;
            }

            $conn->close();//释放数据库连接
            return false;
        }

        $conn->close();//释放数据库连接
        return true;
    }

    /**
     * 获取数据库中已登录的手机端的phone_num
     * @param $phone_num
     * @param $fd
     * @return bool
     */
    static function dbGetAppClientPhoneNum(& $phone_num, $fd)
    {
        //获取数据库连接
        $conn = null;
        if(! self::createDbConnection($conn))
        {
            return false;
        }
        //获取数据库中已登录的手机端的phone_num
        $sql = "select phone_num from tb_appClients where fd = $fd";
        $result = $conn->query($sql);
        if ($result->num_rows > 0)
        {//查找到记录
            while($row = $result->fetch_assoc())
            {
                $phone_num = $row['phone_num'];
                break;
            }
            if (DEBUG_DATABASE)
            {
                echo 'Database:succeed to get phone_num:'.$phone_num.' of app client:'.$fd.PHP_EOL;
            }
        }
        else
        {
            if (DEBUG_DATABASE)
            {
                echo 'Database:fail to get phone_num of app client:'.$fd.PHP_EOL;
            }

            $conn->close();//释放数据库连接
            return false;
        }

        $conn->close();//释放数据库连接
        return true;
    }

    /**
     * 获取数据库中已登录的设备端的dev_type、dev_series、dev_id
     * @param $dev_type
     * @param $dev_series
     * @param $dev_id
     * @param $fd
     * @return bool
     */
    static function dbGetDevClient(& $dev_type, & $dev_series, & $dev_id, $ADR, $fd)
    {
        //获取数据库连接
        $conn = null;
        if(! self::createDbConnection($conn))
        {
            return false;
        }
        //获取数据库中已登录的设备端的dev_type、dev_series、dev_id
        $sql = "select dev_type, dev_series, dev_id from tb_devClients where adr=$ADR and fd = $fd";
        $result = $conn->query($sql);
        if ($result->num_rows > 0)
        {//查找到记录
            while($row = $result->fetch_assoc())
            {
                $dev_type = $row['dev_type'];
                $dev_series = $row['dev_series'];
                $dev_id = $row['dev_id'];
                break;
            }
            if (DEBUG_DATABASE)
            {
                echo 'Database:succeed to get dev_id:'.$dev_id.' of dev client:'.$fd.PHP_EOL;
            }
        }
        else
        {
            if (DEBUG_DATABASE)
            {
                echo 'Database:fail to get dev_id of dev client:'.$fd.PHP_EOL;
            }

            $conn->close();//释放数据库连接
            return false;
        }

        $conn->close();//释放数据库连接
        return true;
    }

    /**
     * 获取数据库中已登录的设备端的$devClients
     * @param $devClients
     * @param $fd
     * @return bool
     */
    static function dbGetDevClientWithoutADR(& $devClients, $fd)
    {
        //获取数据库连接
        $conn = null;
        if(! self::createDbConnection($conn))
        {
            return false;
        }
        //获取数据库中已登录的设备端的dev_type、dev_series、dev_id
        $sql = "select dev_type, dev_series, dev_id from tb_devClients where fd = $fd";
        $result = $conn->query($sql);
        if ($result->num_rows > 0)
        {//查找到记录
            $index = 0;
            while($row = $result->fetch_assoc())
            {
                $devClients[$index++] = array($row['dev_type'],$row['dev_series'],$row['dev_id']);
            }
            if (DEBUG_DATABASE)
            {
                echo 'Database:succeed to get dev clients of fd = '.$fd.PHP_EOL;
            }
        }
        else
        {
            if (DEBUG_DATABASE)
            {
                echo 'Database:fail to get dev clients of fd = '.$fd.PHP_EOL;
            }

            $conn->close();//释放数据库连接
            return false;
        }

        $conn->close();//释放数据库连接
        return true;
    }

    /**获取tb_devClients中的记录总数
     * @param $count
     * @return bool
     */
    static function dbGetDevClientCount(& $count)
    {
        //获取数据库连接
        $conn = null;
        if(! self::createDbConnection($conn))
        {
            return false;
        }
        //获取数据库中记录总数
        $sql = "select count(*) from tb_devClients";
        $result = $conn->query($sql);
        if ($result->num_rows > 0)
        {//查找到记录
            while($row = $result->fetch_assoc())
            {
                $count = $row['count(*)'];
                break;
            }
            if (DEBUG_DATABASE)
            {
                echo 'Database:succeed to get devClient count:'.$count.PHP_EOL;
            }
        }
        else
        {
            if (DEBUG_DATABASE)
            {
                echo 'Database:fail to get devClient count:'.$count.PHP_EOL;
            }

            $conn->close();//释放数据库连接
            return false;
        }

        $conn->close();//释放数据库连接
        return true;
    }

    /**
     * 逐条获取tb_devClients中的记录
     * @param $index
     * @param $dev_type
     * @param $dev_series
     * @param $dev_id
     * @param $ADR
     * @param $fd
     * @return bool
     */
    static function dbGetDevClientOneByOne($index, & $dev_type, & $dev_series, & $dev_id, & $ADR, & $fd)
    {
        //获取数据库连接
        $conn = null;
        if(! self::createDbConnection($conn))
        {
            return false;
        }
        //获取数据库中已登录的设备端
        $sql = "select * from tb_devClients limit $index, 1";
        $result = $conn->query($sql);
        if ($result->num_rows > 0)
        {//查找到记录
            while($row = $result->fetch_assoc())
            {
                $dev_type = $row['dev_type'];
                $dev_series = $row['dev_series'];
                $dev_id = $row['dev_id'];
                $ADR = $row['adr'];
                $fd = $row['fd'];
                break;
            }
            if (DEBUG_DATABASE)
            {
                echo 'Database:succeed to get devClient index = '.$index.PHP_EOL;
            }
        }
        else
        {
            if (DEBUG_DATABASE)
            {
                echo 'Database:fail to get devClient index = '.$index.PHP_EOL;
            }

            $conn->close();//释放数据库连接
            return false;
        }

        $conn->close();//释放数据库连接
        return true;
    }

    /**
     * 插入分享设备
     * @param $dev_type
     * @param $dev_series
     * @param $dev_id
     * @param $phone_share
     * @return bool
     */
    static function dbInsertShareDev($dev_type, $dev_series, $dev_id, $phone_share)
    {
        //获取数据库连接
        $conn = null;
        if(! self::createDbConnection($conn))
        {
            return false;
        }
        $sql = 'insert into tb_shareDevs(dev_type, dev_series, dev_id, phone_num) '.
            "values('$dev_type', '$dev_series', '$dev_id', '$phone_share')";
        if(true == $conn->query($sql))
        {
            if(DEBUG_DATABASE)
            {
                echo 'Database:succeed to insert share device:'.$dev_type.','.$dev_series.','.$dev_id.PHP_EOL;
            }
        }
        else
        {
            if(DEBUG_DATABASE)
            {
                echo 'Database:fail to insert share device:'.$dev_type.','.$dev_series.','.$dev_id.':';
                var_dump($conn->error, $conn->errno);
                echo PHP_EOL;
            }

            $conn->close();//释放数据库连接
            return false;
        }

        $conn->close();//释放数据库连接
        return true;
    }

    /**
     * 检测该设备是否已经被该手机号分享
     * @param $dev_type
     * @param $dev_series
     * @param $dev_id
     * @param $phone_share
     * @return bool
     */
    static function dbCheckShareDevPhone($dev_type, $dev_series, $dev_id, $phone_share)
    {
        //获取数据库连接
        $conn = null;
        if(! self::createDbConnection($conn))
        {
            return false;
        }
        //获取数据库中已登录的设备端的fd
        $sql = "select * from tb_shareDevs where dev_type='$dev_type'".
            " and dev_series='$dev_series' and dev_id='$dev_id' and phone_num='$phone_share'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0)
        {//查找到记录
            if (DEBUG_DATABASE)
            {
                echo 'Database:share dev and phone already exist'.PHP_EOL;
            }
        }
        else
        {
            if (DEBUG_DATABASE)
            {
                echo 'Database:share dev and phone not exist'.PHP_EOL;
            }

            $conn->close();//释放数据库连接
            return false;
        }

        $conn->close();//释放数据库连接
        return true;
    }

    /**
     * 检测设备是否已经被分享
     * @param $dev_type
     * @param $dev_series
     * @param $dev_id
     * @return bool
     */
    static function dbCheckShareDev($dev_type, $dev_series, $dev_id)
    {
        //获取数据库连接
        $conn = null;
        if(! self::createDbConnection($conn))
        {
            return false;
        }
        //获取数据库中已登录的设备端的fd
        $sql = "select * from tb_shareDevs where dev_type='$dev_type'".
            " and dev_series='$dev_series' and dev_id='$dev_id'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0)
        {//查找到记录
            if (DEBUG_DATABASE)
            {
                echo 'Database:share dev already exist'.PHP_EOL;
            }
        }
        else
        {
            if (DEBUG_DATABASE)
            {
                echo 'Database:share dev not exist'.PHP_EOL;
            }

            $conn->close();//释放数据库连接
            return false;
        }

        $conn->close();//释放数据库连接
        return true;
    }

    /**
     * 取消设备分享
     * @param $dev_type
     * @param $dev_series
     * @param $dev_id
     * @param $phone_share
     * @return bool
     */
    static function dbDeleteShareDevPhone($dev_type, $dev_series, $dev_id, $phone_share)
    {
        //获取数据库连接
        $conn = null;
        if(! self::createDbConnection($conn))
        {
            return false;
        }
        //获取数据库中已登录的设备端的fd
        $sql = "delete from tb_shareDevs where dev_type='$dev_type'".
            " and dev_series='$dev_series' and dev_id='$dev_id' and phone_num='$phone_share'";
        if (true == $conn->query($sql))
        {//删除成功
            if (DEBUG_DATABASE)
            {
                echo 'Database:delete share dev and phone success'.PHP_EOL;
            }
        }
        else
        {
            if (DEBUG_DATABASE)
            {
                echo 'Database:delete share dev and phone fail'.PHP_EOL;
            }

            $conn->close();//释放数据库连接
            return false;
        }

        $conn->close();//释放数据库连接
        return true;
    }

    /**
     * 删除所有分享
     * @param $dev_type
     * @param $dev_series
     * @param $dev_id
     * @return bool
     */
    static function dbDeleteShareDev($dev_type, $dev_series, $dev_id)
    {
        //获取数据库连接
        $conn = null;
        if(! self::createDbConnection($conn))
        {
            return false;
        }
        $sql = "delete from tb_shareDevs where dev_type='$dev_type' and dev_series='$dev_series' and dev_id='$dev_id'";
        if (true == $conn->query($sql))
        {//删除成功
            if (DEBUG_DATABASE)
            {
                echo 'Database:delete share dev success'.PHP_EOL;
            }
        }
        else
        {
            if (DEBUG_DATABASE)
            {
                echo 'Database:delete share dev fail'.PHP_EOL;
            }

            $conn->close();//释放数据库连接
            return false;
        }

        $conn->close();//释放数据库连接
        return true;
    }

    /**
     * 获取设备共享者列表
     * @param $dev_type
     * @param $dev_series
     * @param $dev_id
     * @param $share_user_list
     * @return bool
     */
    static function dbGetShareDevUsers($dev_type, $dev_series, $dev_id, & $share_user_list)
    {
        //获取数据库连接
        $conn = null;
        if(! self::createDbConnection($conn))
        {
            return false;
        }
        $sql = "select phone_num from tb_shareDevs where dev_type='$dev_type' ".
            "and dev_series='$dev_series' and dev_id='$dev_id'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0)
        {//查找到记录
            while($row = $result->fetch_assoc())
            {
                if (self::dbGetUsername($row['phone_num'], $uname))
                {
                    $share_user_list[$row['phone_num']] = $uname;
                }
            }
            if (DEBUG_DATABASE)
            {
                echo 'Database:get share user list success'.PHP_EOL;
            }
        }
        else
        {
            if (DEBUG_DATABASE)
            {
                echo 'Database:get share user list fail'.PHP_EOL;
            }

            $conn->close();//释放数据库连接
            return false;
        }

        $conn->close();//释放数据库连接
        return true;
    }

    /**
     * 获取分享的设备列表
     * @param $dev_type
     * @param $dev_series
     * @param $phone_num
     * @param $share_dev_list
     * @return bool
     */
    static function dbGetShareDevs($dev_type, $dev_series, $phone_num, & $share_dev_list)
    {
        //获取数据库连接
        $conn = null;
        if(! self::createDbConnection($conn))
        {
            return false;
        }
        $sql = "select dev_id from tb_shareDevs where dev_type='$dev_type'".
            " and dev_series='$dev_series' and phone_num='$phone_num'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0)
        {//查找到记录
            while($row = $result->fetch_assoc())
            {
                if (self::dbGetDevName($dev_type, $dev_series, $row['dev_id'], $dev_name))
                {
                    $share_dev_list[$row['dev_id']] = $dev_name;
                }
            }
            if (DEBUG_DATABASE)
            {
                echo 'Database:get share devs list success'.PHP_EOL;
            }
        }
        else
        {
            if (DEBUG_DATABASE)
            {
                echo 'Database:get share devs list fail'.PHP_EOL;
            }

            $conn->close();//释放数据库连接
            return false;
        }

        $conn->close();//释放数据库连接
        return true;
    }

    /**
     * 插入哪个用户请求哪个设备
     * @param $dev_type
     * @param $dev_series
     * @param $dev_id
     * @param $phone_res
     * @return bool
     */
    static function dbInsertAppResDev($dev_type, $dev_series, $dev_id, $CID2, $phone_res)
    {
        //获取数据库连接
        $conn = null;
        if(! self::createDbConnection($conn))
        {
            return false;
        }
        $sql = 'insert into tb_appResDev(dev_type, dev_series, dev_id, CID2, phone_num) '.
            "values('$dev_type', '$dev_series', '$dev_id', $CID2, '$phone_res')";
        if(true == $conn->query($sql))
        {
            if(DEBUG_DATABASE)
            {
                echo 'Database:succeed to insert app res dev'.PHP_EOL;
            }
        }
        else
        {
            if(DEBUG_DATABASE)
            {
                echo 'Database:fail to insert app res dev:';
                var_dump($conn->error, $conn->errno);
                echo PHP_EOL;
            }

            $conn->close();//释放数据库连接
            return false;
        }

        $conn->close();//释放数据库连接
        return true;
    }

    /**获取app请求dev的phone_num和时间戳
     * @param $dev_type
     * @param $dev_series
     * @param $dev_id
     * @param $phone_res
     * @param $time
     * @return bool
     */
    static function dbGetAppResDevPhoneTime($dev_type, $dev_series, $dev_id, & $CID2, & $phone_res, & $time)
    {
        //获取数据库连接
        $conn = null;
        if(! self::createDbConnection($conn))
        {
            return false;
        }

        $sql = "select CID2,phone_num,res_time from tb_appResDev where dev_type='$dev_type'" .
            " and dev_series='$dev_series' and dev_id='$dev_id'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0)
        {//查找到记录
            while($row = $result->fetch_assoc())
            {
                $CID2 = $row['CID2'];
                $phone_res = $row['phone_num'];
                $time = $row['res_time'];
                break;
            }
            if (DEBUG_DATABASE)
            {
                echo 'Database:succeed to get res_time:'.$time.PHP_EOL;
            }
        }
        else
        {
            if (DEBUG_DATABASE)
            {
                echo 'Database:fail to get res_time'.PHP_EOL;
            }

            $conn->close();//释放数据库连接
            return false;
        }

        $conn->close();//释放数据库连接
        return true;
    }

    /**
     * 删除app请求dev
     * @param $dev_type
     * @param $dev_series
     * @param $dev_id
     * @return bool
     */
    static function dbDeleteAppResDev($dev_type, $dev_series, $dev_id)
    {
        //获取数据库连接
        $conn = null;
        if(! self::createDbConnection($conn))
        {
            return false;
        }
        //获取数据库中已登录的设备端的fd
        $sql = "delete from tb_appResDev where dev_type='$dev_type'".
            " and dev_series='$dev_series' and dev_id='$dev_id'";
        if (true == $conn->query($sql))
        {//删除成功
            if (DEBUG_DATABASE)
            {
                echo 'Database:delete app res dev success'.PHP_EOL;
            }
        }
        else
        {
            if (DEBUG_DATABASE)
            {
                echo 'Database:delete share app res dev fail'.PHP_EOL;
            }

            $conn->close();//释放数据库连接
            return false;
        }

        $conn->close();//释放数据库连接
        return true;
    }

    /**
     * 插入app和验证码
     * @param $phone_res
     * @param $code
     * @return bool
     */
    static function dbInsertAppCode($phone_res, $code)
    {
        //获取数据库连接
        $conn = null;
        if(! self::createDbConnection($conn))
        {
            return false;
        }
        $sql = "insert into tb_appCode(phone_num, code) values('$phone_res', '$code')";
        if(true == $conn->query($sql))
        {
            if(DEBUG_DATABASE)
            {
                echo 'Database:succeed to insert app code'.PHP_EOL;
            }
        }
        else
        {
            if(DEBUG_DATABASE)
            {
                echo 'Database:fail to insert app code:';
                var_dump($conn->error, $conn->errno);
                echo PHP_EOL;
            }

            $conn->close();//释放数据库连接
            return false;
        }

        $conn->close();//释放数据库连接
        return true;
    }

    /**
     * 获取检验码发送时间
     * @param $phone_res
     * @param $code
     * @param $time
     * @return bool
     */
    static function dbGetAppCodeTime($phone_res, & $code, & $time)
    {
        //获取数据库连接
        $conn = null;
        if(! self::createDbConnection($conn))
        {
            return false;
        }

        $sql = "select code,res_time from tb_appCode where phone_num='$phone_res'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0)
        {//查找到记录
            while($row = $result->fetch_assoc())
            {
                $code = $row['code'];
                $time = $row['res_time'];
                break;
            }
            if (DEBUG_DATABASE)
            {
                echo 'Database:succeed to get code res_time:'.$time.PHP_EOL;
            }
        }
        else
        {
            if (DEBUG_DATABASE)
            {
                echo 'Database:fail to get code res_time'.PHP_EOL;
            }

            $conn->close();//释放数据库连接
            return false;
        }

        $conn->close();//释放数据库连接
        return true;
    }

    /**
     * 删除验证码信息
     * @param $phone_res
     * @return bool
     */
    static function dbDeleteAppCode($phone_res)
    {
        //获取数据库连接
        $conn = null;
        if(! self::createDbConnection($conn))
        {
            return false;
        }
        //获取数据库中已登录的设备端的fd
        $sql = "delete from tb_appCode where phone_num='$phone_res'";
        if (true == $conn->query($sql))
        {//删除成功
            if (DEBUG_DATABASE)
            {
                echo 'Database:delete app code success'.PHP_EOL;
            }
        }
        else
        {
            if (DEBUG_DATABASE)
            {
                echo 'Database:delete app code fail'.PHP_EOL;
            }

            $conn->close();//释放数据库连接
            return false;
        }

        $conn->close();//释放数据库连接
        return true;
    }

    /**
     * 删除消息推送（主要用在取消设备分享时的操作）
     * @param $dev_type
     * @param $dev_series
     * @param $dev_id
     * @param $phone_num
     * @return bool
     */
    static function dbDeletePushMsgsByPhone($dev_type, $dev_series, $dev_id, $phone_num)
    {
        //获取数据库连接
        $conn = null;
        if(! self::createDbConnection($conn))
        {
            return false;
        }
        //删除数据库中已登录的设备端的fd
        $sql = 'delete from tb_pushMsgs'." where dev_type='$dev_type' and dev_series='$dev_series'" .
            " and dev_id='$dev_id' and phone_num='$phone_num'";
        if(true == $conn->query($sql))
        {
            if(DEBUG_DATABASE)
            {
                echo 'Database:dbDeletePushMsgsByPhone succeed to delete push msgs:'.$phone_num.PHP_EOL;
            }
        }
        else
        {
            if(DEBUG_DATABASE)
            {
                echo 'Database:dbDeletePushMsgsByPhone fail to delete push msgs:'.$phone_num.':';
                var_dump($conn->error, $conn->errno);
                echo PHP_EOL;
            }

            $conn->close();//释放数据库连接
            return false;
        }

        $conn->close();//释放数据库连接
        return true;
    }

    /**
     * 删除消息推送（主要用在解绑设备时的操作）
     * @param $dev_type
     * @param $dev_series
     * @param $dev_id
     * @return bool
     */
    static function dbDeletePushMsgs($dev_type, $dev_series, $dev_id)
    {
        //获取数据库连接
        $conn = null;
        if(! self::createDbConnection($conn))
        {
            return false;
        }
        //删除数据库中已登录的设备端的fd
        $sql = 'delete from tb_pushMsgs'." where dev_type='$dev_type' and dev_series='$dev_series' and dev_id='$dev_id'";
        if(true == $conn->query($sql))
        {
            if(DEBUG_DATABASE)
            {
                echo 'Database:dbDeletePushMsgs succeed to delete push msgs'.PHP_EOL;
            }
        }
        else
        {
            if(DEBUG_DATABASE)
            {
                echo 'Database:dbDeletePushMsgs fail to delete push msgs:';
                var_dump($conn->error, $conn->errno);
                echo PHP_EOL;
            }

            $conn->close();//释放数据库连接
            return false;
        }

        $conn->close();//释放数据库连接
        return true;
    }

    /**
     * 添加推送消息到数据库
     * @param $dev_type
     * @param $dev_series
     * @param $dev_id
     * @param $dev_name
     * @param $msg_type
     * @param $phone_num
     * @param $content
     * @return bool
     */
    static function dbInsertPushMsg($dev_type, $dev_series, $dev_id, $dev_name, $msg_type, $phone_num, $content)
    {
        //获取数据库连接
        $conn = null;
        if(! self::createDbConnection($conn))
        {
            return false;
        }
        $sql = 'insert into tb_pushMsgs(dev_type, dev_series, dev_id, dev_name, msg_type, phone_num, content)'.
            " values('$dev_type','$dev_series','$dev_id','$dev_name','$msg_type','$phone_num','$content')";
        if(true == $conn->query($sql))
        {
            if(DEBUG_DATABASE)
            {
                echo 'Database:succeed to insert push msg of '.$phone_num.PHP_EOL;
            }
        }
        else
        {
            if(DEBUG_DATABASE)
            {
                echo 'Database:fail to insert push msg :';
                var_dump($conn->error, $conn->errno);
                echo PHP_EOL;
            }

            $conn->close();//释放数据库连接
            return false;
        }

        $conn->close();//释放数据库连接
        return true;
    }

    /**
     * 获取推送消息
     * @param $num
     * @param $msg_type
     * @param $phone_num
     * @param $extra
     * @return bool
     */
    static function dbGetPushMsg($num, $msg_type, $phone_num, & $extra)
    {
        //获取数据库连接
        $conn = null;
        if(! self::createDbConnection($conn))
        {
            return false;
        }
        $sql = 'select dev_type,dev_series,dev_id,dev_name,content,push_time from tb_pushMsgs' .
            " where msg_type='$msg_type' and phone_num='$phone_num' order by id desc";
        $result = $conn->query($sql);
        if ($result->num_rows > 0)
        {//查找到记录
            if (DEBUG_DATABASE)
            {
                echo 'Database:succeed to get dev list:'.PHP_EOL;
            }

            $count = 0;
            while($row = $result->fetch_assoc())
            {
                $count++;
                if ($num == $count)
                {
                    $extra['devType'] = $row['dev_type'];
                    $extra['devSeries'] = $row['dev_series'];
                    $extra['devID'] = $row['dev_id'];
                    $extra['devName'] = $row['dev_name'];
                    $extra['content'] = $row['content'];
                    $extra['pushTime'] = $row['push_time'];
                    break;
                }
            }

            if ($num != $count)
            {
                if (DEBUG_DATABASE)
                {
                    echo 'Database:pushMsg num is too big'.PHP_EOL;
                }

                $conn->close();//释放数据库连接
                return false;
            }
        }
        else
        {
            if (DEBUG_DATABASE)
            {
                echo 'Database:fail to get dev list'.PHP_EOL;
            }

            $conn->close();//释放数据库连接
            return false;
        }

        $conn->close();//释放数据库连接
        return true;
    }
}

?>

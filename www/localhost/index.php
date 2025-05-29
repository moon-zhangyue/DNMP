<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo '<h1 style="text-align: center;">欢迎使用DNMP！</h1>';
echo '<h2>版本信息</h2>';

echo '<ul>';
echo '<li>PHP版本：', PHP_VERSION, '</li>';
echo '<li>Nginx版本：', getNginxVersion(), '</li>';
echo '<li>MySQL服务器版本：', getMysqlVersion(), '</li>';
echo '<li>Redis服务器版本：', getRedisVersion(), '</li>';
echo '<li>MongoDB服务器版本：', getMongoVersion(), '</li>';
echo '</ul>';

echo '<h2>已安装扩展</h2>';
printExtensions();


/**
 * 获取Nginx版本
 */
function getNginxVersion()
{
    // 方法1：尝试从SERVER_SOFTWARE获取
    if (isset($_SERVER['SERVER_SOFTWARE'])) {
        $serverSoftware = $_SERVER['SERVER_SOFTWARE'];
        if (preg_match('/nginx\/([0-9.]+)/', $serverSoftware, $matches)) {
            return $matches[1];
        }
    }

    // 方法2：尝试从HTTP_SERVER头获取
    if (isset($_SERVER['HTTP_SERVER'])) {
        if (preg_match('/nginx\/([0-9.]+)/', $_SERVER['HTTP_SERVER'], $matches)) {
            return $matches[1];
        }
    }

    // 方法3：尝试通过curl获取头信息
    if (function_exists('curl_init')) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'http://localhost');
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 3);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
        $headers = curl_exec($ch);
        curl_close($ch);

        if ($headers && preg_match('/Server:\s*nginx\/([0-9.]+)/i', $headers, $matches)) {
            return $matches[1];
        }
    }

    // 方法4：检查所有$_SERVER变量中包含nginx的
    foreach ($_SERVER as $key => $value) {
        if (is_string($value) && preg_match('/nginx\/([0-9.]+)/', $value, $matches)) {
            return $matches[1];
        }
    }

    // 方法5：尝试从响应头获取（如果函数可用）
    if (function_exists('apache_response_headers')) {
        $headers = apache_response_headers();
        if (isset($headers['Server']) && preg_match('/nginx\/([0-9.]+)/', $headers['Server'], $matches)) {
            return $matches[1];
        }
    }

    // 如果所有方法都失败，返回SERVER_SOFTWARE或默认信息
    return $_SERVER['SERVER_SOFTWARE'] ?? '无法获取版本信息';
}

/**
 * 获取MySQL版本
 */
function getMysqlVersion()
{
    if (extension_loaded('PDO_MYSQL')) {
        try {
            //注意host地址
            $dbh = new PDO('mysql:host=mysql5;port=3306;dbname=mysql', 'root', '123456');
            $sth = $dbh->query('SELECT VERSION() as version');
            $info = $sth->fetch();
        } catch (PDOException $e) {
            return $e->getMessage();
        }
        return $info['version'];
    } else {
        return 'PDO_MYSQL 扩展未安装 ×';
    }
}

/**
 * 获取Redis版本
 */
function getRedisVersion()
{
    if (extension_loaded('redis')) {
        try {
            $redis = new Redis();
            $redis->connect('redis', 6379);
            $info = $redis->info();
            return $info['redis_version'];
        } catch (Exception $e) {
            return $e->getMessage();
        }
    } else {
        return 'Redis 扩展未安装 ×';
    }
}

/**
 * 获取MongoDB版本
 */
function getMongoVersion()
{
    if (extension_loaded('mongodb')) {
        try {
            $manager = new MongoDB\Driver\Manager('mongodb://root:123456@mongodb:27017/thinkphp8?authSource=admin');
            $command = new MongoDB\Driver\Command(array('serverStatus' => true));

            $cursor = $manager->executeCommand('thinkphp8', $command);

            return $cursor->toArray()[0]->version;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    } else {
        return 'MongoDB 扩展未安装 ×';
    }
}

/**
 * 获取已安装扩展列表
 */
function printExtensions()
{
    echo '<ol>';
    foreach (get_loaded_extensions() as $i => $name) {
        echo "<li>", $name, '=', phpversion($name), '</li>';
    }
    echo '</ol>';
}

echo phpinfo();

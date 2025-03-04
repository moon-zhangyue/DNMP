<?php

echo "开始测试 TDengine 连接...\n";

$host = "tdengine";
$port = 6041;
$user = "root";
$password = "taosdata";

// 使用 RESTful 接口连接 TDengine
function query($sql) {
    global $host, $port, $user, $password;
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "http://$host:$port/rest/sql");
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $sql);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authorization: Basic " . base64_encode("$user:$password")
    ]);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($httpCode !== 200) {
        throw new Exception("HTTP Error: $httpCode\nResponse: $response");
    }
    
    return json_decode($response, true);
}

try {
    // 创建数据库
    echo "创建数据库...\n";
    $result = query("CREATE DATABASE IF NOT EXISTS test");
    echo "数据库创建成功!\n";
    
    // 使用数据库
    query("USE test");
    
    // 创建超级表
    echo "创建超级表...\n";
    $result = query("CREATE STABLE IF NOT EXISTS sensors (
        ts TIMESTAMP,
        temperature FLOAT,
        humidity FLOAT
    ) TAGS (
        location VARCHAR(64),
        device_id INT
    )");
    echo "超级表创建成功!\n";
    
    // 创建子表并插入数据
    echo "创建子表并插入数据...\n";
    $result = query("CREATE TABLE IF NOT EXISTS sensor_1 USING sensors TAGS (\"办公室\", 1)");
    
    // 插入测试数据
    $timestamp = time() * 1000; // TDengine 使用毫秒时间戳
    $result = query("INSERT INTO sensor_1 VALUES
        ($timestamp, 23.5, 60.0),
        (" . ($timestamp + 1000) . ", 23.6, 60.2),
        (" . ($timestamp + 2000) . ", 23.4, 60.1)
    ");
    echo "测试数据插入成功!\n";
    
    // 查询数据
    echo "查询数据...\n";
    $result = query("SELECT * FROM sensor_1 ORDER BY ts DESC LIMIT 10");
    echo "查询结果:\n";
    print_r($result);
    
    // 聚合查询示例
    echo "\n聚合查询示例...\n";
    $result = query("SELECT AVG(temperature) as avg_temp, MAX(humidity) as max_hum FROM sensor_1");
    echo "平均温度和最大湿度:\n";
    print_r($result);
    
    // 删除测试数据
    echo "\n清理测试数据...\n";
    query("DROP TABLE IF EXISTS sensor_1");
    query("DROP STABLE IF EXISTS sensors");
    query("DROP DATABASE IF EXISTS test");
    echo "测试数据清理完成!\n";
    
} catch (Exception $e) {
    echo "错误: " . $e->getMessage() . "\n";
    exit(1);
} 
<?php

echo "开始测试 PostgreSQL 连接...\n";

$host = 'postgres';
$port = 5432;
$dbname = 'default';
$user = 'postgres';
$password = '123456';

try {
    // 创建连接
    $dsn = "pgsql:host=$host;port=$port;dbname=$dbname;user=$user;password=$password";
    $pdo = new PDO($dsn);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "成功连接到 PostgreSQL!\n";
    
    // 创建测试表
    $pdo->exec("DROP TABLE IF EXISTS test_users");
    $pdo->exec("CREATE TABLE test_users (
        id SERIAL PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        email VARCHAR(100) UNIQUE NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");
    echo "测试表创建成功!\n";
    
    // 插入测试数据
    $stmt = $pdo->prepare("INSERT INTO test_users (name, email) VALUES (?, ?)");
    $stmt->execute(['测试用户', 'test@example.com']);
    echo "测试数据插入成功，ID: " . $pdo->lastInsertId() . "\n";
    
    // 查询数据
    $stmt = $pdo->query("SELECT * FROM test_users");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "查询结果:\n";
    print_r($users);
    
    // 测试事务
    $pdo->beginTransaction();
    try {
        $stmt = $pdo->prepare("INSERT INTO test_users (name, email) VALUES (?, ?)");
        $stmt->execute(['事务测试1', 'transaction1@example.com']);
        $stmt->execute(['事务测试2', 'transaction2@example.com']);
        $pdo->commit();
        echo "事务测试成功!\n";
    } catch (Exception $e) {
        $pdo->rollBack();
        echo "事务回滚: " . $e->getMessage() . "\n";
    }
    
    // 清理测试数据
    $pdo->exec("DROP TABLE test_users");
    echo "测试表删除成功!\n";
    
} catch (PDOException $e) {
    echo "连接失败: " . $e->getMessage() . "\n";
    exit(1);
} 
<?php

// 启用错误报告
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "Starting Kafka test script\n";

// 创建死信队列主题
$adminConf = new RdKafka\Conf();
$adminConf->set('metadata.broker.list', 'kafka:9092');
$producer = new RdKafka\Producer($adminConf);
$topic = $producer->newTopic("dead_letter_queue");
$topic->produce(RD_KAFKA_PARTITION_UA, 0, ""); // 发送一个空消息以确保主题被创建
$producer->flush(10000);
echo "Dead letter queue topic created\n";

// 配置 Kafka 生产者
$conf = new RdKafka\Conf();

// 设置调试级别
$conf->set('debug', 'all');
$conf->set('log_level', (string) LOG_DEBUG);
$conf->set('metadata.broker.list', 'kafka:9092');

echo "Creating producer\n";
try {
    $producer = new RdKafka\Producer($conf);
} catch (Exception $e) {
    echo "Error creating producer: " . $e->getMessage() . "\n";
    exit(1);
}

// 等待元数据
echo "Waiting for metadata\n";
try {
    $metadata = $producer->getMetadata(true, null, 10000);
    echo "Broker count: " . count($metadata->getBrokers()) . "\n";
    foreach ($metadata->getBrokers() as $broker) {
        printf("Broker %d: %s:%d\n", $broker->getId(), $broker->getHost(), $broker->getPort());
    }
} catch (Exception $e) {
    echo "Error getting metadata: " . $e->getMessage() . "\n";
    exit(1);
}

// 创建主题
$topic = $producer->newTopic("test_topic");

// 发送消息
echo "Sending message\n";
try {
    $topic->produce(RD_KAFKA_PARTITION_UA, 0, "Hello, Kafka!");
    $result = $producer->flush(10000);
    echo "Flush result: " . $result . "\n";
    echo "Message sent\n";
} catch (Exception $e) {
    echo "Error sending message: " . $e->getMessage() . "\n";
    exit(1);
}

// 配置 Kafka 消费者
$conf = new RdKafka\Conf();
$conf->set('debug', 'all');
$conf->set('group.id', 'test_group');
$conf->set('metadata.broker.list', 'kafka:9092');
$conf->set('auto.offset.reset', 'earliest');

echo "Creating consumer\n";
try {
    $consumer = new RdKafka\Consumer($conf);
} catch (Exception $e) {
    echo "Error creating consumer: " . $e->getMessage() . "\n";
    exit(1);
}

// 等待元数据
echo "Waiting for consumer metadata\n";
try {
    $metadata = $consumer->getMetadata(true, null, 10000);
    echo "Consumer broker count: " . count($metadata->getBrokers()) . "\n";
    foreach ($metadata->getBrokers() as $broker) {
        printf("Consumer broker %d: %s:%d\n", $broker->getId(), $broker->getHost(), $broker->getPort());
    }
} catch (Exception $e) {
    echo "Error getting consumer metadata: " . $e->getMessage() . "\n";
    exit(1);
}

try {
    $topic = $consumer->newTopic("test_topic");
} catch (Exception $e) {
    echo "Error creating consumer topic: " . $e->getMessage() . "\n";
    exit(1);
}

// 开始消费
try {
    $topic->consumeStart(0, RD_KAFKA_OFFSET_BEGINNING);
} catch (Exception $e) {
    echo "Error starting consumption: " . $e->getMessage() . "\n";
    exit(1);
}

echo "Starting consumption\n";
$timeout = time() + 10; // 10秒超时
while (time() < $timeout) {
    try {
        $message = $topic->consume(0, 1000);
        if ($message === null) {
            echo "No message received\n";
            continue;
        }
        if ($message->err) {
            echo "Error: " . $message->errstr() . "\n";
            break;
        } else {
            echo "Received message: " . $message->payload . "\n";
            break;
        }
    } catch (Exception $e) {
        echo "Error during consumption: " . $e->getMessage() . "\n";
        break;
    }
}

echo "Test completed\n"; 
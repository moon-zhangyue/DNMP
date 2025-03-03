docker-compose exec kafka kafka-topics.sh --create --bootstrap-server kafka:9092 --topic dead_letter_queue --partitions 3 --replication-factor 1

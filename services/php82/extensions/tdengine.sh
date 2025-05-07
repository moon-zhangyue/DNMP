#!/bin/sh

# 安装必要的构建工具
apk add --no-cache git autoconf gcc g++ make cmake

# 下载并安装TDengine PHP扩展
cd /tmp
git clone https://github.com/Yurunsoft/php-tdengine.git
cd php-tdengine
git checkout v1.0.6

# 编译安装
phpize
./configure
make -j$(nproc)
make install

# 启用扩展
echo "extension=tdengine" > /usr/local/etc/php/conf.d/tdengine.ini

# 清理
cd /tmp
rm -rf php-tdengine



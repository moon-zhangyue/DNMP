FROM influxdb:2.7-alpine

ARG TZ=Asia/Shanghai

RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

EXPOSE 8086

CMD ["influxd"]
FROM tdengine/tdengine:3.2.3.0

ARG TZ

RUN ln -sf /usr/share/zoneinfo/${TZ} /etc/localtime \
    && echo ${TZ} > /etc/timezone

EXPOSE 6030 6041 6042

CMD ["/usr/bin/entrypoint.sh"]
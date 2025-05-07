ARG ELASTICSEARCH_VERSION
FROM elasticsearch:${ELASTICSEARCH_VERSION}

ARG ELASTICSEARCH_VERSION
ARG ELASTICSEARCH_PLUGINS

ENV PLUGINS=",${ELASTICSEARCH_PLUGINS},"

# 设置非交互式安装，避免插件安装时的提示
ENV ELASTIC_CONTAINER=true

# 设置插件安装的批处理模式
RUN echo "Installing plugins with --batch mode"

# 创建临时目录用于插件安装
RUN mkdir -p /tmp/plugins

# 复制本地插件文件到容器中
# COPY plugins/analysis-ik-Latest.zip /usr/share/elasticsearch/plugins/
# COPY plugins/analysis-pinyin-Latest.zip /usr/share/elasticsearch/plugins/


RUN if [[ -z "${PLUGINS##*,amazon-ec2,*}" ]]; then \
        printf "y\n" | elasticsearch-plugin install amazon-ec2; \
    fi \
    && \
    if [[ -z "${PLUGINS##*,analysis-icu,*}" ]]; then \
        printf "y\n" | elasticsearch-plugin install analysis-icu; \
    fi \
    && \
    if [[ -z "${PLUGINS##*,analysis-kuromoji,*}" ]]; then \
        printf "y\n" | elasticsearch-plugin install analysis-kuromoji; \
    fi \
    && \
    if [[ -z "${PLUGINS##*,analysis-nori,*}" ]]; then \
        printf "y\n" | elasticsearch-plugin install analysis-nori; \
    fi \
    && \
    if [[ -z "${PLUGINS##*,analysis-phonetic,*}" ]]; then \
        printf "y\n" | elasticsearch-plugin install analysis-phonetic; \
    fi \
    && \
    if [[ -z "${PLUGINS##*,analysis-smartcn,*}" ]]; then \
        printf "y\n" | elasticsearch-plugin install analysis-smartcn; \
    fi \
    && \
    if [[ -z "${PLUGINS##*,analysis-stempel,*}" ]]; then \
        printf "y\n" | elasticsearch-plugin install analysis-stempel; \
    fi \
    && \
    if [[ -z "${PLUGINS##*,analysis-ukrainian,*}" ]]; then \
        printf "y\n" | elasticsearch-plugin install analysis-ukrainian; \
    fi \
    && \
    if [[ -z "${PLUGINS##*,discovery-azure-classic,*}" ]]; then \
        printf "y\n" | elasticsearch-plugin install discovery-azure-classic; \
    fi \
    && \
    if [[ -z "${PLUGINS##*,discovery-ec2,*}" ]]; then \
        printf "y\n" | elasticsearch-plugin install discovery-ec2; \
    fi \
    && \
    if [[ -z "${PLUGINS##*,discovery-file,*}" ]]; then \
        printf "y\n" | elasticsearch-plugin install discovery-file; \
    fi \
    && \
    if [[ -z "${PLUGINS##*,discovery-gce,*}" ]]; then \
        printf "y\n" | elasticsearch-plugin install discovery-gce; \
    fi \
    && \
    if [[ -z "${PLUGINS##*,google-cloud-storage,*}" ]]; then \
        printf "y\n" | elasticsearch-plugin install google-cloud-storage; \
    fi \
    && \
    if [[ -z "${PLUGINS##*,ingest-attachment,*}" ]]; then \
        printf "y\n" | elasticsearch-plugin install ingest-attachment; \
    fi \
    && \
    if [[ -z "${PLUGINS##*,ingest-geoip,*}" ]]; then \
        printf "y\n" | elasticsearch-plugin install ingest-geoip; \
    fi \
    && \
    if [[ -z "${PLUGINS##*,ingest-user-agent,*}" ]]; then \
        printf "y\n" | elasticsearch-plugin install ingest-user-agent; \
    fi \
    && \
    if [[ -z "${PLUGINS##*,mapper-murmur3,*}" ]]; then \
        printf "y\n" | elasticsearch-plugin install mapper-murmur3; \
    fi \
    && \
    if [[ -z "${PLUGINS##*,mapper-size,*}" ]]; then \
        printf "y\n" | elasticsearch-plugin install mapper-size; \
    fi \
    && \
    if [[ -z "${PLUGINS##*,microsoft-azure-storage,*}" ]]; then \
        printf "y\n" | elasticsearch-plugin install microsoft-azure-storage; \
    fi \
    && \
    if [[ -z "${PLUGINS##*,qa,*}" ]]; then \
        printf "y\n" | elasticsearch-plugin install qa; \
    fi \
    && \
    if [[ -z "${PLUGINS##*,repository-azure,*}" ]]; then \
        printf "y\n" | elasticsearch-plugin install repository-azure; \
    fi \
    && \
    if [[ -z "${PLUGINS##*,repository-gcs,*}" ]]; then \
        printf "y\n" | elasticsearch-plugin install repository-gcs; \
    fi \
    && \
    if [[ -z "${PLUGINS##*,repository-hdfs,*}" ]]; then \
        printf "y\n" | elasticsearch-plugin install repository-hdfs; \
    fi \
    && \
    if [[ -z "${PLUGINS##*,repository-s3,*}" ]]; then \
        printf "y\n" | elasticsearch-plugin install repository-s3; \
    fi \
    && \
    if [[ -z "${PLUGINS##*,store-smb,*}" ]]; then \
        printf "y\n" | elasticsearch-plugin install store-smb; \
    fi \
    && \
    if [[ -z "${PLUGINS##*,analysis-ik,*}" ]]; then \
        # 使用本地插件文件，不从网络下载\
        printf "y\n" | elasticsearch-plugin install --batch file:///usr/share/elasticsearch/plugins/analysis-ik-Latest.zip; \
    fi \
    && \
    if [[ -z "${PLUGINS##*,analysis-pinyin,*}" ]]; then \
        # 使用本地插件文件，不从网络下载\
        printf "y\n" | elasticsearch-plugin install --batch file:///usr/share/elasticsearch/plugins/analysis-pinyin-Latest.zip; \
    fi \
    && \
    if [[ -z "${PLUGINS##*,analysis-smartcn,*}" ]]; then \
      printf "y\n" | elasticsearch-plugin install analysis-smartcn; \
    fi

# 复制安装脚本并设置执行权限
# COPY plugins/install-plugins.sh /usr/local/bin/
# RUN chmod +x /usr/local/bin/install-plugins.sh

# 清理plugins目录中的zip文件，避免被当作目录处理
RUN find /usr/share/elasticsearch/plugins -name "*.zip" -type f -delete
RUN find /usr/share/elasticsearch/plugins -name "install-plugins.sh" -type f -delete

# 设置启动命令，确保在启动Elasticsearch前运行安装脚本
# CMD ["/bin/bash", "-c", "/usr/local/bin/install-plugins.sh && /usr/local/bin/docker-entrypoint.sh eswrapper"]
CMD ["/bin/bash", "-c", "/usr/local/bin/docker-entrypoint.sh eswrapper"]


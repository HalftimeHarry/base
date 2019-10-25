FROM gitpod/workspace-full:latest

USER root

# install via Ubuntu's APT:
# * Apache - the web server
# * Multitail - see logs live in the terminal
RUN apt-get update \
 && apt-get -y install apache2 multitail \
 && apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/*

# 1. give write permission to the gitpod-user to apache directories
# 2. let Apache use apache.conf and apache.env.sh from our /workspace/<myproject> folder
RUN chown -R gitpod:gitpod /var/run/apache2 /var/lock/apache2 /var/log/apache2 \
 && echo "include \${GITPOD_REPO_ROOT}/apache.conf" > /etc/apache2/apache2.conf \
 && echo ". \${GITPOD_REPO_ROOT}/apache.env.sh" > /etc/apache2/envvars
 
USER root

# Install MySQL
RUN apt-get update \
 && apt-get install -y mysql-server \
 && apt-get clean && rm -rf /var/cache/apt/* /var/lib/apt/lists/* /tmp/* \
 && mkdir /var/run/mysqld \
 && chown -R gitpod:gitpod /etc/mysql /var/run/mysqld /var/log/mysql /var/lib/mysql /var/lib/mysql-files /var/lib/mysql-keyring /var/lib/mysql-upgrade

# Install our own MySQL config
COPY mysql.cnf /etc/mysql/mysql.conf.d/mysqld.cnf

# Install default-login for MySQL clients
COPY client.cnf /etc/mysql/mysql.conf.d/client.cnf

COPY mysql-bashrc-launch.sh /etc/mysql/mysql-bashrc-launch.sh

USER gitpod

RUN echo "/etc/mysql/mysql-bashrc-launch.sh" >> ~/.bashrc

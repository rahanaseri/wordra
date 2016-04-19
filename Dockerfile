FROM wordpress:latest
MAINTAINER Raha Naseri
RUN apt-get update && apt-get -y install apt-utils vim software-properties-common && \
yes ''| apt-get -y install php5-dev php5 php5-mysqlnd &&\
yes '' | apt-add-repository ppa:linuxjedi/ppa && \
apt-get -y install g++ make cmake libssl-dev git gcc libgmp-dev openssl libpcre3-dev && \
apt-get -f install && \
curl http://downloads.datastax.com/cpp-driver/ubuntu/14.04/dependencies/libuv/v1.2.1/libuv-dev_1.2.1-1_amd64.deb > libuv-dev_1.2.1-1_amd64.deb && \
curl http://downloads.datastax.com/cpp-driver/ubuntu/14.04/dependencies/libuv/v1.2.1/libuv_1.2.1-1_amd64.deb > libuv_1.2.1-1_amd64.deb && \
curl http://downloads.datastax.com/cpp-driver/ubuntu/14.04/v2.3.0/cassandra-cpp-driver_2.3.0-1_amd64.deb > cassandra-cpp-driver_2.3.0-1_amd64.deb && \
curl http://downloads.datastax.com/cpp-driver/ubuntu/14.04/v2.3.0/cassandra-cpp-driver-dev_2.3.0-1_amd64.deb > cassandra-cpp-driver-dev_2.3.0-1_amd64.deb && \
dpkg -i libuv_1.2.1-1_amd64.deb && \
dpkg -i libuv-dev_1.2.1-1_amd64.deb && \
dpkg -i cassandra-cpp-driver_2.3.0-1_amd64.deb && \
dpkg -i cassandra-cpp-driver-dev_2.3.0-1_amd64.deb && \
git clone https://github.com/datastax/cpp-driver.git &&\
mkdir cpp-driver/build && \
cd cpp-driver/build && \
cmake .. && \
make && \
pecl install cassandra 

ADD cassandra.ini /etc/php5/mods-available/
ADD 20-cassandra.ini /etc/php5/apache2/conf.d/
ADD .htaccess /var/www/html/
RUN mkdir -p /var/www/html/wp-content/plugins/raha
ADD rg.php /var/www/html/wp-content/plugins/raha/
ADD uploader.php /var/www/html/wp-content/plugins/raha/
ADD options.php /var/www/html/wp-content/plugins/raha/
ADD play.php /var/www/html/wp-content/plugins/raha/
ADD list.php /var/www/html/wp-content/plugins/raha/
ADD list.css /var/www/html/wp-content/plugins/raha/
ADD next.php /var/www/html/wp-content/plugins/raha/
ADD login.php /var/www/html/wp-content/plugins/raha/
RUN mkdir -p /var/www/html/wp-content/plugins/raha/js
ADD js/ /var/www/html/wp-content/plugins/raha/js/
RUN php5enmod cassandra &&\
service apache2 restart




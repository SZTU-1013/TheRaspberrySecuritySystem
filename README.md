TheRaspberrySecuritySystem
===================================
author：sztu-1013<br>
`配置环境:`<br>
安装 Nginx 和 PHP7<br>
```BASH
sudo apt-get update
sudo apt-get install nginx php7.0-fpm php7.0-cli php7.0-curl php7.0-gd php7.0-mcrypt php7.0-cgi
sudo service nginx start
sudo service php7.0-fpm restart
```
`配置tornado:`<br>
```BASH
pip install tornado
```
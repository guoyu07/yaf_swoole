# yaf_swoole
yaf和swoole的http服务简单结合案例，主要运用php的ob相关方法达到目的

问题解决

1.解决了yaf的框架跳转问题，统一构建一个location方法用来做页面跳转

2.解决swoole的http服务导致php session的问题，重写了session的存储，构建session方法用来读写session，以及delSession方法用来删除session，默session存储在memcache

服务启动说明
php server/server.php默认监听为 8888端口，可以自己需要配置server.php文件中的相关常量

web服务器转发，这里以nginx为例简单配置如下
```shell
location / {
            root   /usr/local/nginx/html/public;
            index  index.php index.html index.htm;

            if (!-e $request_filename) {
                proxy_pass http://127.0.0.1:8888;
            }
        }
location ~ .*\.(php|php5)?$ {
           proxy_pass http://127.0.0.1:8888;
       }
```

其他说明
1.此程序仅为简单测试例子，不保障生产环境可用

2.运行程序前需要创建mysql数据库服务器，并且构建test库和相关的表，详见server.sql

3.代码仅仅只是让程序跑起来，未对php的上传等做处理，如果需要详细处理，请继续调整server/server.php

4.默认页面显示编码强制为UTF-8详见server/server.php

5.重申一遍，代码仅仅爱好玩玩，是否运用于生产环境，自己斟酌

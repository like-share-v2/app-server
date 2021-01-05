```shell script
docker run -p 6379:6379 --name redis --net network --ip 10.0.1.24 --restart=always -v /etc/docker/redis/redis.conf:/etc/redis/redis.conf -v /etc/docker/redis/data:/data -d redis redis-server /etc/redis/redis.conf --appendonly yes
```
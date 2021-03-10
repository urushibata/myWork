#! /bin/sh

aws ecr get-login-password --region ap-northeast-1 | docker login --username AWS --password-stdin 013311312082.dkr.ecr.ap-northeast-1.amazonaws.com;
cd ..

# nginx image build
cp ./env/docker/nginx/.nginx.dockerignore .dockerignore
docker build -f .\env\docker\nginx\DockerfileEcr  -t my_work_nginx . --no-cache
docker tag my_work_nginx:latest 013311312082.dkr.ecr.ap-northeast-1.amazonaws.com/my_work_nginx:latest;
docker push 013311312082.dkr.ecr.ap-northeast-1.amazonaws.com/my_work_nginx:latest;
rm .dockerignore

# app image build
cp ./env/docker/php/.php.dockerignore .dockerignore
docker build -f .\env\docker\php\DockerfileEcr  -t my_work_php . --no-cache
docker tag my_work_php:latest 013311312082.dkr.ecr.ap-northeast-1.amazonaws.com/my_work_php:latest;
docker push 013311312082.dkr.ecr.ap-northeast-1.amazonaws.com/my_work_php:latest;
rm .dockerignore

# db image build
cp ./env/docker/db/.db.dockerignore .dockerignore
docker build -f DockerfileEcr -t my_work_db . --no-cache
docker tag my_work_db:latest 013311312082.dkr.ecr.ap-northeast-1.amazonaws.com/my_work_db:latest;
docker push 013311312082.dkr.ecr.ap-northeast-1.amazonaws.com/my_work_db:latest;
rm .dockerignore

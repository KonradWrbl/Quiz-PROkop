FROM nginx:latest
COPY .docker/build/nginx/default.conf /etc/nginx/conf.d/
COPY . /var/www/quiz
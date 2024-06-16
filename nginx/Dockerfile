FROM nginx:latest

COPY default.conf /etc/nginx/conf.d/default.conf
COPY .. /var/www/html/

EXPOSE 80

ENTRYPOINT ["nginx","-g","daemon off;"]

FROM nginx:1.19.3-alpine

COPY _docker/site.conf /etc/nginx/conf.d/site.conf
COPY posts/static /static/static
COPY content /static/content
COPY assets /static/assets

RUN rm /static/content/.gitignore /etc/nginx/conf.d/default.conf

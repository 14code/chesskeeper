FROM php:8.4-cli-alpine
RUN apk update && apk upgrade
RUN echo "upload_max_filesize=20M" > /usr/local/etc/php/conf.d/uploads.ini \
 && echo "post_max_size=200M" >> /usr/local/etc/php/conf.d/uploads.ini \
 && echo "max_file_uploads=100" >> /usr/local/etc/php/conf.d/uploads.ini
COPY . /app
WORKDIR /app
CMD [ "php", "-S", "0.0.0.0:8080", "-t" ,"./public/" ]

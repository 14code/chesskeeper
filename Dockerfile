FROM php:8.4-cli-alpine
RUN apk update && apk upgrade
COPY . /app
WORKDIR /app
CMD [ "php", "-S", "0.0.0.0:8080", "-t" ,"./public/" ]

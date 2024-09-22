FROM node:alpine

WORKDIR /var/www/front

COPY ./front/package*.json /var/www/front

RUN npm install -g @angular/cli

RUN npm install

EXPOSE 4200

CMD ["ng", "serve", "--host", "0.0.0.0", "--watch", "--poll", "100", "--disable-host-check"]
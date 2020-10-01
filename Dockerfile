FROM php:7.2-fpm-alpine
LABEL Name=teamplanning Version=0.0.1 Author=cedric73
ENV TEAM_DATABASE_SERVER localhost
ENV TEAM_DATABASE_USER root
ENV TEAM_DATABASE_PASSWORD cedrix
ENV TEAM_DATABASE_NAME team_planning
RUN apt-get update
RUN docker-php-ext-install pdo pdo_myslq
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli
RUN apt-get install -y libpq-dev && docker-php-ext-install pdo pdo_pgsql
EXPOSE 80
CMD ["/app/docker/start.sh"]
# docker build Dockerfile -t [Cedrix73]/[teamplanning]
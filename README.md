## Requirements

`docker 1.13.0+`

## How to run?
- Clone repository with either SSH or HTTPS by running following command:  
`git clone git@gitlab.com:printer-iot/backend.git`(SSH)  
`git clone https://gitlab.com/printer-iot/backend.git` (HTTPS)

- Change directory to `backend`

- Run Docker service

- Run following commands:  
```
docker-compose up -d
docker exec -it padgy_php composer install
docker exec -it padgy_php php bin/console make:migration
docker exec -it padgy_php php bin/console doctrine:migrations:migrate
```  
- Open `localhost:8088` in web browser# Quiz-PROkop

# Transfer Money
This project is the code challenge of the Snapshop team

## Requirement

To start this project, you need to install Docker, please make sure to install the latest version of Docker before starting

Please follow the steps below after cloning the project

```bash
cp .env.example .env

docker compose up -d

docker exec -it TransferMoneyApplication composer install

docker exec -it TransferMoneyApplication php /var/www/html/artisan key:generate

docker exec -it TransferMoneyApplication php /var/www/html/artisan migrate
```

## Test
```bash
docker exec -it TransferMoneyApplication php /var/www/html/artisan test
```

## Routes
```
api/v1/cards/transfer
api/v1/cards/report
```

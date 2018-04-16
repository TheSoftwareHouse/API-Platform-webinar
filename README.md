# About this app

A demonstration application for the webinar API Platform – Symfony APIs done quick and right.

## Configuration

Configure your .env parameters (or use the example.env)  and run docker-compose up -d. The API documentation will be available at port 8080.

Don't forget to install packages, create the schema and, if you find it useful, load the doctrine fixtures.

```
docker-compose exec php composer install
docker-compose exec php bin/console doctrine:schema:create
docker-compose exec php bin/console --env=dev doctrine:fixtures:load
```

## Troubleshooting

Sometimes you'll need to update file permisions for var folder.



# Teste Liberfly

### Passo a passo
Clone Repositório
```sh
git clone https://github.com/maxrodrigues/api_liberfly
```

Acesse a pasta do projeto
```sh
cd api_liberfly/
```


Crie o Arquivo .env
```sh
cp .env.example .env
```


Atualize as variáveis de ambiente do arquivo .env
```dosini
APP_NAME="Liberfly API"
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=liberfly
DB_USERNAME=root
DB_PASSWORD=root

CACHE_DRIVER=redis
QUEUE_CONNECTION=redis
SESSION_DRIVER=redis

REDIS_HOST=redis
REDIS_PASSWORD=null
REDIS_PORT=6379
```


Suba os containers do projeto
```sh
docker-compose up -d
```


Acessar o container
```sh
docker-compose exec app bash
```


Instalar as dependências do projeto
```sh
composer install
```


Gerar a key do projeto Laravel
```sh
php artisan key:generate
```

Os testes foram feitos usando PEST
```sh
docker-compose exec app ./vendor/bin/pest --parallel
```

Acessar o projeto
[http://localhost:8000](http://localhost:8989)

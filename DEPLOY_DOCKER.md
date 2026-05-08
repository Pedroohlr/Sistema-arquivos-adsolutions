# Deploy Docker

## Visao geral

Este projeto pode subir em uma VM limpa usando um unico container com Apache + PHP 8.3 e SQLite persistido em volume Docker.

O banco SQLite fica em `storage/app/database.sqlite`, dentro do volume `app_storage`, para evitar os problemas de permissao e path que aconteceram na hospedagem anterior.

## Arquivos

- `Dockerfile`: imagem de producao com build dos assets do Vite
- `docker-compose.yml`: sobe a aplicacao em `:80`
- `docker/apache/000-default.conf`: Apache apontando para `public/`
- `docker/entrypoint.sh`: cria diretorios runtime, prepara SQLite e roda migrations

## Preparacao da VM

1. Instalar Docker e plugin Compose
2. Clonar o repositorio
3. Copiar `.env.example` para `.env`
4. Ajustar pelo menos:

```env
APP_NAME="ADSolutions"
APP_ENV=production
APP_DEBUG=false
APP_URL=http://IP_DA_VM

DB_CONNECTION=sqlite
DB_DATABASE=/var/www/html/storage/app/database.sqlite

SESSION_DRIVER=file
CACHE_STORE=file

RUN_MIGRATIONS=true
RUN_SEEDERS=false
APP_PORT=80
```

## Subida

```bash
docker compose build
docker compose up -d
docker compose logs -f app
```

## Comandos uteis

```bash
docker compose exec app php artisan migrate:status
docker compose exec app php artisan db:seed --force
docker compose exec app php artisan tinker
docker compose exec app ls -la storage/app
```

## Observacoes

- Nao usar `route:cache`, porque o projeto tem rota com closure.
- Se quiser popular dados de demo no primeiro boot, defina `RUN_SEEDERS=true` na `.env`, suba uma vez, depois volte para `false`.
- Se futuramente quiser migrar para MySQL/MariaDB, a estrutura Docker continua aproveitavel; basta trocar a conexao e adicionar o servico do banco no `docker-compose.yml`.
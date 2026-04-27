# Deploy em Produção (VPS) - Sistema Arquivos ADSolutions

Este guia considera Ubuntu/Debian com Nginx + PHP-FPM + MySQL/MariaDB.

Se sua VPS for Arch Linux, veja a secao "Arch Linux" no final deste arquivo.

## 1) Pré-requisitos da VPS

Instale os pacotes (ajuste a versão do PHP se necessário):

```bash
sudo apt update
sudo apt install -y nginx git unzip curl supervisor mysql-server \
  php8.2-fpm php8.2-cli php8.2-common php8.2-mysql php8.2-mbstring \
  php8.2-xml php8.2-curl php8.2-zip php8.2-bcmath php8.2-intl php8.2-gd
```

Instale Node.js LTS (necessário para gerar os assets Vite no servidor):

```bash
curl -fsSL https://deb.nodesource.com/setup_lts.x | sudo -E bash -
sudo apt install -y nodejs
```

Instale Composer (caso ainda nao exista):

```bash
cd ~
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php composer-setup.php
sudo mv composer.phar /usr/local/bin/composer
rm composer-setup.php
```

## 2) Banco de dados

Crie banco e usuario no MySQL/MariaDB:

```sql
CREATE DATABASE adsolutions CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'adsolutions_user'@'localhost' IDENTIFIED BY 'SENHA_FORTE_AQUI';
GRANT ALL PRIVILEGES ON adsolutions.* TO 'adsolutions_user'@'localhost';
FLUSH PRIVILEGES;
```

## 3) Clonar projeto e preparar ambiente

```bash
mkdir -p ~/adsolutions.siteup.dev/www
cd ~/adsolutions.siteup.dev/www
git clone <URL_DO_REPOSITORIO> usuarios
cd usuarios
cp .env.example .env
php artisan key:generate
```

Edite o `.env` para producao:

```env
APP_NAME="Sistema Arquivos ADSolutions"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://SEU_DOMINIO

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=adsolutions
DB_USERNAME=adsolutions_user
DB_PASSWORD=SENHA_FORTE_AQUI

QUEUE_CONNECTION=database
CACHE_STORE=database
SESSION_DRIVER=database
```

## 4) Primeiro deploy

No diretorio do projeto:

```bash
bash deploy.sh
```

## 5) Nginx (Virtual Host)

Crie o arquivo `/etc/nginx/sites-available/adsolutions`:

```nginx
server {
    listen 80;
    server_name SEU_DOMINIO www.SEU_DOMINIO;

    root /home/SEU_USUARIO/adsolutions.siteup.dev/www/usuarios/public;
    index index.php;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/run/php/php8.2-fpm.sock;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

Ative o site:

```bash
sudo ln -s /etc/nginx/sites-available/adsolutions /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl reload nginx
```

## 6) HTTPS (Let's Encrypt)

```bash
sudo apt install -y certbot python3-certbot-nginx
sudo certbot --nginx -d SEU_DOMINIO -d www.SEU_DOMINIO
```

## 7) Filas com Supervisor

Crie `/etc/supervisor/conf.d/adsolutions-worker.conf`:

```ini
[program:adsolutions-worker]
process_name=%(program_name)s_%(process_num)02d
command=/usr/bin/php /home/SEU_USUARIO/adsolutions.siteup.dev/www/usuarios/artisan queue:work database --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=1
redirect_stderr=true
stdout_logfile=/home/SEU_USUARIO/adsolutions.siteup.dev/www/usuarios/storage/logs/worker.log
stopwaitsecs=3600
```

Ative:

```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start adsolutions-worker:*
```

## 8) Cron do Laravel Scheduler

```bash
crontab -e
```

Adicione:

```cron
* * * * * cd /home/SEU_USUARIO/adsolutions.siteup.dev/www/usuarios && /usr/bin/php artisan schedule:run >> /dev/null 2>&1
```

## 9) Atualizacao de versao (dia a dia)

Sempre que atualizar o repositorio:

```bash
cd /home/SEU_USUARIO/adsolutions.siteup.dev/www/usuarios
bash deploy.sh
```

## 10) Checklist rapido de validacao

- Site abre via HTTPS
- Login de admin/cliente funciona
- Upload e download de arquivos funcionando
- Pasta `public/storage` acessivel
- Fila em execucao (`sudo supervisorctl status`)
- Logs sem erro critico (`storage/logs/laravel.log`)

## Observacoes

- O `deploy.sh` usa `APP_DIR` e `BRANCH` opcionais por variavel de ambiente.
- Exemplo para branch diferente:

```bash
APP_DIR=/home/SEU_USUARIO/adsolutions.siteup.dev/www/usuarios BRANCH=main bash deploy.sh
```

## Arch Linux

No Arch Linux, os nomes de pacote/servico e o usuario web mudam.

Instalacao de pacotes:

```bash
sudo pacman -Syu --noconfirm nginx php php-fpm php-sqlite nodejs npm composer git unzip supervisor
```

Habilitar servicos:

```bash
sudo systemctl enable --now nginx
sudo systemctl enable --now php-fpm
sudo systemctl enable --now supervisord
```

Usuario web no Arch geralmente e `http` (nao `www-data`).

Para deploy com usuario web correto:

```bash
WEB_USER=http WEB_GROUP=http bash deploy.sh
```

Se quiser conferir o usuario do php-fpm/nginx:

```bash
ps aux | grep -E "php-fpm|nginx" | grep -v grep
```

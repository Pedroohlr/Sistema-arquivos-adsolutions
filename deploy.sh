#!/usr/bin/env bash
# =============================================================
# Script de deploy — Sistema Arquivos ADSolutions
# Execute no servidor: bash deploy.sh
# =============================================================

set -euo pipefail

APP_DIR="${APP_DIR:-$HOME/adsolutions.siteup.dev/www/usuarios}"
BRANCH="${BRANCH:-main}"
WEB_USER="${WEB_USER:-}"
WEB_GROUP="${WEB_GROUP:-}"

echo "=============================="
echo " Iniciando deploy..."
echo " Diretório: $APP_DIR"
echo " Branch:    $BRANCH"
echo "=============================="

if [[ ! -d "$APP_DIR" ]]; then
	echo "Erro: diretório da aplicação não existe: $APP_DIR"
	exit 1
fi

cd "$APP_DIR"

if [[ ! -f artisan ]]; then
	echo "Erro: arquivo artisan não encontrado em $APP_DIR"
	exit 1
fi

if [[ ! -f .env ]]; then
	echo "Erro: arquivo .env não encontrado. Crie e configure antes do deploy."
	exit 1
fi

if [[ -z "$WEB_USER" ]]; then
	if id -u www-data >/dev/null 2>&1; then
		WEB_USER="www-data"
	elif id -u http >/dev/null 2>&1; then
		WEB_USER="http"
	else
		WEB_USER="$(id -un)"
	fi
fi

if [[ -z "$WEB_GROUP" ]]; then
	if getent group "$WEB_USER" >/dev/null 2>&1; then
		WEB_GROUP="$WEB_USER"
	else
		WEB_GROUP="$(id -gn)"
	fi
fi

echo "[1/10] Ativando modo manutenção..."
php artisan down || true

echo "[2/10] Atualizando código via git..."
git fetch origin "$BRANCH"
git checkout "$BRANCH"
git pull --ff-only origin "$BRANCH"

echo "[3/10] Instalando dependências PHP (produção)..."
composer install --no-dev --prefer-dist --optimize-autoloader --no-interaction

echo "[4/10] Instalando dependências Node..."
npm ci --no-audit --no-fund

echo "[5/10] Gerando build de assets (Vite)..."
npm run build

echo "[6/10] Garantindo link público de storage..."
php artisan storage:link || true

echo "[7/10] Rodando migrations..."
php artisan migrate --force

echo "[8/10] Limpando e reconstruindo caches..."
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

echo "[9/10] Ajustando permissões..."
chmod -R 775 storage bootstrap/cache database
chown -R "$WEB_USER":"$WEB_GROUP" storage bootstrap/cache public/storage database 2>/dev/null || true

echo "[10/10] Reiniciando workers de fila e saindo da manutenção..."
php artisan queue:restart || true
php artisan up

echo ""
echo "=============================="
echo " Deploy concluído com sucesso!"
echo "=============================="

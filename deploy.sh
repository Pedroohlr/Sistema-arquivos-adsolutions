#!/bin/bash
# =============================================================
# Script de deploy — Sistema Arquivos ADSolutions
# Execute no servidor: bash deploy.sh
# =============================================================

set -e  # Para imediatamente se qualquer comando falhar

APP_DIR="$HOME/adsolutions.siteup.dev/www/usuarios"

echo "=============================="
echo " Iniciando deploy..."
echo "=============================="

cd "$APP_DIR"

# 1. Baixar atualizações do repositório
echo "[1/7] Atualizando código via git..."
git pull origin main

# 2. Instalar/atualizar dependências PHP (sem pacotes dev)
echo "[2/7] Instalando dependências PHP..."
composer install --no-dev --optimize-autoloader --no-interaction

# 3. Rodar migrations sem pedir confirmação
echo "[3/7] Rodando migrations..."
php artisan migrate --force

# 4. Limpar e recriar caches de otimização
echo "[4/6] Otimizando caches..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# 5. Garantir permissões corretas
echo "[5/6] Ajustando permissões..."
chmod -R 775 storage bootstrap/cache database
chown -R www-data:www-data storage bootstrap/cache public/storage database 2>/dev/null || true

# 6. Reiniciar filas (se estiver usando supervisor)
echo "[6/6] Reiniciando workers de fila..."
php artisan queue:restart

echo ""
echo "=============================="
echo " Deploy concluído com sucesso!"
echo "=============================="

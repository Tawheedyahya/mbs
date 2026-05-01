#!/bin/bash
# ============================================================
# MBS - Production Deployment Script
# Run this ON the EC2 server inside the project directory
# ============================================================

set -e

echo "============================================"
echo "  MBS - Production Deployment"
echo "============================================"

# 1. Copy production env
echo ""
echo "[1/7] Setting up environment file..."
cp .env.production .env
echo "  ✓ .env.production → .env"

# 2. Build and start containers
echo ""
echo "[2/7] Building Docker containers (this may take a few minutes)..."
docker compose -f docker-compose.prod.yml build --no-cache
echo "  ✓ Containers built"

echo ""
echo "[3/7] Starting containers..."
docker compose -f docker-compose.prod.yml up -d
echo "  ✓ Containers started"

# Wait for app container to be ready
echo ""
echo "  Waiting for app container to be ready..."
sleep 10

# 3. Generate application key
echo ""
echo "[4/7] Generating application key..."
docker exec mbs_app php artisan key:generate --force
echo "  ✓ APP_KEY generated"

# 4. Run migrations
echo ""
echo "[5/7] Running database migrations..."
docker exec mbs_app php artisan migrate --force
echo "  ✓ Migrations completed"

# 5. Create storage link
echo ""
echo "[6/7] Creating storage link & setting permissions..."
docker exec mbs_app php artisan storage:link 2>/dev/null || true
docker exec mbs_app chmod -R 775 storage bootstrap/cache
docker exec mbs_app chown -R www-data:www-data storage bootstrap/cache
echo "  ✓ Storage linked & permissions set"

# 6. Optimize for production
echo ""
echo "[7/7] Optimizing for production..."
docker exec mbs_app php artisan config:cache
docker exec mbs_app php artisan route:cache
docker exec mbs_app php artisan view:cache
echo "  ✓ Caches optimized"

echo ""
echo "============================================"
echo "  ✅ Deployment Complete!"
echo "  Open: http://43.216.181.3"
echo "  Login: admin@gmail.com"
echo "============================================"
echo ""

# Show container status
docker compose -f docker-compose.prod.yml ps

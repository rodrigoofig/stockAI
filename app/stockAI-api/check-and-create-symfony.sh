#!/bin/sh
set -e

cd /var/www/html

echo "Verificando se projeto Symfony existe..."

# Se não existir composer.json, cria novo projeto Symfony
if [ ! -f "composer.json" ]; then
    echo "🎻 Criando novo projeto Symfony..."
    composer create-project symfony/skeleton:"6.*" . --no-interaction
    composer require webapp doctrine annotations --no-interaction
    
    echo "✅ Symfony criado com sucesso!"
fi

# Instala dependências se vendor não existir
if [ ! -d "vendor" ]; then
    echo "📦 Instalando dependências PHP..."
    composer install --no-interaction --optimize-autoloader
    composer composer require nelmio/cors-bundle
fi

# Configura o .env para usar MySQL
if [ ! -f ".env.local" ]; then
    echo "🔧 Configurando variáveis de ambiente..."
    cat > .env.local << 'EOF'
# Configuração do Banco de Dados
DATABASE_URL="mysql://stockai_user:stockai_password@mysql:3306/stockai_db?serverVersion=8.0&charset=utf8mb4"

# Configuração da Aplicação
APP_ENV=dev
APP_SECRET=14bf6d6c18dfc25b06e5c223742d7d0f
EOF
    echo "✅ Variáveis de ambiente configuradas!"
fi

# Cria o controller se não existir
if [ ! -f "src/Controller/HomeController.php" ]; then
    echo "🎯 Criando HomeController..."
    mkdir -p src/Controller
    
    cat > src/Controller/HomeController.php << 'EOF'
<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class HomeController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/', name: 'home')]
    public function index(): JsonResponse
    {
        // Verifica conexão com o banco de dados
        try {
            $connection = $this->entityManager->getConnection();
            $connection->connect();
            $dbStatus = $connection->isConnected() ? 'connected' : 'disconnected';
        } catch (\Exception $e) {
            $dbStatus = 'error: ' . $e->getMessage();
        }

        return $this->json([
            'message' => 'Welcome to StockAI API',
            'status' => 'OK',
            'database' => $dbStatus,
            'timestamp' => time(),
            'environment' => $_ENV['APP_ENV'] ?? 'unknown'
        ]);
    }

    #[Route('/health', name: 'health')]
    public function health(): JsonResponse
    {
        return $this->json([
            'status' => 'healthy',
            'services' => [
                'api' => 'running',
                'database' => 'checking...'
            ]
        ]);
    }
}
EOF
    echo "✅ HomeController criado!"
fi

# Configura permissões
echo "🔧 Configurando permissões..."
chown -R www-data:www-data /var/www/html
chmod -R 755 /var

# Cria o banco de dados se não existir
echo "🗄️  Verificando banco de dados..."
if [ -f "bin/console" ]; then
    php bin/console doctrine:database:create --if-not-exists --no-interaction
    php bin/console doctrine:schema:update --force --no-interaction
fi

echo "🚀 Iniciando aplicação Symfony..."
exec "$@"
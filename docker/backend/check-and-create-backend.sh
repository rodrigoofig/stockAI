#!/bin/sh
set -e

cd /app

echo "Verificando se projeto Python existe..."

# Se não existir requirements.txt, cria projeto Python básico
if [ ! -f "requirements.txt" ]; then
    echo "🐍 Criando projeto Python básico..."
    echo "fastapi==0.104.1" > requirements.txt
    echo "uvicorn[standard]==0.24.0" >> requirements.txt
    echo 'from fastapi import FastAPI

app = FastAPI()

@app.get("/")
async def root():
    return {"message": "Hello World from StockAI Backend"}

@app.get("/health")
async def health():
    return {"status": "healthy"}' > main.py
    echo "✅ Projeto Python criado com sucesso!"
fi

# Instala dependências
echo "📦 Instalando dependências Python..."
pip install -r requirements.txt

echo "🚀 Iniciando aplicação Python..."
exec "$@"
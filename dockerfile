FROM ubuntu:22.04

ENV DEBIAN_FRONTEND=noninteractive
ENV TZ=Europe/Lisbon

# Configura timezone e instala dependências básicas
RUN apt-get update && apt-get install -y \
    curl \
    wget \
    gnupg \
    ca-certificates \
    software-properties-common \
    lsb-release \
    tzdata \
    && ln -fs /usr/share/zoneinfo/Europe/Lisbon /etc/localtime \
    && dpkg-reconfigure -f noninteractive tzdata \
    && rm -rf /var/lib/apt/lists/*

# Adiciona repositório do PHP 8.3
RUN add-apt-repository ppa:ondrej/php -y

# Adiciona repositório do Node.js corretamente
RUN curl -fsSL https://deb.nodesource.com/gpgkey/nodesource-repo.gpg.key | gpg --dearmor -o /etc/apt/keyrings/nodesource.gpg
RUN echo "deb [signed-by=/etc/apt/keyrings/nodesource.gpg] https://deb.nodesource.com/node_18.x nodistro main" | tee /etc/apt/sources.list.d/nodesource.list

# Atualiza repositórios
RUN apt-get update

# Instala PHP 8.3 (REMOVE php8.3-json que não existe mais)
RUN apt-get install -y --no-install-recommends \
    php8.3 \
    php8.3-cli \
    php8.3-common \
    php8.3-curl \
    php8.3-mbstring \
    php8.3-xml \
    php8.3-zip \
    php8.3-mysql \
    php8.3-sqlite3

# Instala Node.js
RUN apt-get install -y --no-install-recommends \
    nodejs

# Instala Python e ferramentas
RUN apt-get install -y --no-install-recommends \
    python3 \
    python3-pip \
    python3-venv \
    git \
    vim \
    nano \
    htop

# Limpa cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Instala Composer globalmente
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Instala JSON Server globalmente
RUN npm install -g json-server

# Cria diretórios de trabalho
RUN mkdir -p /app /data /config /logs

WORKDIR /app

# Copia apenas o requirements.txt para instalar dependências
COPY . .

# Instala dependências Python do requirements.txt
RUN pip3 install --no-cache-dir -r app/stockAI-backend/requirements.txt

EXPOSE 3000 8000 8080

# Default command (can be overridden in docker-compose)
CMD ["tail", "-f", "/dev/null"]
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

# Atualiza repositórios
RUN apt-get update

# Instala PHP 8.3
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

# Instala dependências Python comuns
RUN pip3 install --no-cache-dir flask django fastapi uvicorn

WORKDIR /app

COPY . /app

RUN pip install -r requirements.txt

CMD ["python3", "receipt/receipt_reader.py"]


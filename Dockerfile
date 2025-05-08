# 📦 Étape 1 : Base PHP officielle en CLI
FROM php:8.2-cli

# 🧱 Installation de l’extension PDO MySQL
RUN docker-php-ext-install pdo pdo_mysql

# 📁 Dossier de travail (là où tes .php seront copiés)
WORKDIR /app

# 🗂️ Copie tous les fichiers PHP dans le conteneur
COPY . .

# 🔓 Rend la variable DATABASE_URL visible (injectée par Railway)
ENV DATABASE_URL=${DATABASE_URL}

# 🚪 Railway utilise le port 8080
EXPOSE 8080

# 🏁 Lance le serveur PHP embarqué
CMD ["php", "-S", "0.0.0.0:8080", "-t", "."]

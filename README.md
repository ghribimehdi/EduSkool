# Cloner le dépôt (si ce n'est pas déjà fait)
git clone https://github.com/ghribimehdi/EduSkool.git
cd ton-depot

# Mettre à jour les dépendances avec Composer
composer install

# Appliquer les migrations de la base de données
php bin/console doctrine:migrations:migrate

# Démarrer le serveur Symfony
symfony serve

# Accéder au projet dans le navigateur à l'adresse suivante :
# https://127.0.0.1:8000/activity


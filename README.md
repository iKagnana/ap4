AP4 - BTS SIO 2 SLAM -  ITH Kagnana

Ce projet est développé en PHP.
Le but principal de cette application est de permettre à l'utilisateur de gérer les stocks de leur entreprise (GSB). On va également permettre à des clients de créer des comptes et de faire des commandes.

# Comment lancer le projet
Le projet a une configuration Docker, ce qui permet le lancement et le déploiement plus facile.

## Création de variable d'environnement

Dans ce projet, ajouter un .env à la racine.
Ceci est un exemple de valeur utilisée dans l'application. Remplacez les champs SQL par ceux de votre choix et l'hôte correspondant.
```
HOST = http://localhost:8089
MYSQL_ROOT_PASSWORD = pass
MYSQL_DATABASE = ap4
MYSQL_USER = user
MYSQL_PASSWORD = pass
```


Faites attention si jamais vous changez le nom du fichier .env, veuillez également faire le changement dans le fichier docker-compose.yml dans les champs env_file.

## Création et lancement du container

Pour créer et lancer le container avec Docker :
```bash
docker-compose up -d
```


## Ajouter des données à la base 
Si vous êtes en local, vous pouvez accéder à phpmyadmin : http://localhost:8080
Sinon avec votre hôte au port 8080.
Connectez vous avez les identifiants que vous avez renseigné dans votre dossier .env

Connectez vous de préférence avec le compte root pour faire l'importation du script scrpt-create.sql afin de pouvoir importer tous le contenu du script.

## Tester
Maintenant vous pouvez tester l'application web sur le port 8089.

### Utilisateurs test
Client :
email : d.jean@fidele.com
mdp : test
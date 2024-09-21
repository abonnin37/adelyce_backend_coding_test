# adelyce_backend_coding_test

## Sujet

Réalisation d’une application permettant de créer une liste de course alimentaire « partagée » : chaque utilisateur, lorsqu’il se connecte (s’il possède un compte) accède à sa liste de course à laquelle il peut ajouter des éléments et peut également partager un élément de cette liste avec un autre utilisateur en l’ajoutant à sa liste.

## Pré-requis

- Possibilité de créer un compte utilisateur et de se connecter avec son compte créé
- Possibilité d’ajouter, éditer, supprimer un élément à sa liste de course
- Possibilité de visualiser sa liste de course
- Possibilité de partager/annuler le partage d’un élément de sa liste de course avec un autre utilisateur,
- Possibilité de visualiser les éléments qu’un utilisateur a partagé avec vous en lecture uniquement (donner la possibilité d’identifier visuellement qu’il s’agit d’un élément partagé)

## Stack technique
- Back: Symfony (sans API Plateform)
- Front: Angular
- BDD: MariaDB

## Procédure d'installation et d'exécution du projet en local sur windows
1. Installer Docker Desktop.
2. Cloner le [projet git](https://github.com/abonnin37/adelyce_backend_coding_test) sur votre pc grâce à `git clone`.
3. Se positionner à la racine du projet et exécuter la commande `docker-compose up --build`.
4. Les conteneurs se lancent automatiquement après le build. Vous pouvez vous rendre à l'adresse suivante pour accéder à l'application web: `http://localhost:8080/`.
5. Vous pouvez vous rendre à l'adresse suivante pour accéder au panneau d'administration de la base de données.

## Erreurs commune lors du build
### Format des fichiers
Erreurs rencontrés : 
- `/usr/bin/env: 'bash\r': No such file or directory`
- `/usr/bin/env: use -[v]S to pass options in shebang lines`
- `exited with code 127`

Le projet se construit dans un conteneur Docker sous environnement Linux. Il y a certains fichiers qui doivent être basculés au format Line Separator "LF" : les fichiers .env et le fichier build.sh.
Relancez le commande `docker-compose up --build` après avoir fait ces modifications.
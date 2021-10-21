# Projet 7 - OpenClassrooms - BileMo
## _Parcours Développeur d'application - PHP / Symfony_


### Descriptif du besoin

Le premier client a enfin signé un contrat de partenariat avec BileMo ! C’est le branle-bas de combat pour répondre aux besoins de ce premier client qui va permettre de mettre en place l’ensemble des API et de les éprouver tout de suite.

Après une réunion dense avec le client, il a été identifié un certain nombre d’informations. Il doit être possible de :

-   consulter la liste des produits BileMo ;
-   consulter les détails d’un produit BileMo ;
-   consulter la liste des utilisateurs inscrits liés à un client sur le site web ;
-   consulter le détail d’un utilisateur inscrit lié à un client ;
-   ajouter un nouvel utilisateur lié à un client ;
-   supprimer un utilisateur ajouté par un client.

Seuls les clients référencés peuvent accéder aux API. Les clients de l’API doivent être authentifiés via OAuth ou JWT.

## Installer le Projet

- Clonez le Repo sur votre machine
- Rendez-vous dans l'invite de commande, puis dans le dossier du projet, lancez la commande
```sh
composer install
```
- Modifiez le fichier .env à la racine du projet afin d'entrer votre configuration (DATABASE_URL), vérifiez bien que les variables JWT_SECRET_KEY, JWT_PUBLIC_KEY et JWT_PASSPHRASE sont bien présentes (vous pouvez changer la passphrase si besoin)

- Lancez les commandes suivante :
```
php bin/console lexik:jwt:generate-keypair (doit retourner 'OK')
php bin/console d:d:c (creation de la db)
php bin/console d:m:m (Migrations)
php bin/console doctrine:fixtures:load (enregistrement des données de tests)
```

- Il ne vous restes plus qu'à lancer le serveur :

```
php bin/console server:run OU symfony serve
```

- Accédez à l'url 'localhost:8000/doc' afin de visualiser la documentation API

- ENJOY !
 
## Identifiants de l'utilisateur par défaut

MAIL :

> test@gmail.com

Mot de passe :

> test 


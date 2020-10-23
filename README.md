# Exercice d'aide au stage AJAX

*Exercice d'aide au stage sur AJAX. Vous devez fork ce dépôt pour travailler dessus.*

## Exercice

Nous allons réaliser un jeu du « plus ou moins ». Le joueur doit deviner un nombre mystère.  
Pour réaliser l’exercice, vous devez :
*	Avoir un serveur web, de type LAMP / WAMP / MAMP
*	Télécharger et placer les trois fichiers fournis dans le même répertoire à la racine de votre site local
*	Utiliser un IDE (Netbeans, Visual Studio Code ou autre)
*	**Compléter le fichier game.js pour atteindre le fonctionnement attendu**

## Fonctionnement attendu
Le joueur doit lui-même démarrer une partie en cliquant sur un bouton.
Une fois la partie démarrée, il saisit un nombre et clique sur un bouton pour tester.
Le jeu lui répond s’il est plus petit, plus grand ou égal, et affiche le nombre d’essais réalisés.
Si le joueur a trouvé le nombre mystère, la partie se finit automatiquement.
Le joueur peut aussi décider lui-même de finir la partie en cliquant sur un bouton.

## Fichiers fournis
Le fichier index.html contient la base HTML, à ne pas modifier.  
Le fichier api.php contient l’API PHP, à ne pas modifier.  
Le fichier game.js contient l’application JS, à modifier.  
Ce fichier contient déjà les méthodes utilitaires qui vont se lancer aux clics des différents boutons.  

__Notre objectif est de remplir les trous dans les TODO. Vous aurez à créer d’autres fonctions au besoin.__


Plusieurs fonctions sont déjà fournies, avec leur documentation. Vous aurez besoin d’effectuer des recherches sur les termes que vous ne comprenez pas.

## Documentation API
Ci-dessous est fournie la documentation API : une liste des appels AJAX que vous pouvez réaliser à api.php.
| Méthode | URI | Paramètres | Description | Retour |
| --- | --- | --- | --- | --- |
| GET | api.php | | Obtient le statut du jeu et le nombre d’essais. | « started » : booléen, si le jeu est démarré<br />« tries » : entier, nombre d’essais |
| PUT | api.php |  | Démarre une nouvelle partie.	| |
| DELETE | api.php |  | Termine la partie en cours. | |
| POST | api.php | nb | Teste un nombre nb. Si le nombre testé est plus grand que le nombre mystère, renvoie 1. S’il est plus petit, renvoie -1. S’il est égal, renvoie 0. | « mystery » : entier, si le nombre mystère a été trouvé<br />« result » : le résultat de la tentative (-1, 0 ou 1)<br />« tries » : entier, le nombre d’essais réalisés |


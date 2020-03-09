Présentation 
===

Plugin développé en partenariat avec la société eeSmart qui distribue les produits D2L.
Leur produit est un module ERL à brancher sur le compteur Linky. Ce module envoi de manière régulière sur un serveur donné (pour le moment : suivi.consospy) les données du compteur Linky.

Ce plugin permet, via l’API qu’eeSmart met à disposition, de récupérer les données présentes sur le site suivi.consospy afin de les suivre dans Jeedom.

Configuration
===

Une fois le plugin installé, vous devez :
- Aller dans la page Configuration du plugin et renseigner votre identifiant et votre mot de passe eeSmart
- Sauvegarder
- Raffraichir la page
Si les informations saisies sont correctes, le champ "clé API" doit apparaître.

Vous pouvez ensuite ajouter un équipement dans le menu habituel.
Votre seule action sera alors de sélectionner le module correspondant à celui que vous voulez analyser.
Vous pouvez mettre vos différents modules si vous en avez plusieurs.

L'équipement alors créé se mettra en forme au premier rafraichissement (cron de 5 minutes) pour n'afficher que les index correspondants à ceux liés au contrat.

Info
===
La puissance affichée est déterminée par calcul sur la base d'un courant à 230 Volts :
Puissance théorique : Intensité * 230V (P=UI)

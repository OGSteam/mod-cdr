## Module OGSpy Champs de Ruines (CDR)

Ce module permet de trier et visualiser par taille les champs de ruines (CDR) repérés sur le jeu via l'extension Xtense.

### Pré Requis ###

* Nécessite OGSpy >= 3.3.6 — [Dépôt OGSpy](https://github.com/ogsteam/ogspy)
* Modules OGspy requis : Xtense

### Fonctionnalités ###

* Affichage de la liste des champs de ruines détectés (métal, cristal, total, coordonnées, nombre de recycleurs nécessaires, date)
* Filtrage par galaxie
* Tri configurable (par coordonnées, total, métal, cristal ou date, croissant ou décroissant)
* Coloration des champs de ruines selon leur taille (configurable par l'utilisateur)
* Seuil de taille minimum paramétrable pour l'affichage
* Rétention des données configurable (en jours)

### Installation ###

Le module s'installe via le gestionnaire de modules d'OGSpy. Le fichier `install.php` crée automatiquement les tables nécessaires en base de données.

### Configuration ###

Chaque utilisateur peut personnaliser dans les options du module :

* Le seuil minimal de taille d'un CDR pour l'affichage
* Les seuils et couleurs de mise en évidence (petit, moyen, grand CDR)
* L'ordre de tri par défaut
* La galaxie affichée par défaut
* La durée de rétention des données (en jours)

### Pour nous contacter ###

* [Forum OGSteam](https://forum.ogsteam.eu) : Vous y trouverez notre équipe de support ainsi que l'invitation vers notre salon Discord
* Discord : https://discord.gg/Azcb67b

### Licence ###

GPL-2.0-only — voir [GNU General Public License](http://opensource.org/licenses/gpl-license.php)

### Historique des Versions ###

Version 1.9.4
- Mise à jour de compatibilité OGSpy 3.3.9

Version 1.9.0
- Ajout de la gestion de la rétention des données (durée configurable en jours)

Version 1.70
- Ajout du support multilingue (français / anglais)
- Ajout du tri de tableau côté client (tablesort)

Version 1.62
- Ajout du script de mise à jour (`update.php`)
- Amélioration du script d'installation

Version 1.60
- Intégration avec Xtense v2 (callbacks)
- Suppression préventive des doublons lors de la mise à jour des données

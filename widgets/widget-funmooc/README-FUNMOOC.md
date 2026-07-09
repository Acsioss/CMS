# Widget PHP pour FUN MOOC - Formations CNFPT

Widget PHP moderne et responsive pour afficher les formations depuis l'API FUN MOOC.

## 🎯 Aperçu

Ce widget permet d'afficher de manière élégante les formations disponibles sur la plateforme FUN MOOC, avec un focus particulier sur les formations du CNFPT (Centre national de la fonction publique territoriale).

### Données affichées

Pour chaque formation, le widget affiche :
- **Titre** : "Accompagner les transitions professionnelles des agents territoriaux"
- **Organisation** : "Centre national de la fonction publique territoriale (CNFPT)"
- **Code de formation** : "87058"
- **Statut** : "Ouvert à l'inscription"
- **Lien** : Vers la page de la formation sur FUN MOOC
- **Image de couverture** : Visuel de la formation
- **Durée et effort** : Informations pratiques
- **Gratuit** : Badge si la formation est gratuite

## 📁 Fichiers inclus

- `widget-funmooc.php` - Classe principale du widget
- `widget-funmooc-template.php` - Template HTML/CSS pour l'affichage
- `exemple-funmooc.php` - Exemples d'utilisation complets
- `README-FUNMOOC.md` - Cette documentation

## 🚀 Installation rapide

### 1. Téléchargement
Téléchargez les fichiers suivants dans le même répertoire :
- `widget-funmooc.php`
- `widget-funmooc-template.php`

### 2. Utilisation basique

```php
<?php
require_once 'widget-funmooc.php';

// Créer une instance pour le CNFPT (ID: 2809)
$widget = new FunMoocWidget('2809');

// Afficher le widget
$widget->render();
?>
```

C'est tout ! Le widget affichera automatiquement les formations disponibles.

## 💡 Exemples d'utilisation

### Exemple 1 : Configuration simple

```php
$widget = new FunMoocWidget('2809');
$widget->render([
    'titre' => 'Nos formations CNFPT',
    'limite' => 12,
]);
```

### Exemple 2 : Affichage compact (4 colonnes)

```php
$widget = new FunMoocWidget('2809');
$widget->render([
    'titre' => 'Catalogue complet',
    'limite' => 20,
    'colonnes' => 4,
    'afficher_code' => false,
    'afficher_duree' => false,
    'afficher_effort' => false,
]);
```

### Exemple 3 : Affichage mobile-first (1 colonne)

```php
$widget = new FunMoocWidget('2809');
$widget->render([
    'titre' => 'Formations à la une',
    'limite' => 6,
    'colonnes' => 1,
]);
```

### Exemple 4 : Récupération des données sans affichage

```php
$widget = new FunMoocWidget('2809');
$formations = $widget->fetchFormations(10);

foreach ($formations as $formation) {
    echo $formation['titre'] . " - " . $formation['statut'] . "\n";
}
```

### Exemple 5 : Avec pagination

```php
// Page 1 (formations 0-20)
$widget->render(['limite' => 20, 'offset' => 0]);

// Page 2 (formations 21-40)
$widget->render(['limite' => 20, 'offset' => 21]);
```

## ⚙️ Options de configuration

| Option | Type | Défaut | Description |
|--------|------|--------|-------------|
| `titre` | string | "Formations FUN MOOC" | Titre affiché en haut du widget |
| `limite` | int | 21 | Nombre maximum de formations à afficher |
| `offset` | int | 0 | Point de départ pour la pagination |
| `afficher_code` | bool | true | Afficher le code de formation |
| `afficher_duree` | bool | true | Afficher la durée de la formation |
| `afficher_effort` | bool | true | Afficher l'effort requis |
| `colonnes` | int | 3 | Nombre de colonnes (1, 2, 3 ou 4) |
| `classe_css` | string | "widget-funmooc" | Classe CSS personnalisée |

## 🏢 Changer d'organisation

Pour afficher les formations d'une autre organisation, modifiez l'ID lors de l'instanciation :

```php
// CNFPT (ID: 2809)
$widget = new FunMoocWidget('2809');

// Autre organisation
$widget = new FunMoocWidget('XXXX'); // Remplacez XXXX par l'ID souhaité
```

## 🔄 Gestion du cache

Le widget met automatiquement en cache les données pendant 1 heure pour optimiser les performances.

### Vider le cache manuellement

```php
$widget = new FunMoocWidget('2809');
$widget->clearCache();
```

### Modifier la durée du cache

Dans `widget-funmooc.php`, ligne 7 :

```php
private $cacheExpiration = 3600; // 1 heure (en secondes)

// Exemples :
// 30 minutes : 1800
// 2 heures : 7200
// 1 jour : 86400
// Désactiver : 0
```

## 📊 Structure des données

Chaque formation retournée contient :

```php
[
    'id' => '11185',
    'titre' => 'Accompagner les transitions professionnelles...',
    'code' => '87058',
    'organisation' => 'Centre national de la fonction publique territoriale (CNFPT)',
    'statut' => 'ouvert à l\'inscription',
    'call_to_action' => 's\'inscrire maintenant',
    'url' => 'https://www.fun-mooc.fr/fr/cours/...',
    'image' => 'https://...',
    'duree' => '3 semaines',
    'effort' => '9 heures',
    'introduction' => '...',
    'gratuit' => true,
    'state_priority' => 0 // 0 = ouvert, plus élevé = autres statuts
]
```

## 🎨 Personnalisation du style

### Méthode 1 : Modifier le template

Éditez directement `widget-funmooc-template.php` pour changer les couleurs, les polices, etc.

### Méthode 2 : Surcharger le CSS

Ajoutez vos propres styles après l'inclusion du widget :

```html
<style>
.widget-funmooc {
    background: #f0f0f0;
}

.formation-item {
    border: 2px solid #3498db;
}

.statut-badge.ouvert {
    background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
}
</style>
```

### Classes CSS disponibles

- `.widget-funmooc` - Container principal
- `.formations-grid` - Grille des formations
- `.formation-item` - Carte de formation individuelle
- `.formation-image` - Image de la formation
- `.formation-titre` - Titre de la formation
- `.formation-organisation` - Nom de l'organisation
- `.formation-code` - Code de la formation
- `.meta-badge` - Badges d'information (durée, effort)
- `.statut-badge` - Badge de statut
- `.statut-badge.ouvert` - Statut ouvert à l'inscription
- `.statut-badge.bientot` - Statut bientôt disponible
- `.statut-badge.archive` - Statut archivé

## 📱 Responsive Design

Le widget s'adapte automatiquement à tous les écrans :

- **Desktop (> 1200px)** : Nombre de colonnes configuré (par défaut 3)
- **Tablette (< 1200px)** : 2 colonnes automatiquement
- **Mobile (< 768px)** : 1 colonne automatiquement

## 🔧 Fonctionnalités avancées

### Tri automatique

Les formations sont automatiquement triées par priorité de statut :
1. Ouvertes à l'inscription (priority: 0)
2. Bientôt disponibles
3. Archivées

### Animation au chargement

Les cartes s'animent au chargement avec un effet de fondu progressif.

### Lazy loading des images

Les images sont chargées uniquement lorsqu'elles entrent dans le viewport, optimisant ainsi les performances.

### Gestion des erreurs

Le widget gère automatiquement :
- Erreurs de connexion à l'API
- Formations sans image
- Données manquantes
- Timeout des requêtes

## 🐛 Débogage

### Problème : Aucune formation ne s'affiche

**Solutions :**

1. Vérifiez que l'API est accessible :
```php
$widget = new FunMoocWidget('2809');
$formations = $widget->fetchFormations(5);
var_dump($formations);
```

2. Vérifiez les logs d'erreurs PHP
3. Testez l'URL directement : https://www.fun-mooc.fr/api/v1.0/courses/?limit=5&organizations=2809

### Problème : Les images ne s'affichent pas

**Solutions :**

1. Vérifiez que les URLs des images sont correctes
2. Vérifiez les en-têtes CORS si vous êtes en local
3. Les images peuvent être bloquées par certains bloqueurs de publicités

### Problème : Le cache ne se rafraîchit pas

**Solutions :**

```php
// Vider manuellement le cache
$widget = new FunMoocWidget('2809');
$widget->clearCache();

// Ou supprimer le fichier de cache
unlink('funmooc_cache_2809.json');
```

## 📋 Prérequis

- **PHP** : Version 7.0 ou supérieure
- **Extension cURL** : Activée sur le serveur
- **Permissions** : Écriture dans le répertoire pour le cache (optionnel)

### Vérifier cURL

```bash
php -m | grep curl
```

Si cURL n'est pas installé :
```bash
# Ubuntu/Debian
sudo apt-get install php-curl

# CentOS/RHEL
sudo yum install php-curl
```

## 🔐 Sécurité

Le widget implémente plusieurs mesures de sécurité :

- ✅ Échappement HTML avec `htmlspecialchars()`
- ✅ Validation des réponses API
- ✅ Gestion des erreurs cURL
- ✅ Timeout de 10 secondes sur les requêtes
- ✅ Vérification SSL des certificats

## 📈 Performance

Optimisations incluses :

- ✅ Mise en cache des données (1 heure par défaut)
- ✅ Lazy loading des images
- ✅ Animations CSS optimisées
- ✅ Timeout configuré sur les requêtes API

## 🆘 Support et assistance

### Ressources utiles

- **API FUN MOOC** : https://www.fun-mooc.fr/api/v1.0/
- **Documentation API** : Consultez directement l'API pour voir les champs disponibles

### Tester l'API directement

```bash
curl "https://www.fun-mooc.fr/api/v1.0/courses/?limit=5&organizations=2809"
```

### Questions fréquentes

**Q : Puis-je afficher plusieurs organisations ?**
R : Actuellement, le widget supporte une organisation à la fois. Pour en afficher plusieurs, créez plusieurs instances du widget.

**Q : Comment paginer les résultats ?**
R : Utilisez les paramètres `limite` et `offset` :
```php
// Page 1
$widget->render(['limite' => 20, 'offset' => 0]);
// Page 2
$widget->render(['limite' => 20, 'offset' => 20]);
```

**Q : Les formations sont-elles toujours à jour ?**
R : Le cache se rafraîchit automatiquement toutes les heures. Pour forcer une mise à jour, utilisez `clearCache()`.

## 📄 Licence

Libre d'utilisation et de modification.

## 🙏 Crédits

- **API** : FUN MOOC (France Université Numérique)
- **Organisation** : CNFPT (Centre national de la fonction publique territoriale)

---

**Widget créé pour l'API FUN MOOC**
URL de base : https://www.fun-mooc.fr/api/v1.0/courses/

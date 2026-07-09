<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formations CNFPT - FUN MOOC</title>
</head>
<body>

<?php
/**
 * DÉMO RAPIDE - Widget FUN MOOC
 * 
 * Ce fichier montre l'utilisation la plus simple du widget.
 * Uploadez simplement ce fichier avec widget-funmooc.php et 
 * widget-funmooc-template.php sur votre serveur et ça fonctionne !
 */

// Inclure le widget
require_once 'widget-funmooc.php';

// Créer et afficher le widget
$widget = new FunMoocWidget('2809'); // ID du CNFPT
$widget->render([
    'titre' => 'Formations CNFPT sur FUN MOOC',
    'limite' => 12,
    'colonnes' => 3,
]);

?>

<!-- 
═══════════════════════════════════════════════════════════════
PERSONNALISATION RAPIDE
═══════════════════════════════════════════════════════════════

Pour personnaliser, modifiez les options ci-dessus :

$widget->render([
    'titre' => 'Votre titre',           // Titre du widget
    'limite' => 12,                     // Nombre de formations
    'colonnes' => 3,                    // Colonnes : 1, 2, 3 ou 4
    'afficher_code' => true,            // Afficher le code
    'afficher_duree' => true,           // Afficher la durée
    'afficher_effort' => true,          // Afficher l'effort
]);

═══════════════════════════════════════════════════════════════
-->

</body>
</html>

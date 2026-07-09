<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Widget FUN MOOC - Exemples d'utilisation</title>
    <style>
        body {
            background: #f5f7fa;
            margin: 0;
            padding: 20px;
        }
        .section-divider {
            margin: 60px auto;
            max-width: 1400px;
            border-top: 2px solid #dee2e6;
        }
        .example-title {
            max-width: 1400px;
            margin: 40px auto 20px;
            padding: 0 20px;
        }
        .example-title h1 {
            color: #2c3e50;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }
        .code-block {
            max-width: 1400px;
            margin: 20px auto;
            padding: 20px;
            background: #2c3e50;
            color: #ecf0f1;
            border-radius: 8px;
            overflow-x: auto;
        }
        .code-block pre {
            margin: 0;
            font-family: 'Courier New', monospace;
            font-size: 14px;
        }
    </style>
</head>
<body>

<?php
// Inclure le widget
require_once 'widget-funmooc.php';

// ============================================
// EXEMPLE 1 : Utilisation basique
// ============================================
?>
<div class="example-title">
    <h1>📚 Exemple 1 : Utilisation basique (CNFPT)</h1>
    <p>Affichage simple des formations du CNFPT avec les paramètres par défaut.</p>
</div>

<div class="code-block">
<pre>
&lt;?php
require_once 'widget-funmooc.php';

$widget = new FunMoocWidget('2809'); // ID du CNFPT
$widget->render();
?&gt;
</pre>
</div>

<?php
$widget1 = new FunMoocWidget('2809');
$widget1->render();
?>

<div class="section-divider"></div>

<?php
// ============================================
// EXEMPLE 2 : Configuration personnalisée
// ============================================
?>
<div class="example-title">
    <h1>⚙️ Exemple 2 : Configuration personnalisée</h1>
    <p>Affichage limité à 6 formations avec 2 colonnes.</p>
</div>

<div class="code-block">
<pre>
&lt;?php
$widget = new FunMoocWidget('2809');
$widget->render([
    'titre' => 'Sélection de formations CNFPT',
    'limite' => 6,
    'colonnes' => 2,
    'afficher_code' => true,
    'afficher_duree' => true,
    'afficher_effort' => true,
]);
?&gt;
</pre>
</div>

<?php
$widget2 = new FunMoocWidget('2809');
$widget2->render([
    'titre' => 'Sélection de formations CNFPT',
    'limite' => 6,
    'colonnes' => 2,
    'afficher_code' => true,
    'afficher_duree' => true,
    'afficher_effort' => true,
]);
?>

<div class="section-divider"></div>

<?php
// ============================================
// EXEMPLE 3 : Affichage en 4 colonnes
// ============================================
?>
<div class="example-title">
    <h1>🎨 Exemple 3 : Grille 4 colonnes</h1>
    <p>Affichage compact avec 4 colonnes et informations minimales.</p>
</div>

<div class="code-block">
<pre>
&lt;?php
$widget = new FunMoocWidget('2809');
$widget->render([
    'titre' => 'Toutes nos formations',
    'limite' => 12,
    'colonnes' => 4,
    'afficher_code' => false,
    'afficher_duree' => false,
    'afficher_effort' => false,
]);
?&gt;
</pre>
</div>

<?php
$widget3 = new FunMoocWidget('2809');
$widget3->render([
    'titre' => 'Toutes nos formations',
    'limite' => 12,
    'colonnes' => 4,
    'afficher_code' => false,
    'afficher_duree' => false,
    'afficher_effort' => false,
]);
?>

<div class="section-divider"></div>

<?php
// ============================================
// EXEMPLE 4 : Récupération des données brutes
// ============================================
?>
<div class="example-title">
    <h1>🔧 Exemple 4 : Utilisation des données brutes</h1>
    <p>Récupération et traitement personnalisé des données.</p>
</div>

<div class="code-block">
<pre>
&lt;?php
$widget = new FunMoocWidget('2809');
$formations = $widget->fetchFormations(5);

echo "&lt;h3&gt;Formations disponibles :&lt;/h3&gt;";
echo "&lt;ul&gt;";
foreach ($formations as $formation) {
    echo "&lt;li&gt;";
    echo "&lt;strong&gt;" . htmlspecialchars($formation['titre']) . "&lt;/strong&gt; - ";
    echo htmlspecialchars($formation['statut']);
    echo "&lt;/li&gt;";
}
echo "&lt;/ul&gt;";
?&gt;
</pre>
</div>

<div style="max-width: 1400px; margin: 20px auto; padding: 20px; background: white; border-radius: 8px;">
<?php
$widget4 = new FunMoocWidget('2809');
$formations = $widget4->fetchFormations(5);

echo "<h3>Formations disponibles :</h3>";
echo "<ul style='line-height: 1.8;'>";
foreach ($formations as $formation) {
    echo "<li>";
    echo "<strong>" . htmlspecialchars($formation['titre']) . "</strong> - ";
    echo htmlspecialchars($formation['statut']);
    if (!empty($formation['code'])) {
        echo " (Code: " . htmlspecialchars($formation['code']) . ")";
    }
    echo "</li>";
}
echo "</ul>";
?>
</div>

<div class="section-divider"></div>

<?php
// ============================================
// EXEMPLE 5 : Informations détaillées
// ============================================
?>
<div class="example-title">
    <h1>📊 Exemple 5 : Analyse des données</h1>
</div>

<div style="max-width: 1400px; margin: 20px auto; padding: 30px; background: white; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
<?php
$widget5 = new FunMoocWidget('2809');
$toutesFormations = $widget5->fetchFormations(100); // Récupérer plus de formations

$stats = [
    'total' => count($toutesFormations),
    'gratuites' => 0,
    'ouvertes' => 0,
];

$statuts = [];

foreach ($toutesFormations as $formation) {
    if ($formation['gratuit']) {
        $stats['gratuites']++;
    }
    
    if (stripos($formation['statut'], 'inscription') !== false) {
        $stats['ouvertes']++;
    }
    
    $statut = $formation['statut'];
    if (!isset($statuts[$statut])) {
        $statuts[$statut] = 0;
    }
    $statuts[$statut]++;
}

echo "<h3>📈 Statistiques des formations CNFPT</h3>";
echo "<div style='display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin: 20px 0;'>";

echo "<div style='background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 20px; border-radius: 8px;'>";
echo "<div style='font-size: 2.5em; font-weight: bold;'>" . $stats['total'] . "</div>";
echo "<div>Formations au total</div>";
echo "</div>";

echo "<div style='background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white; padding: 20px; border-radius: 8px;'>";
echo "<div style='font-size: 2.5em; font-weight: bold;'>" . $stats['gratuites'] . "</div>";
echo "<div>Formations gratuites</div>";
echo "</div>";

echo "<div style='background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white; padding: 20px; border-radius: 8px;'>";
echo "<div style='font-size: 2.5em; font-weight: bold;'>" . $stats['ouvertes'] . "</div>";
echo "<div>Ouvertes à l'inscription</div>";
echo "</div>";

echo "</div>";

echo "<h4>📋 Répartition par statut</h4>";
echo "<ul style='line-height: 1.8;'>";
arsort($statuts);
foreach ($statuts as $statut => $count) {
    $pourcentage = round(($count / $stats['total']) * 100, 1);
    echo "<li><strong>" . htmlspecialchars($statut) . "</strong> : " . $count . " formations (" . $pourcentage . "%)</li>";
}
echo "</ul>";
?>
</div>

<!-- Documentation -->
<div style="max-width: 1400px; margin: 60px auto 40px; padding: 30px; background: #fff; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
    <h2 style="color: #2c3e50; border-bottom: 3px solid #3498db; padding-bottom: 10px;">📖 Documentation complète</h2>
    
    <h3>🚀 Installation</h3>
    <ol style="line-height: 1.8;">
        <li>Télécharger <code>widget-funmooc.php</code> et <code>widget-funmooc-template.php</code></li>
        <li>Placer les fichiers dans votre projet PHP</li>
        <li>Inclure le widget : <code>require_once 'widget-funmooc.php';</code></li>
    </ol>
    
    <h3>⚙️ Options disponibles</h3>
    <table style="width: 100%; border-collapse: collapse; margin: 20px 0;">
        <thead>
            <tr style="background: #f8f9fa;">
                <th style="padding: 12px; text-align: left; border: 1px solid #dee2e6;">Option</th>
                <th style="padding: 12px; text-align: left; border: 1px solid #dee2e6;">Type</th>
                <th style="padding: 12px; text-align: left; border: 1px solid #dee2e6;">Défaut</th>
                <th style="padding: 12px; text-align: left; border: 1px solid #dee2e6;">Description</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="padding: 12px; border: 1px solid #dee2e6;"><code>titre</code></td>
                <td style="padding: 12px; border: 1px solid #dee2e6;">string</td>
                <td style="padding: 12px; border: 1px solid #dee2e6;">"Formations FUN MOOC"</td>
                <td style="padding: 12px; border: 1px solid #dee2e6;">Titre du widget</td>
            </tr>
            <tr style="background: #f8f9fa;">
                <td style="padding: 12px; border: 1px solid #dee2e6;"><code>limite</code></td>
                <td style="padding: 12px; border: 1px solid #dee2e6;">int</td>
                <td style="padding: 12px; border: 1px solid #dee2e6;">21</td>
                <td style="padding: 12px; border: 1px solid #dee2e6;">Nombre de formations à afficher</td>
            </tr>
            <tr>
                <td style="padding: 12px; border: 1px solid #dee2e6;"><code>offset</code></td>
                <td style="padding: 12px; border: 1px solid #dee2e6;">int</td>
                <td style="padding: 12px; border: 1px solid #dee2e6;">0</td>
                <td style="padding: 12px; border: 1px solid #dee2e6;">Décalage pour la pagination</td>
            </tr>
            <tr style="background: #f8f9fa;">
                <td style="padding: 12px; border: 1px solid #dee2e6;"><code>afficher_code</code></td>
                <td style="padding: 12px; border: 1px solid #dee2e6;">bool</td>
                <td style="padding: 12px; border: 1px solid #dee2e6;">true</td>
                <td style="padding: 12px; border: 1px solid #dee2e6;">Afficher le code de formation</td>
            </tr>
            <tr>
                <td style="padding: 12px; border: 1px solid #dee2e6;"><code>afficher_duree</code></td>
                <td style="padding: 12px; border: 1px solid #dee2e6;">bool</td>
                <td style="padding: 12px; border: 1px solid #dee2e6;">true</td>
                <td style="padding: 12px; border: 1px solid #dee2e6;">Afficher la durée</td>
            </tr>
            <tr style="background: #f8f9fa;">
                <td style="padding: 12px; border: 1px solid #dee2e6;"><code>afficher_effort</code></td>
                <td style="padding: 12px; border: 1px solid #dee2e6;">bool</td>
                <td style="padding: 12px; border: 1px solid #dee2e6;">true</td>
                <td style="padding: 12px; border: 1px solid #dee2e6;">Afficher l'effort requis</td>
            </tr>
            <tr>
                <td style="padding: 12px; border: 1px solid #dee2e6;"><code>colonnes</code></td>
                <td style="padding: 12px; border: 1px solid #dee2e6;">int</td>
                <td style="padding: 12px; border: 1px solid #dee2e6;">3</td>
                <td style="padding: 12px; border: 1px solid #dee2e6;">Nombre de colonnes (1-4)</td>
            </tr>
            <tr style="background: #f8f9fa;">
                <td style="padding: 12px; border: 1px solid #dee2e6;"><code>classe_css</code></td>
                <td style="padding: 12px; border: 1px solid #dee2e6;">string</td>
                <td style="padding: 12px; border: 1px solid #dee2e6;">"widget-funmooc"</td>
                <td style="padding: 12px; border: 1px solid #dee2e6;">Classe CSS personnalisée</td>
            </tr>
        </tbody>
    </table>
    
    <h3>🏢 Organisations disponibles</h3>
    <p>Vous pouvez modifier l'ID d'organisation lors de l'instanciation :</p>
    <pre style="background: #2c3e50; color: #ecf0f1; padding: 15px; border-radius: 4px; overflow-x: auto;">
// CNFPT (par défaut)
$widget = new FunMoocWidget('2809');

// Autre organisation (remplacer par l'ID souhaité)
$widget = new FunMoocWidget('XXXX');
    </pre>
    
    <h3>🗑️ Gestion du cache</h3>
    <p>Pour vider le cache manuellement :</p>
    <pre style="background: #2c3e50; color: #ecf0f1; padding: 15px; border-radius: 4px; overflow-x: auto;">
$widget = new FunMoocWidget('2809');
$widget->clearCache();
    </pre>
    
    <h3>💾 Données retournées</h3>
    <p>Structure d'une formation :</p>
    <pre style="background: #2c3e50; color: #ecf0f1; padding: 15px; border-radius: 4px; overflow-x: auto;">
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
    'gratuit' => true,
    'state_priority' => 0
]
    </pre>
    
    <h3>🎨 Personnalisation du style</h3>
    <p>Le widget utilise des classes CSS que vous pouvez surcharger :</p>
    <ul style="line-height: 1.8;">
        <li><code>.widget-funmooc</code> - Container principal</li>
        <li><code>.formations-grid</code> - Grille des formations</li>
        <li><code>.formation-item</code> - Carte individuelle</li>
        <li><code>.formation-titre</code> - Titre de la formation</li>
        <li><code>.statut-badge</code> - Badge de statut</li>
    </ul>
    
    <h3>📱 Responsive</h3>
    <p>Le widget s'adapte automatiquement :</p>
    <ul style="line-height: 1.8;">
        <li><strong>Desktop</strong> : Affichage selon le nombre de colonnes configuré</li>
        <li><strong>Tablette</strong> (< 1200px) : 2 colonnes</li>
        <li><strong>Mobile</strong> (< 768px) : 1 colonne</li>
    </ul>
</div>

</body>
</html>

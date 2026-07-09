<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        .<?php echo $config['classe_css']; ?> {
            max-width: 1400px;
            margin: 0 auto;
            padding: 20px;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
        }
        
        .widget-funmooc h2 {
            color: #1a1a1a;
            font-size: 2em;
            margin-bottom: 30px;
            font-weight: 700;
        }
        
        .formations-grid {
            display: grid;
            grid-template-columns: repeat(<?php echo $config['colonnes']; ?>, 1fr);
            gap: 25px;
            margin-bottom: 30px;
        }
        
        .formation-item {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
            height: 100%;
        }
        
        .formation-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0,0,0,0.15);
        }
        
        .formation-image-container {
            width: 100%;
            height: 180px;
            overflow: hidden;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            position: relative;
        }
        
        .formation-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }
        
        .formation-item:hover .formation-image {
            transform: scale(1.05);
        }
        
        .formation-no-image {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 3em;
        }
        
        .formation-content {
            padding: 20px;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }
        
        .formation-header {
            margin-bottom: 15px;
        }
        
        .formation-titre {
            font-size: 1.1em;
            font-weight: 600;
            color: #1a1a1a;
            margin-bottom: 8px;
            line-height: 1.4;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        
        .formation-organisation {
            color: #666;
            font-size: 0.85em;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        .formation-code {
            display: inline-block;
            background: #f0f0f0;
            color: #555;
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 0.75em;
            font-family: monospace;
            margin-bottom: 10px;
        }
        
        .formation-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-bottom: 15px;
        }
        
        .meta-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 4px 10px;
            background: #f8f9fa;
            color: #495057;
            border-radius: 6px;
            font-size: 0.8em;
        }
        
        .meta-badge.gratuit {
            background: #d4edda;
            color: #155724;
        }
        
        .formation-statut {
            margin-top: auto;
            padding-top: 15px;
            border-top: 1px solid #e9ecef;
        }
        
        .statut-badge {
            display: inline-block;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 0.85em;
            font-weight: 600;
            text-align: center;
            width: 100%;
        }
        
        .statut-badge.ouvert {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        
        .statut-badge.bientot {
            background: #fff3cd;
            color: #856404;
        }
        
        .statut-badge.archive {
            background: #e9ecef;
            color: #6c757d;
        }
        
        .formation-link {
            text-decoration: none;
            color: inherit;
            display: block;
            height: 100%;
        }
        
        .aucune-formation {
            text-align: center;
            padding: 60px 20px;
            color: #6c757d;
        }
        
        .aucune-formation-icon {
            font-size: 4em;
            margin-bottom: 20px;
        }
        
        /* Responsive */
        @media (max-width: 1200px) {
            .formations-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        
        @media (max-width: 768px) {
            .formations-grid {
                grid-template-columns: 1fr;
            }
            
            .formation-image-container {
                height: 200px;
            }
        }
        
        /* Animation au chargement */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .formation-item {
            animation: fadeInUp 0.5s ease-out;
        }
        
        .formation-item:nth-child(1) { animation-delay: 0.05s; }
        .formation-item:nth-child(2) { animation-delay: 0.1s; }
        .formation-item:nth-child(3) { animation-delay: 0.15s; }
        .formation-item:nth-child(4) { animation-delay: 0.2s; }
        .formation-item:nth-child(5) { animation-delay: 0.25s; }
        .formation-item:nth-child(6) { animation-delay: 0.3s; }
    </style>
</head>
<body>
    <div class="<?php echo htmlspecialchars($config['classe_css']); ?>">
        <h2><?php echo htmlspecialchars($config['titre']); ?></h2>
        
        <?php if (empty($formations)): ?>
            <div class="aucune-formation">
                <div class="aucune-formation-icon">📚</div>
                <p><strong>Aucune formation disponible pour le moment.</strong></p>
                <p style="color: #adb5bd; font-size: 0.9em;">Revenez bientôt pour découvrir de nouvelles formations.</p>
            </div>
        <?php else: ?>
            <div class="formations-grid">
                <?php foreach ($formations as $formation): ?>
                    <div class="formation-item">
                        <a href="<?php echo htmlspecialchars($formation['url']); ?>" 
                           target="_blank" 
                           class="formation-link"
                           title="<?php echo htmlspecialchars($formation['titre']); ?>">
                            
                            <div class="formation-image-container">
                                <?php if (!empty($formation['image'])): ?>
                                    <img src="<?php echo htmlspecialchars($formation['image']); ?>" 
                                         alt="<?php echo htmlspecialchars($formation['titre']); ?>"
                                         class="formation-image"
                                         loading="lazy">
                                <?php else: ?>
                                    <div class="formation-no-image">📚</div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="formation-content">
                                <div class="formation-header">
                                    <h3 class="formation-titre">
                                        <?php echo htmlspecialchars($formation['titre']); ?>
                                    </h3>
                                    
                                    <div class="formation-organisation">
                                        <span>🏛️</span>
                                        <span><?php echo htmlspecialchars($formation['organisation']); ?></span>
                                    </div>
                                    
                                    <?php if ($config['afficher_code'] && !empty($formation['code'])): ?>
                                        <div class="formation-code">
                                            Code: <?php echo htmlspecialchars($formation['code']); ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="formation-meta">
                                    <?php if ($config['afficher_duree'] && !empty($formation['duree'])): ?>
                                        <span class="meta-badge">
                                            ⏱️ <?php echo htmlspecialchars($formation['duree']); ?>
                                        </span>
                                    <?php endif; ?>
                                    
                                    <?php if ($config['afficher_effort'] && !empty($formation['effort'])): ?>
                                        <span class="meta-badge">
                                            📊 <?php echo htmlspecialchars($formation['effort']); ?>
                                        </span>
                                    <?php endif; ?>
                                    
                                    <?php if ($formation['gratuit']): ?>
                                        <span class="meta-badge gratuit">
                                            ✓ Gratuit
                                        </span>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="formation-statut">
                                    <?php 
                                    $statutClass = 'ouvert';
                                    $statut = strtolower($formation['statut']);
                                    
                                    if (strpos($statut, 'archiv') !== false || strpos($statut, 'termin') !== false) {
                                        $statutClass = 'archive';
                                    } elseif (strpos($statut, 'bientôt') !== false || strpos($statut, 'prochainement') !== false) {
                                        $statutClass = 'bientot';
                                    }
                                    ?>
                                    <span class="statut-badge <?php echo $statutClass; ?>">
                                        <?php echo htmlspecialchars(ucfirst($formation['statut'])); ?>
                                    </span>
                                </div>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>

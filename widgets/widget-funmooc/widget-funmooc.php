<?php
/**
 * Widget PHP pour lister les formations FUN MOOC
 * API: https://www.fun-mooc.fr/api/v1.0/courses/
 */

class FunMoocWidget {
    private $apiUrl = 'https://www.fun-mooc.fr/api/v1.0/courses/';
    private $organizationId = '2809'; // CNFPT par défaut
    private $cacheFile = 'funmooc_cache.json';
    private $cacheExpiration = 3600; // 1 heure
    
    /**
     * Constructeur
     */
    public function __construct($organizationId = '2809') {
        $this->organizationId = $organizationId;
        $this->cacheFile = 'funmooc_cache_' . $organizationId . '.json';
    }
    
    /**
     * Récupère les formations depuis l'API FUN MOOC
     */
    public function fetchFormations($limit = 21, $offset = 0) {
        // Vérifier le cache
        if ($this->isCacheValid()) {
            return $this->getFromCache();
        }
        
        $url = $this->apiUrl . '?limit=' . $limit . '&offset=' . $offset . '&organizations=' . $this->organizationId;
        
        $response = $this->callAPI($url);
        
        if ($response && isset($response['objects'])) {
            $formations = $this->processFormations($response['objects']);
            $this->saveToCache($formations);
            return $formations;
        }
        
        return [];
    }
    
    /**
     * Appel à l'API REST
     */
    private function callAPI($url) {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Accept: application/json',
        ]);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);
        
        if ($error) {
            error_log("Erreur cURL FUN MOOC: " . $error);
            return null;
        }
        
        if ($httpCode !== 200) {
            error_log("Erreur HTTP FUN MOOC: " . $httpCode);
            return null;
        }
        
        return json_decode($response, true);
    }
    
    /**
     * Traite et structure les données des formations
     */
    private function processFormations($objects) {
        $formations = [];
        
        foreach ($objects as $course) {
            // Extraire l'image de couverture
            $coverImage = '';
            if (isset($course['cover_image']['src'])) {
                $coverImage = $course['cover_image']['src'];
            } elseif (isset($course['cover_image']['srcset'])) {
                // Extraire la première image du srcset
                $srcset = $course['cover_image']['srcset'];
                preg_match('/https?:\/\/[^\s]+/', $srcset, $matches);
                if (!empty($matches)) {
                    $coverImage = $matches[0];
                }
            }
            
            // Construire l'URL absolue
            $baseUrl = 'https://www.fun-mooc.fr';
            $absoluteUrl = $baseUrl . ($course['absolute_url'] ?? '');
            
            // Statut de la formation
            $statut = $course['state']['text'] ?? 'Non disponible';
            $callToAction = $course['state']['call_to_action'] ?? '';
            
            // Organisation
            $organisation = $course['organization_highlighted'] ?? 'Organisation non spécifiée';
            
            $formations[] = [
                'id' => $course['id'] ?? '',
                'titre' => $course['title'] ?? 'Sans titre',
                'code' => $course['code'] ?? '',
                'organisation' => $organisation,
                'statut' => $statut,
                'call_to_action' => $callToAction,
                'url' => $absoluteUrl,
                'image' => $coverImage,
                'duree' => $course['duration'] ?? '',
                'effort' => $course['effort'] ?? '',
                'introduction' => $course['introduction'] ?? '',
                'gratuit' => ($course['offer'] ?? '') === 'free',
                'state_priority' => $course['state']['priority'] ?? 99,
            ];
        }
        
        // Trier par priorité de statut (0 = ouvert, plus c'est bas, plus c'est prioritaire)
        usort($formations, function($a, $b) {
            return $a['state_priority'] <=> $b['state_priority'];
        });
        
        return $formations;
    }
    
    /**
     * Vérifie si le cache est valide
     */
    private function isCacheValid() {
        if (!file_exists($this->cacheFile)) {
            return false;
        }
        
        $cacheTime = filemtime($this->cacheFile);
        return (time() - $cacheTime) < $this->cacheExpiration;
    }
    
    /**
     * Récupère les données du cache
     */
    private function getFromCache() {
        $content = file_get_contents($this->cacheFile);
        return json_decode($content, true);
    }
    
    /**
     * Sauvegarde les données en cache
     */
    private function saveToCache($data) {
        file_put_contents($this->cacheFile, json_encode($data));
    }
    
    /**
     * Vide le cache
     */
    public function clearCache() {
        if (file_exists($this->cacheFile)) {
            unlink($this->cacheFile);
        }
    }
    
    /**
     * Affiche le widget HTML
     */
    public function render($options = []) {
        // Options par défaut
        $defaults = [
            'titre' => 'Formations FUN MOOC',
            'limite' => 21,
            'offset' => 0,
            'afficher_code' => true,
            'afficher_duree' => true,
            'afficher_effort' => true,
            'classe_css' => 'widget-funmooc',
            'colonnes' => 3, // Nombre de colonnes (1, 2, 3, ou 4)
        ];
        
        $config = array_merge($defaults, $options);
        
        $formations = $this->fetchFormations($config['limite'], $config['offset']);
        
        include 'widget-funmooc-template.php';
    }
}
?>

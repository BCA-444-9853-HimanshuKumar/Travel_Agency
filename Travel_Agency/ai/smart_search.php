<?php
// AI-Powered Smart Search and Filtering System
class SmartSearchEngine {
    private $packages;
    private $searchIndex = [];
    
    public function __construct() {
        global $con;
        
        // Load packages from database
        $result = mysqli_query($con, "SELECT * FROM packages");
        $this->packages = [];
        
        while ($row = mysqli_result_fetch_assoc($result)) {
            $this->packages[$row['id']] = $row;
            $this->buildSearchIndex($row['id'], $row);
        }
    }
    
    private function buildSearchIndex($packageId, $package) {
        $index = [
            'id' => $packageId,
            'name' => strtolower($package['name']),
            'description' => strtolower($package['description']),
            'duration' => strtolower($package['duration']),
            'price' => $package['price'],
            'keywords' => $this->extractKeywords($package),
            'categories' => $this->categorizePackage($package),
            'features' => $this->extractFeatures($package),
            'seasons' => $this->inferSeasons($package),
            'activities' => $this->inferActivities($package)
        ];
        
        $this->searchIndex[$packageId] = $index;
    }
    
    private function extractKeywords($package) {
        $text = strtolower($package['name'] . ' ' . $package['description']);
        $keywords = [];
        
        // Travel-related keywords
        $travelKeywords = [
            'beach', 'mountain', 'city', 'island', 'resort', 'hotel', 'flight', 'tour', 'trip',
            'vacation', 'holiday', 'adventure', 'luxury', 'romantic', 'family', 'culture',
            'food', 'shopping', 'sightseeing', 'nature', 'wildlife', 'history', 'museum',
            'temple', 'palace', 'garden', 'park', 'cruise', 'diving', 'snorkeling', 'spa'
        ];
        
        foreach ($travelKeywords as $keyword) {
            if (strpos($text, $keyword) !== false) {
                $keywords[] = $keyword;
            }
        }
        
        return array_unique($keywords);
    }
    
    private function categorizePackage($package) {
        $categories = [];
        $text = strtolower($package['name'] . ' ' . $package['description']);
        
        if (strpos($text, 'luxury') !== false || strpos($text, 'premium') !== false || $package['price'] > 1500) {
            $categories[] = 'luxury';
        }
        
        if (strpos($text, 'adventure') !== false || strpos($text, 'thrill') !== false) {
            $categories[] = 'adventure';
        }
        
        if (strpos($text, 'romantic') !== false || strpos($text, 'couple') !== false || strpos($text, 'love') !== false) {
            $categories[] = 'romantic';
        }
        
        if (strpos($text, 'family') !== false || strpos($text, 'kid') !== false || strpos($text, 'children') !== false) {
            $categories[] = 'family';
        }
        
        if (strpos($text, 'beach') !== false || strpos($text, 'island') !== false || strpos($text, 'sea') !== false) {
            $categories[] = 'beach';
        }
        
        if (strpos($text, 'culture') !== false || strpos($text, 'museum') !== false || strpos($text, 'history') !== false) {
            $categories[] = 'cultural';
        }
        
        return $categories;
    }
    
    private function extractFeatures($package) {
        $features = [];
        $text = strtolower($package['description']);
        
        // Accommodation features
        if (strpos($text, 'resort') !== false) $features[] = 'resort';
        if (strpos($text, 'hotel') !== false) $features[] = 'hotel';
        if (strpos($text, 'villa') !== false) $features[] = 'villa';
        if (strpos($text, 'bungalow') !== false) $features[] = 'bungalow';
        
        // Activity features
        if (strpos($text, 'diving') !== false || strpos($text, 'snorkeling') !== false) $features[] = 'water_sports';
        if (strpos($text, 'spa') !== false) $features[] = 'spa';
        if (strpos($text, 'shopping') !== false) $features[] = 'shopping';
        if (strpos($text, 'guide') !== false) $features[] = 'guided_tour';
        
        // Transportation features
        if (strpos($text, 'flight') !== false) $features[] = 'flights_included';
        if (strpos($text, 'cruise') !== false) $features[] = 'cruise';
        
        return array_unique($features);
    }
    
    private function inferSeasons($package) {
        $seasons = [];
        $text = strtolower($package['description']);
        
        // Season-specific keywords
        if (strpos($text, 'summer') !== false || strpos($text, 'sun') !== false || strpos($text, 'beach') !== false) {
            $seasons[] = 'summer';
        }
        
        if (strpos($text, 'winter') !== false || strpos($text, 'snow') !== false || strpos($text, 'ski') !== false) {
            $seasons[] = 'winter';
        }
        
        if (strpos($text, 'spring') !== false || strpos($text, 'bloom') !== false || strpos($text, 'flower') !== false) {
            $seasons[] = 'spring';
        }
        
        if (strpos($text, 'fall') !== false || strpos($text, 'autumn') !== false || strpos($text, 'harvest') !== false) {
            $seasons[] = 'fall';
        }
        
        // Default to all seasons if no specific season mentioned
        if (empty($seasons)) {
            $seasons = ['summer', 'winter', 'spring', 'fall'];
        }
        
        return $seasons;
    }
    
    private function inferActivities($package) {
        $activities = [];
        $text = strtolower($package['description']);
        
        $activityMap = [
            'sightseeing' => ['sightseeing', 'tour', 'explore', 'visit'],
            'beach' => ['beach', 'sea', 'ocean', 'swim', 'sunbathe'],
            'adventure' => ['adventure', 'hike', 'trek', 'climb', 'expedition'],
            'culture' => ['culture', 'museum', 'temple', 'history', 'heritage'],
            'shopping' => ['shopping', 'mall', 'market', 'buy', 'shop'],
            'dining' => ['food', 'dining', 'restaurant', 'cuisine', 'meal'],
            'relaxation' => ['relax', 'spa', 'massage', 'rest', 'peaceful'],
            'nightlife' => ['nightlife', 'party', 'club', 'entertainment'],
            'nature' => ['nature', 'wildlife', 'forest', 'park', 'garden']
        ];
        
        foreach ($activityMap as $activity => $keywords) {
            foreach ($keywords as $keyword) {
                if (strpos($text, $keyword) !== false) {
                    $activities[] = $activity;
                    break;
                }
            }
        }
        
        return array_unique($activities);
    }
    
    public function smartSearch($query, $filters = []) {
        $query = strtolower(trim($query));
        $results = [];
        
        foreach ($this->searchIndex as $packageId => $index) {
            $score = 0;
            $matchReasons = [];
            
            // Text matching
            if (!empty($query)) {
                $textScore = $this->calculateTextScore($query, $index);
                $score += $textScore['score'];
                $matchReasons = array_merge($matchReasons, $textScore['reasons']);
            }
            
            // Filter matching
            $filterScore = $this->calculateFilterScore($filters, $index);
            $score += $filterScore['score'];
            $matchReasons = array_merge($matchReasons, $filterScore['reasons']);
            
            if ($score > 0) {
                $results[] = [
                    'package' => $this->packages[$packageId],
                    'score' => $score,
                    'match_reasons' => array_unique($matchReasons)
                ];
            }
        }
        
        // Sort by score (highest first)
        usort($results, function($a, $b) {
            return $b['score'] <=> $a['score'];
        });
        
        return $results;
    }
    
    private function calculateTextScore($query, $index) {
        $score = 0;
        $reasons = [];
        
        // Exact name match
        if (strpos($index['name'], $query) !== false) {
            $score += 100;
            $reasons[] = 'Exact name match';
        }
        
        // Partial name match
        $nameWords = explode(' ', $index['name']);
        foreach ($nameWords as $word) {
            if (strpos($word, $query) !== false || strpos($query, $word) !== false) {
                $score += 50;
                $reasons[] = 'Partial name match';
                break;
            }
        }
        
        // Keyword matching
        foreach ($index['keywords'] as $keyword) {
            if (strpos($keyword, $query) !== false) {
                $score += 30;
                $reasons[] = "Keyword match: $keyword";
            }
        }
        
        // Category matching
        foreach ($index['categories'] as $category) {
            if (strpos($category, $query) !== false) {
                $score += 25;
                $reasons[] = "Category match: $category";
            }
        }
        
        // Description matching
        if (strpos($index['description'], $query) !== false) {
            $score += 15;
            $reasons[] = 'Description match';
        }
        
        // Feature matching
        foreach ($index['features'] as $feature) {
            if (strpos($feature, $query) !== false) {
                $score += 20;
                $reasons[] = "Feature match: $feature";
            }
        }
        
        return ['score' => $score, 'reasons' => $reasons];
    }
    
    private function calculateFilterScore($filters, $index) {
        $score = 0;
        $reasons = [];
        
        // Price filter
        if (isset($filters['price_range'])) {
            $minPrice = $filters['price_range']['min'] ?? 0;
            $maxPrice = $filters['price_range']['max'] ?? PHP_INT_MAX;
            
            if ($index['price'] >= $minPrice && $index['price'] <= $maxPrice) {
                $score += 20;
                $reasons[] = 'Price within range';
            }
        }
        
        // Duration filter
        if (isset($filters['duration'])) {
            $durationDays = $this->extractDurationDays($index['duration']);
            if ($durationDays >= $filters['duration']['min'] && $durationDays <= $filters['duration']['max']) {
                $score += 15;
                $reasons[] = 'Duration matches';
            }
        }
        
        // Category filter
        if (isset($filters['categories'])) {
            foreach ($filters['categories'] as $category) {
                if (in_array($category, $index['categories'])) {
                    $score += 25;
                    $reasons[] = "Category filter: $category";
                }
            }
        }
        
        // Season filter
        if (isset($filters['season'])) {
            if (in_array($filters['season'], $index['seasons'])) {
                $score += 10;
                $reasons[] = 'Season appropriate';
            }
        }
        
        // Activity filter
        if (isset($filters['activities'])) {
            foreach ($filters['activities'] as $activity) {
                if (in_array($activity, $index['activities'])) {
                    $score += 15;
                    $reasons[] = "Activity available: $activity";
                }
            }
        }
        
        // Feature filter
        if (isset($filters['features'])) {
            foreach ($filters['features'] as $feature) {
                if (in_array($feature, $index['features'])) {
                    $score += 20;
                    $reasons[] = "Feature included: $feature";
                }
            }
        }
        
        return ['score' => $score, 'reasons' => $reasons];
    }
    
    private function extractDurationDays($duration) {
        // Extract number of days from duration string like "3 Days 2 Nights"
        if (preg_match('/(\d+)\s*days?/i', $duration, $matches)) {
            return (int)$matches[1];
        }
        return 3; // Default
    }
    
    public function getSearchSuggestions($query) {
        $suggestions = [];
        $query = strtolower(trim($query));
        
        if (strlen($query) < 2) {
            return $suggestions;
        }
        
        // Category suggestions
        $categories = ['luxury', 'adventure', 'romantic', 'family', 'beach', 'cultural'];
        foreach ($categories as $category) {
            if (strpos($category, $query) === 0) {
                $suggestions[] = [
                    'type' => 'category',
                    'text' => $category,
                    'description' => "Search for $category packages"
                ];
            }
        }
        
        // Activity suggestions
        $activities = ['sightseeing', 'beach', 'adventure', 'culture', 'shopping', 'dining', 'relaxation'];
        foreach ($activities as $activity) {
            if (strpos($activity, $query) === 0) {
                $suggestions[] = [
                    'type' => 'activity',
                    'text' => $activity,
                    'description' => "Packages with $activity activities"
                ];
            }
        }
        
        // Feature suggestions
        $features = ['spa', 'resort', 'water_sports', 'guided_tour', 'flights_included'];
        foreach ($features as $feature) {
            if (strpos($feature, $query) === 0) {
                $suggestions[] = [
                    'type' => 'feature',
                    'text' => $feature,
                    'description' => "Packages with $feature"
                ];
            }
        }
        
        return array_slice($suggestions, 0, 5);
    }
    
    public function getPopularFilters() {
        return [
            'categories' => [
                ['value' => 'luxury', 'label' => 'Luxury', 'count' => 2],
                ['value' => 'adventure', 'label' => 'Adventure', 'count' => 3],
                ['value' => 'romantic', 'label' => 'Romantic', 'count' => 2],
                ['value' => 'beach', 'label' => 'Beach', 'count' => 3],
                ['value' => 'cultural', 'label' => 'Cultural', 'count' => 2]
            ],
            'activities' => [
                ['value' => 'sightseeing', 'label' => 'Sightseeing', 'count' => 4],
                ['value' => 'beach', 'label' => 'Beach Activities', 'count' => 3],
                ['value' => 'shopping', 'label' => 'Shopping', 'count' => 3],
                ['value' => 'culture', 'label' => 'Cultural Activities', 'count' => 2],
                ['value' => 'dining', 'label' => 'Dining', 'count' => 4]
            ],
            'features' => [
                ['value' => 'flights_included', 'label' => 'Flights Included', 'count' => 5],
                ['value' => 'resort', 'label' => 'Resort Stay', 'count' => 3],
                ['value' => 'guided_tour', 'label' => 'Guided Tours', 'count' => 4],
                ['value' => 'spa', 'label' => 'Spa Services', 'count' => 2]
            ],
            'price_ranges' => [
                ['min' => 0, 'max' => 1000, 'label' => 'Under Rs. 1,000'],
                ['min' => 1000, 'max' => 1500, 'label' => 'Rs. 1,000 - 1,500'],
                ['min' => 1500, 'max' => 2000, 'label' => 'Rs. 1,500 - 2,000'],
                ['min' => 2000, 'max' => 9999, 'label' => 'Above Rs. 2,000']
            ],
            'durations' => [
                ['min' => 1, 'max' => 3, 'label' => '1-3 Days'],
                ['min' => 4, 'max' => 5, 'label' => '4-5 Days'],
                ['min' => 6, 'max' => 7, 'label' => '6-7 Days'],
                ['min' => 8, 'max' => 30, 'label' => '8+ Days']
            ]
        ];
    }
}

// Handle API requests
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $searchEngine = new SmartSearchEngine();
    
    if ($_POST['action'] === 'search') {
        $query = $_POST['query'] ?? '';
        $filters = json_decode($_POST['filters'] ?? '{}', true);
        $results = $searchEngine->smartSearch($query, $filters);
        
        header('Content-Type: application/json');
        echo json_encode(['results' => $results]);
        exit;
    }
    
    if ($_POST['action'] === 'suggestions') {
        $query = $_POST['query'] ?? '';
        $suggestions = $searchEngine->getSearchSuggestions($query);
        
        header('Content-Type: application/json');
        echo json_encode(['suggestions' => $suggestions]);
        exit;
    }
    
    if ($_POST['action'] === 'popular_filters') {
        $filters = $searchEngine->getPopularFilters();
        
        header('Content-Type: application/json');
        echo json_encode(['filters' => $filters]);
        exit;
    }
}
?>

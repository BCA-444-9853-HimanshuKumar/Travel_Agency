<?php
// AI Trip Recommendation Engine
class AIRecommendationEngine {
    private $preferences = [
        'budget' => ['low', 'medium', 'high'],
        'travel_style' => ['adventure', 'luxury', 'romantic', 'cultural', 'relaxation', 'family'],
        'duration' => ['short' => 3, 'medium' => 5, 'long' => 7],
        'season' => ['summer', 'winter', 'spring', 'fall'],
        'group_size' => ['solo', 'couple', 'family', 'friends']
    ];
    
    private $packages = [
        'paris' => [
            'name' => 'Paris Getaway',
            'price' => 1299.99,
            'duration' => 3,
            'category' => 'romantic',
            'seasons' => ['spring', 'summer', 'fall'],
            'group_types' => ['couple', 'solo'],
            'activities' => ['sightseeing', 'culture', 'romance', 'food'],
            'budget_level' => 'medium'
        ],
        'bali' => [
            'name' => 'Bali Adventure',
            'price' => 899.99,
            'duration' => 5,
            'category' => 'adventure',
            'seasons' => ['summer', 'spring'],
            'group_types' => ['friends', 'couple', 'solo'],
            'activities' => ['beach', 'adventure', 'culture', 'nature'],
            'budget_level' => 'low'
        ],
        'dubai' => [
            'name' => 'Dubai Luxury',
            'price' => 1599.99,
            'duration' => 4,
            'category' => 'luxury',
            'seasons' => ['winter', 'fall', 'spring'],
            'group_types' => ['couple', 'family', 'friends'],
            'activities' => ['shopping', 'luxury', 'adventure', 'modern'],
            'budget_level' => 'high'
        ],
        'tokyo' => [
            'name' => 'Tokyo Explorer',
            'price' => 1199.99,
            'duration' => 6,
            'category' => 'cultural',
            'seasons' => ['spring', 'fall'],
            'group_types' => ['solo', 'friends', 'couple'],
            'activities' => ['culture', 'technology', 'food', 'sightseeing'],
            'budget_level' => 'medium'
        ],
        'maldives' => [
            'name' => 'Maldives Paradise',
            'price' => 1999.99,
            'duration' => 5,
            'category' => 'relaxation',
            'seasons' => ['winter', 'summer'],
            'group_types' => ['couple', 'family'],
            'activities' => ['beach', 'relaxation', 'luxury', 'nature'],
            'budget_level' => 'high'
        ]
    ];
    
    public function getPersonalizedRecommendations($userProfile) {
        $scores = [];
        
        foreach ($this->packages as $key => $package) {
            $scores[$key] = $this->calculatePackageScore($package, $userProfile);
        }
        
        // Sort by score (highest first)
        arsort($scores);
        
        $recommendations = [];
        foreach ($scores as $packageKey => $score) {
            if ($score > 0) {
                $package = $this->packages[$packageKey];
                $package['score'] = $score;
                $package['match_reasons'] = $this->getMatchReasons($package, $userProfile);
                $recommendations[] = $package;
            }
        }
        
        return array_slice($recommendations, 0, 3); // Top 3 recommendations
    }
    
    private function calculatePackageScore($package, $userProfile) {
        $score = 0;
        $maxScore = 100;
        
        // Budget matching (30 points)
        if (isset($userProfile['budget'])) {
            if ($userProfile['budget'] === $package['budget_level']) {
                $score += 30;
            } elseif ($this->isBudgetCompatible($userProfile['budget'], $package['budget_level'])) {
                $score += 15;
            }
        }
        
        // Travel style matching (25 points)
        if (isset($userProfile['travel_style'])) {
            if ($userProfile['travel_style'] === $package['category']) {
                $score += 25;
            } elseif ($this->isStyleCompatible($userProfile['travel_style'], $package['category'])) {
                $score += 10;
            }
        }
        
        // Duration matching (20 points)
        if (isset($userProfile['duration'])) {
            $userDuration = $this->preferences['duration'][$userProfile['duration']] ?? 5;
            $durationDiff = abs($userDuration - $package['duration']);
            if ($durationDiff === 0) {
                $score += 20;
            } elseif ($durationDiff <= 2) {
                $score += 10;
            }
        }
        
        // Season matching (15 points)
        if (isset($userProfile['season'])) {
            if (in_array($userProfile['season'], $package['seasons'])) {
                $score += 15;
            }
        }
        
        // Group size matching (10 points)
        if (isset($userProfile['group_size'])) {
            if (in_array($userProfile['group_size'], $package['group_types'])) {
                $score += 10;
            }
        }
        
        return $score;
    }
    
    private function isBudgetCompatible($userBudget, $packageBudget) {
        $compatibility = [
            'low' => ['low', 'medium'],
            'medium' => ['low', 'medium', 'high'],
            'high' => ['medium', 'high']
        ];
        
        return in_array($packageBudget, $compatibility[$userBudget] ?? []);
    }
    
    private function isStyleCompatible($userStyle, $packageCategory) {
        $compatibility = [
            'adventure' => ['adventure', 'cultural'],
            'luxury' => ['luxury', 'relaxation', 'romantic'],
            'romantic' => ['romantic', 'luxury', 'relaxation'],
            'cultural' => ['cultural', 'adventure'],
            'relaxation' => ['relaxation', 'luxury', 'romantic'],
            'family' => ['relaxation', 'adventure', 'cultural']
        ];
        
        return in_array($packageCategory, $compatibility[$userStyle] ?? []);
    }
    
    private function getMatchReasons($package, $userProfile) {
        $reasons = [];
        
        if (isset($userProfile['budget']) && $userProfile['budget'] === $package['budget_level']) {
            $reasons[] = "Perfect match for your {$userProfile['budget']} budget";
        }
        
        if (isset($userProfile['travel_style']) && $userProfile['travel_style'] === $package['category']) {
            $reasons[] = "Ideal for {$userProfile['travel_style']} travelers";
        }
        
        if (isset($userProfile['season']) && in_array($userProfile['season'], $package['seasons'])) {
            $reasons[] = "Great choice for {$userProfile['season']} season";
        }
        
        if (isset($userProfile['group_size']) && in_array($userProfile['group_size'], $package['group_types'])) {
            $reasons[] = "Perfect for {$userProfile['group_size']} travel";
        }
        
        return $reasons;
    }
    
    public function getTrendingDestinations() {
        $trending = [
            'bali' => [
                'trend_score' => 95,
                'reason' => 'Perfect summer destination with amazing beaches and culture',
                'popularity' => 'Very High'
            ],
            'paris' => [
                'trend_score' => 88,
                'reason' => 'Romantic spring destination with blooming gardens',
                'popularity' => 'High'
            ],
            'dubai' => [
                'trend_score' => 82,
                'reason' => 'Luxury winter escape with modern attractions',
                'popularity' => 'High'
            ],
            'tokyo' => [
                'trend_score' => 78,
                'reason' => 'Cherry blossoms and cultural experiences',
                'popularity' => 'Medium'
            ],
            'maldives' => [
                'trend_score' => 75,
                'reason' => 'Tropical paradise for relaxation seekers',
                'popularity' => 'Medium'
            ]
        ];
        
        return $trending;
    }
    
    public function getPricePredictions($destination, $monthsAhead = 3) {
        $basePrice = $this->packages[$destination]['price'] ?? 1000;
        $predictions = [];
        
        for ($i = 1; $i <= $monthsAhead; $i++) {
            $seasonality = $this->getSeasonalityFactor($destination, $i);
            $demand = $this->getDemandFactor($destination, $i);
            $predictedPrice = $basePrice * $seasonality * $demand;
            
            $predictions[] = [
                'month' => date('F', strtotime("+$i months")),
                'predicted_price' => round($predictedPrice, 2),
                'trend' => $predictedPrice > $basePrice ? 'increasing' : 'decreasing',
                'confidence' => rand(75, 95) . '%'
            ];
        }
        
        return $predictions;
    }
    
    private function getSeasonalityFactor($destination, $monthsAhead) {
        $seasonFactors = [
            'paris' => [1.2, 1.3, 1.4, 1.1, 0.9, 0.8, 0.9, 1.0, 1.1, 1.2, 1.3, 1.2],
            'bali' => [1.1, 1.2, 1.3, 1.4, 1.3, 1.1, 0.9, 0.8, 0.9, 1.0, 1.1, 1.1],
            'dubai' => [0.9, 1.0, 1.1, 1.2, 1.3, 1.4, 1.3, 1.2, 1.1, 1.0, 0.9, 0.9],
            'tokyo' => [1.0, 1.1, 1.3, 1.4, 1.3, 1.1, 0.9, 0.8, 0.9, 1.0, 1.1, 1.2],
            'maldives' => [1.2, 1.3, 1.2, 1.1, 0.9, 0.8, 0.8, 0.9, 1.0, 1.1, 1.2, 1.3]
        ];
        
        $currentMonth = (int)date('n') - 1;
        $targetMonth = ($currentMonth + $monthsAhead - 1) % 12;
        
        return $seasonFactors[$destination][$targetMonth] ?? 1.0;
    }
    
    private function getDemandFactor($destination, $monthsAhead) {
        // Simulate demand patterns
        $baseDemand = 1.0;
        $randomFactor = 0.9 + (rand(0, 20) / 100); // 0.9 to 1.1
        
        return $baseDemand * $randomFactor;
    }
    
    public function generateTravelItinerary($destination, $duration, $preferences = []) {
        $itineraries = [
            'paris' => [
                'day1' => 'Arrival in Paris, check-in at hotel, evening Seine River cruise',
                'day2' => 'Eiffel Tower visit, Louvre Museum, Champs-Élysées shopping',
                'day3' => 'Versailles Palace day trip, Montmartre district exploration',
                'extra' => ['Notre-Dame visit', 'French cooking class', 'Wine tasting tour']
            ],
            'bali' => [
                'day1' => 'Arrival in Bali, beach relaxation, traditional Balinese dinner',
                'day2' => 'Ubud rice terraces, Monkey Forest, traditional art market',
                'day3' => 'Temple tour, water sports at Tanjung Benoa, sunset at Uluwatu',
                'extra' => ['Snorkeling trip', 'Balinese massage', 'Cooking class']
            ],
            'dubai' => [
                'day1' => 'Arrival in Dubai, Dubai Mall visit, Burj Khalifa observation deck',
                'day2' => 'Desert safari with dune bashing, camel ride, BBQ dinner',
                'day3' => 'Dubai Marina cruise, Gold Souk shopping, Dubai Fountain show',
                'extra' => ['Skydiving', 'Dubai Aquarium', 'Museum of the Future']
            ],
            'tokyo' => [
                'day1' => 'Arrival in Tokyo, Shibuya crossing, teamLab Borderless museum',
                'day2' => 'Tokyo Tower, Imperial Palace, Akihabara electronics district',
                'day3' => 'Mount Fuji day trip, traditional tea ceremony, Ginza shopping',
                'extra' => ['Sumo wrestling', 'Robot Restaurant', 'Tsukiji fish market']
            ],
            'maldives' => [
                'day1' => 'Arrival in Maldives, speedboat transfer to resort, beach relaxation',
                'day2' => 'Snorkeling adventure, dolphin watching, spa treatment',
                'day3' => 'Island hopping, underwater restaurant, sunset cruise',
                'extra' => ['Scuba diving', 'Fishing trip', 'Water sports']
            ]
        ];
        
        return $itineraries[$destination] ?? [];
    }
}

// Handle API requests
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $engine = new AIRecommendationEngine();
    
    if ($_POST['action'] === 'recommend') {
        $userProfile = json_decode($_POST['profile'] ?? '{}', true);
        $recommendations = $engine->getPersonalizedRecommendations($userProfile);
        
        header('Content-Type: application/json');
        echo json_encode(['recommendations' => $recommendations]);
        exit;
    }
    
    if ($_POST['action'] === 'trending') {
        $trending = $engine->getTrendingDestinations();
        
        header('Content-Type: application/json');
        echo json_encode(['trending' => $trending]);
        exit;
    }
    
    if ($_POST['action'] === 'price_prediction') {
        $destination = $_POST['destination'] ?? 'paris';
        $predictions = $engine->getPricePredictions($destination);
        
        header('Content-Type: application/json');
        echo json_encode(['predictions' => $predictions]);
        exit;
    }
    
    if ($_POST['action'] === 'itinerary') {
        $destination = $_POST['destination'] ?? 'paris';
        $duration = $_POST['duration'] ?? 3;
        $itinerary = $engine->generateTravelItinerary($destination, $duration);
        
        header('Content-Type: application/json');
        echo json_encode(['itinerary' => $itinerary]);
        exit;
    }
}
?>

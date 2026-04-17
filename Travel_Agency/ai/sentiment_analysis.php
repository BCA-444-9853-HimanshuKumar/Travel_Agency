<?php
// AI Sentiment Analysis System for Reviews
class SentimentAnalyzer {
    private $positiveWords = [
        'excellent', 'amazing', 'wonderful', 'fantastic', 'great', 'good', 'beautiful', 'perfect', 
        'love', 'loved', 'awesome', 'brilliant', 'outstanding', 'superb', 'magnificent', 'spectacular',
        'incredible', 'unforgettable', 'memorable', 'enjoyable', 'pleasurable', 'satisfying',
        'comfortable', 'luxurious', 'delicious', 'friendly', 'helpful', 'professional', 'organized',
        'smooth', 'easy', 'convenient', 'recommend', 'highly recommend', 'worth it', 'value',
        'stunning', 'breathtaking', 'gorgeous', 'paradise', 'heaven', 'dream', 'magical'
    ];
    
    private $negativeWords = [
        'terrible', 'awful', 'horrible', 'bad', 'poor', 'disappointing', 'worst', 'hate', 'hated',
        'disgusting', 'disaster', 'nightmare', 'waste', 'useless', 'pathetic', 'annoying',
        'frustrating', 'difficult', 'complicated', 'confusing', 'unprofessional', 'rude',
        'unfriendly', 'unhelpful', 'dirty', 'uncomfortable', 'expensive', 'overpriced',
        'cheap', 'broken', 'damaged', 'late', 'delayed', 'cancelled', 'lost', 'stolen',
        'scam', 'fraud', 'cheat', 'lie', 'misleading', 'false', 'fake', 'unrealistic'
    ];
    
    private $neutralWords = [
        'okay', 'fine', 'average', 'normal', 'standard', 'typical', 'regular', 'ordinary',
        'acceptable', 'decent', 'reasonable', 'fair', 'moderate', 'medium', 'sufficient',
        'adequate', 'passable', 'so-so', 'alright', 'not bad', 'could be better'
    ];
    
    private $aspectKeywords = [
        'service' => ['service', 'staff', 'team', 'crew', 'guide', 'support', 'help', 'assistance'],
        'accommodation' => ['hotel', 'room', 'bed', 'resort', 'stay', 'accommodation', 'lodging'],
        'food' => ['food', 'meal', 'dining', 'restaurant', 'breakfast', 'lunch', 'dinner', 'cuisine'],
        'transportation' => ['flight', 'transport', 'travel', 'transfer', 'car', 'bus', 'vehicle'],
        'activities' => ['activities', 'tours', 'excursions', 'sightseeing', 'adventure', 'experience'],
        'value' => ['price', 'cost', 'value', 'money', 'budget', 'affordable', 'expensive'],
        'location' => ['location', 'destination', 'place', 'spot', 'area', 'beach', 'city']
    ];
    
    public function analyzeSentiment($text) {
        $text = strtolower($text);
        $words = $this->tokenize($text);
        
        $positiveScore = 0;
        $negativeScore = 0;
        $neutralScore = 0;
        $totalWords = count($words);
        
        foreach ($words as $word) {
            if (in_array($word, $this->positiveWords)) {
                $positiveScore++;
            } elseif (in_array($word, $this->negativeWords)) {
                $negativeScore++;
            } elseif (in_array($word, $this->neutralWords)) {
                $neutralScore++;
            }
        }
        
        // Calculate sentiment scores
        $sentimentScore = ($positiveScore - $negativeScore) / max($totalWords, 1);
        
        // Determine sentiment category
        if ($sentimentScore > 0.1) {
            $sentiment = 'positive';
        } elseif ($sentimentScore < -0.1) {
            $sentiment = 'negative';
        } else {
            $sentiment = 'neutral';
        }
        
        // Calculate confidence
        $confidence = min(abs($sentimentScore) * 2, 1.0);
        
        return [
            'sentiment' => $sentiment,
            'score' => round($sentimentScore, 3),
            'confidence' => round($confidence, 3),
            'positive_words' => $positiveScore,
            'negative_words' => $negativeScore,
            'neutral_words' => $neutralScore,
            'total_words' => $totalWords
        ];
    }
    
    public function analyzeAspects($text) {
        $text = strtolower($text);
        $aspects = [];
        
        foreach ($this->aspectKeywords as $aspect => $keywords) {
            $aspectScore = 0;
            $aspectWords = [];
            
            foreach ($keywords as $keyword) {
                if (strpos($text, $keyword) !== false) {
                    $aspectScore++;
                    $aspectWords[] = $keyword;
                }
            }
            
            if ($aspectScore > 0) {
                $aspects[$aspect] = [
                    'score' => $aspectScore,
                    'keywords' => $aspectWords,
                    'sentiment' => $this->getAspectSentiment($text, $keywords)
                ];
            }
        }
        
        return $aspects;
    }
    
    private function getAspectSentiment($text, $aspectKeywords) {
        $aspectSentiment = ['positive' => 0, 'negative' => 0, 'neutral' => 0];
        
        foreach ($aspectKeywords as $keyword) {
            $context = $this->getContext($text, $keyword);
            $sentiment = $this->analyzeSentiment($context);
            $aspectSentiment[$sentiment['sentiment']]++;
        }
        
        // Determine dominant sentiment for this aspect
        $maxScore = max($aspectSentiment);
        $dominantSentiment = array_search($maxScore, $aspectSentiment);
        
        return [
            'dominant' => $dominantSentiment,
            'scores' => $aspectSentiment
        ];
    }
    
    private function getContext($text, $keyword, $window = 5) {
        $words = $this->tokenize($text);
        $keywordIndex = array_search($keyword, $words);
        
        if ($keywordIndex === false) {
            return '';
        }
        
        $start = max(0, $keywordIndex - $window);
        $end = min(count($words), $keywordIndex + $window + 1);
        
        return implode(' ', array_slice($words, $start, $end));
    }
    
    private function tokenize($text) {
        // Remove punctuation and split into words
        $text = preg_replace('/[^\w\s]/', ' ', $text);
        return array_filter(explode(' ', $text));
    }
    
    public function generateSummary($reviews) {
        $totalReviews = count($reviews);
        $sentimentCounts = ['positive' => 0, 'negative' => 0, 'neutral' => 0];
        $aspectCounts = [];
        $commonPhrases = [];
        
        foreach ($reviews as $review) {
            $sentiment = $this->analyzeSentiment($review['text']);
            $sentimentCounts[$sentiment['sentiment']]++;
            
            $aspects = $this->analyzeAspects($review['text']);
            foreach ($aspects as $aspect => $data) {
                if (!isset($aspectCounts[$aspect])) {
                    $aspectCounts[$aspect] = ['positive' => 0, 'negative' => 0, 'neutral' => 0];
                }
                $aspectCounts[$aspect][$data['sentiment']['dominant']]++;
            }
            
            // Extract common phrases
            $phrases = $this->extractPhrases($review['text']);
            $commonPhrases = array_merge($commonPhrases, $phrases);
        }
        
        // Calculate percentages
        $sentimentPercentages = [];
        foreach ($sentimentCounts as $sentiment => $count) {
            $sentimentPercentages[$sentiment] = round(($count / $totalReviews) * 100, 1);
        }
        
        // Find most common phrases
        $phraseCounts = array_count_values($commonPhrases);
        arsort($phraseCounts);
        $topPhrases = array_slice($phraseCounts, 0, 5, true);
        
        return [
            'total_reviews' => $totalReviews,
            'sentiment_distribution' => $sentimentPercentages,
            'aspect_analysis' => $aspectCounts,
            'top_phrases' => $topPhrases,
            'overall_sentiment' => $this->getOverallSentiment($sentimentPercentages)
        ];
    }
    
    private function extractPhrases($text, $minLength = 3) {
        $phrases = [];
        $sentences = preg_split('/[.!?]+/', $text);
        
        foreach ($sentences as $sentence) {
            $sentence = trim($sentence);
            if (strlen($sentence) >= $minLength) {
                $phrases[] = strtolower($sentence);
            }
        }
        
        return $phrases;
    }
    
    private function getOverallSentiment($percentages) {
        if ($percentages['positive'] > 60) {
            return 'Very Positive';
        } elseif ($percentages['positive'] > 40) {
            return 'Positive';
        } elseif ($percentages['negative'] > 40) {
            return 'Negative';
        } elseif ($percentages['negative'] > 60) {
            return 'Very Negative';
        } else {
            return 'Mixed';
        }
    }
    
    public function getRecommendations($sentimentData) {
        $recommendations = [];
        
        // Service recommendations
        if (isset($sentimentData['aspect_analysis']['service'])) {
            $serviceData = $sentimentData['aspect_analysis']['service'];
            if ($serviceData['negative'] > $serviceData['positive']) {
                $recommendations[] = 'Improve staff training and customer service';
            } elseif ($serviceData['positive'] > $serviceData['negative'] * 2) {
                $recommendations[] = 'Maintain excellent service standards';
            }
        }
        
        // Value recommendations
        if (isset($sentimentData['aspect_analysis']['value'])) {
            $valueData = $sentimentData['aspect_analysis']['value'];
            if ($valueData['negative'] > $valueData['positive']) {
                $recommendations[] = 'Review pricing strategy and offer better value packages';
            }
        }
        
        // Accommodation recommendations
        if (isset($sentimentData['aspect_analysis']['accommodation'])) {
            $accommodationData = $sentimentData['aspect_analysis']['accommodation'];
            if ($accommodationData['negative'] > $accommodationData['positive']) {
                $recommendations[] = 'Upgrade accommodation quality and amenities';
            }
        }
        
        // Overall sentiment recommendations
        if ($sentimentData['overall_sentiment'] === 'Very Negative' || $sentimentData['overall_sentiment'] === 'Negative') {
            $recommendations[] = 'Urgent: Address major customer concerns immediately';
            $recommendations[] = 'Implement comprehensive service improvement plan';
        } elseif ($sentimentData['overall_sentiment'] === 'Very Positive') {
            $recommendations[] = 'Leverage positive reviews in marketing campaigns';
            $recommendations[] = 'Create loyalty programs for satisfied customers';
        }
        
        return $recommendations;
    }
}

// Handle API requests
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $analyzer = new SentimentAnalyzer();
    
    if ($_POST['action'] === 'analyze') {
        $text = $_POST['text'] ?? '';
        $analysis = $analyzer->analyzeSentiment($text);
        $aspects = $analyzer->analyzeAspects($text);
        
        header('Content-Type: application/json');
        echo json_encode([
            'sentiment' => $analysis,
            'aspects' => $aspects
        ]);
        exit;
    }
    
    if ($_POST['action'] === 'batch_analyze') {
        $reviews = json_decode($_POST['reviews'] ?? '[]', true);
        $summary = $analyzer->generateSummary($reviews);
        $recommendations = $analyzer->getRecommendations($summary);
        
        header('Content-Type: application/json');
        echo json_encode([
            'summary' => $summary,
            'recommendations' => $recommendations
        ]);
        exit;
    }
}
?>

<?php
// AI Chatbot System for Travel Agency
class TravelChatbot {
    private $responses = [
        'greeting' => [
            'Hello! Welcome to Travel Agency AI Assistant! How can I help you plan your dream vacation today?',
            'Hi there! I\'m your AI travel assistant. What kind of adventure are you looking for?',
            'Greetings! Ready to explore amazing destinations? I\'m here to help!'
        ],
        'packages' => [
            'We offer amazing packages to Paris, Bali, Dubai, Tokyo, and Maldives. Each package includes flights, accommodation, and guided tours. Which destination interests you?',
            'Our premium packages include: Paris Getaway (3 days), Bali Adventure (5 days), Dubai Luxury (4 days), Tokyo Explorer (6 days), and Maldives Paradise (5 days). Would you like details about any of these?',
            'I can help you choose from our curated packages. We have romantic getaways, adventure trips, luxury experiences, and beach paradises. What\'s your travel style?'
        ],
        'booking' => [
            'To book a package, simply visit our booking page, select your preferred package, choose travel dates, number of travelers, and complete the payment. The whole process takes just 5 minutes!',
            'Booking is easy! Go to our booking section, pick your destination, select dates, and we\'ll handle the rest. Need help with the booking process?',
            'You can book online 24/7. Just choose your package, fill in your details, and pay securely. I can guide you through each step if needed!'
        ],
        'pricing' => [
            'Our packages range from Rs. 899.99 to Rs. 1999.99. Bali Adventure starts at Rs. 899.99, Paris Getaway at Rs. 1299.99, Dubai Luxury at Rs. 1599.99, Tokyo Explorer at Rs. 1199.99, and Maldives Paradise at Rs. 1999.99.',
            'Prices vary by destination and duration. Budget-friendly options like Bali (Rs. 899.99) to luxury experiences like Maldives (Rs. 1999.99). All packages include flights, hotels, and activities!',
            'We have packages for every budget! Starting from Rs. 899.99 for Bali Adventure up to Rs. 1999.99 for Maldives Paradise. Which price range works for you?'
        ],
        'payment' => [
            'We accept all major payment methods: Credit/Debit cards, UPI, and Cash. Our payment gateway is secure and encrypted. You can pay in full or choose EMI options.',
            'Payment is simple and secure! We accept cards, UPI, and cash. All transactions are protected with SSL encryption. Your payment information is completely safe.',
            'Flexible payment options available! Pay by card, UPI, or cash. We also offer EMI plans for larger packages. All payments are 100% secure.'
        ],
        'support' => [
            'Our support team is available Mon-Fri 9AM-6PM and Sat 10AM-4PM. Call us at +1 (555) 123-4567 or email support@travelagency.com. I\'m also here 24/7 to help!',
            'Need human assistance? Our team is ready to help! Call +1 (555) 123-4567 during business hours or email anytime. For instant help, I\'m always here!',
            'Support options: Phone (Mon-Fri 9AM-6PM, Sat 10AM-4PM), Email (24/7), or chat with me anytime! What do you need help with?'
        ],
        'cancellation' => [
            'You can cancel up to 48 hours before travel for a full refund. Between 48-24 hours, there\'s a 25% cancellation fee. Within 24 hours, no refund. Need to cancel a booking?',
            'Our cancellation policy is flexible! Full refund if cancelled 48+ hours before travel. 25% fee for 24-48 hours. No refund within 24 hours. I can help you cancel if needed.',
            'Cancellation is easy! Full refund with 48+ hours notice. Partial refund (75%) with 24-48 hours notice. No refund within 24 hours. Would you like to cancel a booking?'
        ],
        'recommendation' => [
            'Based on your interests, I recommend our Bali Adventure package for amazing beaches and culture, or Paris Getaway for romantic experiences. What type of vacation do you prefer?',
            'For first-time travelers, I suggest Bali Adventure - great value and beautiful. For luxury seekers, Dubai Luxury or Maldives Paradise. What\'s your budget and travel style?',
            'Let me recommend the perfect package! If you love beaches: Bali or Maldives. For culture: Paris or Tokyo. For luxury: Dubai. What interests you most?'
        ],
        'default' => [
            'I\'m here to help with your travel needs! I can assist with packages, bookings, pricing, payments, and general travel advice. What would you like to know?',
            'I can help you plan your perfect vacation! Ask me about destinations, packages, booking process, or any travel-related questions.',
            'Let me assist you with your travel plans! I have information about all our packages, booking process, and can provide personalized recommendations.'
        ]
    ];
    
    private $patterns = [
        'greeting' => ['hello', 'hi', 'hey', 'greetings', 'good morning', 'good afternoon', 'good evening'],
        'packages' => ['package', 'destination', 'where', 'place', 'location', 'travel to', 'visit'],
        'booking' => ['book', 'booking', 'reserve', 'how to book', 'booking process'],
        'pricing' => ['price', 'cost', 'how much', 'rate', 'fee', 'charge', 'budget'],
        'payment' => ['pay', 'payment', 'card', 'upi', 'cash', 'transaction'],
        'support' => ['help', 'support', 'contact', 'phone', 'email', 'assist'],
        'cancellation' => ['cancel', 'refund', 'cancellation', 'money back'],
        'recommendation' => ['recommend', 'suggest', 'best', 'which one', 'choose']
    ];
    
    public function getResponse($message) {
        $message = strtolower(trim($message));
        
        // Check for patterns
        foreach ($this->patterns as $category => $keywords) {
            foreach ($keywords as $keyword) {
                if (strpos($message, $keyword) !== false) {
                    return $this->getRandomResponse($category);
                }
            }
        }
        
        // Check for specific questions
        if (strpos($message, 'what packages') !== false || strpos($message, 'available packages') !== false) {
            return $this->getRandomResponse('packages');
        }
        
        if (strpos($message, 'how to book') !== false || strpos($message, 'booking process') !== false) {
            return $this->getRandomResponse('booking');
        }
        
        // Default response
        return $this->getRandomResponse('default');
    }
    
    private function getRandomResponse($category) {
        $responses = $this->responses[$category];
        return $responses[array_rand($responses)];
    }
    
    public function getQuickActions() {
        return [
            'View All Packages',
            'Check Prices',
            'How to Book',
            'Payment Options',
            'Contact Support',
            'Cancel Booking'
        ];
    }
    
    public function getSmartRecommendation($userPreferences = []) {
        $recommendations = [
            'adventure' => 'Bali Adventure - Perfect for thrill-seekers with beaches, temples, and rice terraces!',
            'luxury' => 'Dubai Luxury - Experience the high life with Burj Khalifa and luxury shopping!',
            'romantic' => 'Paris Getaway - City of love with Eiffel Tower and romantic experiences!',
            'culture' => 'Tokyo Explorer - Immerse yourself in Japanese culture and technology!',
            'relaxation' => 'Maldives Paradise - Relax in overwater bungalows and pristine beaches!'
        ];
        
        if (empty($userPreferences)) {
            return array_values($recommendations);
        }
        
        $matching = [];
        foreach ($userPreferences as $pref) {
            if (isset($recommendations[$pref])) {
                $matching[] = $recommendations[$pref];
            }
        }
        
        return empty($matching) ? array_values($recommendations) : $matching;
    }
}

// Handle AJAX requests
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $chatbot = new TravelChatbot();
    
    if ($_POST['action'] === 'chat') {
        $message = $_POST['message'] ?? '';
        $response = $chatbot->getResponse($message);
        
        header('Content-Type: application/json');
        echo json_encode(['response' => $response]);
        exit;
    }
    
    if ($_POST['action'] === 'recommend') {
        $preferences = $_POST['preferences'] ?? [];
        $recommendations = $chatbot->getSmartRecommendation($preferences);
        
        header('Content-Type: application/json');
        echo json_encode(['recommendations' => $recommendations]);
        exit;
    }
}
?>

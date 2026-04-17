<?php
// Include simple mail service first
require_once 'simple_mail.php';

// Mail service factory function
function createMailService() {
    // Check if PHPMailer is available
    if (file_exists('vendor/autoload.php')) {
        try {
            // Load PHPMailer classes
            require_once 'vendor/autoload.php';
            
            // Import classes after loading autoloader
            if (class_exists('PHPMailer\PHPMailer\PHPMailer')) {
                return new AdvancedMailService();
            }
        } catch (Exception $e) {
            error_log("PHPMailer loading failed: " . $e->getMessage());
        }
    }
    
    // Fallback to simple mail
    return new SimpleMailService();
}

// Advanced mail service with PHPMailer
if (file_exists('vendor/autoload.php')) {
    require_once 'vendor/autoload.php';
    
    class AdvancedMailService {
        private $mail;
        
        public function __construct() {
            $this->mail = new PHPMailer\PHPMailer\PHPMailer(true);
            
            // Server settings
            $this->mail->isSMTP();
            $this->mail->Host       = 'smtp.gmail.com';
            $this->mail->SMTPAuth   = true;
            $this->mail->Username   = 'your-email@gmail.com';
            $this->mail->Password   = 'your-app-password';
            $this->mail->SMTPSecure = PHPMailer\PHPMailer\SMTP::ENCRYPTION_SMTPS;
            $this->mail->Port       = 465;
            
            // Recipients
            $this->mail->setFrom('your-email@gmail.com', 'Travel Agency');
            
            // Content settings
            $this->mail->isHTML(true);
            $this->mail->CharSet = 'UTF-8';
        }
        
        public function sendThankYouEmail($userEmail, $userName, $bookingDetails) {
            try {
                $this->mail->clearAddresses();
                $this->mail->addAddress($userEmail, $userName);
                
                $this->mail->Subject = 'Thank You for Your Booking! - Travel Agency';
                $this->mail->Body = $this->getThankYouTemplate($userName, $bookingDetails);
                $this->mail->AltBody = $this->getThankYouTextTemplate($userName, $bookingDetails);
                
                $this->mail->send();
                return true;
                
            } catch (Exception $e) {
                error_log("Email sending failed: " . $this->mail->ErrorInfo);
                return false;
            }
        }
        
        public function sendBookingConfirmationEmail($userEmail, $userName, $bookingDetails) {
            try {
                $this->mail->clearAddresses();
                $this->mail->addAddress($userEmail, $userName);
                
                $this->mail->Subject = 'Booking Confirmation - Travel Agency';
                $this->mail->Body = $this->getConfirmationTemplate($userName, $bookingDetails);
                $this->mail->AltBody = $this->getConfirmationTextTemplate($userName, $bookingDetails);
                
                $this->mail->send();
                return true;
                
            } catch (Exception $e) {
                error_log("Email sending failed: " . $this->mail->ErrorInfo);
                return false;
            }
        }
        
        private function getThankYouTemplate($userName, $bookingDetails) {
            return '
            <!DOCTYPE html>
            <html>
            <head>
                <meta charset="UTF-8">
                <title>Thank You for Your Booking</title>
                <style>
                    body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                    .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                    .header { background: linear-gradient(135deg, #007bff, #0056b3); color: white; padding: 30px; text-align: center; border-radius: 10px 10px 0 0; }
                    .content { background: #f8f9fa; padding: 30px; border-radius: 0 0 10px 10px; }
                    .booking-details { background: white; padding: 20px; border-radius: 8px; margin: 20px 0; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
                    .detail-row { display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid #eee; }
                    .detail-row:last-child { border-bottom: none; }
                    .footer { text-align: center; margin-top: 30px; color: #666; }
                    .btn { display: inline-block; background: #007bff; color: white; padding: 12px 25px; text-decoration: none; border-radius: 5px; margin: 20px 0; }
                </style>
            </head>
            <body>
                <div class="container">
                    <div class="header">
                        <h1>Thank You for Your Booking! </h1>
                        <p>Your travel adventure awaits!</p>
                    </div>
                    <div class="content">
                        <p>Dear <strong>' . htmlspecialchars($userName) . '</strong>,</p>
                        <p>Thank you for choosing Travel Agency for your upcoming journey! We\'re excited to help you create unforgettable memories.</p>
                        
                        <div class="booking-details">
                            <h3>Booking Details</h3>
                            <div class="detail-row">
                                <span><strong>Booking ID:</strong></span>
                                <span>#' . $bookingDetails['booking_id'] . '</span>
                            </div>
                            <div class="detail-row">
                                <span><strong>Package:</strong></span>
                                <span>' . htmlspecialchars($bookingDetails['package_name']) . '</span>
                            </div>
                            <div class="detail-row">
                                <span><strong>From:</strong></span>
                                <span>' . htmlspecialchars($bookingDetails['source']) . '</span>
                            </div>
                            <div class="detail-row">
                                <span><strong>To:</strong></span>
                                <span>' . htmlspecialchars($bookingDetails['destination']) . '</span>
                            </div>
                            <div class="detail-row">
                                <span><strong>Travel Date:</strong></span>
                                <span>' . $bookingDetails['date'] . '</span>
                            </div>
                            <div class="detail-row">
                                <span><strong>Number of Seats:</strong></span>
                                <span>' . $bookingDetails['seats'] . '</span>
                            </div>
                            <div class="detail-row">
                                <span><strong>Total Amount:</strong></span>
                                <span style="color: #28a745; font-weight: bold;">Rs. ' . number_format($bookingDetails['amount'], 2) . '</span>
                            </div>
                        </div>
                        
                        <h3>Next Steps:</h3>
                        <ul>
                            <li>Complete your payment to confirm the booking</li>
                            <li>You will receive a payment confirmation email</li>
                            <li>Our team will contact you 24 hours before your travel date</li>
                        </ul>
                        
                        <div style="text-align: center;">
                            <a href="http://localhost/Travel_Agency/payment.php" class="btn">Complete Payment</a>
                        </div>
                        
                        <div class="footer">
                            <p><strong>Need Help?</strong></p>
                            <p>Phone: +1 (555) 123-4567</p>
                            <p>Email: support@travelagency.com</p>
                            <p>Hours: Mon-Fri 9AM-6PM, Sat 10AM-4PM</p>
                        </div>
                    </div>
                </div>
            </body>
            </html>';
        }
        
        private function getThankYouTextTemplate($userName, $bookingDetails) {
            return "Thank You for Your Booking - Travel Agency\n\n" .
                   "Dear $userName,\n\n" .
                   "Thank you for choosing Travel Agency! Your booking has been received.\n\n" .
                   "Booking Details:\n" .
                   "Booking ID: #" . $bookingDetails['booking_id'] . "\n" .
                   "Package: " . $bookingDetails['package_name'] . "\n" .
                   "From: " . $bookingDetails['source'] . "\n" .
                   "To: " . $bookingDetails['destination'] . "\n" .
                   "Date: " . $bookingDetails['date'] . "\n" .
                   "Seats: " . $bookingDetails['seats'] . "\n" .
                   "Amount: Rs. " . number_format($bookingDetails['amount'], 2) . "\n\n" .
                   "Next Steps:\n" .
                   "1. Complete your payment to confirm the booking\n" .
                   "2. You will receive a payment confirmation email\n" .
                   "3. Our team will contact you 24 hours before your travel date\n\n" .
                   "Complete payment at: http://localhost/Travel_Agency/payment.php\n\n" .
                   "Need Help? Call us at +1 (555) 123-4567\n" .
                   "Travel Agency Team";
        }
        
        private function getConfirmationTemplate($userName, $bookingDetails) {
            return '
            <!DOCTYPE html>
            <html>
            <head>
                <meta charset="UTF-8">
                <title>Booking Confirmation</title>
                <style>
                    body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                    .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                    .header { background: linear-gradient(135deg, #28a745, #20c997); color: white; padding: 30px; text-align: center; border-radius: 10px 10px 0 0; }
                    .content { background: #f8f9fa; padding: 30px; border-radius: 0 0 10px 10px; }
                    .confirmation-badge { background: #28a745; color: white; padding: 10px 20px; border-radius: 20px; display: inline-block; margin: 20px 0; }
                    .booking-details { background: white; padding: 20px; border-radius: 8px; margin: 20px 0; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
                    .detail-row { display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid #eee; }
                    .detail-row:last-child { border-bottom: none; }
                    .footer { text-align: center; margin-top: 30px; color: #666; }
                </style>
            </head>
            <body>
                <div class="container">
                    <div class="header">
                        <h1>Booking Confirmed! </h1>
                        <p>Your payment has been received successfully</p>
                    </div>
                    <div class="content">
                        <div class="confirmation-badge">
                            <strong>Payment Successful</strong>
                        </div>
                        
                        <p>Dear <strong>' . htmlspecialchars($userName) . '</strong>,</p>
                        <p>Congratulations! Your booking has been confirmed and payment received. Get ready for an amazing travel experience!</p>
                        
                        <div class="booking-details">
                            <h3>Confirmed Booking Details</h3>
                            <div class="detail-row">
                                <span><strong>Booking ID:</strong></span>
                                <span>#' . $bookingDetails['booking_id'] . '</span>
                            </div>
                            <div class="detail-row">
                                <span><strong>Package:</strong></span>
                                <span>' . htmlspecialchars($bookingDetails['package_name']) . '</span>
                            </div>
                            <div class="detail-row">
                                <span><strong>From:</strong></span>
                                <span>' . htmlspecialchars($bookingDetails['source']) . '</span>
                            </div>
                            <div class="detail-row">
                                <span><strong>To:</strong></span>
                                <span>' . htmlspecialchars($bookingDetails['destination']) . '</span>
                            </div>
                            <div class="detail-row">
                                <span><strong>Travel Date:</strong></span>
                                <span>' . $bookingDetails['date'] . '</span>
                            </div>
                            <div class="detail-row">
                                <span><strong>Number of Seats:</strong></span>
                                <span>' . $bookingDetails['seats'] . '</span>
                            </div>
                            <div class="detail-row">
                                <span><strong>Amount Paid:</strong></span>
                                <span style="color: #28a745; font-weight: bold;">Rs. ' . number_format($bookingDetails['amount'], 2) . '</span>
                            </div>
                            <div class="detail-row">
                                <span><strong>Payment Method:</strong></span>
                                <span>' . htmlspecialchars($bookingDetails['payment_method']) . '</span>
                            </div>
                        </div>
                        
                        <h3>Important Information:</h3>
                        <ul>
                            <li>Please arrive at the departure point 30 minutes before scheduled time</li>
                            <li>Carry a valid ID proof and this booking confirmation</li>
                            <li>Our team will contact you 24 hours before your travel date</li>
                            <li>For any changes, please contact us at least 48 hours in advance</li>
                        </ul>
                        
                        <div class="footer">
                            <p><strong>Have a wonderful journey!</strong></p>
                            <p>Phone: +1 (555) 123-4567</p>
                            <p>Email: support@travelagency.com</p>
                        </div>
                    </div>
                </div>
            </body>
            </html>';
        }
        
        private function getConfirmationTextTemplate($userName, $bookingDetails) {
            return "Booking Confirmation - Travel Agency\n\n" .
                   "Dear $userName,\n\n" .
                   "Congratulations! Your booking has been confirmed and payment received.\n\n" .
                   "Confirmed Booking Details:\n" .
                   "Booking ID: #" . $bookingDetails['booking_id'] . "\n" .
                   "Package: " . $bookingDetails['package_name'] . "\n" .
                   "From: " . $bookingDetails['source'] . "\n" .
                   "To: " . $bookingDetails['destination'] . "\n" .
                   "Date: " . $bookingDetails['date'] . "\n" .
                   "Seats: " . $bookingDetails['seats'] . "\n" .
                   "Amount Paid: Rs. " . number_format($bookingDetails['amount'], 2) . "\n" .
                   "Payment Method: " . $bookingDetails['payment_method'] . "\n\n" .
                   "Important Information:\n" .
                   "- Please arrive 30 minutes before departure time\n" .
                   "- Carry valid ID proof and this confirmation\n" .
                   "- We will contact you 24 hours before travel date\n" .
                   "- For changes, contact us 48 hours in advance\n\n" .
                   "Have a wonderful journey!\n" .
                   "Travel Agency Team";
        }
    }
}
?>

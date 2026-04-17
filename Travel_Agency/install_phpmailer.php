<?php
// Auto-install PHPMailer script
echo "<h2>Installing PHPMailer...</h2>";

// Check if composer is available
if (!file_exists('composer.phar')) {
    echo "<p>Downloading Composer...</p>";
    copy('https://getcomposer.org/installer', 'composer-setup.php');
    
    if (php_ini_loaded_file()) {
        $ini_path = php_ini_loaded_file();
        echo "<p>Using PHP config: $ini_path</p>";
    }
    
    echo "<p>Installing Composer...</p>";
    shell_exec('php composer-setup.php');
    unlink('composer-setup.php');
}

// Install PHPMailer
echo "<p>Installing PHPMailer package...</p>";
$output = shell_exec('php composer.phar require phpmailer/phpmailer');

echo "<h3>Installation Complete!</h3>";
echo "<pre>$output</pre>";

// Check if vendor directory exists
if (is_dir('vendor')) {
    echo "<p style='color: green;'>Vendor directory created successfully!</p>";
} else {
    echo "<p style='color: orange;'>Vendor directory not found. Please run this command manually:</p>";
    echo "<code>composer require phpmailer/phpmailer</code>";
}
?>

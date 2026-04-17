<?php
// Manual PHPMailer download script
echo "<h2>Downloading PHPMailer...</h2>";

// Create vendor directory if it doesn't exist
if (!is_dir('vendor')) {
    mkdir('vendor', 0755, true);
    echo "<p>Created vendor directory</p>";
}

// Create PHPMailer directory structure
if (!is_dir('vendor/phpmailer')) {
    mkdir('vendor/phpmailer', 0755, true);
    echo "<p>Created PHPMailer directory</p>";
}

if (!is_dir('vendor/phpmailer/phpmailer')) {
    mkdir('vendor/phpmailer/phpmailer', 0755, true);
    echo "<p>Created PHPMailer src directory</p>";
}

// Download the latest PHPMailer release
$zipUrl = 'https://github.com/PHPMailer/PHPMailer/archive/refs/tags/v6.9.1.zip';
$zipFile = 'phpmailer.zip';

echo "<p>Downloading PHPMailer from GitHub...</p>";
$zipContent = file_get_contents($zipUrl);

if ($zipContent) {
    file_put_contents($zipFile, $zipContent);
    echo "<p>Downloaded PHPMailer zip file</p>";
    
    // Extract the zip file
    $zip = new ZipArchive();
    if ($zip->open($zipFile) === TRUE) {
        // Extract to a temporary directory
        $zip->extractTo('temp_phpmailer');
        $zip->close();
        
        // Move the files to the correct location
        $sourceDir = 'temp_phpmailer/PHPMailer-6.9.1/src';
        $destDir = 'vendor/phpmailer/phpmailer';
        
        if (is_dir($sourceDir)) {
            $files = scandir($sourceDir);
            foreach ($files as $file) {
                if ($file !== '.' && $file !== '..') {
                    copy($sourceDir . '/' . $file, $destDir . '/' . $file);
                }
            }
            echo "<p style='color: green;'>PHPMailer files extracted successfully!</p>";
        }
        
        // Clean up
        $this->removeDirectory('temp_phpmailer');
        unlink($zipFile);
        
        echo "<p style='color: green;'>Installation complete!</p>";
        echo "<p><a href='index.php'>Go to Home</a></p>";
        
    } else {
        echo "<p style='color: red;'>Failed to extract zip file</p>";
    }
} else {
    echo "<p style='color: red;'>Failed to download PHPMailer</p>";
    echo "<p>Please install manually using Composer:</p>";
    echo "<code>composer require phpmailer/phpmailer</code>";
}

function removeDirectory($dir) {
    if (!is_dir($dir)) {
        return;
    }
    $files = array_diff(scandir($dir), ['.', '..']);
    foreach ($files as $file) {
        $path = "$dir/$file";
        is_dir($path) ? removeDirectory($path) : unlink($path);
    }
    rmdir($dir);
}
?>

<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$file = 'test-roboto.woff2';

if (file_exists($file)) {
    echo "File exists: YES\n";
    
    // Test with finfo
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mimeType = $finfo->file($file);
    echo "MIME type (finfo): $mimeType\n";
    
    // Create a proper temporary file for testing
    $tempFile = tempnam(sys_get_temp_dir(), 'font_test');
    copy($file, $tempFile);
    
    // Test with Laravel's UploadedFile (simulating real upload)
    $uploadedFile = new \Illuminate\Http\UploadedFile(
        $tempFile,
        'test-roboto.woff2',
        $mimeType,
        null,
        false // Set to false to simulate real upload
    );
    
    echo "Laravel MIME type: " . $uploadedFile->getMimeType() . "\n";
    echo "Laravel extension: " . $uploadedFile->getClientOriginalExtension() . "\n";
    echo "File is valid: " . ($uploadedFile->isValid() ? 'YES' : 'NO') . "\n";
    echo "File size: " . $uploadedFile->getSize() . " bytes\n";
    
    // Test validation
    $allowedMimes = ['png','jpg','jpeg','svg','otf','ttf','woff','woff2'];
    $extension = $uploadedFile->getClientOriginalExtension();
    echo "Extension '$extension' allowed: " . (in_array($extension, $allowedMimes) ? 'YES' : 'NO') . "\n";
    
    // Test Laravel validation rule
    $validator = \Illuminate\Support\Facades\Validator::make(
        ['file' => $uploadedFile],
        ['file' => 'required|file|mimes:png,jpg,jpeg,svg,otf,ttf,woff,woff2']
    );
    
    echo "Laravel validation passes: " . ($validator->passes() ? 'YES' : 'NO') . "\n";
    if ($validator->fails()) {
        echo "Validation errors: " . json_encode($validator->errors()->toArray()) . "\n";
    }
    
    // Clean up
    unlink($tempFile);
} else {
    echo "File does not exist\n";
}
<?php
// config/cors.php

return [
    'paths' => ['api/*', 'sanctum/csrf-cookie', 'livewire/upload-file'],
    'allowed_methods' => ['*'],
    'allowed_origins' => [
        'https://triagung-frontend.vercel.app', // Tambahkan domain frontend Anda di sini!
        'http://localhost:5173', // Untuk development lokal
    ],
    'allowed_origins_patterns' => [],
    'allowed_headers' => ['*'],
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => true,

];
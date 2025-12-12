<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->bootstrap();

use App\User;

$q = 'a';
$users = User::where(function($query) use ($q) {
    $query->where('name', 'like', '%' . $q . '%')
          ->orWhere('email', 'like', '%' . $q . '%')
          ->orWhere('phone', 'like', '%' . $q . '%');
})->limit(5)->get();

$results = [];
foreach ($users as $user) {
    $results[] = [
        'id' => $user->id,
        'text' => $user->name,
        'name' => $user->name,
        'email' => $user->email,
        'phone' => $user->phone,
    ];
}

header('Content-Type: application/json');
echo json_encode(['results' => $results], JSON_PRETTY_PRINT);

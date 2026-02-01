<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Client Count: " . App\Models\Client::count() . "\n";
$john = App\Models\Client::where('name', 'like', '%John%')->get();
echo "Found Johns: " . $john->count() . "\n";
foreach ($john as $j) {
    echo " - " . $j->name . " (" . $j->username . ")\n";
}

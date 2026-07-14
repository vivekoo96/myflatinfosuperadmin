<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$permissions = \App\Models\Permission::all();
foreach ($permissions as $p) {
    echo "ID: " . $p->id . " | Name: " . $p->name . " | Slug: " . $p->slug . " | Guard: " . $p->guard . " | Group: " . $p->group . "\n";
}

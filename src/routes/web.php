<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Welcome page
Route::get('/', function () {
    return view('welcome');
});
   
Route::get('/setup', function () {
    $credentials = [
        'email' => 'admin@admin.com',
        'password' => 'password'
    ];

    if (!Auth::attempt($credentials)) {
        $user = new App\Models\User();

        $user->name = 'Admin';
        $user->email = $credentials['email'];
        $user->password = Hash::make($credentials['password']);

        $user->save();

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            
            $adminToken = $user->createToken('admin-token', ['create', 'update', 'delete']);
            $updateToken = $user->createToken('update-token', ['create', 'update']);
            $basicToken = $user->createToken('basic-token');

            return [
                'admin' => $adminToken->plainTextToken,
                'update' => $updateToken->plainTextToken,
                'basic' => $basicToken->plainTextToken
            ];
        }
    }
});

// admin : "1|kz1dA1PswiFWSBphJFxxdZgm9QbXbW1ICMXdXaDI5d9f8ead"
// update : "2|5PtPQZXk1WBhfyeUZwIW3U44uw9hPEbqfslmt6L2f3e4f96f"
// basic : "3|6nlhXUT8HSQW0EJDcjh2BtciZlMzE1AFhTdnYzJRd7bf3888"

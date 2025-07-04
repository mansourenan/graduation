<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SettingsController extends Controller
{
    public function index()
    {
        return response()->json([
            'account' => Auth::user(),
            'general' => [
                'notifications' => true,
                'language' => 'en',
            ],
            'about' => [
                'version' => '1.0.0',
                'developer' => 'YourTeamName',
                'contact_email' => 'support@example.com'
            ]
        ]);
    }

    public function general()
    {
        return response()->json([
            'notifications' => true,
            'language' => 'en',
        ]);
    }

    public function about()
    {
        return response()->json([
            'version' => '1.0.0',
            'developer' => 'YourTeamName',
            'contact_email' => 'support@example.com',
        ]);
    }
}

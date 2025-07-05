<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DetectionController extends Controller
{
    public function status()
    {
        // يمكنك ربط هذا بمنطق الذكاء الاصطناعي لاحقاً
        $status = 'good'; // أو tired, sleepy, ...
        return response()->json([
            'status' => $status,
            'message' => $status === 'good' ? "The Driver's Condition Is Good" : "The Driver Needs Rest"
        ]);
    }
} 
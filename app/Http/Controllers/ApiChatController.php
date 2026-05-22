<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ApiChatController extends Controller
{
    public function chat(Request $request)
    {
        return response()->json([
            'success' => true,
            'message' => 'Chat API working'
        ]);
    }
}

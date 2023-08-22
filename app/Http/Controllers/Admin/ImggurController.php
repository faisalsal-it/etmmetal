<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ImggurController extends Controller
{
    public function upload(Request $request) {
        // $request->file('image')->move('assets/', 'sample.jpg');
        $client = new \GuzzleHttp\Client();
        $response = $client->request('POST', 'https://api.imgur.com/3/upload', [
            'headers' => [
                'authorization' => 'Client-ID ' . '4a89486edfe85eb',
                'content-type' => 'application/x-www-form-urlencoded'
            ],
            'form_params' => [
                'image' => file_get_contents($request->file('image')->path()),
                'type' => 'file'
            ]
        ]);

        return response()->json(json_decode($response->getBody()->getContents()));
    }
}

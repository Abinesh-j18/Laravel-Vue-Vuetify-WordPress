<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class WordPressService
{
    protected $baseUrl = 'https://public-api.wordpress.com/rest/v1.1/sites/';
    protected $site = 'abiproject3.wordpress.com';
    protected $token;

    public function __construct()
    {
        $this->token = session('wordpress_token'); // OAuth token saved in session
    }

    // Get posts
    public function getPosts()
    {
        $url = $this->baseUrl . $this->site . '/posts/';
        $response = Http::get($url);
        return $response->json();
    }

    // Create a post
    public function createPost($title, $content)
    {
        if (!$this->token) {
            return ['error' => 'OAuth token not found'];
        }

        $url = $this->baseUrl . $this->site . '/posts/new';
        $response = Http::withToken($this->token)->post($url, [
            'title'   => $title,
            'content' => $content,
            'status'  => 'publish',
        ]);

        return $response->json();
    }
}

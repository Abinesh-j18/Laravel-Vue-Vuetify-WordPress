<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use App\Models\WpToken;

class BlogController extends Controller
{
    private $siteSlug = 'abiproject3.wordpress.com';

    public function fetchPosts(Request $request)
    {
        $token = WpToken::first();
        if (!$token) return response()->json(['error' => 'Please connect to WordPress first'], 401);

        $params = [
            'status' => 'publish,draft',
            'per_page' => 20,
            'orderby' => 'date',
            'order' => 'desc',
        ];

        if ($request->has('search') && $request->search) {
            $params['search'] = $request->search;
        }

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token->access_token
        ])->get("https://public-api.wordpress.com/wp/v2/sites/{$this->siteSlug}/posts", $params);

        if (!$response->successful()) {
            return response()->json(['error' => $response->json()], $response->status());
        }

        $posts = $response->json();
        foreach ($posts as &$post) {
            $post['priority'] = $post['priority'] ?? 0;
        }

        return response()->json($posts);
    }

    public function store(Request $request)
    {
        $token = WpToken::first();
        if (!$token) return response()->json(['error' => 'Please connect to WordPress first'], 401);

        $request->validate([
            'title' => 'required|string',
            'content' => 'required|string',
            'status' => 'required|string|in:publish,draft'
        ]);

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token->access_token
        ])->post("https://public-api.wordpress.com/wp/v2/sites/{$this->siteSlug}/posts", [
            'title' => $request->title,
            'content' => $request->content,
            'status' => $request->status
        ]);

        return response()->json($response->json(), $response->status());
    }

    public function update(Request $request, $id)
    {
        $token = WpToken::first();
        if (!$token) return response()->json(['error' => 'Please connect to WordPress first'], 401);

        $request->validate([
            'title' => 'required|string',
            'content' => 'required|string',
            'status' => 'required|string|in:publish,draft'
        ]);

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token->access_token
        ])->patch("https://public-api.wordpress.com/wp/v2/sites/{$this->siteSlug}/posts/{$id}", [
            'title' => $request->title,
            'content' => $request->content,
            'status' => $request->status
        ]);

        return response()->json($response->json(), $response->status());
    }

    public function destroy($id)
    {
        $token = WpToken::first();
        if (!$token) return response()->json(['error' => 'Please connect to WordPress first'], 401);

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token->access_token
        ])->delete("https://public-api.wordpress.com/wp/v2/sites/{$this->siteSlug}/posts/{$id}", ['force' => true]);

        return response()->json($response->json(), $response->status());
    }

    public function updatePriority(Request $request, $id)
    {
        $priority = (int) $request->input('priority', 0);

        $updated = DB::table('wp_posts')
            ->where('id', $id)
            ->update(['priority' => $priority]);

        if (!$updated) return response()->json(['ok' => false]);

        return response()->json(['ok' => true]);
    }
}

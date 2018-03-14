<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;
use App\Post;
use Config;
use Cache;
use Auth;

class PageController extends Controller
{
    /**
     * Show a single page based on slug.
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function view($slug)
    {
        $user = Auth::user();
        //$minutes = Config::get('settings.pages_cache_time');
        /*
        $page = Cache::remember('pages_' . $slug, $minutes, function() use ($slug, $user) {
            return Post::where('post_type', 'page')->where('slug', $slug)->firstOrFail();
        });
        */
        $page = Post::where('post_type', 'page')->where('slug', $slug)->firstOrFail();
        
        // Make sure to check if we are allowed to view unpublished pages
        if ($page->status == 'inactive') {
            if (!$user || !$user->hasPermissionTo('preview posts', 'web')) {
                abort(404);
            }
        }

        return view()->first([
            "pages/{$page->template}",
            "pages/default-page"
        ], compact('page'));
    }
}
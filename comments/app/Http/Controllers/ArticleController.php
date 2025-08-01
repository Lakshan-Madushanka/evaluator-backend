<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ArticleController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Article $article, Request $request)
    {
        if (! Auth::check()) {
            Auth::login(User::query()->whereEmail('test@example.com')->first());
        }

        //  Auth::logout();

        return view('articles.show', ['article' => $article]);
    }
}

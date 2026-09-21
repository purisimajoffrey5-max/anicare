<?php

namespace App\Http\Controllers;

use App\Models\HelpArticle;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class HelpCenterController extends Controller
{
    public function index(Request $request)
    {
        $role = (string) ($request->user()->role ?? 'resident');
        $category = $request->string('category')->toString();
        $search = trim($request->string('q')->toString());

        $articles = HelpArticle::query()
            ->where('is_active', true)
            ->forRole($role)
            ->when($category, fn ($q) => $q->where('category', $category))
            ->when($search, function ($q) use ($search) {
                $term = '%' . $search . '%';
                $q->where(function ($inner) use ($term) {
                    $inner->where('title', 'like', $term)
                        ->orWhere('summary', 'like', $term)
                        ->orWhere('content', 'like', $term);
                });
            })
            ->orderBy('sort_order')
            ->orderBy('title')
            ->get();

        $categories = HelpArticle::query()
            ->where('is_active', true)
            ->forRole($role)
            ->orderBy('category')
            ->pluck('category')
            ->unique()
            ->values();

        return view('help.index', compact('articles', 'categories', 'role', 'search', 'category'));
    }

    public function article(Request $request, string $slug)
    {
        $role = (string) ($request->user()->role ?? 'resident');

        $article = HelpArticle::query()
            ->where('is_active', true)
            ->where('slug', $slug)
            ->forRole($role)
            ->firstOrFail();

        return view('help.article', compact('article', 'role'));
    }

    public function chat(Request $request)
    {
        $data = $request->validate([
            'message' => ['required', 'string', 'max:1000'],
            'current_route' => ['nullable', 'string', 'max:255'],
        ]);

        $role = (string) ($request->user()->role ?? 'resident');
        $message = Str::lower(trim($data['message']));
        $tokens = collect(preg_split('/[^a-z0-9]+/i', $message))
            ->filter(fn ($token) => strlen($token) >= 3)
            ->unique()
            ->values();

        $articles = HelpArticle::query()
            ->where('is_active', true)
            ->forRole($role)
            ->get();

        $ranked = $articles->map(function (HelpArticle $article) use ($tokens, $message, $data) {
            $haystack = Str::lower(implode(' ', [
                $article->title,
                $article->summary,
                $article->content,
                implode(' ', $article->keywords ?? []),
                $article->route_name,
            ]));

            $score = 0;
            foreach ($tokens as $token) {
                if (Str::contains($haystack, $token)) {
                    $score += 2;
                }
            }

            if ($article->route_name && !empty($data['current_route']) && $article->route_name === $data['current_route']) {
                $score += 5;
            }

            if (Str::contains(Str::lower($article->title), $message)) {
                $score += 8;
            }

            return ['article' => $article, 'score' => $score];
        })->sortByDesc('score')->values();

        $best = $ranked->first();
        $bestScore = $best['score'] ?? 0;

        if (!$best || $bestScore < 2) {
            $suggestions = $articles->sortBy('sort_order')->take(4)->map(fn ($a) => [
                'title' => $a->title,
                'slug' => $a->slug,
            ])->values();

            return response()->json([
                'ok' => true,
                'answer' => "I could not find an exact answer in the ANI-CARE Help Center yet. Try asking about a specific module, such as Orders, Marketplace, Milling, Inventory, Reports, Profile, or Notifications.",
                'suggestions' => $suggestions,
            ]);
        }

        /** @var HelpArticle $article */
        $article = $best['article'];
        $answer = $article->content;

        return response()->json([
            'ok' => true,
            'answer' => $answer,
            'article' => [
                'title' => $article->title,
                'slug' => $article->slug,
                'url' => route('help.article', $article->slug),
            ],
            'suggestions' => $ranked->skip(1)->take(3)->map(fn ($row) => [
                'title' => $row['article']->title,
                'slug' => $row['article']->slug,
            ])->values(),
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\PortfolioItem;
use App\Models\Profile;
use App\Models\Slide;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class HomeController extends Controller
{
    public function index()
    {
        $profile = Profile::query()->oldest()->first();

        return view('home', [
            'slides' => Slide::query()
                ->where('is_active', true)
                ->orderBy('display_order')
                ->get(),
            'profile' => $profile,
            'posts' => BlogPost::query()
                ->where('is_published', true)
                ->latest('published_at')
                ->take(3)
                ->get(),
            'portfolioItems' => PortfolioItem::query()
                ->where('is_active', true)
                ->orderBy('display_order')
                ->take(6)
                ->get(),
        ]);
    }

    public function profile()
    {
        return view('profile', [
            'profile' => Profile::query()->oldest()->first(),
        ]);
    }

    public function blog(Request $request)
    {
        $search = trim((string) $request->query('q', ''));
        $category = trim((string) $request->query('category', ''));
        $tag = trim((string) $request->query('tag', ''));

        $postsQuery = BlogPost::query()
            ->published()
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery
                        ->where('title', 'like', "%{$search}%")
                        ->orWhere('excerpt', 'like', "%{$search}%")
                        ->orWhere('content', 'like', "%{$search}%");
                });
            })
            ->when($category !== '', fn ($query) => $query->where('category', $category))
            ->when($tag !== '', fn ($query) => $query->whereRaw('LOWER(tags) LIKE ?', ['%"' . Str::lower(addcslashes($tag, '"')) . '"%']))
            ->latest('published_at');

        return view('blog.index', [
            'posts' => $postsQuery->paginate(6)->withQueryString(),
            'search' => $search,
            'activeCategory' => $category,
            'activeTag' => $tag,
            'categories' => BlogPost::query()
                ->published()
                ->whereNotNull('category')
                ->where('category', '!=', '')
                ->select('category')
                ->distinct()
                ->orderBy('category')
                ->pluck('category'),
            'popularTags' => $this->extractPopularTags(BlogPost::query()->published()->pluck('tags')),
        ]);
    }

    public function blogShow(BlogPost $blogPost)
    {
        abort_unless($blogPost->is_published, 404);

        $blogPost->increment('view_count');

        $relatedPosts = BlogPost::query()
            ->published()
            ->whereKeyNot($blogPost->getKey())
            ->when(filled($blogPost->category) || !empty($blogPost->tags), function ($query) use ($blogPost) {
                $query->where(function ($subQuery) use ($blogPost) {
                    if (filled($blogPost->category)) {
                        $subQuery->where('category', $blogPost->category);
                    }

                    foreach (($blogPost->tags ?? []) as $relatedTag) {
                        $subQuery->orWhereRaw('LOWER(tags) LIKE ?', ['%"' . Str::lower(addcslashes((string) $relatedTag, '"')) . '"%']);
                    }
                });
            })
            ->latest('published_at')
            ->take(3)
            ->get();

        if ($relatedPosts->count() < 3) {
            $fallbackPosts = BlogPost::query()
                ->published()
                ->whereKeyNot($blogPost->getKey())
                ->whereNotIn('id', $relatedPosts->pluck('id'))
                ->latest('published_at')
                ->take(3 - $relatedPosts->count())
                ->get();

            $relatedPosts = $relatedPosts->concat($fallbackPosts);
        }

        return view('blog.show', [
            'post' => $blogPost,
            'relatedPosts' => $relatedPosts,
        ]);
    }

    public function portfolioVisit(PortfolioItem $portfolioItem)
    {
        abort_unless($portfolioItem->is_active, 404);
        abort_if(empty($portfolioItem->project_url), 404);

        $scheme = strtolower(parse_url($portfolioItem->project_url, PHP_URL_SCHEME) ?: '');
        abort_unless(in_array($scheme, ['http', 'https'], true), 404);

        $portfolioItem->increment('view_count');

        return redirect()->away($portfolioItem->project_url);
    }

    public function portfolio()
    {
        return view('portfolio.index', [
            'items' => PortfolioItem::query()
                ->where('is_active', true)
                ->orderBy('display_order')
                ->paginate(9),
        ]);
    }

    public function adminDashboard()
    {
        return view('admin.dashboard', [
            'profileCount' => Profile::query()->count(),
            'blogCount' => BlogPost::query()->count(),
            'portfolioCount' => PortfolioItem::query()->count(),
            'slideCount' => Slide::query()->count(),
            'totalBlogViews' => (int) BlogPost::query()->sum('view_count'),
            'totalPortfolioViews' => (int) PortfolioItem::query()->sum('view_count'),
            'latestPosts' => BlogPost::query()->latest()->take(5)->get(),
        ]);
    }

    private function extractPopularTags(Collection $tagsCollection, int $limit = 8): Collection
    {
        $groupedTags = $tagsCollection
            ->filter(fn ($tags) => is_array($tags))
            ->flatten()
            ->filter(fn ($tag) => is_string($tag) && trim($tag) !== '')
            ->reduce(function (Collection $carry, string $tag) {
                $name = trim($tag);
                $normalizedTag = Str::lower($name);
                $existing = $carry->get($normalizedTag);

                if ($existing === null) {
                    $carry->put($normalizedTag, [
                        'name' => $name,
                        'count' => 1,
                    ]);

                    return $carry;
                }

                $existing['count']++;
                $carry->put($normalizedTag, $existing);

                return $carry;
            }, collect());

        return $groupedTags
            ->sortByDesc('count')
            ->take($limit)
            ->values();
    }
}

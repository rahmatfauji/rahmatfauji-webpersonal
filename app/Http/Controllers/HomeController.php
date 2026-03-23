<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\PortfolioItem;
use App\Models\Profile;
use App\Models\Slide;

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

    public function blog()
    {
        return view('blog.index', [
            'posts' => BlogPost::query()
                ->where('is_published', true)
                ->latest('published_at')
                ->paginate(6),
        ]);
    }

    public function blogShow(BlogPost $blogPost)
    {
        abort_unless($blogPost->is_published, 404);

        $blogPost->increment('view_count');

        return view('blog.show', [
            'post' => $blogPost,
        ]);
    }

    public function portfolioVisit(PortfolioItem $portfolioItem)
    {
        abort_unless($portfolioItem->is_active, 404);
        abort_if(empty($portfolioItem->project_url), 404);

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
}

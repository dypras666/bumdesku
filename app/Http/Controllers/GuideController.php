<?php

namespace App\Http\Controllers;

use App\Models\Guide;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class GuideController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['publicIndex', 'publicShow']);
        $this->middleware(function ($request, $next) {
            if (Auth::check() && Auth::user()->role->name !== 'super_admin') {
                abort(403, 'Unauthorized action.');
            }
            return $next($request);
        })->except(['publicIndex', 'publicShow']);
    }

    /**
     * Display a listing of the resource for admin.
     */
    public function index()
    {
        $guides = Guide::with('creator')
            ->orderBy('category')
            ->orderBy('order')
            ->orderBy('title')
            ->paginate(15);

        return view('guides.index', compact('guides'));
    }

    /**
     * Display a listing of the resource for public.
     */
    public function publicIndex()
    {
        $categories = Guide::getCategories();
        $guidesByCategory = [];

        foreach ($categories as $key => $name) {
            $guidesByCategory[$key] = Guide::published()
                ->byCategory($key)
                ->ordered()
                ->get();
        }

        return view('guides.public.index', compact('guidesByCategory', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Guide::getCategories();
        return view('guides.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category' => 'required|string|in:' . implode(',', array_keys(Guide::getCategories())),
            'order' => 'nullable|integer|min:0',
            'description' => 'nullable|string|max:500',
            'icon' => 'nullable|string|max:100',
            'youtube_url' => 'nullable|url',
            'is_published' => 'boolean'
        ]);

        $guide = Guide::create([
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'content' => $request->content,
            'category' => $request->category,
            'order' => $request->order ?? 0,
            'description' => $request->description,
            'icon' => $request->icon,
            'youtube_url' => $request->youtube_url,
            'is_published' => $request->boolean('is_published', true),
            'created_by' => Auth::id()
        ]);

        return redirect()->route('guides.index')
            ->with('success', 'Panduan berhasil dibuat.');
    }

    /**
     * Display the specified resource for admin.
     */
    public function show(Guide $guide)
    {
        return view('guides.show', compact('guide'));
    }

    /**
     * Display the specified resource for public.
     */
    public function publicShow($slug)
    {
        $guide = Guide::where('slug', $slug)
            ->published()
            ->firstOrFail();

        $relatedGuides = Guide::published()
            ->byCategory($guide->category)
            ->where('id', '!=', $guide->id)
            ->ordered()
            ->limit(5)
            ->get();

        // Get previous and next guides in the same category
        $previousGuide = Guide::published()
            ->byCategory($guide->category)
            ->where('order', '<', $guide->order)
            ->orderBy('order', 'desc')
            ->first();

        $nextGuide = Guide::published()
            ->byCategory($guide->category)
            ->where('order', '>', $guide->order)
            ->orderBy('order', 'asc')
            ->first();

        // If no previous/next by order, try by ID as fallback
        if (!$previousGuide) {
            $previousGuide = Guide::published()
                ->byCategory($guide->category)
                ->where('id', '<', $guide->id)
                ->orderBy('id', 'desc')
                ->first();
        }

        if (!$nextGuide) {
            $nextGuide = Guide::published()
                ->byCategory($guide->category)
                ->where('id', '>', $guide->id)
                ->orderBy('id', 'asc')
                ->first();
        }

        return view('guides.public.show', compact('guide', 'relatedGuides', 'previousGuide', 'nextGuide'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Guide $guide)
    {
        // Validate guide exists and is accessible
        if (!$guide || !$guide->exists) {
            abort(404, 'Guide not found');
        }
        
        // Clean content for safe display
        $guide->content = mb_convert_encoding($guide->content, 'UTF-8', 'UTF-8');
        
        $categories = Guide::getCategories();
        
        return view('guides.edit', compact('guide', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Guide $guide)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category' => 'required|string|in:' . implode(',', array_keys(Guide::getCategories())),
            'order' => 'nullable|integer|min:0',
            'description' => 'nullable|string|max:500',
            'icon' => 'nullable|string|max:100',
            'youtube_url' => 'nullable|url',
            'is_published' => 'boolean'
        ]);

        $guide->update([
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'content' => $request->content,
            'category' => $request->category,
            'order' => $request->order ?? 0,
            'description' => $request->description,
            'icon' => $request->icon,
            'youtube_url' => $request->youtube_url,
            'is_published' => $request->boolean('is_published', true)
        ]);

        return redirect()->route('guides.index')
            ->with('success', 'Panduan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Guide $guide)
    {
        $guide->delete();

        return redirect()->route('guides.index')
            ->with('success', 'Panduan berhasil dihapus.');
    }
}

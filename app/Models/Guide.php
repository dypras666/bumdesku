<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Guide extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'content',
        'category',
        'order',
        'is_published',
        'icon',
        'description',
        'youtube_url',
        'created_by'
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'order' => 'integer'
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($guide) {
            if (empty($guide->slug)) {
                $guide->slug = Str::slug($guide->title);
            }
        });
        
        static::updating(function ($guide) {
            if ($guide->isDirty('title') && empty($guide->slug)) {
                $guide->slug = Str::slug($guide->title);
            }
        });
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order')->orderBy('title');
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public static function getCategories()
    {
        return [
            'getting-started' => 'Memulai',
            'financial-management' => 'Manajemen Keuangan',
            'system-administration' => 'Administrasi Sistem',
            'troubleshooting' => 'Pemecahan Masalah',
            'best-practices' => 'Best Practices',
            'comprehensive' => 'Panduan Lengkap'
        ];
    }

    /**
     * Extract YouTube video ID from URL
     */
    public function getYoutubeVideoIdAttribute()
    {
        if (!$this->youtube_url) {
            return null;
        }

        $url = $this->youtube_url;
        
        // Handle different YouTube URL formats
        if (preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/|youtube\.com\/embed\/)([a-zA-Z0-9_-]+)/', $url, $matches)) {
            return $matches[1];
        }
        
        return null;
    }

    /**
     * Get YouTube embed URL
     */
    public function getYoutubeEmbedUrlAttribute()
    {
        $videoId = $this->youtube_video_id;
        return $videoId ? "https://www.youtube.com/embed/{$videoId}" : null;
    }
}

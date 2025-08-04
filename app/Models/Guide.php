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
            'user-management' => 'Manajemen Pengguna',
            'transactions' => 'Transaksi',
            'financial-reports' => 'Laporan Keuangan',
            'master-data' => 'Data Master',
            'system-settings' => 'Pengaturan Sistem',
            'troubleshooting' => 'Pemecahan Masalah'
        ];
    }
}

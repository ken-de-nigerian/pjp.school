<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    public $timestamps = true;

    protected $table = 'news';

    protected $primaryKey = 'newsid';

    protected $fillable = [
        'title',
        'slug',
        'content',
        'category',
        'author',
        'image',
        'imagelocation',
        'date_added',
        'created_at',
        'updated_at',
    ];

    protected function casts(): array
    {
        return [
            'date_added' => 'datetime',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /** Cover image: legacy uses imagelocation; fallback to image. */
    public function getCoverImageAttribute(): ?string
    {
        return $this->imagelocation ?? $this->image ?? null;
    }
}

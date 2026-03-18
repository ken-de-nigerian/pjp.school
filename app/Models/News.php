<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property mixed $id
 * @property mixed $title
 */
class News extends Model
{
    public $timestamps = true;

    protected $table = 'news';

    protected $primaryKey = 'newsid';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'title',
        'slug',
        'content',
        'category',
        'author',
        'imagelocation',
        'date_added',
        'created_at',
        'updated_at',
        'newsid',
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
        return $this->imagelocation ?? null;
    }
}

<?php

namespace App\Domain\Resource;

use App\Domain\Comment\Comment;
use App\Domain\Folder\Folder;
use App\Domain\Tag\Tag;
use App\Domain\User\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Resource extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'name',
        'url',
        'description',
        'use_cases',
        'user_id',
        'folder_id',
    ];

    protected $appends = ['first_comments'];

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class, 'resource_id', 'id');
    }

    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'user_id', 'id');
    }

    public function folder(): HasOne
    {
        return $this->hasOne(Folder::class, 'folder_id', 'id');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(
            Tag::class, 
            'resource_tag', 
            'resource_id', 
            'tag_id', 
            'id', 
            'id'
        );
    }

    public function getFirstCommentsAttribute(): Collection
    {
        return $this->comments()->take(10)->get();
    }
}

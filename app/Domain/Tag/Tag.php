<?php

namespace App\Domain\Tag;

use App\Domain\Resource\Resource;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Tag extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = ['name', 'tag-key'];

    public function resources(): BelongsToMany
    {
        return $this->belongsToMany(
            Resource::class, 
            'resource_tag', 
            'tag_id', 
            'resource_id', 
            'id', 
            'id'
        );
    }
}

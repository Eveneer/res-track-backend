<?php

namespace App\Domain\Folder;

use App\Domain\Resource\Resource;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Folder extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'name',
        'parent_id'
    ];

    protected $appends = [
        'first_resources',
        'first_sub_folders'
    ];

    public function resources(): HasMany
    {
        return $this->hasMany(Resource::class, 'folder_id', 'id');
    }

    public function subFolders(): HasMany
    {
        return $this->hasMany(Folder::class, 'parent_id', 'id');
    }

    public function getFirstResourcesAttribute(): Collection
    {
        return $this->resources()->take(10)->get();
    }

    public function getFirstSubFoldersAttribute(): Collection
    {
        return $this->subFolders()->take(10)->get();
    }

}

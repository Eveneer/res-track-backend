<?php

declare(strict_types=1);

namespace App\Domain\Tag\Actions;

use App\Domain\Tag\Tag;
use Illuminate\Support\Str;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;


class CreateTag
{
    use AsAction;

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255']
        ];
    }

    public function handle(array $data): Tag
    {
        $data['tag_key'] = Str::kebab($data['name']);
        
        return Tag::create($data);
    }

    public function asController(ActionRequest $request): Tag
    {
        return $this->handle($request->validated());
    }

    public function jsonResponse(Tag $tag): array
    {
        return [
            'message' => 'Tag created',
            'data' => [
                'tag' => $tag
            ]
        ];
    }
}
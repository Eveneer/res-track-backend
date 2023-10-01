<?php

declare(strict_types=1);

namespace App\Domain\Tag\Actions;

use App\Domain\Tag\Tag;
use Illuminate\Support\Str;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;


class UpdateTag
{
    use AsAction;

    public function rules(): array
    {
        return [
            'id' => ['required', 'exists:tags,is'],
            'name' => ['required', 'string', 'max:255']
        ];
    }

    public function handle(array $data): Tag
    {
        $tag = Tag::find($data['id']);
        $data['tag_key'] = Str::kebab($data['name']);
        $tag->fill($data);
        $tag->save();

        return $tag;
    }

    public function asController(ActionRequest $request): Tag
    {
        return $this->handle($request->validated());
    }

    public function jsonResponse(Tag $tag): array
    {
        return [
            'message' => 'Tag updated',
            'data' => [
                'tag' => $tag
            ]
        ];
    }
}
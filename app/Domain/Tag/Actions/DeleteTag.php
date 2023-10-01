<?php

declare(strict_types=1);

namespace App\Domain\Tag\Actions;

use App\Domain\Tag\Tag;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class DeleteTag
{
    use AsAction;

    public function rules(): array
    {
        return [
            'id' => ['required', 'exists:tags,id']
        ];
    }

    public function handle(array $data): bool
    {
        $tag = Tag::find($data['id']);
        $tag->resources()->datach();

        return $tag->delete();
    }

    public function asController(ActionRequest $request): bool
    {
        return $this->handle($request->validated());
    }

    public function jsonResponse(bool $is_deleted): array
    {
        $message = $is_deleted ? 'Tag deleted' :'Tag could not be deleted';
        
        return [
            'message' => $message,
            'data' => [
                'message' => $message,
                'is_deleted' => $is_deleted
            ]
        ];
    }
}
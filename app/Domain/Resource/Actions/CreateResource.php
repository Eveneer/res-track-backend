<?php

declare(strict_types=1);

namespace App\Domain\Resource\Actions;

use App\Domain\Resource\Resource;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class CreateResource
{
    use AsAction;

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'url' => ['sometimes', 'string', 'url:http,https'],
            'description' => ['sometimes', 'string'],
            'use_cases' => ['sometimes', 'array'],
            'use_cases.*' => ['sometimes', 'string'],
            'user_id' => ['required', 'exists:users,id'],
            'folder_id' => ['sometimes', 'exists:folders,id']
        ];
    }

    public function prepareForValidation(ActionRequest $request): void
    {
        $request->mergeIfMissing(['user_id' => auth()->user()->id]);
    }

    public function handle(array $data): Resource
    {
        return Resource::create($data);
    }

    public function asController(ActionRequest $request): Resource
    {
        return $this->handle($request->validated());
    }

    public function jsonResponse(Resource $resource): array
    {
        return [
            'message' => 'Resource created',
            'data' => [
                'resource' => $resource
            ]
        ];
    }
}
<?php

declare(strict_types=1);

namespace App\Domain\Resource\Actions;

use App\Domain\Resource\Resource;
use Illuminate\Auth\Access\Response;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class UpdateResource
{
    use AsAction;

    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'string', 'max:255'],
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

    public function authorize(ActionRequest $request): Response
    {
        if (auth()->user()->id !== $request->user_id) {
            return Response::deny('You do not have permission to edit this resource');
        }

        return Response::allow();
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
            'message' => 'Resource updated',
            'data' => [
                'resource' => $resource
            ]
        ];
    }
}
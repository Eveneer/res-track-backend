<?php

declare(strict_types=1);

namespace App\Domain\Resource\Actions;

use App\Domain\Resource\Resource;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class GetResource
{
    use AsAction;

    public function rules(): array
    {
        return [
            'id' => ['required', 'exists:resources,id']
        ];
    }

    public function handle(array $data): Resource
    {
        return Resource::find($data['id']);
    }

    public function asController(ActionRequest $request): Resource
    {
        return $this->handle($request->validated());
    }

    public function jsonResponse(Resource $resource): array
    {
        return [
            'message' => 'Resource retrieved',
            'data' => [
                'resource' => $resource,
            ]
        ];
    }
}
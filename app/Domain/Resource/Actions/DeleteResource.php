<?php

declare(strict_types=1);

namespace App\Domain\Resource\Actions;

use App\Domain\Resource\Resource;
use Illuminate\Auth\Access\Response;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class DeleteResource
{
    use AsAction;

    public function rules(): array
    {
        return [
            'resource_id' => ['required', 'exists:resources,id'],
            'user_id' => ['required', 'exists:users,id']
        ];
    }

    public function prepareForValidation(ActionRequest $request): void
    {
        $request->mergeIfMissing(['user_id' => auth()->user()->id]);
    }

    public function authorize(ActionRequest $request): Response
    {
        if (auth()->user()->id !== $request->user_id) {
            return Response::deny('You do not have permission to delete this resource');
        }

        return Response::allow();
    }

    public function handle(array $data): bool
    {
        return Resource::find($data['id'])->delete();
    }

    public function asController(ActionRequest $request): bool
    {
        return $this->handle($request->validated());
    }

    public function jsonResponse(bool $is_deleted): array
    {
        $message = $is_deleted ? 'Resource deleted' : 'Resource could not be deleted';
        return [
            'message' => $message,
            'data' => [
                'message' => $message,
                'is_deleted' => $is_deleted
            ]
        ];
    }
}
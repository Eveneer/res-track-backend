<?php

declare(strict_types=1);

namespace App\Domain\Comment\Actions;

use App\Domain\Comment\Comment;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class GetResourceComments
{
    use AsAction;

    public function rules(): array
    {
        return [
            'resource_id' => ['required', 'exists:resources,id'],
            'page' => ['sometimes', 'integer', 'min:1']
        ];
    }

    public function handle(array $data): LengthAwarePaginator
    {
        return Comment::whereResourceId($data['resource_id'])->paginate(5, ['*'], $data['page'] ?? 1);
    }

    public function asController(ActionRequest $request): LengthAwarePaginator
    {
        return $this->handle($request->validated());
    }

    public function jsonResponse(LengthAwarePaginator $comments): array
    {
        return [
            'message' => 'Resource comments',
            'data' => [
                'comments' => $comments
            ]
        ];
    }
}
<?php

declare(strict_types=1);

namespace App\Domain\Comment\Actions;

use App\Domain\Comment\Comment;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class DeleteResourceComments
{
    use AsAction;

    public function rules(): array
    {
        return [
            'resource_id' => ['required', 'exists:resources,id']
        ];
    }

    public function handle(array $data): array
    {
        $comments = Comment::whereResourceId($data['resource_id'])->get();
        $counts = ['total' => count($comments), 'deleted' => 0];

        foreach ($comments as $comment) {
            $counts['deleted'] += $comment->delete() ? 1 : 0;
        }

        return $counts;
    }

    public function asController(ActionRequest $request): array
    {
        return $this->handle($request->validated());
    }

    public function jsonResponse(array $delete_counts): array
    {
        return [
            'message' => 'Completed running deletes',
            'data' => $delete_counts
        ];
    }
}
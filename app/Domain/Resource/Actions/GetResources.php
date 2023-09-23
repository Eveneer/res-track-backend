<?php

declare(strict_types=1);

namespace App\Domain\Resource\Actions;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class GetResources
{
    use AsAction;

    public function rules(): array
    {
        return [
            'ids' => ['sometimes', 'array'],
            'ids.*' => ['sometimes', 'exists:resources,id'],
            'folder_id' => ['sometimes', 'exists:folders,id'],
            'user_id' => ['sometimes', 'exists:users,id'],
            'search' => ['sometimes', 'string', 'max:255'],
            'page' => ['sometimes', 'integer', 'min:1']
        ];
    }

    public function handle(array $data): LengthAwarePaginator
    {
        $query = DB::table('resources');
        if (isset($data['ids'])) {
            $query = $query->whereIn('id', $data['ids']);
        } else {
            if (isset($data['user_id'])) {
                $query = $query->whereUserId($data['user_id']);
            }

            if (isset($data['folder_id'])) {
                $query = $query->whereFolderId($data['folder_id']);
            }

            if (isset($data['search'])) {
                $query = $query->where('name', 'like', "%" . $data['search'] . "%");
                $query = $query->where('description', 'like', "%" . $data['search'] . "%");
                $query = $query->where('url', 'like', "%" . $data['search'] . "%");
                $query = $query->orWhereRaw('use_cases->"$[*].*" like ?', "%" . $data['search'] . "%");
            }
        }

        return $query->paginate(5, ['*'], $data['page'] ?? 1);
    }

    public function asController(ActionRequest $request): LengthAwarePaginator
    {
        return $this->handle($request->validated());
    }

    public function jsonResponse(LengthAwarePaginator $data): array
    {
        return [
            'message' => 'Resources retrieved',
            'data' => [
                'resources' => $data
            ]
        ];
    }
}
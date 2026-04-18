<?php

declare(strict_types=1);

namespace App\Domain\Lead;

use App\Models\Lead;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class LeadService
{
    public function getAll(): Collection
    {
        return Lead::all();
    }

    public function getById(int $id): ?Lead
    {
        return Lead::find($id);
    }

    public function getByUuid(string $uuid): ?Lead
    {
        return Lead::where('uuid', $uuid)->first();
    }

    public function search(?string $search = null, string $sort = 'created_at', string $direction = 'desc'): LengthAwarePaginator
    {
        return Lead::query()
            ->when($search, fn ($query) => $query->where('title', 'like', sprintf('%%%s%%', $search)))
            ->orderBy($sort, $direction)
            ->paginate(10)
            ->withQueryString();
    }

    public function create(array $data): Lead
    {
        return Lead::create($data);
    }

    public function update(Lead $lead, array $data): Lead
    {
        $lead->update($data);

        return $lead->fresh();
    }

    public function delete(Lead $lead): int|bool
    {
        return $lead->delete();
    }
}

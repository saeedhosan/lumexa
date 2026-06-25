<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Override;

class CompanyCollection extends ResourceCollection
{
    public $collects = CompanyResource::class;

    #[Override]
    public function toArray(Request $request): array
    {
        return [
            'data' => $this->collection,
            'meta' => [
                'current_page'   => $this->currentPage(),
                'last_page'      => $this->lastPage(),
                'per_page'       => $this->perPage(),
                'total'          => $this->total(),
                'first_page_url' => $this->url(1),
                'last_page_url'  => $this->url($this->lastPage()),
                'next_page_url'  => $this->nextPageUrl(),
                'prev_page_url'  => $this->previousPageUrl(),
            ],
        ];
    }
}

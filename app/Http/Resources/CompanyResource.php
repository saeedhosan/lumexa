<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Override;

class CompanyResource extends JsonResource
{
    #[Override]
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'uuid'        => $this->uuid,
            'name'        => $this->name,
            'slug'        => $this->slug,
            'logo'        => $this->logo,
            'title'       => $this->title,
            'description' => $this->description,
            'language'    => $this->language,
            'timezone'    => $this->timezone,
            'currency'    => $this->currency,
            'country'     => $this->country,
            'is_active'   => $this->is_active,
            'plan'        => $this->whenLoaded('plan', fn (): array => [
                'id'   => $this->plan?->id,
                'name' => $this->plan?->name,
            ]),
            'created_at' => $this->created_at?->toIsoString(),
            'updated_at' => $this->updated_at?->toIsoString(),
        ];
    }
}

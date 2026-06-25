<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Override;

class ServiceResource extends JsonResource
{
    #[Override]
    public function toArray(Request $request): array
    {
        return [
            'id'         => $this->id,
            'uuid'       => $this->uuid,
            'name'       => $this->name,
            'slug'       => $this->slug,
            'icon'       => $this->icon,
            'logo'       => $this->logo,
            'code'       => $this->code,
            'about'      => $this->about,
            'is_active'  => $this->is_active,
            'version'    => $this->version,
            'provider'   => $this->provider,
            'features'   => $this->features,
            'created_at' => $this->created_at?->toIsoString(),
            'updated_at' => $this->updated_at?->toIsoString(),
        ];
    }
}

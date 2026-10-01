<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VacancyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     * ТЗ п. 4.1: API для внешних потребителей
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'salary_min' => $this->salary_min,
            'salary_max' => $this->salary_max,
            'currency' => $this->currency,
            'city' => $this->city,
            'url' => $this->url,
            'published_at' => $this->published_at?->toDateTimeString(),
            'source' => [
                'id' => $this->source->id ?? null,
                'name' => $this->source->name ?? null,
            ],
            'tags' => $this->whenLoaded('tags', fn() => $this->tags->pluck('name')),
            'employer' => [
                'id' => $this->employer->id ?? null,
                'company_name' => $this->employer->company_name ?? null,
            ],
            'created_at' => $this->created_at->toDateTimeString(),
        ];
    }
}
<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ExperienceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'companyLogo' => $this->companyLogo,
            'link' => $this->link,
            'startDate' => $this->start_date->format('F j, Y'),
            'endDate' => $this->end_date->format('F j, Y'),
            'createdAt' => $this->created_at->format('F j, Y'),
            'updatedAt' => $this->updated_at->format('F j, Y'),
        ];
    }
}

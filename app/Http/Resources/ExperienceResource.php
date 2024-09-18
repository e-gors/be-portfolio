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
            'jobPosition' => $this->job_position,
            'companyName' => $this->company_name,
            'description' => $this->description,
            'companyLogo' => $this->companyLogo,
            'link' => $this->link,
            'startDate' => $this->start_date,
            'endDate' => $this->end_date ?? "Now",
            'createdAt' => $this->created_at->format('F j, Y'),
            'updatedAt' => $this->updated_at->format('F j, Y'),
        ];
    }
}

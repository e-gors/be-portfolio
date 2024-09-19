<?php

namespace App\Http\Resources;

use App\Models\Project;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResource extends JsonResource
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
            'type' => $this->type,
            'name' => $this->name,
            'link' => $this->link,
            'picture' => $this->picture,
            'description' => $this->description,
            'totalProjects' => Project::count(),
            'createdAt' => $this->created_at->format('F j, Y'),
            'updatedAt' => $this->updated_at->format('F j, Y'),
        ];
    }
}

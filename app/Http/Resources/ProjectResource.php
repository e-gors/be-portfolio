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
        $totalClients = Project::count();
        
        // Avoid division by zero
        if ($totalClients > 0) {
            $worldwide = Project::where('client_type', 'worldwide')->count();
            $local = Project::where('client_type', 'local')->count();

            $worldwidePercentage = ($worldwide / $totalClients) * 100;
            $localPercentage = ($local / $totalClients) * 100;
        } else {
            $worldwidePercentage = 0;
            $localPercentage = 0;
        }

        return [
            'id' => $this->id,
            'clientType' => $this->client_type,
            'type' => $this->type,
            'name' => $this->name,
            'link' => $this->link,
            'picture' => $this->picture,
            'description' => $this->description,
            'totalProjects' => Project::count(),
            'worldwide' => $worldwidePercentage,
            'local' => $localPercentage,
            'createdAt' => $this->created_at->format('F j, Y'),
            'updatedAt' => $this->updated_at->format('F j, Y'),
        ];
    }
}

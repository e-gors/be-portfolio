<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FeedbackResource extends JsonResource
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
            'userId' => $this->user_id,
            'profileImage' => $this->profile_image,
            'guestName' => $this->guest_name,
            'project' => $this->project,
            'message' => $this->message,
            'status' => $this->status,
            'rating' => $this->rating,
            'createdAt' => $this->created_at->format('F j, Y'),
            'updatedAt' => $this->updated_at->format('F j, Y'),
            'user' => $this->whenLoaded('user')
        ];
    }
}

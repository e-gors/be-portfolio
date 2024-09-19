<?php

namespace App\Http\Resources;

use App\Models\Experience;
use Carbon\Carbon;
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
        $dateRanges = Experience::whereNotNull('end_date')->get();

        $totalYears = $dateRanges->reduce(function ($carry, $dateRange) {
            // Parse the start and end dates
            $startDate = Carbon::parse($dateRange->start_date);
            $endDate = Carbon::parse($dateRange->end_date);

            // Calculate the difference in years
            $yearsDifference = $endDate->diffInYears($startDate);

            // Calculate the remaining months after full years and convert to decimal (e.g., 6 months = 0.5 years)
            $monthsDifference = $endDate->diffInMonths($startDate) % 12;
            $decimalMonths = $monthsDifference / 12;

            // Add both the year and the decimal part of months to the total
            return $carry + $yearsDifference + $decimalMonths;
        }, 0);

        return [
            'id' => $this->id,
            'jobPosition' => $this->job_position,
            'companyName' => $this->company_name,
            'description' => $this->description,
            'companyLogo' => $this->companyLogo,
            'link' => $this->link,
            'startDate' => $this->start_date,
            'endDate' => $this->end_date ?? "Now",
            'totalExperience' => round($totalYears, 1),
            'createdAt' => $this->created_at->format('F j, Y'),
            'updatedAt' => $this->updated_at->format('F j, Y'),
        ];
    }
}

<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        self::withoutWrapping();
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'due_date' => Carbon::parse($this->due_date),
            'status' => $this->status,
            'assignee' => $this->user_id,
            'parent_task' => $this->when($this->parent_id, $this->parent_id),
            'dependencies' => $this->whenLoaded(
                'tasks',
                fn() => TaskResource::collection($this->tasks)
            )
        ];
    }
}

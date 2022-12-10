<?php

namespace App\Events;

use App\Services\PrettyIdGenerator;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SetModelPrettyId
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Model $model)
    {
        $model->pretty_id = PrettyIdGenerator::generate('questions', 'ques_', 13);
    }
}

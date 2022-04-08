<?php

namespace App\Policies;

use App\Models\Delivery;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DeliveryPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function update_delivery(User $user, $id)
    {
        $data = Delivery::findOrFail($id);
        return $user->id === $data->user_id;
    }
    
    public function delete_delivery(User $user, $id)
    {
        $data = Delivery::findOrFail($id);
        return $user->id === $data->user_id;
    }
}

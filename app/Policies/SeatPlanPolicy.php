<?php

namespace App\Policies;

use App\Models\SeatPlan;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class SeatPlanPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, SeatPlan $seatPlan): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, SeatPlan $seatPlan): bool
    {
        return $user->id === 1 || $user->id === $seatPlan->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, SeatPlan $seatPlan): bool
    {
        return $user->id === 1 || $user->id === $seatPlan->user_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, SeatPlan $seatPlan): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, SeatPlan $seatPlan): bool
    {
        return false;
    }
}

<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\User;
use App\Enums\UserRole;

class OrderPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->role === UserRole::ADMIN->value;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Order $order): bool
    {
        // Admin can see everything, customer only their own
        return $user->role === UserRole::ADMIN->value || $user->id === $order->user_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true; // Any authenticated user can create an order (checkout)
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Order $order): bool
    {
        return $user->role === UserRole::ADMIN->value;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Order $order): bool
    {
        return $user->role === UserRole::ADMIN->value;
    }
}

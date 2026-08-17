<?php

namespace App\Services;

use App\Models\InAppNotification;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Throwable;

class NotificationService
{
    /**
     * Send one in-app notification to one user.
     *
     * IMPORTANT:
     * Notification failures must never break the main transaction.
     */
    public function send(
        ?int $userId,
        string $title,
        ?string $message = null,
        string $type = 'info',
        ?string $url = null,
        ?string $icon = null,
        array $data = [],
        ?int $actorId = null
    ): ?InAppNotification {
        if (!$userId) {
            return null;
        }

        if (!Schema::hasTable('in_app_notifications')) {
            return null;
        }

        try {
            return InAppNotification::create([
                'user_id' => $userId,
                'actor_id' => $actorId ?? Auth::id(),
                'title' => $title,
                'message' => $message,
                'type' => $type,
                'icon' => $icon ?? $this->defaultIcon($type),
                'url' => $url,
                'data' => $data,
                'is_read' => false,
                'read_at' => null,
            ]);
        } catch (Throwable $e) {
            report($e);

            return null;
        }
    }

    /**
     * Send the same notification to multiple users.
     */
    public function sendMany(
        iterable $userIds,
        string $title,
        ?string $message = null,
        string $type = 'info',
        ?string $url = null,
        ?string $icon = null,
        array $data = [],
        ?int $actorId = null
    ): void {
        foreach ($userIds as $userId) {
            $this->send(
                (int) $userId,
                $title,
                $message,
                $type,
                $url,
                $icon,
                $data,
                $actorId
            );
        }
    }

    /**
     * Send a notification to all users with a given role.
     */
    public function sendToRole(
        string $role,
        string $title,
        ?string $message = null,
        string $type = 'info',
        ?string $url = null,
        ?string $icon = null,
        array $data = [],
        ?int $actorId = null,
        array $excludeUserIds = []
    ): void {
        $query = User::query()
            ->whereRaw('LOWER(role) = ?', [strtolower($role)]);

        if (!empty($excludeUserIds)) {
            $query->whereNotIn('id', $excludeUserIds);
        }

        $this->sendMany(
            $query->pluck('id'),
            $title,
            $message,
            $type,
            $url,
            $icon,
            $data,
            $actorId
        );
    }

    /**
     * Shortcut for all admin users.
     */
    public function sendToAdmins(
        string $title,
        ?string $message = null,
        string $type = 'info',
        ?string $url = '/admin/dashboard',
        ?string $icon = 'bi-shield-check',
        array $data = [],
        ?int $actorId = null
    ): void {
        $this->sendToRole(
            'admin',
            $title,
            $message,
            $type,
            $url,
            $icon,
            $data,
            $actorId
        );
    }

    /**
     * Send a notification to every registered user.
     */
    public function sendToAllUsers(
        string $title,
        ?string $message = null,
        string $type = 'announcement',
        ?string $url = null,
        ?string $icon = 'bi-megaphone-fill',
        array $data = [],
        ?int $actorId = null,
        array $excludeUserIds = []
    ): void {
        $query = User::query();

        if (!empty($excludeUserIds)) {
            $query->whereNotIn('id', $excludeUserIds);
        }

        $this->sendMany(
            $query->pluck('id'),
            $title,
            $message,
            $type,
            $url,
            $icon,
            $data,
            $actorId
        );
    }

    /**
     * Default Bootstrap icon for each notification type.
     */
    private function defaultIcon(string $type): string
    {
        return match ($type) {
            'order' => 'bi-bag-check-fill',
            'delivery' => 'bi-truck',
            'payment' => 'bi-cash-coin',
            'account' => 'bi-person-check-fill',
            'milling' => 'bi-gear-wide-connected',
            'inventory' => 'bi-box-seam-fill',
            'distribution' => 'bi-box2-heart-fill',
            'product' => 'bi-basket2-fill',
            'announcement' => 'bi-megaphone-fill',
            'success' => 'bi-check-circle-fill',
            'warning' => 'bi-exclamation-triangle-fill',
            default => 'bi-bell-fill',
        };
    }
}
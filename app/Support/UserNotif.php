<?php

namespace App\Support;

use App\Models\User;
use App\Models\UserNotification;
use Illuminate\Support\Collection;

final class UserNotif
{
    /**
     * @param  array{url?: string, icon?: string}  $data
     */
    public static function push(
        User $user,
        string $title,
        ?string $body = null,
        string $type = 'info',
        array $data = []
    ): UserNotification {
        return UserNotification::query()->create([
            'user_id' => $user->id,
            'title' => $title,
            'body' => $body,
            'type' => $type,
            'data' => $data,
        ]);
    }

    /**
     * @param  iterable<User>  $users
     * @param  array{url?: string, icon?: string}  $data
     */
    public static function pushToMany(iterable $users, string $title, ?string $body = null, string $type = 'info', array $data = []): void
    {
        if ($users instanceof Collection) {
            $ids = $users->pluck('id')->all();
        } else {
            $ids = [];
            foreach ($users as $user) {
                $ids[] = $user->id;
            }
        }

        $ids = array_values(array_unique(array_filter($ids, fn ($id) => (int) $id > 0)));
        if (empty($ids)) {
            return;
        }

        $now = now();
        $rows = array_map(fn ($id) => [
            'user_id' => $id,
            'title' => $title,
            'body' => $body,
            'type' => $type,
            'data' => json_encode($data),
            'created_at' => $now,
            'updated_at' => $now,
        ], $ids);

        UserNotification::query()->insert($rows);
    }
}
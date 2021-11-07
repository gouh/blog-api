<?php

namespace Gouh\BlogApi\App\DTO\Strategy;

use Gouh\BlogApi\App\Entity\Role;
use Gouh\BlogApi\App\Entity\User;

class UserArrayStrategy implements InterfaceStrategy
{
    /**
     * @param Role|null $role
     * @return array
     */
    public function mapRoleUser(?Role $role): array
    {
        $roleMap = [];
        if ($role != null) {
            $roleMap = [
                'roleId' => $role->getRoleId(),
                'name' => $role->getName(),
            ];
        }
        return $roleMap;
    }

    /**
     * @param User $user
     * @return array
     */
    public function mapUser(User $user): array
    {
        return [
            'userId' => $user->getUserId(),
            'name' => $user->getName(),
            'lastName' => $user->getLastName(),
            'email' => $user->getEmail(),
            'role' => $this->mapRoleUser($user->getRole()),
        ];
    }

    /**
     * @param $users
     * @return array
     */
    private function usersToArray($users): array
    {
        return array_map(function ($user) {
            return $this->mapUser($user);
        }, $users);
    }

    /**
     * @param User|array $data
     * @return mixed
     */
    public function build($data): array
    {
        if (is_array($data)) {
            $result = $this->usersToArray($data);
        } else {
            $result = $this->mapUser($data);
        }
        return $result;
    }
}

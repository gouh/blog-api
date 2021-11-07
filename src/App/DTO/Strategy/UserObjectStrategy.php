<?php

namespace Gouh\BlogApi\App\DTO\Strategy;

use Gouh\BlogApi\App\Entity\User;

class UserObjectStrategy implements InterfaceStrategy
{
    /**
     * @param array $data
     * @return User
     */
    private function arrayToUser(array $data): User
    {
        $user = new User;
        $user->setName($data['name'] ?? '');
        $user->setLastName($data['lastName'] ?? '');
        $user->setEmail($data['email'] ?? '');
        $user->setPassword($data['password'] ?? '');
        $user->setRoleId($data['roleId'] ?? '');
        return $user;
    }

    /**
     * @param array|object $data
     * @return mixed|void
     */
    public function build($data)
    {
        return $this->arrayToUser($data);
    }
}

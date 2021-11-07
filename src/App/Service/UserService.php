<?php

namespace Gouh\BlogApi\App\Service;

use Gouh\BlogApi\App\DAO\InterfaceDAO;
use Gouh\BlogApi\App\DTO\InterfaceDTO;
use Gouh\BlogApi\App\DTO\Strategy\UserArrayStrategy;
use Gouh\BlogApi\App\DTO\Strategy\UserObjectStrategy;
use Gouh\BlogApi\App\Entity\Role;
use Gouh\BlogApi\App\Entity\User;

class UserService
{
    /** @var InterfaceDAO  */
    private InterfaceDAO $userDao;

    /** @var InterfaceDAO  */
    private InterfaceDAO $roleDao;

    /** @var InterfaceDTO  */
    private InterfaceDTO $userDto;

    public function __construct(InterfaceDAO $userDao, InterfaceDAO $roleDao, InterfaceDTO $userDto)
    {
        $this->userDao = $userDao;
        $this->roleDao = $roleDao;
        $this->userDto = $userDto;
    }

    /**
     * @param array $data
     * @return array
     */
    public function save(array $data): array
    {
        /** @var User $user */
        $user = $this->userDto->map($data, UserObjectStrategy::class);
        $user = $this->userDao->save($user);
        /** @var Role $role */
        $role = $this->roleDao->find($user->getRoleId());
        $user->setRole($role);
        return $this->userDto->map($user, UserArrayStrategy::class);
    }
}

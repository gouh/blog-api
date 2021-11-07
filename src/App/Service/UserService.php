<?php

namespace Gouh\BlogApi\App\Service;

use Gouh\BlogApi\App\DAO\InterfaceDAO;
use Gouh\BlogApi\App\DTO\InterfaceDTO;
use Gouh\BlogApi\App\DTO\Strategy\UserArrayStrategy;
use Gouh\BlogApi\App\DTO\Strategy\UserObjectStrategy;
use Gouh\BlogApi\App\Entity\Role;
use Gouh\BlogApi\App\Entity\User;
use Gouh\BlogApi\App\Traits\EncryptTrait;

class UserService
{
    use EncryptTrait;

    /** @var InterfaceDAO  */
    private InterfaceDAO $userDao;

    /** @var InterfaceDAO  */
    private InterfaceDAO $roleDao;

    /** @var InterfaceDTO  */
    private InterfaceDTO $userDto;

    /** @var array  */
    private array $config;

    public function __construct(InterfaceDAO $userDao, InterfaceDAO $roleDao, InterfaceDTO $userDto, array $config)
    {
        $this->userDao = $userDao;
        $this->roleDao = $roleDao;
        $this->userDto = $userDto;
        $this->config = $config;
    }

    /**
     * @param array $data
     * @return array
     */
    public function save(array $data): array
    {
        /** @var User $user */
        $user = $this->userDto->map($data, UserObjectStrategy::class);
        $user->setPassword($this->encrypt($user->getPassword()));
        $user = $this->userDao->save($user);
        /** @var Role $role */
        $role = $this->roleDao->find($user->getRoleId());
        $user->setRole($role);
        return $this->userDto->map($user, UserArrayStrategy::class);
    }
}

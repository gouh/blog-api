<?php

namespace Gouh\BlogApi\App\Service;

use Gouh\BlogApi\App\Traits\EncryptTrait;

class RegisterService
{
    use EncryptTrait;

    /** @var UserService */
    private UserService $userService;

    /** @var array */
    private array $config;

    public function __construct(UserService $userService, array $config)
    {
        $this->userService = $userService;
        $this->config = $config;
    }

    /**
     * @param $data
     * @return array
     */
    public function register($data)
    {
        $data['password'] = $this->encrypt($data['password']);
        return $this->userService->save($data);
    }
}

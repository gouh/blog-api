<?php

namespace Gouh\BlogApi\App\Service;

use Gouh\BlogApi\App\DAO\InterfaceDAO;
use Gouh\BlogApi\App\Entity\User;
use Gouh\BlogApi\App\Traits\EncryptTrait;
use Gouh\BlogApi\App\Traits\JWTTrait;

class LoginService
{
    use EncryptTrait;
    use JWTTrait;

    /** @var InterfaceDAO */
    private InterfaceDAO $userDao;

    /** @var array */
    private array $config;

    /**
     * @param InterfaceDAO $userDao
     * @param array $config
     */
    public function __construct(InterfaceDAO $userDao, array $config)
    {
        $this->userDao = $userDao;
        $this->config = $config;
    }

    /**
     * @param array $data
     * @return array
     */
    public function login(array $data): array
    {
        $user = $this->userDao->findBy(['email' => $data['email']]);
        $userFound = [];
        if (!empty($user)) {
            /** @var User $user */
            $user = $user[0];
            if ($user->getPassword() == $this->encrypt($data['password'])) {
                $headers = [
                    'alg' => 'HS256',
                    'typ' => 'JWT'
                ];
                $payload = [
                    'user' => $user->getUserId(),
                    'role' => $user->getRoleId(),
                    'exp' => strtotime('+30 day')
                ];
                $userFound = ['jwt' => $this->generateJwt($headers, $payload)];
            }
        }
        return $userFound;
    }
}

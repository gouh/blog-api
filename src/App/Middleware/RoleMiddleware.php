<?php

namespace Gouh\BlogApi\App\Middleware;

use Gouh\BlogApi\App\Traits\JWTTrait;
use Gouh\BlogApi\Request\ServerRequest;
use Gouh\BlogApi\Response\ServerResponse;

class RoleMiddleware
{
    use JWTTrait;

    # 1 -> Rol básico - permiso de acceso
    # 2 -> Rol medio - permiso de acceso y consulta
    # 3 -> Rol medio alto - Permiso de de acceso y agregar
    # 4 -> Rol alto medio - permiso de acceso, consulta, agregar y actualizar
    # 5 -> Rol alto - Permiso de acceso, consulta, agregar, actualizar y eliminar
    private const ROLE = [1, 2, 3, 4, 5];

    private array $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function process(ServerRequest $request): ServerRequest
    {
        $jwtPayload = $this->validJwt($this->getJwt($request));
        if (empty($jwtPayload)) {
            ServerResponse::JsonResponse([
                'message' => 'Invalid jwt.',
                'data' => [],
                'paginate' => [],
            ], 403);
        }

        $role = intval($jwtPayload['role']);
        if (in_array($role, self::ROLE)) {
            $requiredRole = $request->getAttribute('requiredRole');
            if (in_array($role, explode(',', $requiredRole))) {
                $body = $request->getParsedBody();
                $body['userId'] = $jwtPayload['user'];
                $request->setParsedBody($body);
            } else {
                ServerResponse::JsonResponse([
                    'message' => 'You do not have access to the indicated resource.',
                    'data' => [],
                    'paginate' => [],
                ], 401);
            }
        } else {
            ServerResponse::JsonResponse([
                'message' => 'Invalid role.',
                'data' => [],
                'paginate' => [],
            ], 401);
        }

        return $request;
    }

    private function getJwt(ServerRequest $request): string
    {
        $headers = $request->getHeaders();
        $jwt = '';
        if (isset($headers['Authorization'])) {
            if ($headers['Authorization']) {
                $bearer = explode(" ", $headers['Authorization']);
                $jwt = $bearer[1];
            }
        } else {
            ServerResponse::JsonResponse([
                'message' => 'Unauthorized',
                'data' => [],
                'paginate' => [],
            ], 401);
        }
        return $jwt;
    }
}

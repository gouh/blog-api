<?php

namespace Gouh\BlogApi\Response;

class ServerResponse extends ResponseCode
{
    public static function JsonResponse($data, $code = 200)
    {
        header('Content-Type: application/json; charset=utf-8');
        self::httpResponseCode($code);
        echo json_encode($data);
        exit();
    }
}

<?php

class HttpCodes
{
    public const HTTP_OK = 200;
    public const HTTP_CREATED = 201;
    public const HTTP_NO_CONTENT = 204;
    public const HTTP_BAD_REQUEST = 400;
    public const HTTP_NOT_FOUND = 404;
    public const HTTP_INTERNAL_SERVER_ERROR = 500;

    public static function sendResponse($statusCode, $message)
    {
        $response = array(
            'status' => $statusCode,
            'message' => $message,
        );

        header('Content-Type: application/json');
        http_response_code($statusCode);
        echo json_encode($response);
        exit();
    }
}

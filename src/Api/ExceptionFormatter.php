<?php

namespace App\Api;

use Symfony\Component\HttpFoundation\Response;

class ExceptionFormatter {

    public static function formatException($message) {
        return new Response(json_encode($message), 500, [
            "Content-Type" => "application/json"
        ]);
    }

}
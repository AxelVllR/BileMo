<?php 

namespace App\Api;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Normalizer\NormalizableInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class ApiHelper {

    private NormalizerInterface $normalizer;

    public function __construct(NormalizerInterface $normalizer)
    {
        $this->normalizer = $normalizer;
    }

    public function response($value, $code) {
        return new Response(json_encode($value), $code, [
            "Content-Type" => "application/json"
        ]);
    }

    public function normalizeProducts($products) {
        return $this->normalizer->normalize($products);
    }

    public function normalizeUsers($users) {
        return $this->normalizer->normalize($users, null, [
            "groups" => "users:read"
        ]);
    }

}
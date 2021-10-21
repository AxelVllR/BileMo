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

    public function notFoundResonse() {
        $error = json_encode([
            "error" => "data not found"
        ]);
        return new Response($error, 404, [
            "Content-Type" => "application/json"
        ]);
    }

    public function forbiddenResponse() {
        $error = json_encode([
            "error" => "forbidden: you cannot do this action"
        ]);
        return new Response($error, 403, [
            "Content-Type" => "application/json"
        ]);
    }

    public function deleteReponse() {
        return new Response(null, 204, [
            "Content-Type" => "application/json"
        ]);
    }

    public function createdResponse($entity) {
        return new Response(json_encode($entity), 201, [
            "Content-Type" => "application/json"
        ]);
    }

    public function badRequest($error) {
        return new Response(json_encode($error), 400, [
            "Content-Type" => "application/json"
        ]);
    }

    public function updatedResponse($entity) {
        return new Response(json_encode($entity), 200, [
            "Content-Type" => "application/json"
        ]);
    }

    public function normalizeProducts($products) {
        return $this->normalizer->normalize($products, null, [
            "groups" => "products"
        ]);
    }
    public function normalizeProduct($product) {
        return $this->normalizer->normalize($product, null, [
            "groups" => "product"
        ]);
    }

    public function normalizeUsers($users) {
        return $this->normalizer->normalize($users, null, [
            "groups" => "users:read"
        ]);
    }

    public function normalizeUser($user) {
        return $this->normalizer->normalize($user, null, [
            "groups" => "user:read"
        ]);
    }

}
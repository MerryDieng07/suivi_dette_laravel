<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

interface AuthentificationServiceInterface
{
    public function login(Request $request): JsonResponse;
    public function register(Request $request): JsonResponse;
    public function logout(Request $request): JsonResponse;
}

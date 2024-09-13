<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\App\Services\PassportAuthentificationService;
use App\Services\AuthentificationServiceInterface;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthentificationServiceInterface $authService)
    
    {
        
        $this->authService = $authService;
    }

    public function login(Request $request)
    {
        return $this->authService->login($request);
    }

    public function register(Request $request)
    {
        //dd($request);  // pour vérifier les données entrées dans la requête
        return $this->authService->register($request);
    }

    public function logout(Request $request)
    {
        return $this->authService->logout($request);
    }
    
}

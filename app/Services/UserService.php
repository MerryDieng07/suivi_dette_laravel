<?php

namespace App\Services;

use App\Models\User;
use Illuminate\http\UploadedFile;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;
use App\Services\UploadServiceInterface;

class UserService
{
    protected $userRepository;
    protected $uploadService;

    public function __construct(UserRepository $userRepository, UploadServiceInterface $uploadService)
    {
        $this->userRepository = $userRepository;
        $this->uploadService = $uploadService;
    }

    public function getAllUsers($active = null, $role = null)
    {
        return $this->userRepository->getAll($active, $role);
    }

    public function getUserById(int $id)
    {
        return $this->userRepository->findById($id);
    }

    public function createUser(array $data): User
    {
        // Hash du mot de passe
        $data['password'] = Hash::make($data['password']);
        
        // Upload de la photo sur Cloudinary si elle existe
        if (isset($data['photo']) && $data['photo'] instanceof UploadedFile) {
            $data['photo'] = $this->uploadService->uploadFile($data['photo'], 'users_photos');
        }
        
        return $this->userRepository->create($data);
    }

    public function updateUser(int $id, array $data): User
    {
        // Si un nouveau mot de passe est fourni, le hacher
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        // Upload de la nouvelle photo si elle existe et mise à jour du lien
        if (isset($data['photo']) && $data['photo'] instanceof UploadedFile) {
            $data['photo'] = $this->uploadService->uploadFile($data['photo'], 'users_photos');
        }

        return $this->userRepository->update($id, $data);
    }

    public function deleteUser(int $id): void
    {
        $this->userRepository->delete($id);
    }
}
<?php


namespace App\Interfaces;



interface UserRepositoryInterface
{

    public function createUser(array $data): array;
    public function loginUser(array $userCredentials);
    public function logoutUser(): void;

}

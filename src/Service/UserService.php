<?php

namespace App\Service;

use App\Dto\auth\RegisterDTO;
use App\Entity\User;
use App\Factory\UserFactory;
use App\Repository\UserRepository;

class UserService
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly UserFactory $userFactory
    ){}

    public function createFromRequest(RegisterDTO $DTO): User {
        $user = $this->userFactory->createFromRequest($DTO);
        
        if ($this->userRepository->isUniqueEmail($user->getEmail())) {
            $this->userRepository->save($user, true);
        } else {
            throw new \Exception("User already exists");
        }

        return $user;
    }
}

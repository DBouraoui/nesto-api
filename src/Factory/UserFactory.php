<?php

namespace App\Factory;

use App\Dto\auth\RegisterDTO;
use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Uid\Uuid;

class UserFactory
{
 public function __construct(
     private readonly UserPasswordHasherInterface $userPasswordHasher,
 ){}

    public function createFromRequest(RegisterDTO $DTO): User
    {
        $user = new User();

        $user->setEmail($DTO->email);
        $user->setUuid(Uuid::v7());
        $user->setRoles(['ROLE_USER']);
        $user->setFirstName($DTO->firstName);
        $user->setLastName($DTO->lastName);
        $user->setType($DTO->type);
        $user->setCreatedAt(new \DateTimeImmutable());

        $hashedPassword = $this->userPasswordHasher->hashPassword(
            $user,
            $DTO->password
        );
        $user->setPassword($hashedPassword);

        return $user;
    }
}

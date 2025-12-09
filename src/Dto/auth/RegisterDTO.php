<?php

namespace App\Dto\auth;

use App\Enum\UserTypeEnum;
use Symfony\Component\Validator\Constraints as Assert;

class RegisterDTO
{
    #[Assert\NotBlank(message: "The email address cannot be empty.")]
    #[Assert\Length(min: 5, max: 180, minMessage: "Email must be at least 5 characters long.", maxMessage: "Email cannot be longer than 180 characters.")]
    #[Assert\Email(message: "The provided value is not a valid email address.")]
    public string $email;

    #[Assert\NotBlank(message: "The password cannot be empty.")]
    #[Assert\Length(min: 8, max: 255, minMessage: "The password must be at least 8 characters long.")]
    #[Assert\NotCompromisedPassword(message: "This password has been found in data breaches and is compromised. Please choose a different one.")]
    public string $password;

    #[Assert\NotBlank(message: "The first name cannot be empty.")]
    #[Assert\Length(min: 1, max: 100, maxMessage: "First name cannot be longer than 100 characters.")]
    public string $firstName;

    #[Assert\NotBlank(message: "The last name cannot be empty.")]
    #[Assert\Length(min: 1, max: 100, maxMessage: "Last name cannot be longer than 100 characters.")]
    public string $lastName;

    public UserTypeEnum $type;
}

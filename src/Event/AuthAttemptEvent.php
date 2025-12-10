<?php

namespace App\Event;

use App\Entity\User;
use Symfony\Contracts\EventDispatcher\Event;

class AuthAttemptEvent extends Event
{
    public string $ipAddress;
    public User $user;
    public string $agent;
    public function __construct(string $ipAddress, User $user, string $agent
    ){
        $this->ipAddress = $ipAddress;
        $this->user = $user;
        $this->agent = $agent;
    }
}

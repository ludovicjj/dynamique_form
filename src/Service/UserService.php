<?php

namespace App\Service;

use App\Entity\User;

class UserService
{
    static function generateTrigram(User $user): string
    {
        $firstname = preg_replace("/[^a-zA-Z]/", "", $user->getFirstname());
        $lastname = preg_replace("/[^a-zA-Z]/", "", $user->getLastname());

        $firstnameLetter = substr($firstname, 0, 1);
        $lastnameLetters = substr($lastname, 0, 2);

        return strtoupper($firstnameLetter . $lastnameLetters);
    }
}
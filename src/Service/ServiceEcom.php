<?php

namespace App\Service;

use Doctrine\Persistence\ObjectManager;

class ServiceEcom{

    public function makeUpUsername(): ? string{
        return "User#".rand(1,999);
    }



}
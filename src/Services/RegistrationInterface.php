<?php

namespace App\Services;

use App\Entity\User;
use Symfony\Component\Form\FormInterface;

interface RegistrationInterface
{
    public function addUser(User $user, FormInterface $form);
    public function sendConfirmationMessage(User $user);
}
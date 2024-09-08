<?php

namespace App\Services;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\Security\Core\User\UserInterface;

interface UploadFileInterface
{
    public function uploadFileFromRequest(UserInterface $user, FormInterface $form);
}
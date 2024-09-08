<?php

namespace App\Services;

use App\Entity\Photo;
use App\Repository\PhotoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;

class PhotoVisibilityService
{
    public function __construct(private readonly PhotoRepository $photoRepository, private readonly Security $security, private readonly EntityManagerInterface $entityManager)
    {
    }

    public function makeVisible(int $id, bool $visibility)
    {
        $photo = $this->photoRepository->find($id);

        if ($this->isPhotoBelongToCurrentUser($photo)) {
            $photo->setIsPublic($visibility);
            $this->entityManager->persist($photo);
            $this->entityManager->flush();
            return true;
        } else {
            return false;
        }
    }

    private function isPhotoBelongToCurrentUser(Photo $photo)
    {
        if ($photo->getUser() === $this->security->getUser()) {
            return true;
        } else {
            return false;
        }
    }
}

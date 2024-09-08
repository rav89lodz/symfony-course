<?php

namespace App\Services;

use App\Entity\Photo;
use DateTimeImmutable;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Log\LoggerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Security\Core\User\UserInterface;

class UploadFileService implements UploadFileInterface
{
    public function __construct(private readonly ManagerRegistry $doctrine, private readonly LoggerInterface $logger)
    {
    }

    public function uploadFileFromRequest(UserInterface $user, FormInterface $form): array
    {
        try {
            /** @var UploadedFile $picture_file_name */
            $picture_file_name = $form->get('filename')->getData();

            $new_file_name = $this->moveFile($picture_file_name);

            $this->storePhoto($new_file_name, $form->get('is_public')->getData(), $user);            
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
            
            return ['error', 'Something wrong, check log file'];
        }

        return ['success', 'Photo added'];
    }

    private function moveFile($picture_file_name): string
    {
        $original_file_mame = pathinfo($picture_file_name->getClientOriginalName(), PATHINFO_FILENAME);
        $safe_file_name = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $original_file_mame);
        $new_file_name = $safe_file_name.'-'.uniqid().'.'.$picture_file_name->guessExtension();
        $picture_file_name->move('images/hosting', $new_file_name);
        return $new_file_name;
    }

    private function storePhoto(string $file_name, bool $is_public, UserInterface $user): void
    {
        $em = $this->doctrine->getManager();

        try {
            $entity_photos = new Photo();
            $entity_photos->setFilename($file_name);
            $entity_photos->setIsPublic($is_public);
            $entity_photos->setUploadedAt(new DateTimeImmutable());
            $entity_photos->setUser($user);

            $em->persist($entity_photos);
            $em->flush();
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
        }
    }
}
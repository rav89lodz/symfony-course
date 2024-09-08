<?php

namespace App\Controller;

use App\Entity\Photo;
use App\Services\PhotoVisibilityService;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class MyController
 * @package App\Controller
 * @IsGranted("ROLE_USER")
 */
class MyController extends AbstractController
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    #[Route('/my/photos', name: 'my_photos')]
    public function index()
    {
        return $this->render('my/index.html.twig', [
            'myPhotos' => $this->entityManager->getRepository(Photo::class)->findBy(['user' => $this->getUser()]),
        ]);
    }

    #[Route('/my/photos/set_visibility/{id}/{visibility}', name: 'my_photos_set_visibility')]
    public function myPhotoChangeVisibility(PhotoVisibilityService $photoVisibilityService, int $id, bool $visibility)
    {
        $messages = [
            '1' => 'publiczne',
            '0' => 'prywatne'
        ];
        if ($photoVisibilityService->makeVisible($id, $visibility)) {
            $this->addFlash('success', 'Ustawiono jako '.$messages[$visibility].'.');
        } else {
            $this->addFlash('error', 'Wystąpił problem przy ustawianiu jako '.$messages[$visibility].'.');
        }

        return $this->redirectToRoute('my_photos');
    }

    #[Route('/my/photos/remove/{id}', name: 'my_photos_remove', )]
    public function myPhotoRemove(int $id)
    {
        $myPhoto = $this->entityManager->getRepository(Photo::class)->find($id);

        if ($this->getUser() == $myPhoto->getUser())
        {
            $fileManager = new Filesystem();
            $fileManager->remove('images/hosting/'.$myPhoto->getFilename());
            if ($fileManager->exists('images/hosting/'.$myPhoto->getFilename())) {
                $this->addFlash('error', 'Nie udało się usunąć zdjęcia');
            } else {
                $this->entityManager->remove($myPhoto);
                $this->entityManager->flush();
                $this->addFlash('success', 'Usunięto zdjęcie.');
            }
        } else {
            $this->addFlash('error', 'Nie usunięto zdjęcia, ponieważ nie jesteś jego właścicielem.');
        }

        return $this->redirectToRoute('my_photos');
    }
}

<?php

namespace App\Controller;

use App\Entity\Photo;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class LatestPhotosController extends AbstractController
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    #[Route('/latest', name: 'latest_photos')]
    public function index()
    {
        return $this->render('latest_photos/index.html.twig', [
            'latestPhotosPublic' => $this->entityManager->getRepository(Photo::class)->findAllPublic(),
        ]);
    }
}

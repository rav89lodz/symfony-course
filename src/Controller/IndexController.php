<?php

namespace App\Controller;

use App\Entity\Photo;
use App\Form\UploadPhotoType;
use DateTimeImmutable;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Routing\Attribute\Route;

class IndexController extends AbstractController
{
    public function __construct(private readonly ManagerRegistry $doctrine)
    {
    }

    #[Route('/', name: 'app_index')]
    public function index(Request $request): Response
    {
        $form = $this->createForm(UploadPhotoType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->doctrine->getManager();

            if ($this->getUser()) {
                /** @var UploadedFile $pictureFileName */
                $pictureFileName = $form->get('filename')->getData();
                if ($pictureFileName) {
                    try {
                        $originalFileName = pathinfo($pictureFileName->getClientOriginalName(), PATHINFO_FILENAME);
                        $safeFileName = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFileName);
                        $newFileName = $safeFileName.'-'.uniqid().'.'.$pictureFileName->guessExtension();
                        $pictureFileName->move('images/hosting', $newFileName);

                        $entityPhotos = new Photo();
                        $entityPhotos->setFilename($newFileName);
                        $entityPhotos->setPublic($form->get('is_public')->getData());
                        $entityPhotos->setUploadedAt(new DateTimeImmutable());
                        $entityPhotos->setUser($this->getUser());

                        $em->persist($entityPhotos);
                        $em->flush();
                        $this->addFlash('success','Dodano zdjęcie!');
                    } catch (\Exception $e) {
                        $this->addFlash('error', 'Wystąpił nieoczekiwany błąd!');
                    }
                }
            }
        }

        return $this->render('index/index.html.twig', [
            'controller_name' => 'IndexController',
            'form' => $form->createView(),
        ]);
    }

    #[Route('/test', name: 'test')]
    public function test(): Response
    {
        $numbers = ["1" => 1, "2" => 2, "3" => 3, "4" => 4];
        return $this->render('index/test.html.twig', [
            'controller_name' => 'IndexController',
            'numbers' => $numbers
        ]);
    }
}

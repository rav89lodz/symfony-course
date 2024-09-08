<?php

namespace App\Controller;


use App\Form\UploadPhotoType;
use App\Services\UploadFileInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Routing\Attribute\Route;

class IndexController extends AbstractController
{
    public function __construct(private readonly UploadFileInterface $uploadFile)
    {
    }

    #[Route('/', name: 'app_index')]
    public function index(Request $request): Response
    {
        $form = $this->createForm(UploadPhotoType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser();
            $response = $this->uploadFile->uploadFileFromRequest($user, $form);
            $this->addFlash($response[0], $response[1]);
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

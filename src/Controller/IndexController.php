<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class IndexController extends AbstractController
{
    #[Route('/', name: 'app_index')]
    public function index(): Response
    {
        $numbers = ["1" => 1, "2" => 2, "3" => 3, "4" => 4];
        return $this->render('index/index.html.twig', [
            'controller_name' => 'IndexController',
            'numbers' => $numbers
        ]);
    }
}

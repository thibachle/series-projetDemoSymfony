<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MainController extends AbstractController
{
    /*
     * autre possibilité d'écriture pour les version inférieurs à PHP 8
     * @Route('/main', 'app_main')
     * */

    #[Route('/home', name: 'main_home')]
    #[Route('', name: 'main_home_2')]
    public function home(): Response
    {
        return $this->render('main/home.html.twig');
    }


    #[Route('/test', name: 'main_test', methods: ['GET'])]
    public function test(): Response
    {
        $serie1 = ['title' => 'GOT', 'year' => 2011];
        $serie2 = ['title' => 'The Boys' , 'year' => 2019];

        $name = '<h1>Hélène</h1>';

        return $this->render('main/test.html.twig', [
            'got' => $serie1,
            'boys' => $serie2,
            'username' => $name
        ]);
    }
}

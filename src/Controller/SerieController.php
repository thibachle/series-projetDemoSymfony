<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/series', name: 'series_')]
class SerieController extends AbstractController
{
    #[Route('', name: 'list')]
    public function list(): Response
    {
        //TODO renvoter la liste de series
        return $this->render('series/list.html.twig');
    }


    #[Route('/{id}', name: 'detail', requirements: ['id' => '\d+'])]
    public function detail(int $id): Response
    {
        //TODO renvoter une série
        return $this->render('series/detail.html.twig');
    }


    #[Route('/create', name: 'create')]
    public function create(): Response
    {
        //TODO renvoter un formulaire de création de séries
        return $this->render('series/create.html.twig');
    }




}

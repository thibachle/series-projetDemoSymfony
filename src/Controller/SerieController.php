<?php

namespace App\Controller;

use App\Entity\Serie;
use App\Form\SerieType;
use App\Repository\SerieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/series', name: 'series_')]
class SerieController extends AbstractController
{
    #[Route('', name: 'list')]
    public function list(SerieRepository $serieRepository): Response
    {
//        $series = $serieRepository->findAll();

//        $series = $serieRepository->findBy([],["popularity" => "DESC"], 50, 50);

        $series = $serieRepository->findBestSeries();

        return $this->render('series/list.html.twig', [
            "series" => $series
        ]);
    }


    #[Route('/{id}', name: 'detail', requirements: ['id' => '\d+'])]
    public function detail(SerieRepository $serieRepository, Serie $id ): Response
    {
//        dump($id);
//        $serie = $serieRepository->find($id);
//
//        if(!$serie){
//            throw $this->createNotFoundException("Ooops ! Serie is not found !");
//        }
        return $this->render('series/detail.html.twig', [
            'serie' => $id
        ]);
    }


    #[Route('/create', name: 'create')]
    public function create(EntityManagerInterface $entityManager, Request $request): Response
    {
        //créer une instance de l'entité
        $serie = new Serie();

        //creation du formulaire associé à l'instance de serie
        $serieForm = $this->createForm(SerieType::class, $serie);

        dump($serie);
        dump($request);
        //extraie des informations de la requête HTTP
        $serieForm->handleRequest($request);

        if($serieForm->isSubmitted()){
            dump($serie);
            $entityManager->persist($serie);
            $entityManager->flush();

            $this->addFlash('success', 'Series are added !');

            return $this->redirectToRoute('series_detail', ['id'=>$serie->getId()]);
        }


        //TODO renvoter un formulaire de création de séries
        return $this->render('series/create.html.twig', [
            'serieForm' =>$serieForm
        ]);
    }




}

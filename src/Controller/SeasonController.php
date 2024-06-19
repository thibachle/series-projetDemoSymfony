<?php

namespace App\Controller;

use App\Entity\Season;
use App\Entity\Serie;
use App\Form\SeasonType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
#[Route('/season', name: 'season_')]
class SeasonController extends AbstractController
{
    #[Route('/create/{serie}', name: 'create_with_serie')]
    #[Route('/create', name: 'create')]
    public function create(
        EntityManagerInterface $entityManager,
        Request $request,
        Serie $serie = null
    ): Response
    {
        $season = new Season();
        //permet de passer des données préremplis à la formulaire
        //ici permet de présélectionner la bonne série dans le select
        if($serie){
            $season->setSerie($serie);
        }
        $seasonForm = $this->createForm(SeasonType::class, $season);

        $seasonForm->handleRequest($request);

        if($seasonForm->isSubmitted() && $seasonForm->isValid()){
            $season->setDateCreated(new \DateTime());
            $entityManager->persist($season);
            $entityManager->flush();

            $this->addFlash('success', 'season is added !');

            return $this->redirectToRoute('series_detail', ['id' => $season->getSerie()->getId()]);
        }

        return $this->render('season/create.html.twig', [
            'seasonForm' => $seasonForm
        ]);
    }
}

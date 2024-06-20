<?php

namespace App\Controller;

use App\Entity\Serie;
use App\Form\SerieType;
use App\Repository\SerieRepository;
use App\Utils\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/series', name: 'series_')]
class SerieController extends AbstractController
{
    #[Route('/{page}', name: 'list', requirements: ['page' => '\d+'])]
    public function list(
        SerieRepository $serieRepository,
        int $page = 1
    ): Response
    {
//        $series = $serieRepository->findAll();
//        $series = $serieRepository->findBy([],["popularity" => "DESC"], 50, 50);

        if($page < 1){
            $page = 1;
        }

        $nbSeriesMax = $serieRepository->count([]);
        $maxPage = ceil($nbSeriesMax / Serie::SERIES_PER_PAGE);

        if($page > $maxPage){
            $page = $maxPage;
        }

        $series = $serieRepository->findBestSeries($page);

        return $this->render('series/list.html.twig', [
            "series" => $series,
            "currentPage"=>$page,
            'maxPage' =>$maxPage
        ]);
    }


    #[Route('/detail/{id}', name: 'detail', requirements: ['id' => '\d+'])]
    public function detail(SerieRepository $serieRepository, Serie $id ): Response
    {
        $serie = $serieRepository->find($id);

        if(!$serie){
            throw $this->createNotFoundException("Ooops ! Serie is not found !");
        }

        //permet de faire des requêtes GET facilement
        $json = file_get_contents('https://jsonplaceholder.typicode.com/comments');
        $comments = json_decode($json, true);

        return $this->render('series/detail.html.twig', [
            'serie' => $serie,
            'comments' => $comments
        ]);
    }


    #[Route('/create', name: 'create')]
    #[IsGranted('ROLE_USER')]
    public function create(
        EntityManagerInterface $entityManager,
        Request $request,
        FileUploader $fileUploader
    ): Response
    {
        //créer une instance de l'entité
        $serie = new Serie();

        //creation du formulaire associé à l'instance de serie
        $serieForm = $this->createForm(SerieType::class, $serie);

         //extraie des informations de la requête HTTP
        $serieForm->handleRequest($request);

        if($serieForm->isSubmitted() && $serieForm->isValid()){

            /*
             * @var Uploaded File $file
             * */
            //récupération du fichier de type UploadedFile
            $file = $serieForm->get('poster')->getData();
            $newFilename = $fileUploader->upload($file, $this->getParameter('serie_poster_directory'), $serie->getName());

            $serie->setPoster($newFilename);
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

    #[Route('/update/{id}', name: 'update')]
    public function update(
        EntityManagerInterface $entityManager,
        Request $request,
        SerieRepository $serieRepository,
        int $id): Response
    {
        $serie = $serieRepository->find($id);

        if(!$serie){
            throw $this->createNotFoundException("Oop ! Series is not found !");
        }
        $serieForm = $this->createForm(SerieType::class, $serie);
        $serieForm->handleRequest($request);

        if($serieForm ->isSubmitted() && $serieForm->isValid()){

            $serie->setDateModified(new \DateTime());
            $entityManager->persist($serie);
            $entityManager->flush();

            $this->addFlash('success', 'Series have been updated');
            return  $this->redirectToRoute('series_detail', ['id' => $id]);
        }

        return $this->render('series/update.html.twig', [
//                'serieForm' => $serieForm
                    'updateSerieForm' => $serieForm
            ]);
    }

    #[Route('/delete/{id}', name: 'delete')]
    public function delete(
        EntityManagerInterface $entityManager,
        SerieRepository $serieRepository,
        int $id
    ): Response
    {
        $serie = $serieRepository->find($id);

        if(!$serie){
            throw $this->createNotFoundException('Series is not found');
        }
        $entityManager->remove($serie);
        $entityManager->flush();

        $this->addFlash('success', 'Series is deleted');
        return $this->redirectToRoute('series_list');
    }



}

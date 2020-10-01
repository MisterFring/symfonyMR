<?php

namespace App\Controller;

use App\Entity\Project;
use App\Entity\Team;
use App\Service\DatabaseService;
use phpDocumentor\Reflection\Type;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Gitlab\Client;
use App\Service\GitlabService;
use Twig\Environment;


class GitlabController extends AbstractController
{
    /**
     * @var GitlabService
     */
    private $service;
    /**
     * @var DatabaseService
     */
    private $DbService;
    /**
     * @var Environment
     */
    private $twig;

    /**
     * GitlabController constructor.
     * @param GitlabService $service
     * @param DatabaseService $DbService
     * @param Environment $twig
     */
    public function __construct(GitlabService $service, DatabaseService $DbService, Environment $twig)
    {
        $this->service = $service;
        $this->DbService = $DbService;
        $this->twig = $twig;
    }

    /**
     * @Route("/list")
     */
    public function index()
    {
        //$issues = $this->client->projects()->all(['owned' => true]);

        $response = $this->service->index();

        dump($response);die;
    }


    /**
     * @param int $id
     * @Route ("/Team-{id}", name="team_sheet")
     * @return Response
     */

    public function teamSheet(int $id){

        /** @var Team $team */
        $team = $this->DbService->getOneById($id);
        /** @var Project $proj */
        $proj = $team->getProjectId();
        dump($proj);die;
        $display = $this->twig->render('Home/team.html.twig', [
            'team' => $team
        ]);
        return new Response($display);
    }


    /**
     * @Route ("/Projects", name="projects")
     * @return Response
     */
    public function listProjects(){

        /* $team = $this->dbService->getOneById($id);
        /** @var Project $item */
        /*
        foreach ($team->getProjectId() as $item) {
            dump($item->getIdGitlab());
        }
        */
        $arrayOfProjects = $this->service->getAllProjects();
        //dump($arrayOfProjects);die;
        $myArray =[];
        /** @var Project $proj */
        foreach ($arrayOfProjects as $toto){

            $proj = new Project($toto['name'], $toto['id']);
            $test = $proj->getId();
            dump($test);die;

        }
        dump($myArray);die;
        $display = $this->twig->render('Home/blocks.html.twig', [
            'projects' => $arrayOfProjects
        ]);
        return new Response($display);

    }
}
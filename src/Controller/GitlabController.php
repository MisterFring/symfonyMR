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
        $projects = $team->getProjectId();

// find all gitlab ids of projects for this team
        $arrayOfGitlabId = [];
        foreach ($projects as $project){
            array_push($arrayOfGitlabId, $project->getIdGitlab());
        }
        // dump($arrayOfGitlabId);die;

        $arrayOfInfo = [];
        $arrayOfMR = [];
        foreach ($arrayOfGitlabId as $id){
            //array_push($arrayOfInfo, $this->service->getProjectInfoById($id));
            $arrayOfInfo[$id] = $this->service->getProjectInfoById($id);
            array_push($arrayOfMR, $this->service->getMergeRequestById($id));
        }
        //dump($arrayOfInfo);die;
        // Have to find how put associate project with their team
            //** @var Project $proj */
            //$proj = $team->getProjectId();
        // -------------------------------------------------------

        $display = $this->twig->render('Home/team.html.twig', [
            'team' => $team,
            'projects' => $arrayOfInfo,
            'arrayOfMR' => $arrayOfMR

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


        $myArray =[];
        /*/** @var Project $proj */
        /*foreach ($arrayOfProjects as $toto){
            dump($toto['']);die;
            $proj = new Project($toto['name'], $toto['id']);
            $test = $proj->getId();
            dump($test);die;

        }
        //dump($myArray);die;*/
        $display = $this->twig->render('Home/blocks.html.twig', [
            'projects' => $arrayOfProjects
        ]);
        return new Response($display);

    }
}
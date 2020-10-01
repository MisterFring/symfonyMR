<?php

namespace App\Service;

use App\Entity\Project;
use App\Repository\TeamRepository;
use Gitlab\Client;
use App\Entity\Team;
use App\Service\DatabaseService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class GitlabService extends AbstractController
{
    /**
     * @var Client
     */
    private $client;
    /**
     * @var \App\Service\DatabaseService
     */
    private $dbService;

    /**
     * GitlabController constructor.
     * @param Client $client
     * @param \App\Service\DatabaseService $dbService
     */
    public function __construct(Client $client, DatabaseService $dbService)
    {
        $this->dbService = $dbService;
        $this->client = $client;
    }

    /**
     * @var TeamRepository $test
     */
    public function index()
    {
        $issues = $this->client->projects()->all(['owned' => true]);
        dump($issues);die;
        //$mr = $this->client->mergeRequests();
        //dump($issues);die;

        // Creation a an array with project id from gitlab
        $arrayOfProjectId = [];
        foreach ($issues as $issu){
            $project = new Project($issu['name'], $issu['id']);
            $team =new Team();
            $team->addProjectId($project);
            //$this->dbService->persistFlushProject($project);
            array_push($arrayOfProjectId, $issu['id']);
        }

        // Creation a an array with team id
        $arrayOfTeamId = [];
        $teams = $this->dbService->getAll();

        foreach ($teams as $test){
            array_push($arrayOfTeamId, $test->getId());
        }
        foreach ($arrayOfTeamId as $item){

        }
        dump($arrayOfTeamId);die;

    }

    /**
     * @param int $id
     */
    public function getProjectInfoById(int $id)
    {
        $issues = $this->client->projects()->all(['id' => $id]);
        dump($issues);die;

    }

    /**
     *
     */
    public function getAllProjects()
    {
        $issues = $this->client->projects()->all(['owned' => true]);
        return $issues;

    }
}

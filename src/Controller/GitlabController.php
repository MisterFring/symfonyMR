<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Gitlab\Client;
use App\Service\GitlabService;


class GitlabController extends AbstractController
{
    /**
     * @var GitlabService
     */
    private $service;

    /**
     * GitlabController constructor.
     * @param GitlabService $service
     */
    public function __construct(GitlabService $service)
    {
        $this->service = $service;
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

    public function listProjects(){

    }
}
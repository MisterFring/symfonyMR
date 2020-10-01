<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Gitlab\Client;


class GitlabController extends AbstractController
{
    /**
     * @var Client
     */
    private $client;

    /**
     * GitlabController constructor.
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @Route("/list")
     */
    public function index()
    {
        $issues = $this->client->projects()->all(['owned' => true]);

        dump($issues);die;
    }
}
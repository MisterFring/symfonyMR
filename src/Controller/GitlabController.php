<?php


namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Gitlab\Client;
use Symfony\Component\Routing\Annotation\Route;

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
    public function __construct( Client $client) {
        $this->client = $client;
        $this->index();
    }

    /**
     * @Route("/list")
     */

    public function index() {

        $issues = $this->client->mergeRequests()->all('21256854');
        dump($issues);die;

    }
}
<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Gitlab\Client;


class GitLabController extends AbstractController {
    private $client;

    public function __construct(Client $client) {
        $this->client = $client;
    }

    public function index() {
        $issues = $this->client->projects()->all(['owned' => true]);


        dump($issues);
        die;
    }
}
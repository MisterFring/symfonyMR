<?php

namespace App\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

class DefaultController
{
    /**
     * @var Environment
     */
    private $twig;

    /**
     * DefaultController constructor.
     * @param Environment $twig
     */
    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    /**
     * @return Response
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     * @Route ("/home")
     */

    public function index()
    {
        $content = $this->twig->render('Home/home.html.twig' , [ 'name' => 'Pierre']);
        return new Response($content);

    }
}
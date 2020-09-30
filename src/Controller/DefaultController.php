<?php

namespace App\Controller;
use App\Entity\Teams;
use App\Form\TeamForm;
use App\Repository\TeamsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

class DefaultController extends AbstractController
{
    /**
     * @var Environment
     */
    private $twig;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * DefaultController constructor.
     * @param Environment $twig
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(Environment $twig, EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->twig = $twig;
    }

    /**
     * @return Response
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     * @var TeamsRepository $em
     * @Route ("/home")
     */

    public function index(Request $request)
    {
        $team = new Teams('','');

        $form = $this->createForm(TeamForm::class, $team);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($team);
            $this->entityManager->flush($team);
        }


        $em = $this->entityManager->getRepository(Teams::class);
        $response = $em->findAll();

        $display = $this->twig->render('Home/home.html.twig', [
            'formTeam' => $form->createView(),
            'teams' => $response
        ]);
        return new Response($display);

    }
}
<?php

namespace App\Controller;
use App\Entity\Teams;
use App\Form\TeamForm;
use App\Repository\TeamsRepository;
use Container1r6wboF\getTeamsRepositoryService;
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
     * @Route ("/home", name="home")
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

        $display = $this->twig->render('Home/blocks.html.twig', [
            'formTeam' => $form->createView(),
            'teams' => $response
        ]);
        return new Response($display);

    }

    /**
     * @param int $id
     * @var TeamsRepository $em
     * @Route ("/delete/{id}", name="delete_team")
     */

    public function delete(int $id){

        $em = $this->entityManager->getRepository(Teams::class);
        $product = $em->findOneById($id);
        $this->entityManager->remove($product);
        $this->entityManager->flush();
        return $this->redirectToRoute('home');
    }

    /**
     * @param int $id
     * @param Request $request
     * @return Response
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     * @Route ("/modify/{id}", name="modify_team")
     */
    public function modify(int $id, Request $request){
        $em = $this->entityManager->getRepository(Teams::class);
        $team = $em->findOneById($id);


        // Aimerais mettre l'ancien nom en placeholder de l'input
        $form = $this->createForm(TeamForm::class, $team);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($team);
            $this->entityManager->flush($team);
            return $this->redirectToRoute('home');

        }

        $display = $this->twig->render('Home/ModifyTeam.html.twig', [
            'formTeam' => $form->createView(),
            'teams' => $team
        ]);
        return new Response($display);

    }

    /**
     * @param int $id
     * @Route ("/Team-{id}", name="team_sheet")
     */

    public function teamSheet(int $id){

        $em = $this->entityManager->getRepository(Teams::class);
        $team = $em->findOneById($id);

        $display = $this->twig->render('Home/team.html.twig', [
            'team' => $team
        ]);
        return new Response($display);


    }


}
<?php

namespace App\Controller;
use App\Entity\Project;
use App\Entity\Team;
use App\Form\TeamForm;
use App\Service\DatabaseService;
use App\Repository\TeamRepository;
//use Container1r6wboF\getTeamsRepositoryService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
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
     * @var DatabaseService
     */
    private $dbService;

    /**
     * DefaultController constructor.
     * @param Environment $twig
     * @param EntityManagerInterface $entityManager
     * @param DatabaseService $dbService
     */
    public function __construct(Environment $twig, EntityManagerInterface $entityManager, DatabaseService $dbService)
    {
        $this->entityManager = $entityManager;
        $this->twig = $twig;
        $this->dbService = $dbService;
    }

    /**
     * @param Request $request
     * @return Response
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     * @Route ("/home", name="home")
     */

    public function index(Request $request)
    {
        $team = new Team('','');

        $form = $this->createForm(TeamForm::class, $team);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($team);
            $this->entityManager->flush($team);
        }

        $response = $this->dbService->getAll();

        $display = $this->twig->render('Home/blocks.html.twig', [
            'formTeam' => $form->createView(),
            'teams' => $response
        ]);
        return new Response($display);

    }

    /**
     * @param int $id
     * @return RedirectResponse
     * @var TeamRepository $em
     * @Route ("/delete/{id}", name="delete_team")
     */

    public function delete(int $id){


        $product = $this->dbService->getOneById($id);

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

        $team = $this->dbService->getOneById($id);

        // Aimerais mettre l'ancien nom en placeholder de l'input
        $form = $this->createForm(TeamForm::class, $team);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($team);
            $this->entityManager->flush($team);
            return $this->redirectToRoute('home');

        }

        $display = $this->twig->render('Home/blocks.html.twig', [
            'formTeam' => $form->createView(),
            'teams' => $team,
            'modify' => true
        ]);
        return new Response($display);

    }

    /**
     * @param int $id
     * @var Project $item
     * @Route ("/Team-{id}", name="team_sheet")
     * @return Response
     */

    public function teamSheet(int $id){

        $team = $this->dbService->getOneById($id);
        /** @var Project $item */
        foreach ($team->getProjectId() as $item) {
            dump($item->getIdGitlab());
        }
        die;

        $display = $this->twig->render('Home/team.html.twig', [
            'team' => $team
        ]);
        return new Response($display);

    }


}
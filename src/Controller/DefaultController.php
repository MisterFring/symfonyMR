<?php

namespace App\Controller;
use App\Entity\Project;
use App\Entity\Team;
use App\Form\TeamForm;
use App\Service\DatabaseService;
use App\Repository\TeamRepository;
//use Container1r6wboF\getTeamsRepositoryService;
use App\Service\GitlabService;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\This;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
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
     * @var Email
     */
    private $mail;
    /**
     * @var GitlabService
     */
    private $glservice;

    /**
     * DefaultController constructor.
     * @param Environment $twig
     * @param EntityManagerInterface $entityManager
     * @param DatabaseService $dbService
     * @param MailerInterface $mail
     * @param GitlabService $glservice
     */
    public function __construct(
        Environment $twig,
        EntityManagerInterface $entityManager,
        DatabaseService $dbService,
        MailerInterface $mail,
        GitlabService $glservice)
    {
        $this->entityManager = $entityManager;
        $this->twig = $twig;
        $this->dbService = $dbService;
        $this->mail = $mail;
        $this->glservice = $glservice;
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

        $arrayOfProjects = $this->glservice->getAllProjects();

        //$projects = $team->getProjectId();

        //$arrayOfGitlabId = [];
        //foreach ($projects as $project){
         //   $arrayOfGitlabId[$project->getId()] = $project->getIdGitlab();
        //}
        //dump($arrayOfGitlabId);die; donne
        // array:2 [▼
        //  3 => 21256859
        //  5 => 21256849
        //]
        /*$arrayOfInfo = [];

        foreach ($arrayOfGitlabId as $id){
            array_push($arrayOfInfo, $this->glservice->getProjectInfoById($id));
        }*/


        $display = $this->twig->render('Home/blocks.html.twig', [
            'formTeam' => $form->createView(),
            'teams' => $team,
            'modify' => true,
            'ListOfProjects' => $arrayOfProjects
        ]);
        return new Response($display);

    }

    /**
     * @param int $team_id
     * @param int $project_id_gitlab
     * @Route ("/remove/{team_id}/{project_id_gitlab}", name="remove")
     * @return RedirectResponse
     */
    public function removeLink (int $team_id, int $project_id_gitlab){

        $team = $this->dbService->getOneById($team_id);
        $project = $this->dbService->getOneProject($project_id_gitlab);

        $test = $team->removeProjectId($project);
        $this->dbService->persistFlushTeam($test);

        return $this->redirectToRoute('home');
    }

    /**
     * @param int $team_id
     * @param int $project_id_gitlab
     * @Route ("/associate/{team_id}/{project_id_gitlab}", name="associate")
     * @return RedirectResponse
     */
    public function associate(int $team_id, int $project_id_gitlab){
        $team = $this->dbService->getOneById($team_id);
        $project = $this->dbService->getOneProject($project_id_gitlab);
        if ($project == null){
            $project = new Project('blabla', $project_id_gitlab);
        }
        $team->addProjectId($project);
        $this->dbService->persistFlushTeam($team);

        return $this->redirectToRoute('home');
    }

    /**
     * @Route ("/mail", name="mail")
     * @throws TransportExceptionInterface
     */
    public function mail(){

        $email = (new Email())
            ->from('hello@example.com')
            ->to('pierre.decrock@gmail.com')
            //->priority(Email::PRIORITY_HIGH)
            ->subject('Symfony Mailer!')
            ->text('Sending emails is fun again!')
            ->html('<p>Welcome to Symfony</p>');

        $this->mail->send($email);
        return $this->redirectToRoute('home');
    }




}
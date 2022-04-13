<?php

namespace App\Controller\Security;

use Doctrine\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Repository\SystemApp\AppUserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/core" , name="core_")
 * @IsGranted("IS_AUTHENTICATED_FULLY")
 */
class WelcomeController extends AbstractController
{
    private ManagerRegistry $managerRegistry;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
    }
    /**
     * @Route("/welcome", name="welcome")
     * @param AppUserRepository $appUsersRepository
     * @return RedirectResponse
     * @throws \Exception
     */
    public function redirectUser(AppUserRepository $appUsersRepository)
    {
        /*
         * Set logged in user loggedin date and time
         */
        $user = $this->getUser();
        $user->setUserLastLogin(new \DateTime());
//        dd($user);
        $entityManager = $this->managerRegistry->getManager();
        $entityManager->persist($user);
        $this->managerRegistry->getManager()->flush();
        return $this->redirectToRoute('core_home');
    }
    /**
     * @Route("/home", name="home")
     * @Route("/system", name="system")
     * @Route("/master", name="master")
     * @Route("/user", name="user")
     * @Route("/organization", name="organization")
     * @Route("/cms", name="cms")
     * @Route("/form", name="form")
     * @Route("/project", name="project")
     * @Route("/product", name="product")
     * @Route("/partner", name="partner")
     */

    public function index()
    {

        return $this->render('layout/index.html.twig');
    }
}

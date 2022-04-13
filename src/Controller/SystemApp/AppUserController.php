<?php

namespace App\Controller\SystemApp;

use App\Entity\SystemApp\AppUserInfo;
use App\Form\SystemApp\AppUserAccessType;
use DateTime;
use DateTimeZone;
use Doctrine\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Entity\SystemApp\AppUser;
use App\Form\SystemApp\AppUserType;
use App\Repository\SystemApp\AppUserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Ramsey\Uuid\Uuid;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @Route("/core/system/user", name="system_user_")
 * @IsGranted("ROLE_SYS_ADMIN")
 */
class AppUserController extends AbstractController
{

    private ManagerRegistry $managerRegistry;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
    }
    /**
     * @Route("/", name="index", methods={"GET"})
     * @param AppUserRepository $appuserRepository
     * @param Request $request
     * @param PaginatorInterface $paginator
     * @return Response
     */
    public function index(AppUserRepository $appuserRepository, Request $request, PaginatorInterface $paginator): Response
    {
        $q = $request->query->get('q');
        $queryBuilder = $appuserRepository->findAll();
        $pagination = $paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('system_app/app_user/index.html.twig', [
            'app_users' => $pagination,
            'path_index' => 'system_user_index',
            'path_add' => 'system_user_add',
            'path_edit' => 'system_user_edit',
            'path_show' => 'system_user_show',
            'label_title' => 'label.user',
        ]);
    }

    /**
     * @Route("/add", name="add", methods={"GET","POST"})
     * @param Request $request
     * @param UserPasswordHasherInterface $encoder
     * @return Response
     * @throws \Exception
     */
    public function new(Request $request, UserPasswordHasherInterface $encoder): Response
    {
        $appUser = new AppUser();
        $appUserInfo = new AppUserInfo();
        $option = array('password_required' => true);
        $form = $this->createForm(AppUserType::class, $appUser, $option);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $appUser->setRowId(Uuid::uuid4()->toString());
            $appUser->setUserRole($_POST['app_user']['userRole']);
            $appUser->setUserPassword($encoder->hashPassword($appUser, $form->get('userPassword')->getData()));
//            $appUser->setUserCreationDateTime(new DateTime('now', new DateTimeZone('UTC')));
            $appUserInfo->setAppuser($appUser);
            $entityManager = $this->managerRegistry->getManager();
            $entityManager->persist($appUser);
            $entityManager->flush();

            $this->addFlash('success', 'form.created_successfully');
            return $this->redirectToRoute('system_user_index');
        }

        return $this->render('form/form.html.twig', [
            'app_user' => $appUser,
            'form' => $form->createView(),
            'index_path' => 'system_user_index',
            'label_title' => 'label.user',
            'label_button' => 'label.create',
            'mode' => 'add'
        ]);
    }

    /**
     * @Route("/{id}", name="show", methods={"GET"})
     * @param AppUser $appUser
     * @return Response
     */
    public function show(AppUser $appUser): Response
    {
        return $this->render('system_app/app_user/show.html.twig', [
            'data' => $appUser,
            'path_index' => 'system_user_index',
            'path_edit' => 'system_user_edit',
            'path_delete' => 'system_user_delete',
            'label_button' => 'label.delete',
        ]);
    }

    /**
     * @Route("/edit/{id}", name="edit", methods={"GET","POST"})
     * @param Request $request
     * @param AppUser $appUser
     * @param UserPasswordHasherInterface $encoder
     * @return Response
     */
    public function edit(Request $request, AppUser $appUser, UserPasswordHasherInterface $encoder): Response
    {
        // Get the original password ready in case the user does not change the password, we will use this password to update the password
        $originalPassword = $appUser->getPassword();
        $option = array('password_required' => false);
        $form = $this->createForm(AppUserType::class, $appUser, $option);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $appUser->setUserRole($_POST['app_user']['userRole']);
            if (!empty($form->get('userPassword')->getData())) {
                $appUser->setUserPassword($encoder->hashPassword($appUser, $form->get('userPassword')->getData()));
            } else {
                // Set the current password which the user has and have not changes in the edit form
                $appUser->setUserPassword($originalPassword);
            }
            $this->managerRegistry->getManager()->flush();
            $this->addFlash('success', 'form.updated_successfully');
            return $this->redirectToRoute('system_user_index');
        }

        return $this->render('form/form.html.twig', [
            'app_user' => $appUser,
            'form' => $form->createView(),
            'index_path' => 'system_user_index',
            'label_title' => 'label.user',
            'label_button' => 'label.update',
            'mode' => 'edit'

        ]);
    }

    /**
     * @Route("/access/{id}", name="access", methods={"GET","POST"})
     * @param Request $request
     * @param AppUser $appUser
     * @return Response
     */
    public function access(Request $request, AppUser $appUser): Response
    {
        $form = $this->createForm(AppUserAccessType::class, $appUser);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->managerRegistry->getManager()->flush();
            $this->addFlash('success', 'form.updated_successfully');
            return $this->redirectToRoute('system_user_index');
        }

        return $this->render('form/form.html.twig', [
            'app_user' => $appUser,
            'form' => $form->createView(),
            'index_path' => 'system_user_index',
            'label_title' => 'label.user.access',
            'label_button' => 'label.update'
        ]);
    }

    /**
     * @Route("/delete/{id}", name="delete", methods={"DELETE"})
     * @param Request $request
     * @param AppUser $appUser
     * @return Response
     */
    public function delete(Request $request, AppUser $appUser): Response
    {

        if ($this->isCsrfTokenValid('delete' . $appUser->getId(), $request->request->get('_token'))) {
            $entityManager = $this->managerRegistry->getManager();
            $entityManager->remove($appUser);
            $entityManager->flush();
        }

        return $this->redirectToRoute('system_user_index');
    }
}

<?php

namespace App\Controller\Organization;

use App\Entity\Organization\OrgCompany;
use App\Entity\SystemApp\AppUser;
use App\Entity\SystemApp\AppUserInfo;
use App\Form\Organization\OrgCompanyUserType;
use App\Repository\SystemApp\AppUserRepository;
use App\Service\CommonHelper;
use App\Service\Mailer;
use DateTime;
use DateTimeZone;
use Doctrine\Persistence\ManagerRegistry;
use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/core/organization/user", name="org_company_user_")
 * @IsGranted("ROLE_SYS_ADMIN")
 */
class OrgCompanyUserController extends AbstractController
{
    private ManagerRegistry $managerRegistry;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
    }
    /**
     * @Route("/", name="index", methods={"GET"})
     * @param AppUserRepository $appUserRepository
     * @param Request $request
     * @return Response
     */
    public function index(AppUserRepository $appUserRepository, Request $request): Response
    {
        $company_id = $request->query->get('company_id');
        if (!$company_id) {
            return $this->redirectToRoute('org_company_index');
        }
        $orgCompany = $this->managerRegistry->getRepository(OrgCompany::class)->find($company_id);
        return $this->render('organization/org_company_user/index.html.twig', [
            'org_company_users' => $appUserRepository->getUserByCompanyId($company_id),
            'org_company' => $orgCompany,
            'path_index' => 'org_company_user_index',
            'path_add' => 'org_company_user_add',
            'path_edit' => 'org_company_user_edit',
            'path_show' => 'org_company_user_show',
            'label_title' => 'label.company_user',
        ]);
    }

    /**
     * @Route("/add", name="add", methods={"GET","POST"})
     * @param Request $request
     * @param UserPasswordHasherInterface $hasher
     * @param Mailer $mailer
     * @param CommonHelper $commonHelper
     * @return Response
     * @throws Exception
     */
    public function new(Request $request, UserPasswordHasherInterface $hasher, Mailer $mailer, CommonHelper $commonHelper): Response
    {
        $company_id = $request->query->get('company_id');
        if (!$company_id) {
            return $this->redirectToRoute('org_company_index');
        }
        $orgCompany = $this->managerRegistry->getRepository(OrgCompany::class)->find($company_id);

        $appUser = new AppUser();
        $orgCompany = $this->managerRegistry->getRepository(OrgCompany::class)->find($request->query->get('company_id'));
        $appUser->setAppUserInfo(new AppUserInfo())->getAppUserInfo()->setOrgCompany($orgCompany);
        $form = $this->createForm(OrgCompanyUserType::class, $appUser);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $userEmail = $appUser->getAppUserInfo()->getUserEmail();
            $userName = $appUser->getUserName();
            $token = bin2hex(random_bytes(32));
            $appUser->setRowId(Uuid::uuid4()->toString());
            $appUser->setUserCreationToken($token);
            $appUser->setUserRole($_POST['org_company_user']['userRole']);
            $appUser->setUserCreationDateTime(new DateTime('now', new DateTimeZone('UTC')));
            $generatePassword = random_bytes(10);
            $appUser->setUserPassword($hasher->hashPassword($appUser, $generatePassword));
            // In case the role is HBA set the HBA Code
            if (in_array('ROLE_HBA_USER', $_POST['org_company_user']['userRole'])) {
                $appUser->setUserCode($commonHelper->generateRandomString());
            }
            $entityManager = $this->managerRegistry->getManager();
            $entityManager->persist($appUser);
            $entityManager->flush();
            // Send Reset Email to User
            $mailer->mailerCreatePassword($userEmail, $userName, $token);
            $this->addFlash('success', 'form.created_successfully');
            return $this->redirectToRoute('org_company_user_index', $request->query->all());
        }
        return $this->render('organization/org_company_user/form.html.twig', [
            'org_company_user' => $appUser,
            'form' => $form->createView(),
            'index_path' => 'org_user_index',
            'label_title' => 'label.user',
            'label_button' => 'label.create',
            'mode' => 'add'
        ]);
    }

    /**
     * @Route("/edit/{id}", name="edit", methods={"GET","POST"})
     * @param Request $request
     * @param AppUser $appUser
     * @return Response
     */
    public function edit(Request $request, AppUser $appUser): Response
    {
        $company_id = $request->query->get('company_id');
        if (!$company_id) {
            return $this->redirectToRoute('org_company_index');
        }
        $orgCompany = $this->managerRegistry->getRepository(OrgCompany::class)->find($company_id);

        $form = $this->createForm(OrgCompanyuserType::class, $appUser);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->managerRegistry->getManager()->flush();
            $this->addFlash('success', 'form.updated_successfully');
            return $this->redirectToRoute('org_company_user_index', $request->query->all());
        }
        return $this->render('organization/org_company_user/form.html.twig', [
            'org_company_user' => $appUser,
            'form' => $form->createView(),
            'index_path' => 'org_user_index',
            'label_title' => 'label.user',
            'label_button' => 'label.update',
            'mode' => 'edit'
        ]);
    }

    /**
     * @Route("/{id}", name="show", methods={"GET","POST"})
     * @param Request $request
     * @param $id
     * @return Response
     */
    public function show(Request $request, $id): Response
    {
        // If company_id param is entry redirect to Company listing
        $company_id = $request->query->get('company_id');
        if (!$company_id) {
            return $this->redirectToRoute('org_company_user_index');
        }
        $appUser = $this->managerRegistry->getRepository(AppUser::class)->find($id);
        if (!$appUser) {
            throw $this->createNotFoundException('No Data found for id ' . $id);
        }

        return $this->render('organization/org_company_user/show.html.twig', [
            'data' => $appUser,
            'label_title' => 'label.company_user',
            'label_button' => 'label.update',
            'path_index' => 'org_company_user_index',
            'path_edit' => 'org_company_user_edit',
            'path_delete' => 'org_company_user_delete',
        ]);
    }

}

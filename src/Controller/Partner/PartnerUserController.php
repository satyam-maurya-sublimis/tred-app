<?php

namespace App\Controller\Partner;

use App\Entity\SystemApp\AppUser;
use App\Entity\Transaction\TrnVendorPartnerDetails;
use App\Form\SystemApp\AppUserVendorPartnerType;
use App\Repository\SystemApp\AppUserInfoRepository;
use App\Service\Mailer;
use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use Ramsey\Uuid\Uuid;
use App\Service\CommonHelper;
use App\Service\FileUploaderHelper;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/core/partner/user", name="partner_user_")
 * @IsGranted("ROLE_SYS_CONTENT_USER")
 */
class PartnerUserController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET"})
     * @param AppUserInfoRepository $appUserInfoRepository
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    public function index(AppUserInfoRepository $appUserInfoRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $user = $this->getUser();

        if ($user->getAppUserInfo()->getTrnVendorPartnerDetails())
        {
            $trnVendorPartnerDetails = $user->getAppUserInfo()->getTrnVendorPartnerDetails();
            $vendor_partner_id = $trnVendorPartnerDetails->getId();
        }else{
            $vendor_partner_id = $request->get('vendor_partner_id');
        }
        if (empty($vendor_partner_id))
            $queryBuilder = $appUserInfoRepository->getOnlyApplicationUsers();
        else {
            $TrnVendorPartnerDetails = $this->getDoctrine()->getRepository(TrnVendorPartnerDetails::class)->find($vendor_partner_id);
            $queryBuilder = $appUserInfoRepository->getOnlyApplicationUsersByVendorPartner($TrnVendorPartnerDetails);
        }
        $pagination = $paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page', 1),
            10
        );
        return $this->render('partner/user/index.html.twig', [
            'appUserInfos' => $pagination,
            'path_index' => 'partner_user_index',
            'path_add' => 'partner_user_add',
            'path_edit' => 'partner_user_edit',
            'path_show' => 'partner_user_show',
            'path_upload' => 'partner_user_upload',
            'label_title' => 'label.vendor_partner_user',
            'vendor_partner_id' => $vendor_partner_id
        ]);
    }

    /**
     * @Route("/add", name="add", methods={"GET","POST"})
     * @param Request $request
     * @param FileUploaderHelper $fileUploaderHelper
     * @param CommonHelper $commonHelper
     * @return Response
     * @throws Exception
     */
    public function new(Request $request, FileUploaderHelper $fileUploaderHelper, CommonHelper $commonHelper, UserPasswordEncoderInterface $encoder, Mailer $mailer): Response
    {
        $appUser = new AppUser();
        $vendor_partner_id = $request->get('vendor_partner_id');
        if (!empty($vendor_partner_id)) {
            $TrnVendorPartnerDetails = $this->getDoctrine()->getRepository(TrnVendorPartnerDetails::class)->find($vendor_partner_id);
            $appUser->getAppUserInfo()->setTrnVendorPartnerDetails($TrnVendorPartnerDetails);
        }
        $loggedUser = $this->getUser();
        $option = array('password_required' => true,'userId'=>$loggedUser->getId());
        $form = $this->createForm(AppUserVendorPartnerType::class, $appUser, $option);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $imageContentFile = $form['appUserInfo']['userAvatarImagePath']->getData();
            if (!empty($imageContentFile)) {
                $appUserInfo = $appUser->getAppUserInfo();
                $strSaveFileName = $commonHelper->clean(strtolower($form['userName']->getData())).Uuid::uuid4()->toString();
                $newFilename = $fileUploaderHelper->uploadPublicFile($imageContentFile, $strSaveFileName,null);
                $appUserInfo->setUserAvatarImagePath($newFilename);
                $appUserInfo->setAppUser($appUser);
            }
            $appUser->setUserPassword($appUser->getUserName());
            $token = bin2hex(random_bytes(32));
            $appUser->setRowId(Uuid::uuid4()->toString());
            $appUser->setUserPassword($encoder->encodePassword($appUser, $form->get('userPassword')->getData()));
            $appUser->setUserCreationToken($token);
            $appUser->getAppUserInfo()->setUserIpAddress($_SERVER['SERVER_ADDR']);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($appUser);
            $this->getDoctrine()->getManager()->flush();
            #Send Reset Password Email
            $tokenExpiryTime = new \DateTime('+24 hour');
            $userName = $appUser->getUserName();
            $userEmail = $appUser->getAppUserInfo()->getUserEmail();
            $appUser->setUserResetPasswordToken($token);
            $appUser->setUserResetPasswordTokenExpiry($tokenExpiryTime);
            $entityManager->persist($appUser);
            $this->getDoctrine()->getManager()->flush();
            // Send Reset Email to User
            $mailer->mailerResetPassword($userEmail, $userName, $token);
            #Send Reset Password Email
            $this->addFlash('success', 'form.created_successfully');
            return $this->redirectToRoute('partner_user_index');
        }
        return $this->render('partner/user/form.html.twig', [
            'appUser' => $appUser,
            'form' => $form->createView(),
            'label_title' => 'label.vendor_partner_user',
            'label_button' => 'label.create',
            'mode' => 'add'
        ]);
    }

    /**
     * @Route("/edit/{id}", name="edit", methods={"GET","POST"})
     * @param Request $request
     * @param AppUser $appUser
     * @param FileUploaderHelper $fileUploaderHelper
     * @param CommonHelper $commonHelper
     * @param UserPasswordEncoderInterface $encoder
     * @return Response
     * @throws Exception
     */
    public function edit(Request $request, AppUser $appUser, FileUploaderHelper $fileUploaderHelper, CommonHelper $commonHelper, UserPasswordEncoderInterface $encoder): Response
    {
        $loggedUser = $this->getUser();
        $option = array('password_required' => false,'userId'=>$loggedUser->getId());
        $originalPassword = $appUser->getPassword();
        $form = $this->createForm(AppUserVendorPartnerType::class, $appUser);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if (!empty($form->get('userPassword')->getData())) {
                $appUser->setUserPassword($encoder->encodePassword($appUser, $form->get('userPassword')->getData()));
            }
            else {
                // Set the current password which the user has and have not changes in the edit form
                $appUser->setUserPassword($originalPassword);
            }
            $imageContentFile = $form['appUserInfo']['userAvatarImagePath']->getData();
            if (!empty($imageContentFile)) {
                $strSaveFileName = $commonHelper->clean(strtolower($form['userName']->getData())).Uuid::uuid4()
                        ->toString();
                $newFilename = $fileUploaderHelper->uploadPublicFile($imageContentFile, $strSaveFileName,null);
                $appUser->getAppUserInfo()->setUserAvatarImagePath($newFilename);
            }
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($appUser);
            $this->getDoctrine()->getManager()->flush();

            //echo $appUser->getAppUserInfo()->getUserAvatarImagePath();
            //die;
            $this->addFlash('success', 'form.created_successfully');
            return $this->redirectToRoute('partner_user_index');
        }
        return $this->render('partner/user/form.html.twig', [
            'appUser' => $appUser,
            'form' => $form->createView(),
            'label_title' => 'label.vendor_partner_user',
            'label_button' => 'label.create',
            'mode' => 'edit'
        ]);
    }

    /**
     * @Route("/{id}", name="show", methods={"GET"})
     * @param AppUser $appUser
     * @return Response
     */
    public function show(AppUser $appUser): Response
    {
        return $this->render('partner/user/show.html.twig', [
            'data' => $appUser,
            'label_title' => 'label.register',
            'label_button' => 'label.update',
            'path_index' => 'partner_user_index',
            'path_edit' => 'partner_user_edit'
        ]);
    }
}
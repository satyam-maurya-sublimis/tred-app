<?php

namespace App\Controller\Api;

use App\Entity\SystemApp\AppUser;
use App\Entity\SystemApp\AppUserCategory;
use App\Entity\SystemApp\AppUserInfo;
use Doctrine\Persistence\ManagerRegistry;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Ramsey\Uuid\Nonstandard\Uuid;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
/**
 * @Route("/api", name="api_")
 */
class AuthController extends ApiController
{
    private ManagerRegistry $managerRegistry;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
    }
    /**
     * @Route("/register", name="register", methods={"POST"})
     */
    public function register(Request $request, UserPasswordEncoderInterface $encoder)
    {

        $em = $this->managerRegistry->getManager();
        $request = $this->transformJsonBody($request);
        $firstName = $request->get('firstname');
        $middleName = $request->get('middlename');
        $lastName = $request->get('lastname');
        $password = $request->get('password');
        $mobile = $request->get('mobile');
        $email = $request->get('email');

        if (empty($password) || empty($email)){
            return $this->respondValidationError("Invalid Password or Email");
        }
        $appUser = new Appuser();
        $appUserInfo = new AppUserInfo();
        $appuserCategory = $em->getRepository(AppUserCategory::class)->find(2);

        $appUserInfo->setUserFirstName($firstName);
        $appUserInfo->setUserMiddleName($middleName);
        $appUserInfo->setUserLastName($lastName);
        $appUserInfo->setUserMobileNumber($mobile);
        $appUserInfo->setUserEmail($email);
        $uuidGenerator = Uuid::uuid4();
        $rowId = $uuidGenerator->toString();
        $appUser->setRowId($rowId);
        $appRole = array('ROLE_APP_USER');
        $appUser->setUserRole($appRole);
        $appUser->setUserPassword($encoder->encodePassword($appUser, $password));
        $appUserInfo->setAppuser($appUser);
        $appUser->setUsername($email);
        $appUser->setAppUserCategory($appuserCategory);
        $appUser->setIsActive(1);
        $em->persist($appUserInfo);
        $em->persist($appUser);
        $em->flush();
        return $this->respondWithSuccess(sprintf('User %s successfully created', $appUser->getUsername()));
    }

    /**
     * @Route("/login_check", name="login_check")
     * @param UserInterface $user
     * @param JWTTokenManagerInterface $JWTManager
     * @return JsonResponse
     */
    public function getTokenUser(UserInterface $user, JWTTokenManagerInterface $JWTManager)
    {
        return new JsonResponse(['token' => $JWTManager->create($user)]);
    }

    /**
     * @Route("/change-password", name="change_password", methods={"POST"})
     */
    public function changePassword(Request $request, UserPasswordEncoderInterface $encoder)
    {

        $em = $this->managerRegistry->getManager();
        $request = $this->transformJsonBody($request);
        $oldPassword = $request->get('oldpassword');
        $newPassword = $request->get('newpassword');
        $email = $request->get('email');
        if (empty($oldPassword) || empty($email) || empty($newPassword)){
            return $this->respondValidationError("Empty (Old Password, New Password and Email) is not allowed! ");
        }
        $appUser = $em->getRepository(AppUser::class)->findOneBy(["userName"=>$email]);
        if($appUser){
            if($encoder->isPasswordValid($appUser, $oldPassword))
            {
                $appUser->setUserPassword($encoder->encodePassword($appUser, $newPassword));
                $em->persist($appUser);
                $em->flush();
                return $this->setStatusCode(200)->respondWithSuccess('User password is successfully updated');
            }else{
                return $this->respondValidationError('User password is invalid');
            }
        }else{
            return $this->respondValidationError('User not authorized!');
        }
    }
}
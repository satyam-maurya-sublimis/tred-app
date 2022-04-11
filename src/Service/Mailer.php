<?php

namespace App\Service;

use DateTime;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Message;

class Mailer
{
    private $mailer;
    private $fromMail;
    private $ccMail;
    private $bccMail;
    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
        $this->fromMail = 'sales@tred.co.in';
        $this->bccMail = 'jayashree.kotian@sublimis.tech';
//        $this->bccMail = 'summera@tred.co.in';
    }

    // Send User Registration Successful Email to User
    public function mailerUserRegistrationSuccess($userEmail, $userFirstName, $userLastName)
    {
        $email = (new TemplatedEmail())
            ->from($this->fromMail)
            ->to($userEmail)
            ->subject('You have registered successfully on example.com.')
            ->htmlTemplate('mailer/portal/user-registration.html.twig')
            ->context([
                'first_name' => $userFirstName,
                'last_name' => $userLastName,
            ]);
        try {
            $this->mailer->send($email);
        } catch (TransportExceptionInterface $e) {
        }

    }


    // Send Forgot Password Email to User
    public function mailerForgotPassword($userEmail, $token)
    {
        $email = (new TemplatedEmail())
            ->from($this->fromMail)
            ->to($userEmail)
            ->subject('Reset your password')
            ->htmlTemplate('mailer/security/forgotpassword.html.twig')
            ->context([
                'validation_key' => $token,
                'expiration_date' => new DateTime('+24 hours')
            ]);
        try {
            $this->mailer->send($email);
        } catch (TransportExceptionInterface $e) {
        }

    }

    // Send Forgot Password Email to User
    public function mailerPortalForgotPassword($userEmail, $userFirstName, $userLastName, $token)
    {
        $email = (new TemplatedEmail())
            ->from($this->fromMail)
            ->to($userEmail)
            ->subject('Reset your password')
            ->htmlTemplate('mailer/portal/forgot-password.html.twig')
            ->context([
                'validation_key' => $token,
                'first_name' => $userFirstName,
                'last_name' => $userLastName,
                'expiration_date' => new DateTime('+24 hours')
            ]);
        try {
            $this->mailer->send($email);
        } catch (TransportExceptionInterface $e) {
        }

    }

    // Send Create Password Email to Portal Guest  User
    public function mailerPortalCreatePassword($userEmail, $userFirstName, $userLastName, $token)
    {
        $email = (new TemplatedEmail())
            ->from($this->fromMail)
            ->to($userEmail)
            ->subject('Reset your password')
            ->htmlTemplate('mailer/portal/create-password.html.twig')
            ->context([
                'validation_key' => $token,
                'first_name' => $userFirstName,
                'last_name' => $userLastName,
            ]);
        try {
            $this->mailer->send($email);
        } catch (TransportExceptionInterface $e) {
        }

    }


    // Send Create Password Email to User triggered from Organization User Interface
    public function mailerCreatePassword($userEmail, $userName, $token)
    {
        $email = (new TemplatedEmail())
            ->from($this->fromMail)
            ->to($userEmail)
            ->subject('Create your password')
            ->htmlTemplate('mailer/security/createpassword.html.twig')
            ->context([
                'userName' => $userName,
                'validation_key' => $token,
            ]);
        try {
            $this->mailer->send($email);
        } catch (TransportExceptionInterface $e) {
        }
    }
    // Send Reset Password Email to User
    public function mailerResetPassword($userEmail, $userName, $token)
    {
        $email = (new TemplatedEmail())
            ->from($this->fromMail)
            ->to($userEmail)
            ->subject('Reset your password')
            ->htmlTemplate('mailer/security/createpassword.html.twig')
            ->context([
                'userName' => $userName,
                'validation_key' => $token,
                'expiration_date' => new DateTime('+24 hours')
            ]);
        try {
            $this->mailer->send($email);
        } catch (TransportExceptionInterface $e) {
        }

    }

    // Send User Registration Successful Email to User
    public function mailerFormEnquiry($data)
    {
        $email = (new TemplatedEmail())
            ->from($this->fromMail)
            ->to($data->getEnquiryEmailAddress())
            ->bcc($this->bccMail)
            ->subject('Enquiry Form')
            ->htmlTemplate('mailer/portal/enquiry_form/success.html.twig')
            ->context([
                'data' => $data
            ]);
        try {
            $this->mailer->send($email);
        } catch (TransportExceptionInterface $e) {
        }

    }
    // Send User Registration Successful Email to User
    public function mailerFormEnquiryContact($data)
    {
        $email = (new TemplatedEmail())
            ->from($this->fromMail)
            ->to($data->getEnquiryEmailAddress())
            ->bcc($this->bccMail)
            ->subject('Contact Enquiry Form')
            ->htmlTemplate('mailer/portal/enquiry_form/contact.html.twig')
            ->context([
                'data' => $data
            ]);
        try {
            $this->mailer->send($email);
        } catch (TransportExceptionInterface $e) {
        }

    }

    // Send User Registration Successful Email to User
    public function mailerPropertiesFormEnquiry($data)
    {
        $email = (new TemplatedEmail())
            ->from($this->fromMail)
            ->to($data->getResidentialEnquiryEmailAddress())
            ->bcc($this->bccMail)
            ->subject($data->getResidentialEnquiryTitle())
            ->htmlTemplate('mailer/portal/enquiry_form/properties.html.twig')
            ->context([
                'data' => $data
            ]);
        try {
            $this->mailer->send($email);
        } catch (TransportExceptionInterface $e) {
        }

    }

    // Send User Registration Successful Email to User
    public function mailerFormEnquiryVastu($data)
    {
        $email = (new TemplatedEmail())
            ->from($this->fromMail)
            ->to($data->getEnquiryEmailAddress())
            ->bcc($this->bccMail)
            ->subject('Enquiry Form')
            ->htmlTemplate('mailer/portal/enquiry_form/vastu.html.twig')
            ->context([
                'data' => $data
            ]);
        try {
            $this->mailer->send($email);
        } catch (TransportExceptionInterface $e) {
        }

    }

    // Send User Registration Successful Email to User
    public function mailerFurnitureEnquiry($data)
    {
        $email = (new TemplatedEmail())
            ->from($this->fromMail)
            ->to($data->getFurnitureEnquiryEmailAddress())
            ->bcc($this->bccMail)
            ->subject('Enquiry Form')
            ->htmlTemplate('mailer/portal/enquiry_form/furniture.html.twig')
            ->context([
                'data' => $data
            ]);
        try {
            $this->mailer->send($email);
        } catch (TransportExceptionInterface $e) {
        }

    }

    // Send User Registration Successful Email to User
    public function mailerFurnitureProductCataglogEnquiry($data)
    {
        $email = (new TemplatedEmail())
            ->from($this->fromMail)
            ->to($data->getFurnitureEnquiryEmailAddress())
            ->bcc($this->bccMail)
            ->subject('Enquiry Form')
            ->htmlTemplate('mailer/portal/enquiry_form/furniture_product_catalog.html.twig')
            ->context([
                'data' => $data
            ]);
        try {
            $this->mailer->send($email);
        } catch (TransportExceptionInterface $e) {
        }

    }

    // Send vendor feedback to business
    public function mailerPropertyFeedback($data)
    {
        $email = (new TemplatedEmail())
            ->from($this->fromMail)
            ->to($this->bccMail)
            ->subject('Project Feedback from Vendor')
            ->htmlTemplate('mailer/backend/property_feedback.html.twig')
            ->context([
                'data' => $data,
            ]);
        try {
            $this->mailer->send($email);
        } catch (TransportExceptionInterface $e) {
        }

    }

    // Send content User feedback reply to vendor
    public function mailerPropertyFeedbackReply($data)
    {
        $email = (new TemplatedEmail())
            ->from($this->fromMail)
            ->to($data->getCreatedBy()->getAppUserInfo()->getUserEmail())
            ->bcc($this->bccMail)
            ->subject('Project Feedback Reply')
            ->htmlTemplate('mailer/backend/property_feedback_reply.html.twig')
            ->context([
                'data' => $data,
            ]);
        try {
            $this->mailer->send($email);
        } catch (TransportExceptionInterface $e) {
        }

    }

    // Send to tred
    public function mailerFormEnquiryTopAgent($data)
    {
        $email = (new TemplatedEmail())
            ->from($this->fromMail)
            ->to($data->getEnquiryEmailAddress())
            ->bcc($this->bccMail)
            ->subject('Enquiry Form')
            ->htmlTemplate('mailer/portal/enquiry_form/top-agent.html.twig')
            ->context([
                'data' => $data
            ]);
        try {
            $this->mailer->send($email);
        } catch (TransportExceptionInterface $e) {
        }

    }
}
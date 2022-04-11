<?php

namespace App\Controller\Transaction;
use App\Entity\SystemApp\AppUser;
use App\Entity\Transaction\TrnProject;
use App\Entity\Transaction\TrnProjectFeedback;
use App\Entity\Transaction\TrnUploadDocument;
use App\Form\Transaction\TrnProjectFeedbackReplyType;
use App\Form\Transaction\TrnProjectFeedbackType;
use App\Repository\Transaction\TrnProjectFeedbackRepository;
use App\Service\Mailer;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/core/product/property-feedback", name="product_property_feedback_")
 * @IsGranted("ROLE_VENDOR_USER")
 */
class TrnPropertyFeedbackController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET"})
     * @param TrnProjectFeedbackRepository $trnProjectFeedbackRepository
     * @param Request $request
     * @return Response
     */
    public function index(TrnProjectFeedbackRepository $trnProjectFeedbackRepository, Request $request): Response
    {
        $projectId = $request->query->get('projectId');
        if(!$projectId) {
            return $this->redirectToRoute('product_properties_index');
        }
        $user = $this->getUser();
        $trnProject = $this->getDoctrine()->getRepository(TrnProject::class)->find($projectId);
        if (in_array("ROLE_SUPER_ADMIN", $user->getRoles())) {
            $trnProjectFeedbacks = $trnProjectFeedbackRepository->findBy(['trnProjects' => $projectId], ['createdOn'=>'Desc']);
        }else{
            $trnProjectFeedbacks = $trnProjectFeedbackRepository->findBy(['trnProjects' => $projectId,'createdBy'=>$this->getUser()], ['createdOn'=>'Desc']);
        }
//        return $this->render('mailer/backend/property_feedback_reply.html.twig', [
//            'data'=> $trnProjectFeedbacks[1],
//        ]);
        return $this->render('transaction/property/property_feedback/index.html.twig', [
            'trnProjectFeedbacks' => $trnProjectFeedbacks,
            'trnProject' => $trnProject,
            'path_index' => 'product_property_feedback_index',
            'path_add' => 'product_property_feedback_add',
            'path_show' => 'product_property_feedback_show',
            'path_reply' => 'product_property_feedback_reply',
            'label_title' => 'label.project_property_feedback',
            'label_heading' => 'label.project_name'
        ]);
    }

    /**
     * @Route("/add", name="add", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     * @throws \Exception
     */
    public function new(Request $request, Mailer $mailer): Response
    {
        $projectId = $request->query->get('projectId');
        if(!$projectId) {
            return $this->redirectToRoute('product_properties_index');
        }
        $trnProjectFeedback = new TrnProjectFeedback();
        $trnProject = $this->getDoctrine()->getRepository(TrnProject::class)->find($projectId);
        $form = $this->createForm(TrnProjectFeedbackType::class, $trnProjectFeedback,['projectId'=>$projectId]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $trnProjectFeedback->setTrnProjects($trnProject);
            $trnProjectFeedback->setCreatedBy($this->getUser());
            $trnProjectFeedback->setCreatedOn(new \DateTime());
            $entityManager->persist($trnProjectFeedback);
            $entityManager->flush();
            $this->addFlash('success', 'form.created_successfully');
            $mailer->mailerPropertyFeedback($trnProjectFeedback);
            return $this->redirectToRoute('product_property_feedback_index', $request->query->all());
        }
        return $this->render('transaction/property/property_feedback/form.html.twig', [
            'trnProjectFeedback' => $trnProjectFeedback,
            'trnProject' => $trnProject,
            'form' => $form->createView(),
            'index_path' => 'product_property_feedback_index',
            'label_title' => 'label.project_property_feedback',
            'label_button' => 'label.create',
            'label_heading' => 'label.project_name',
            'mode' => 'add'
        ]);
    }

    /**
     * @Route("/reply/{id}", name="reply", methods={"GET","POST"})
     * @param Request $request
     * @param Mailer $mailer
     * @param TrnProjectFeedback $trnProjectFeedback
     * @return Response
     */
    public function adminReply(Request $request, Mailer $mailer, TrnProjectFeedback $trnProjectFeedback): Response
    {
        $user = $this->getUser();
        $projectId = $request->query->get('projectId');
        if(!$projectId && (!in_array("ROLE_SUPER_ADMIN", $user->getRoles()))) {
            return $this->redirectToRoute('product_properties_index');
        }
        $trnProject = $this->getDoctrine()->getRepository(TrnProject::class)->find($projectId);
        $form = $this->createForm(TrnProjectFeedbackReplyType::class, $trnProjectFeedback,['projectId'=>$projectId]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $trnProjectFeedback->setReplyBy($this->getUser());
            $trnProjectFeedback->setReplyOn(new \DateTime());
            $entityManager->persist($trnProjectFeedback);
            $entityManager->flush();
            $mailer->mailerPropertyFeedbackReply($trnProjectFeedback);
            $this->addFlash('success', 'form.created_successfully');
            return $this->redirectToRoute('product_property_feedback_index', $request->query->all());
        }
        return $this->render('transaction/property/property_feedback/reply.html.twig', [
            'trnProjectFeedback' => $trnProjectFeedback,
            'trnProject' => $trnProject,
            'form' => $form->createView(),
            'index_path' => 'product_property_feedback_index',
            'label_title' => 'label.project_property_feedback',
            'label_button' => 'label.reply',
            'label_heading' => 'label.project_name',
            'mode' => 'add'
        ]);
    }

    /**
     * @Route("/{id}", name="show", methods={"GET","POST"})
     * @param TrnProjectFeedback $trnProjectFeedback
     * @return Response
     */
    public function show(TrnProjectFeedback $trnProjectFeedback): Response
    {
        return $this->render('transaction/property/property_feedback/show.html.twig', [
            'data' => $trnProjectFeedback,
            'label_title' => 'label.project_property_feedback',
            'label_button'   => 'label.update',
            'path_index' => 'product_property_feedback_index',
        ]);
    }
}
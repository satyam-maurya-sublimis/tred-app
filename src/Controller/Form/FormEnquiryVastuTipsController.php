<?php

namespace App\Controller\Form;

use App\Entity\Form\FormEnquiryVastuTips;
use App\Entity\Master\MstLeadStatus;
use App\Entity\Master\MstState;
use App\Form\Form\FormEnquiryVastuTipsType;
use App\Form\Form\FormEnquiryType;
use App\Form\Portal\FormEnquirySixType;
use App\Repository\Form\FormEnquiryVastuTipsRepository;
use DateTime;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/core/form/vastu-tips", name="form_vastu_tips_")
 * @IsGranted("ROLE_SYS_CONTENT_USER")
 */
class FormEnquiryVastuTipsController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET"})
     * @param FormEnquiryVastuTipsRepository $formEnquiryVastuTipsRepository
     * @return Response
     */
    public function index(FormEnquiryVastuTipsRepository $formEnquiryVastuTipsRepository): Response
    {
        return $this->render('form/form_vastu_tips/index.html.twig', [
            'form_enquiries' => $formEnquiryVastuTipsRepository->findAll(),
            'path_index' => 'form_vastu_tips_index',
            'path_add' => 'form_vastu_tips_add',
            'path_edit' => 'form_vastu_tips_edit',
            'path_show' => 'form_vastu_tips_show',
            'label_title' => 'label.vastu',
        ]);
    }

    /**
     * @Route("/search", name="search", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function search(Request $request): Response
    {
        $formEnquiry = trim($request->query->get('formEnquiry'));
        $formEnquiries = $this->getDoctrine()->getRepository(FormEnquiryVastuTips::class)->findBy(['enquiryForm' => $formEnquiry]);
        return $this->render('form/form_vastu_tips/_ajax_listing.html.twig', [
            'form_enquiries' => $formEnquiries,
            'path_add' => 'form_vastu_tips_add',
            'path_edit' => 'form_vastu_tips_edit',
            'path_show' => 'form_vastu_tips_show',
            'label_title' => 'label.vastu',
        ]);
    }

    /**
     * @Route("/{id}", name="show", methods={"GET"})
     * @param FormEnquiryVastuTips $formEnquiry
     * @return Response
     */
    public function show(FormEnquiryVastuTips $formEnquiry): Response
    {
        return $this->render('form/form_vastu_tips/show.html.twig', [
            'data' => $formEnquiry,
            'path_index' => 'form_vastu_tips_index',
            'path_edit' => 'form_vastu_tips_edit',
            'label_button' => 'label.vastu',
        ]);
    }

    /**
     * @Route("/edit/{id}", name="edit", methods={"GET","POST"})
     * @param Request $request
     * @param FormEnquiryVastuTips $formEnquiryVastuTips
     * @return Response
     */
    public function edit(Request $request, FormEnquiryVastuTips $formEnquiryVastuTips): Response
    {
        $form = $this->createForm(FormEnquiryVastuTipsType::class, $formEnquiryVastuTips);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'form.updated_successfully');
            return $this->redirectToRoute('form_vastu_tips_index');
        }

        return $this->render('form/form_vastu_tips/form.html.twig', [
            'formEnquiry' => $formEnquiryVastuTips,
            'form' => $form->createView(),
            'label_title' => 'label.vastu',
            'label_button' => 'label.update',
            'mode' => 'edit'
        ]);
    }

}

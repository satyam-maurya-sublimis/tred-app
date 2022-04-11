<?php

namespace App\Controller\Form;

use App\Entity\Form\FormResidentialEnquiry;
use App\Form\Form\FormResidentialEnquiryType;
use App\Repository\Form\FormResidentialEnquiryRepository;
use DateTime;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/core/form/residential-enquiry", name="form_residential_enquiry_")
 * @IsGranted("ROLE_SYS_CONTENT_USER")
 */
class FormResidentialEnquiryController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET"})
     * @param FormResidentialEnquiryRepository $formResidentialEnquiryRepository
     * @return Response
     */
    public function index(FormResidentialEnquiryRepository $formResidentialEnquiryRepository): Response
    {
        return $this->render('form/form_residential_enquiry/index.html.twig', [
            'form_residential_enquirys' => $formResidentialEnquiryRepository->findAll(),
            'path_index' => 'form_residential_enquiry_index',
            'path_add' => 'form_residential_enquiry_add',
            'path_edit' => 'form_residential_enquiry_edit',
            'path_show' => 'form_residential_enquiry_show',
            'label_title' => 'label.residential_enquiry',
        ]);
    }

    /**
     * @Route("/add", name="add", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
        $formResidentialEnquiry = new FormResidentialEnquiry();
        $form = $this->createForm(FormResidentialEnquiryType::class, $formResidentialEnquiry);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $formResidentialEnquiry->setResidentialEnquiryCreateTime(new DateTime());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($formResidentialEnquiry);
            $entityManager->flush();
            $this->addFlash('success', 'form.created_successfully');
            return $this->redirectToRoute('form_residential_enquiry_index');
        }

        return $this->render('form/form_residential_enquiry/form.html.twig', [
            'form_residential_enquiry' => $formResidentialEnquiry,
            'form' => $form->createView(),
            'label_title' => 'label.residential_enquiry',
            'label_button' => 'label.create',
            'mode' => 'add'
        ]);
    }

    /**
     * @Route("/{id}", name="show", methods={"GET"})
     * @param FormResidentialEnquiry $formResidentialEnquiry
     * @return Response
     */
    public function show(FormResidentialEnquiry $formResidentialEnquiry): Response
    {
        return $this->render('form/form_residential_enquiry/show.html.twig', [
            'data' => $formResidentialEnquiry,
            'path_index' => 'form_residential_enquiry_index',
            'path_edit' => 'form_residential_enquiry_edit',
            'path_convert' => 'form_residential_enquiry_convert',
            'path_delete' => 'form_residential_enquiry_delete',
            'label_button' => 'label.residential_enquiry',
        ]);
    }

    /**
     * @Route("/edit/{id}", name="edit", methods={"GET","POST"})
     * @param Request $request
     * @param FormResidentialEnquiry $formResidentialEnquiry
     * @return Response
     */
    public function edit(Request $request, FormResidentialEnquiry $formResidentialEnquiry): Response
    {
        $form = $this->createForm(FormResidentialEnquiryType::class, $formResidentialEnquiry);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'form.updated_successfully');
            return $this->redirectToRoute('form_residential_enquiry_index');
        }

        return $this->render('form/form_residential_enquiry/form.html.twig', [
            'form_residential_enquiry' => $formResidentialEnquiry,
            'form' => $form->createView(),
            'label_title' => 'label.residential_enquiry',
            'label_button' => 'label.update',
            'mode' => 'edit'
        ]);
    }

    /**
     * @Route("/{id}", name="delete", methods={"DELETE"})
     * @param Request $request
     * @param FormResidentialEnquiry $formResidentialEnquiry
     * @return Response
     */
    public function delete(Request $request, FormResidentialEnquiry $formResidentialEnquiry): Response
    {
        if ($this->isCsrfTokenValid('delete'.$formResidentialEnquiry->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($formResidentialEnquiry);
            $entityManager->flush();
        }

        return $this->redirectToRoute('form_residential_enquiry_index');
    }
}

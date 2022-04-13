<?php

namespace App\Controller\Form;

use App\Entity\Form\FormEnquiry;
use App\Entity\Form\FormResidentialEnquiry;
use App\Entity\Master\MstLeadStatus;
use App\Entity\Master\MstState;
use App\Form\Form\FormEnquiryType;
use App\Form\Form\FormResidentialEnquiryType;
use App\Repository\Form\FormEnquiryRepository;
use DateTime;
use Doctrine\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/core/form/enquiry", name="form_enquiry_")
 * @IsGranted("ROLE_SYS_CONTENT_USER")
 */
class FormEnquiryController extends AbstractController
{
    private ManagerRegistry $managerRegistry;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
    }
    /**
     * @Route("/", name="index", methods={"GET"})
     * @param FormEnquiryRepository $formEnquiryRepository
     * @return Response
     */
    public function index(FormEnquiryRepository $formEnquiryRepository): Response
    {
        return $this->render('form/form_enquiry/index.html.twig', [
            'form_enquiries' => $formEnquiryRepository->findAll(),
            'path_index' => 'form_enquiry_index',
            'path_add' => 'form_enquiry_add',
            'path_edit' => 'form_enquiry_edit',
            'path_show' => 'form_enquiry_show',
            'label_title' => 'label.enquiry',
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
        $formEnquiries = $this->managerRegistry->getRepository(FormEnquiry::class)->findBy(['enquiryForm' => $formEnquiry]);
        return $this->render('form/form_enquiry/_ajax_listing.html.twig', [
            'form_enquiries' => $formEnquiries,
            'path_add' => 'form_enquiry_add',
            'path_edit' => 'form_enquiry_edit',
            'path_show' => 'form_enquiry_show',
            'label_title' => 'label.enquiry',
        ]);
    }

    /**
     * @Route("/{slugName}", name="index", methods={"GET"})
     * @param FormEnquiryRepository $formEnquiryRepository
     * @return Response
     */
    public function standalone(Request $request,FormEnquiryRepository $formEnquiryRepository): Response
    {
        $slugName  = $request->get('slugName');
        $formEnquiry = $this->managerRegistry->getRepository(FormEnquiry::class)->findBy(['enquiryForm' => $slugName]);
        return $this->render('form/form_enquiry/alone.html.twig', [
            'form_enquiries' => $formEnquiry,
            'path_index' => 'form_enquiry_index',
            'path_add' => 'form_enquiry_add',
            'path_edit' => 'form_enquiry_edit',
            'path_show' => 'form_enquiry_show',
            'label_title' => 'label.enquiry',
        ]);
    }

    /**
     * @Route("/{id}", name="show", methods={"GET"})
     * @param FormEnquiry $formEnquiry
     * @return Response
     */
    public function show(FormEnquiry $formEnquiry): Response
    {
        return $this->render('form/form_enquiry/show.html.twig', [
            'data' => $formEnquiry,
            'path_index' => 'form_enquiry_index',
            'path_edit' => 'form_enquiry_edit',
            'label_button' => 'label.enquiry',
        ]);
    }

    /**
     * @Route("/edit/{id}", name="edit", methods={"GET","POST"})
     * @param Request $request
     * @param FormEnquiry $formEnquiry
     * @return Response
     */
    public function edit(Request $request, FormEnquiry $formEnquiry): Response
    {
        $form = $this->createForm(FormEnquiryType::class, $formEnquiry);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $slugName = $form->getData()->getEnquiryForm();
            $this->managerRegistry->getManager()->flush();
            $this->addFlash('success', 'form.updated_successfully');
            return $this->redirectToRoute('form_enquiry_index',['slugName'=>$slugName]);
        }

        return $this->render('form/form_enquiry/form.html.twig', [
            'formEnquiry' => $formEnquiry,
            'form' => $form->createView(),
            'label_title' => 'label.enquiry',
            'label_button' => 'label.update',
            'mode' => 'edit'
        ]);
    }

}

<?php

namespace App\Controller\Form;

use App\Entity\Form\FormEnquiryContactUs;
use App\Entity\Master\MstLeadStatus;
use App\Entity\Master\MstState;
use App\Form\Form\FormEnquiryContactUsType;
use App\Form\Form\FormEnquiryType;
use App\Form\Portal\FormEnquirySixType;
use App\Repository\Form\FormEnquiryContactUsRepository;
use DateTime;
use Doctrine\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/core/form/contact-us", name="form_contact_us_")
 * @IsGranted("ROLE_SYS_CONTENT_USER")
 */
class FormEnquiryContactUsController extends AbstractController
{
    private ManagerRegistry $managerRegistry;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
    }
    /**
     * @Route("/", name="index", methods={"GET"})
     * @param FormEnquiryContactUsRepository $formEnquiryContactUsRepository
     * @return Response
     */
    public function index(FormEnquiryContactUsRepository $formEnquiryContactUsRepository): Response
    {
        return $this->render('form/form_contact_us/index.html.twig', [
            'form_enquiries' => $formEnquiryContactUsRepository->findAll(),
            'path_index' => 'form_contact_us_index',
            'path_add' => 'form_contact_us_add',
            'path_edit' => 'form_contact_us_edit',
            'path_show' => 'form_contact_us_show',
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
        $formEnquiries = $this->managerRegistry->getRepository(FormEnquiryContactUs::class)->findBy(['enquiryForm' => $formEnquiry]);
        return $this->render('form/form_contact_us/_ajax_listing.html.twig', [
            'form_enquiries' => $formEnquiries,
            'path_add' => 'form_contact_us_add',
            'path_edit' => 'form_contact_us_edit',
            'path_show' => 'form_contact_us_show',
            'label_title' => 'label.enquiry',
        ]);
    }

    /**
     * @Route("/{id}", name="show", methods={"GET"})
     * @param FormEnquiryContactUs $formEnquiry
     * @return Response
     */
    public function show(FormEnquiryContactUs $formEnquiry): Response
    {
        return $this->render('form/form_contact_us/show.html.twig', [
            'data' => $formEnquiry,
            'path_index' => 'form_contact_us_index',
            'path_edit' => 'form_contact_us_edit',
            'label_button' => 'label.enquiry',
        ]);
    }

    /**
     * @Route("/edit/{id}", name="edit", methods={"GET","POST"})
     * @param Request $request
     * @param FormEnquiryContactUs $formEnquiryContactUs
     * @return Response
     */
    public function edit(Request $request, FormEnquiryContactUs $formEnquiryContactUs): Response
    {
        $form = $this->createForm(FormEnquiryContactUsType::class, $formEnquiryContactUs);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->managerRegistry->getManager()->flush();
            $this->addFlash('success', 'form.updated_successfully');
            return $this->redirectToRoute('form_contact_us_index');
        }

        return $this->render('form/form_contact_us/form.html.twig', [
            'formEnquiry' => $formEnquiryContactUs,
            'form' => $form->createView(),
            'label_title' => 'label.enquiry',
            'label_button' => 'label.update',
            'mode' => 'edit'
        ]);
    }

}

<?php

namespace App\Controller\Form;

use App\Entity\Form\FormFurnitureEnquiry;
use App\Form\Form\FormFurnitureEnquiryType;
use App\Repository\Form\FormFurnitureEnquiryRepository;
use DateTime;
use Doctrine\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/core/form/furniture-enquiry", name="form_furniture_enquiry_")
 * @IsGranted("ROLE_SYS_CONTENT_USER")
 */
class FormFurnitureEnquiryController extends AbstractController
{
    private ManagerRegistry $managerRegistry;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
    }
    /**
     * @Route("/", name="index", methods={"GET"})
     * @param FormFurnitureEnquiryRepository $formFurnitureEnquiryRepository
     * @return Response
     */
    public function index(FormFurnitureEnquiryRepository $formFurnitureEnquiryRepository): Response
    {
        return $this->render('form/form_furniture_enquiry/index.html.twig', [
            'form_furniture_enquirys' => $formFurnitureEnquiryRepository->findAll(),
            'path_index' => 'form_furniture_enquiry_index',
            'path_add' => 'form_furniture_enquiry_add',
            'path_edit' => 'form_furniture_enquiry_edit',
            'path_show' => 'form_furniture_enquiry_show',
            'label_title' => 'label.furniture_enquiry',
        ]);
    }

    /**
     * @Route("/add", name="add", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
        $formFurnitureEnquiry = new FormFurnitureEnquiry();
        $form = $this->createForm(FormFurnitureEnquiryType::class, $formFurnitureEnquiry);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $formFurnitureEnquiry->setFurnitureEnquiryCreateTime(new DateTime());
            $entityManager = $this->managerRegistry->getManager();
            $entityManager->persist($formFurnitureEnquiry);
            $entityManager->flush();
            $this->addFlash('success', 'form.created_successfully');
            return $this->redirectToRoute('form_furniture_enquiry_index');
        }

        return $this->render('form/form_furniture_enquiry/form.html.twig', [
            'form_furniture_enquiry' => $formFurnitureEnquiry,
            'form' => $form->createView(),
            'label_title' => 'label.furniture_enquiry',
            'label_button' => 'label.create',
            'mode' => 'add'
        ]);
    }

    /**
     * @Route("/{id}", name="show", methods={"GET"})
     * @param FormFurnitureEnquiry $formFurnitureEnquiry
     * @return Response
     */
    public function show(FormFurnitureEnquiry $formFurnitureEnquiry): Response
    {
        return $this->render('form/form_furniture_enquiry/show.html.twig', [
            'data' => $formFurnitureEnquiry,
            'path_index' => 'form_furniture_enquiry_index',
            'path_edit' => 'form_furniture_enquiry_edit',
            'path_convert' => 'form_furniture_enquiry_convert',
            'path_delete' => 'form_furniture_enquiry_delete',
            'label_button' => 'label.furniture_enquiry',
        ]);
    }

    /**
     * @Route("/edit/{id}", name="edit", methods={"GET","POST"})
     * @param Request $request
     * @param FormFurnitureEnquiry $formFurnitureEnquiry
     * @return Response
     */
    public function edit(Request $request, FormFurnitureEnquiry $formFurnitureEnquiry): Response
    {
        $form = $this->createForm(FormFurnitureEnquiryType::class, $formFurnitureEnquiry);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->managerRegistry->getManager()->flush();
            $this->addFlash('success', 'form.updated_successfully');
            return $this->redirectToRoute('form_furniture_enquiry_index');
        }

        return $this->render('form/form_furniture_enquiry/form.html.twig', [
            'form_furniture_enquiry' => $formFurnitureEnquiry,
            'form' => $form->createView(),
            'label_title' => 'label.furniture_enquiry',
            'label_button' => 'label.update',
            'mode' => 'edit'
        ]);
    }

    /**
     * @Route("/{id}", name="delete", methods={"DELETE"})
     * @param Request $request
     * @param FormFurnitureEnquiry $formFurnitureEnquiry
     * @return Response
     */
    public function delete(Request $request, FormFurnitureEnquiry $formFurnitureEnquiry): Response
    {
        if ($this->isCsrfTokenValid('delete'.$formFurnitureEnquiry->getId(), $request->request->get('_token'))) {
            $entityManager = $this->managerRegistry->getManager();
            $entityManager->remove($formFurnitureEnquiry);
            $entityManager->flush();
        }

        return $this->redirectToRoute('form_furniture_enquiry_index');
    }
}

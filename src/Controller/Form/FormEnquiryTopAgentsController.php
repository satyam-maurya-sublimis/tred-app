<?php

namespace App\Controller\Form;

use App\Entity\Form\FormEnquiryTopAgents;
use App\Form\Form\FormEnquiryTopAgentsType;
use App\Repository\Form\FormEnquiryTopAgentsRepository;
use DateTime;
use Doctrine\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/core/form/top-agents", name="form_top_agents_")
 * @IsGranted("ROLE_SYS_CONTENT_USER")
 */
class FormEnquiryTopAgentsController extends AbstractController
{
    private ManagerRegistry $managerRegistry;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
    }
    /**
     * @Route("/", name="index", methods={"GET"})
     * @param FormEnquiryTopAgentsRepository $formEnquiryTopAgentsRepository
     * @return Response
     */
    public function index(FormEnquiryTopAgentsRepository $formEnquiryTopAgentsRepository): Response
    {
        return $this->render('form/form_top_agents/index.html.twig', [
            'form_enquiries' => $formEnquiryTopAgentsRepository->findAll(),
            'path_index' => 'form_top_agents_index',
            'path_add' => 'form_top_agents_add',
            'path_edit' => 'form_top_agents_edit',
            'path_show' => 'form_top_agents_show',
            'label_title' => 'label.top_agents',
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
        $formEnquiries = $this->managerRegistry->getRepository(FormEnquiryTopAgents::class)->findBy(['enquiryForm' => $formEnquiry]);
        return $this->render('form/form_top_agents/_ajax_listing.html.twig', [
            'form_enquiries' => $formEnquiries,
            'path_add' => 'form_top_agents_add',
            'path_edit' => 'form_top_agents_edit',
            'path_show' => 'form_top_agents_show',
            'label_title' => 'label.top_agents',
        ]);
    }

    /**
     * @Route("/{id}", name="show", methods={"GET"})
     * @param FormEnquiryTopAgents $formEnquiry
     * @return Response
     */
    public function show(FormEnquiryTopAgents $formEnquiry): Response
    {
        return $this->render('form/form_top_agents/show.html.twig', [
            'data' => $formEnquiry,
            'path_index' => 'form_top_agents_index',
            'path_edit' => 'form_top_agents_edit',
            'label_button' => 'label.top_agents',
        ]);
    }

    /**
     * @Route("/edit/{id}", name="edit", methods={"GET","POST"})
     * @param Request $request
     * @param FormEnquiryTopAgents $formEnquiryTopAgents
     * @return Response
     */
    public function edit(Request $request, FormEnquiryTopAgents $formEnquiryTopAgents): Response
    {
        $form = $this->createForm(FormEnquiryTopAgentsType::class, $formEnquiryTopAgents);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->managerRegistry->getManager()->flush();
            $this->addFlash('success', 'form.updated_successfully');
            return $this->redirectToRoute('form_top_agents_index');
        }

        return $this->render('form/form_top_agents/form.html.twig', [
            'formEnquiry' => $formEnquiryTopAgents,
            'form' => $form->createView(),
            'label_title' => 'label.top_agents',
            'label_button' => 'label.update',
            'mode' => 'edit'
        ]);
    }

}

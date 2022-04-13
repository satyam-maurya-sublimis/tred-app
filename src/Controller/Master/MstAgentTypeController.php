<?php

namespace App\Controller\Master;

use Doctrine\Persistence\ManagerRegistry;
use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Entity\Master\MstAgentType;
use App\Form\Master\MstAgentTypeType;
use App\Repository\Master\MstAgentTypeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Ramsey\Uuid\Uuid;

/**
 * @Route("/core/master/general/agent_type", name="master_agent_type_")
 * @IsGranted("ROLE_SYS_CONTENT_USER")
 */
class MstAgentTypeController extends AbstractController
{
    private ManagerRegistry $managerRegistry;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
    }
    /**
     * @Route("/", name="index", methods={"GET"})
     * @param MstAgentTypeRepository $mstAgentTypeRepository
     * @param Request $request
     * @return Response
     */
    public function index(MstAgentTypeRepository $mstAgentTypeRepository, Request $request): Response
    {
        $agent_types = $mstAgentTypeRepository->findAll();
        return $this->render('master/mst_agent_type/index.html.twig', [
            'mst_agent_types' => $agent_types,
            'path_index' => 'master_agent_type_index',
            'path_add' => 'master_agent_type_add',
            'path_edit' => 'master_agent_type_edit',
            'path_show' => 'master_agent_type_show',
            'label_title' => 'label.agent_type',
        ]);
    }

    /**
     * @Route("/add", name="add", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     * @throws Exception
     */
    public function new(Request $request): Response
    {
        $mstAgentType = new MstAgentType();
        $form = $this->createForm(MstAgentTypeType::class, $mstAgentType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $mstAgentType->setRowId(Uuid::uuid4()->toString());
            $entityManager = $this->managerRegistry->getManager();
            $entityManager->persist($mstAgentType);
            $entityManager->flush();
            $this->addFlash('success', 'form.created_successfully');
            return $this->redirectToRoute('master_agent_type_index');
        }

        return $this->render('form/form.html.twig', [
            'master_agent_type' => $mstAgentType,
            'form' => $form->createView(),
            'index_path' => 'master_agent_type_index',
            'label_title' => 'label.agent_type',
            'label_button' => 'label.create',
            'mode' => 'add'
        ]);
    }

    /**
     * @Route("/search", name="search", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function search(Request $request): Response
    {
        $countryId = trim($request->query->get('countryId'));
        $agent_type = ucwords($request->query->get('agent_typeSearch'));

        $mstAgentType = $this->managerRegistry->getRepository(MstAgentType::class)->getCityListByCountryId($agent_type, $countryId);
        return $this->render('master/mst_agent_type/_ajax_listing.html.twig', [
            'mst_cities' => $mstAgentType,
            'country_id' => $countryId,
            'path_add' => 'master_agent_type_add',
            'path_edit' => 'master_agent_type_edit',
            'path_show' => 'master_agent_type_show',
            'label_title' => 'label.agent_type',
            'mode' => 'add'
        ]);
    }

    /**
     * @Route("/edit/{id}", name="edit", methods={"GET","POST"})
     * @param Request $request
     * @param MstAgentType $mstAgentType
     * @return Response
     */
    public function edit(Request $request, MstAgentType $mstAgentType): Response
    {
        $form = $this->createForm(MstAgentTypeType::class, $mstAgentType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->managerRegistry->getManager()->flush();
            $this->addFlash('success', 'form.updated_successfully');
            return $this->redirectToRoute('master_agent_type_index');
        }

        return $this->render('form/form.html.twig', [
            'master_agent_type' => $mstAgentType,
            'form' => $form->createView(),
            'index_path' => 'master_agent_type_index',
            'label_title' => 'label.agent_type',
            'label_button' => 'label.update',
            'mode' => 'edit'
        ]);
    }

    /**
     * @Route("/{id}", name="delete", methods={"DELETE"})
     * @param Request $request
     * @param MstAgentType $mstAgentType
     * @return Response
     */
    public function delete(Request $request, MstAgentType $mstAgentType): Response
    {
        if ($this->isCsrfTokenValid('delete'.$mstAgentType->getId(), $request->request->get('_token'))) {
            $entityManager = $this->managerRegistry->getManager();
            $entityManager->remove($mstAgentType);
            $entityManager->flush();
        }

        return $this->redirectToRoute('master_agent_type_index');
    }
}

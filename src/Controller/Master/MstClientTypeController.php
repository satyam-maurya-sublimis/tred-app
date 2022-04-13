<?php

namespace App\Controller\Master;

use Doctrine\Persistence\ManagerRegistry;
use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Entity\Master\MstClientType;
use App\Form\Master\MstClientTypeType;
use App\Repository\Master\MstClientTypeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Ramsey\Uuid\Uuid;

/**
 * @Route("/core/master/general/client_type", name="master_client_type_")
 * @IsGranted("ROLE_SYS_CONTENT_USER")
 */
class MstClientTypeController extends AbstractController
{
    private ManagerRegistry $managerRegistry;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
    }
    /**
     * @Route("/", name="index", methods={"GET"})
     * @param MstClientTypeRepository $mstClientTypeRepository
     * @param Request $request
     * @return Response
     */
    public function index(MstClientTypeRepository $mstClientTypeRepository, Request $request): Response
    {
        $client_type = $mstClientTypeRepository->findAll();
        return $this->render('master/mst_client_type/index.html.twig', [
            'mst_client_types' => $client_type,
            'path_index' => 'master_client_type_index',
            'path_add' => 'master_client_type_add',
            'path_edit' => 'master_client_type_edit',
            'path_show' => 'master_client_type_show',
            'label_title' => 'label.client_type',
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
        $mstClientType = new MstClientType();
        $form = $this->createForm(MstClientTypeType::class, $mstClientType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $mstClientType->setRowId(Uuid::uuid4()->toString());
            $entityManager = $this->managerRegistry->getManager();
            $entityManager->persist($mstClientType);
            $entityManager->flush();
            $this->addFlash('success', 'form.created_successfully');
            return $this->redirectToRoute('master_client_type_index');
        }

        return $this->render('form/form.html.twig', [
            'master_client_type' => $mstClientType,
            'form' => $form->createView(),
            'index_path' => 'master_client_type_index',
            'label_title' => 'label.client_type',
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
        $client_type = ucwords($request->query->get('client_typeSearch'));

        $mstClientType = $this->managerRegistry->getRepository(MstClientType::class)->getCityListByCountryId($client_type, $countryId);
        return $this->render('master/mst_client_type/_ajax_listing.html.twig', [
            'mst_cities' => $mstClientType,
            'country_id' => $countryId,
            'path_add' => 'master_client_type_add',
            'path_edit' => 'master_client_type_edit',
            'path_show' => 'master_client_type_show',
            'label_title' => 'label.client_type',
            'mode' => 'add'
        ]);
    }

    /**
     * @Route("/edit/{id}", name="edit", methods={"GET","POST"})
     * @param Request $request
     * @param MstClientType $mstClientType
     * @return Response
     */
    public function edit(Request $request, MstClientType $mstClientType): Response
    {
        $form = $this->createForm(MstClientTypeType::class, $mstClientType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->managerRegistry->getManager()->flush();
            $this->addFlash('success', 'form.updated_successfully');
            return $this->redirectToRoute('master_client_type_index');
        }

        return $this->render('form/form.html.twig', [
            'master_client_type' => $mstClientType,
            'form' => $form->createView(),
            'index_path' => 'master_client_type_index',
            'label_title' => 'label.client_type',
            'label_button' => 'label.update',
            'mode' => 'edit'
        ]);
    }

    /**
     * @Route("/{id}", name="delete", methods={"DELETE"})
     * @param Request $request
     * @param MstClientType $mstClientType
     * @return Response
     */
    public function delete(Request $request, MstClientType $mstClientType): Response
    {
        if ($this->isCsrfTokenValid('delete'.$mstClientType->getId(), $request->request->get('_token'))) {
            $entityManager = $this->managerRegistry->getManager();
            $entityManager->remove($mstClientType);
            $entityManager->flush();
        }

        return $this->redirectToRoute('master_client_type_index');
    }
}

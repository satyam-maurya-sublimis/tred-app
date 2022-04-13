<?php

namespace App\Controller\Master;

use Doctrine\Persistence\ManagerRegistry;
use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Entity\Master\MstRoomConfiguration;
use App\Form\Master\MstRoomConfigurationType;
use App\Repository\Master\MstRoomConfigurationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Ramsey\Uuid\Uuid;

/**
 * @Route("/core/master/tred_project/room_configuration", name="master_room_configuration_")
 * @IsGranted("ROLE_SYS_CONTENT_USER")
 */
class MstRoomConfigurationController extends AbstractController
{
    private ManagerRegistry $managerRegistry;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
    }
    /**
     * @Route("/", name="index", methods={"GET"})
     * @param MstRoomConfigurationRepository $mstRoomConfigurationRepository
     * @param Request $request
     * @return Response
     */
    public function index(MstRoomConfigurationRepository $mstRoomConfigurationRepository, Request $request): Response
    {
        $room_configuration = $mstRoomConfigurationRepository->findAll();
        return $this->render('master/mst_room_configuration/index.html.twig', [
            'room_configurations' => $room_configuration,
            'path_index' => 'master_room_configuration_index',
            'path_add' => 'master_room_configuration_add',
            'path_edit' => 'master_room_configuration_edit',
            'path_show' => 'master_room_configuration_show',
            'label_title' => 'label.room_configuration',
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
        $mstRoomConfiguration = new MstRoomConfiguration();
        $form = $this->createForm(MstRoomConfigurationType::class, $mstRoomConfiguration);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $mstRoomConfiguration->setRowId(Uuid::uuid4()->toString());
            $entityManager = $this->managerRegistry->getManager();
            $entityManager->persist($mstRoomConfiguration);
            $entityManager->flush();
            $this->addFlash('success', 'form.created_successfully');
            return $this->redirectToRoute('master_room_configuration_index');
        }

        return $this->render('form/form.html.twig', [
            'master_room_configuration' => $mstRoomConfiguration,
            'form' => $form->createView(),
            'index_path' => 'master_room_configuration_index',
            'label_title' => 'label.room_configuration',
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
        $room_configuration = ucwords($request->query->get('room_configurationSearch'));

        $mstRoomConfiguration = $this->managerRegistry->getRepository(MstRoomConfiguration::class)->getCityListByCountryId($room_configuration, $countryId);
        return $this->render('master/mst_room_configuration/_ajax_listing.html.twig', [
            'mst_cities' => $mstRoomConfiguration,
            'country_id' => $countryId,
            'path_add' => 'master_room_configuration_add',
            'path_edit' => 'master_room_configuration_edit',
            'path_show' => 'master_room_configuration_show',
            'label_title' => 'label.room_configuration',
            'mode' => 'add'
        ]);
    }

    /**
     * @Route("/edit/{id}", name="edit", methods={"GET","POST"})
     * @param Request $request
     * @param MstRoomConfiguration $mstRoomConfiguration
     * @return Response
     */
    public function edit(Request $request, MstRoomConfiguration $mstRoomConfiguration): Response
    {
        $form = $this->createForm(MstRoomConfigurationType::class, $mstRoomConfiguration);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->managerRegistry->getManager()->flush();
            $this->addFlash('success', 'form.updated_successfully');
            return $this->redirectToRoute('master_room_configuration_index');
        }

        return $this->render('form/form.html.twig', [
            'master_room_configuration' => $mstRoomConfiguration,
            'form' => $form->createView(),
            'index_path' => 'master_room_configuration_index',
            'label_title' => 'label.room_configuration',
            'label_button' => 'label.update',
            'mode' => 'edit'
        ]);
    }

    /**
     * @Route("/{id}", name="delete", methods={"DELETE"})
     * @param Request $request
     * @param MstRoomConfiguration $mstRoomConfiguration
     * @return Response
     */
    public function delete(Request $request, MstRoomConfiguration $mstRoomConfiguration): Response
    {
        if ($this->isCsrfTokenValid('delete'.$mstRoomConfiguration->getId(), $request->request->get('_token'))) {
            $entityManager = $this->managerRegistry->getManager();
            $entityManager->remove($mstRoomConfiguration);
            $entityManager->flush();
        }

        return $this->redirectToRoute('master_room_configuration_index');
    }
}

<?php

namespace App\Controller\Master;

use Doctrine\Persistence\ManagerRegistry;
use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Entity\Master\MstProductServiceMode;
use App\Form\Master\MstProductServiceModeType;
use App\Repository\Master\MstProductServiceModeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Ramsey\Uuid\Uuid;

/**
 * @Route("/core/master/tred_product/product_service_mode", name="master_product_service_mode_")
 * @IsGranted("ROLE_SYS_CONTENT_USER")
 */
class MstProductServiceModeController extends AbstractController
{
    private ManagerRegistry $managerRegistry;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
    }
    /**
     * @Route("/", name="index", methods={"GET"})
     * @param MstProductServiceModeRepository $mstProductServiceModeRepository
     * @param Request $request
     * @return Response
     */
    public function index(MstProductServiceModeRepository $mstProductServiceModeRepository, Request $request): Response
    {
        $product_service_mode = $mstProductServiceModeRepository->findAll();
        return $this->render('master/mst_product_service_mode/index.html.twig', [
            'mst_product_service_modes' => $product_service_mode,
            'path_index' => 'master_product_service_mode_index',
            'path_add' => 'master_product_service_mode_add',
            'path_edit' => 'master_product_service_mode_edit',
            'path_show' => 'master_product_service_mode_show',
            'label_title' => 'label.product_service_mode',
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
        $mstProductServiceMode = new MstProductServiceMode();
        $form = $this->createForm(MstProductServiceModeType::class, $mstProductServiceMode);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $mstProductServiceMode->setRowId(Uuid::uuid4()->toString());
            $entityManager = $this->managerRegistry->getManager();
            $entityManager->persist($mstProductServiceMode);
            $entityManager->flush();
            $this->addFlash('success', 'form.created_successfully');
            return $this->redirectToRoute('master_product_service_mode_index');
        }

        return $this->render('form/form.html.twig', [
            'master_product_service_mode' => $mstProductServiceMode,
            'form' => $form->createView(),
            'index_path' => 'master_product_service_mode_index',
            'label_title' => 'label.product_service_mode',
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
        $product_service_mode = ucwords($request->query->get('product_service_modeSearch'));

        $mstProductServiceMode = $this->managerRegistry->getRepository(MstProductServiceMode::class)->getCityListByCountryId($product_service_mode, $countryId);
        return $this->render('master/mst_product_service_mode/_ajax_listing.html.twig', [
            'mst_cities' => $mstProductServiceMode,
            'country_id' => $countryId,
            'path_add' => 'master_product_service_mode_add',
            'path_edit' => 'master_product_service_mode_edit',
            'path_show' => 'master_product_service_mode_show',
            'label_title' => 'label.product_service_mode',
            'mode' => 'add'
        ]);
    }

    /**
     * @Route("/edit/{id}", name="edit", methods={"GET","POST"})
     * @param Request $request
     * @param MstProductServiceMode $mstProductServiceMode
     * @return Response
     */
    public function edit(Request $request, MstProductServiceMode $mstProductServiceMode): Response
    {
        $form = $this->createForm(MstProductServiceModeType::class, $mstProductServiceMode);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->managerRegistry->getManager()->flush();
            $this->addFlash('success', 'form.updated_successfully');
            return $this->redirectToRoute('master_product_service_mode_index');
        }

        return $this->render('form/form.html.twig', [
            'master_product_service_mode' => $mstProductServiceMode,
            'form' => $form->createView(),
            'index_path' => 'master_product_service_mode_index',
            'label_title' => 'label.product_service_mode',
            'label_button' => 'label.update',
            'mode' => 'edit'
        ]);
    }

    /**
     * @Route("/{id}", name="delete", methods={"DELETE"})
     * @param Request $request
     * @param MstProductServiceMode $mstProductServiceMode
     * @return Response
     */
    public function delete(Request $request, MstProductServiceMode $mstProductServiceMode): Response
    {
        if ($this->isCsrfTokenValid('delete'.$mstProductServiceMode->getId(), $request->request->get('_token'))) {
            $entityManager = $this->managerRegistry->getManager();
            $entityManager->remove($mstProductServiceMode);
            $entityManager->flush();
        }

        return $this->redirectToRoute('master_product_service_mode_index');
    }
}

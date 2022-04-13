<?php

namespace App\Controller\Master;

use Doctrine\Persistence\ManagerRegistry;
use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Entity\Master\MstPaymentMode;
use App\Form\Master\MstPaymentModeType;
use App\Repository\Master\MstPaymentModeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Ramsey\Uuid\Uuid;

/**
 * @Route("/core/master/commercial/payment_mode", name="master_payment_mode_")
 * @IsGranted("ROLE_SYS_CONTENT_USER")
 */
class MstPaymentModeController extends AbstractController
{
    private ManagerRegistry $managerRegistry;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
    }
    /**
     * @Route("/", name="index", methods={"GET"})
     * @param MstPaymentModeRepository $mstPaymentModeRepository
     * @param Request $request
     * @return Response
     */
    public function index(MstPaymentModeRepository $mstPaymentModeRepository, Request $request): Response
    {
        $mstPaymentMode = $mstPaymentModeRepository->findAll();
        return $this->render('master/mst_payment_mode/index.html.twig', [
            'mst_payment_modes' => $mstPaymentMode,
            'path_index' => 'master_payment_mode_index',
            'path_add' => 'master_payment_mode_add',
            'path_edit' => 'master_payment_mode_edit',
            'path_show' => 'master_payment_mode_show',
            'label_title' => 'label.payment_mode',
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
        $mstPaymentMode = new MstPaymentMode();
        $form = $this->createForm(MstPaymentModeType::class, $mstPaymentMode);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $mstPaymentMode->setRowId(Uuid::uuid4()->toString());
            $entityManager = $this->managerRegistry->getManager();
            $entityManager->persist($mstPaymentMode);
            $entityManager->flush();
            $this->addFlash('success', 'form.created_successfully');
            return $this->redirectToRoute('master_payment_mode_index');
        }

        return $this->render('form/form.html.twig', [
            'master_payment_mode' => $mstPaymentMode,
            'form' => $form->createView(),
            'index_path' => 'master_payment_mode_index',
            'label_title' => 'label.payment_mode',
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
        $mstPaymentMode = ucwords($request->query->get('payment_modeSearch'));

        $mstPaymentMode = $this->managerRegistry->getRepository(MstPaymentMode::class)->getCityListByCountryId($mstPaymentMode, $countryId);
        return $this->render('master/mst_payment_mode/_ajax_listing.html.twig', [
            'mst_cities' => $mstPaymentMode,
            'country_id' => $countryId,
            'path_add' => 'master_payment_mode_add',
            'path_edit' => 'master_payment_mode_edit',
            'path_show' => 'master_payment_mode_show',
            'label_title' => 'label.payment_mode',
            'mode' => 'add'
        ]);
    }

    /**
     * @Route("/edit/{id}", name="edit", methods={"GET","POST"})
     * @param Request $request
     * @param MstPaymentMode $mstPaymentMode
     * @return Response
     */
    public function edit(Request $request, MstPaymentMode $mstPaymentMode): Response
    {
        $form = $this->createForm(MstPaymentModeType::class, $mstPaymentMode);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->managerRegistry->getManager()->flush();
            $this->addFlash('success', 'form.updated_successfully');
            return $this->redirectToRoute('master_payment_mode_index');
        }

        return $this->render('form/form.html.twig', [
            'master_payment_mode' => $mstPaymentMode,
            'form' => $form->createView(),
            'index_path' => 'master_payment_mode_index',
            'label_title' => 'label.payment_mode',
            'label_button' => 'label.update',
            'mode' => 'edit'
        ]);
    }

    /**
     * @Route("/{id}", name="delete", methods={"DELETE"})
     * @param Request $request
     * @param MstPaymentMode $mstPaymentMode
     * @return Response
     */
    public function delete(Request $request, MstPaymentMode $mstPaymentMode): Response
    {
        if ($this->isCsrfTokenValid('delete'.$mstPaymentMode->getId(), $request->request->get('_token'))) {
            $entityManager = $this->managerRegistry->getManager();
            $entityManager->remove($mstPaymentMode);
            $entityManager->flush();
        }

        return $this->redirectToRoute('master_payment_mode_index');
    }
}

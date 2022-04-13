<?php

namespace App\Controller\Master;

use Doctrine\Persistence\ManagerRegistry;
use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Entity\Master\MstRequestForPriceOrSetUpPrice;
use App\Form\Master\MstRequestForPriceOrSetUpPriceType;
use App\Repository\Master\MstRequestForPriceOrSetUpPriceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Ramsey\Uuid\Uuid;

/**
 * @Route("/core/master/tred/request_for_price_or_setup_price", name="master_request_for_price_or_setup_price_")
 * @IsGranted("ROLE_SYS_CONTENT_USER")
 */
class MstRequestForPriceOrSetUpPriceController extends AbstractController
{
    private ManagerRegistry $managerRegistry;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
    }
    /**
     * @Route("/", name="index", methods={"GET"})
     * @param MstRequestForPriceOrSetUpPriceRepository $mstRequestForPriceOrSetUpPriceRepository
     * @param Request $request
     * @return Response
     */
    public function index(MstRequestForPriceOrSetUpPriceRepository $mstRequestForPriceOrSetUpPriceRepository, Request $request): Response
    {
        $request_for_price_or_setup_prices = $mstRequestForPriceOrSetUpPriceRepository->findAll();
        return $this->render('master/mst_request_for_price_or_setup_price/index.html.twig', [
            'mst_request_for_price_or_setup_prices' => $request_for_price_or_setup_prices,
            'path_index' => 'master_request_for_price_or_setup_price_index',
            'path_add' => 'master_request_for_price_or_setup_price_add',
            'path_edit' => 'master_request_for_price_or_setup_price_edit',
            'path_show' => 'master_request_for_price_or_setup_price_show',
            'label_title' => 'label.request_for_price_or_setup_price',
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
        $mstRequestForPriceOrSetUpPrice = new MstRequestForPriceOrSetUpPrice();
        $form = $this->createForm(MstRequestForPriceOrSetUpPriceType::class, $mstRequestForPriceOrSetUpPrice);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $mstRequestForPriceOrSetUpPrice->setRowId(Uuid::uuid4()->toString());
            $entityManager = $this->managerRegistry->getManager();
            $entityManager->persist($mstRequestForPriceOrSetUpPrice);
            $entityManager->flush();
            $this->addFlash('success', 'form.created_successfully');
            return $this->redirectToRoute('master_request_for_price_or_setup_price_index');
        }

        return $this->render('form/form.html.twig', [
            'master_request_for_price_or_setup_price' => $mstRequestForPriceOrSetUpPrice,
            'form' => $form->createView(),
            'index_path' => 'master_request_for_price_or_setup_price_index',
            'label_title' => 'label.request_for_price_or_setup_price',
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
        $request_for_price_or_setup_price = ucwords($request->query->get('request_for_price_or_setup_priceSearch'));

        $mstRequestForPriceOrSetUpPrice = $this->managerRegistry->getRepository(MstRequestForPriceOrSetUpPrice::class)->getCityListByCountryId($request_for_price_or_setup_price, $countryId);
        return $this->render('master/mst_request_for_price_or_setup_price/_ajax_listing.html.twig', [
            'mst_cities' => $mstRequestForPriceOrSetUpPrice,
            'country_id' => $countryId,
            'path_add' => 'master_request_for_price_or_setup_price_add',
            'path_edit' => 'master_request_for_price_or_setup_price_edit',
            'path_show' => 'master_request_for_price_or_setup_price_show',
            'label_title' => 'label.request_for_price_or_setup_price',
            'mode' => 'add'
        ]);
    }

    /**
     * @Route("/edit/{id}", name="edit", methods={"GET","POST"})
     * @param Request $request
     * @param MstRequestForPriceOrSetUpPrice $mstRequestForPriceOrSetUpPrice
     * @return Response
     */
    public function edit(Request $request, MstRequestForPriceOrSetUpPrice $mstRequestForPriceOrSetUpPrice): Response
    {
        $form = $this->createForm(MstRequestForPriceOrSetUpPriceType::class, $mstRequestForPriceOrSetUpPrice);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->managerRegistry->getManager()->flush();
            $this->addFlash('success', 'form.updated_successfully');
            return $this->redirectToRoute('master_request_for_price_or_setup_price_index');
        }

        return $this->render('form/form.html.twig', [
            'master_request_for_price_or_setup_price' => $mstRequestForPriceOrSetUpPrice,
            'form' => $form->createView(),
            'index_path' => 'master_request_for_price_or_setup_price_index',
            'label_title' => 'label.request_for_price_or_setup_price',
            'label_button' => 'label.update',
            'mode' => 'edit'
        ]);
    }

    /**
     * @Route("/{id}", name="delete", methods={"DELETE"})
     * @param Request $request
     * @param MstRequestForPriceOrSetUpPrice $mstRequestForPriceOrSetUpPrice
     * @return Response
     */
    public function delete(Request $request, MstRequestForPriceOrSetUpPrice $mstRequestForPriceOrSetUpPrice): Response
    {
        if ($this->isCsrfTokenValid('delete'.$mstRequestForPriceOrSetUpPrice->getId(), $request->request->get('_token'))) {
            $entityManager = $this->managerRegistry->getManager();
            $entityManager->remove($mstRequestForPriceOrSetUpPrice);
            $entityManager->flush();
        }

        return $this->redirectToRoute('master_request_for_price_or_setup_price_index');
    }
}

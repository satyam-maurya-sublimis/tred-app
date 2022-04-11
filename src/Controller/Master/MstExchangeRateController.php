<?php

namespace App\Controller\Master;

use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Entity\Master\MstExchangeRate;
use App\Form\Master\MstExchangeRateType;
use App\Repository\Master\MstExchangeRateRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Ramsey\Uuid\Uuid;

/**
 * @Route("/core/master/commercial/exchange_rate", name="master_exchange_rate_")
 * @IsGranted("ROLE_SYS_CONTENT_USER")
 */
class MstExchangeRateController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET"})
     * @param MstExchangeRateRepository $mstExchangeRateRepository
     * @param Request $request
     * @return Response
     */
    public function index(MstExchangeRateRepository $mstExchangeRateRepository, Request $request): Response
    {
        $mstExchangeRate = $mstExchangeRateRepository->findAll();
        return $this->render('master/mst_exchange_rate/index.html.twig', [
            'mst_exchange_rates' => $mstExchangeRate,
            'path_index' => 'master_exchange_rate_index',
            'path_add' => 'master_exchange_rate_add',
            'path_edit' => 'master_exchange_rate_edit',
            'path_show' => 'master_exchange_rate_show',
            'label_title' => 'label.exchange_rate',
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
        $mstExchangeRate = new MstExchangeRate();
        $form = $this->createForm(MstExchangeRateType::class, $mstExchangeRate);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $mstExchangeRate->setRowId(Uuid::uuid4()->toString());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($mstExchangeRate);
            $entityManager->flush();
            $this->addFlash('success', 'form.created_successfully');
            return $this->redirectToRoute('master_exchange_rate_index');
        }

        return $this->render('form/form.html.twig', [
            'master_exchange_rate' => $mstExchangeRate,
            'form' => $form->createView(),
            'index_path' => 'master_exchange_rate_index',
            'label_title' => 'label.exchange_rate',
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
        $mstExchangeRate = ucwords($request->query->get('exchange_rateSearch'));

        $mstExchangeRate = $this->getDoctrine()->getRepository(MstExchangeRate::class)->getCityListByCountryId($mstExchangeRate, $countryId);
        return $this->render('master/mst_exchange_rate/_ajax_listing.html.twig', [
            'mst_cities' => $mstExchangeRate,
            'country_id' => $countryId,
            'path_add' => 'master_exchange_rate_add',
            'path_edit' => 'master_exchange_rate_edit',
            'path_show' => 'master_exchange_rate_show',
            'label_title' => 'label.exchange_rate',
            'mode' => 'add'
        ]);
    }

    /**
     * @Route("/edit/{id}", name="edit", methods={"GET","POST"})
     * @param Request $request
     * @param MstExchangeRate $mstExchangeRate
     * @return Response
     */
    public function edit(Request $request, MstExchangeRate $mstExchangeRate): Response
    {
        $form = $this->createForm(MstExchangeRateType::class, $mstExchangeRate);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'form.updated_successfully');
            return $this->redirectToRoute('master_exchange_rate_index');
        }

        return $this->render('form/form.html.twig', [
            'master_exchange_rate' => $mstExchangeRate,
            'form' => $form->createView(),
            'index_path' => 'master_exchange_rate_index',
            'label_title' => 'label.exchange_rate',
            'label_button' => 'label.update',
            'mode' => 'edit'
        ]);
    }

    /**
     * @Route("/{id}", name="delete", methods={"DELETE"})
     * @param Request $request
     * @param MstExchangeRate $mstExchangeRate
     * @return Response
     */
    public function delete(Request $request, MstExchangeRate $mstExchangeRate): Response
    {
        if ($this->isCsrfTokenValid('delete'.$mstExchangeRate->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($mstExchangeRate);
            $entityManager->flush();
        }

        return $this->redirectToRoute('master_exchange_rate_index');
    }
}

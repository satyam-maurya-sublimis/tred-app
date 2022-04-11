<?php

namespace App\Controller\Master;

use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Entity\Master\MstDenomination;
use App\Form\Master\MstDenominationType;
use App\Repository\Master\MstDenominationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Ramsey\Uuid\Uuid;

/**
 * @Route("/core/master/general/denomination", name="master_denomination_")
 * @IsGranted("ROLE_SYS_CONTENT_USER")
 */
class MstDenominationController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET"})
     * @param MstDenominationRepository $mstDenominationRepository
     * @param Request $request
     * @return Response
     */
    public function index(MstDenominationRepository $mstDenominationRepository, Request $request): Response
    {
        $denomination = $mstDenominationRepository->findAll();
        return $this->render('master/mst_denomination/index.html.twig', [
            'denominations' => $denomination,
            'path_index' => 'master_denomination_index',
            'path_add' => 'master_denomination_add',
            'path_edit' => 'master_denomination_edit',
            'path_show' => 'master_denomination_show',
            'label_title' => 'label.denomination',
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
        $mstDenomination = new MstDenomination();
        $form = $this->createForm(MstDenominationType::class, $mstDenomination);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $mstDenomination->setRowId(Uuid::uuid4()->toString());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($mstDenomination);
            $entityManager->flush();
            $this->addFlash('success', 'form.created_successfully');
            return $this->redirectToRoute('master_denomination_index');
        }

        return $this->render('form/form.html.twig', [
            'master_denomination' => $mstDenomination,
            'form' => $form->createView(),
            'index_path' => 'master_denomination_index',
            'label_title' => 'label.denomination',
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
        $denomination = ucwords($request->query->get('denominationSearch'));

        $mstDenomination = $this->getDoctrine()->getRepository(MstDenomination::class)->getCityListByCountryId($denomination, $countryId);
        return $this->render('master/mst_denomination/_ajax_listing.html.twig', [
            'mst_cities' => $mstDenomination,
            'country_id' => $countryId,
            'path_add' => 'master_denomination_add',
            'path_edit' => 'master_denomination_edit',
            'path_show' => 'master_denomination_show',
            'label_title' => 'label.denomination',
            'mode' => 'add'
        ]);
    }

    /**
     * @Route("/edit/{id}", name="edit", methods={"GET","POST"})
     * @param Request $request
     * @param MstDenomination $mstDenomination
     * @return Response
     */
    public function edit(Request $request, MstDenomination $mstDenomination): Response
    {
        $form = $this->createForm(MstDenominationType::class, $mstDenomination);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'form.updated_successfully');
            return $this->redirectToRoute('master_denomination_index');
        }

        return $this->render('form/form.html.twig', [
            'master_denomination' => $mstDenomination,
            'form' => $form->createView(),
            'index_path' => 'master_denomination_index',
            'label_title' => 'label.denomination',
            'label_button' => 'label.update',
            'mode' => 'edit'
        ]);
    }

    /**
     * @Route("/{id}", name="delete", methods={"DELETE"})
     * @param Request $request
     * @param MstDenomination $mstDenomination
     * @return Response
     */
    public function delete(Request $request, MstDenomination $mstDenomination): Response
    {
        if ($this->isCsrfTokenValid('delete'.$mstDenomination->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($mstDenomination);
            $entityManager->flush();
        }

        return $this->redirectToRoute('master_denomination_index');
    }
}

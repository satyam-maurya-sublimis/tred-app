<?php

namespace App\Controller\Master;

use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Entity\Master\MstPackingDetails;
use App\Form\Master\MstPackingDetailsType;
use App\Repository\Master\MstPackingDetailsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Ramsey\Uuid\Uuid;

/**
 * @Route("/core/master/tred/packing_details", name="master_packing_details_")
 * @IsGranted("ROLE_SYS_CONTENT_USER")
 */
class MstPackingDetailsController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET"})
     * @param MstPackingDetailsRepository $mstPackingDetailsRepository
     * @param Request $request
     * @return Response
     */
    public function index(MstPackingDetailsRepository $mstPackingDetailsRepository, Request $request): Response
    {
        $mstPackingDetails = $mstPackingDetailsRepository->findAll();
        return $this->render('master/mst_packing_details/index.html.twig', [
            'mst_packing_details' => $mstPackingDetails,
            'path_index' => 'master_packing_details_index',
            'path_add' => 'master_packing_details_add',
            'path_edit' => 'master_packing_details_edit',
            'path_show' => 'master_packing_details_show',
            'label_title' => 'label.packing_details',
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
        $mstPackingDetails = new MstPackingDetails();
        $form = $this->createForm(MstPackingDetailsType::class, $mstPackingDetails);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $mstPackingDetails->setRowId(Uuid::uuid4()->toString());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($mstPackingDetails);
            $entityManager->flush();
            $this->addFlash('success', 'form.created_successfully');
            return $this->redirectToRoute('master_packing_details_index');
        }

        return $this->render('form/form.html.twig', [
            'master_packing_details' => $mstPackingDetails,
            'form' => $form->createView(),
            'index_path' => 'master_packing_details_index',
            'label_title' => 'label.packing_details',
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
        $mstPackingDetails = ucwords($request->query->get('packing_detailsSearch'));

        $mstPackingDetails = $this->getDoctrine()->getRepository(MstPackingDetails::class)->getCityListByCountryId($mstPackingDetails, $countryId);
        return $this->render('master/mst_packing_details/_ajax_listing.html.twig', [
            'mst_cities' => $mstPackingDetails,
            'country_id' => $countryId,
            'path_add' => 'master_packing_details_add',
            'path_edit' => 'master_packing_details_edit',
            'path_show' => 'master_packing_details_show',
            'label_title' => 'label.packing_details',
            'mode' => 'add'
        ]);
    }

    /**
     * @Route("/edit/{id}", name="edit", methods={"GET","POST"})
     * @param Request $request
     * @param MstPackingDetails $mstPackingDetails
     * @return Response
     */
    public function edit(Request $request, MstPackingDetails $mstPackingDetails): Response
    {
        $form = $this->createForm(MstPackingDetailsType::class, $mstPackingDetails);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'form.updated_successfully');
            return $this->redirectToRoute('master_packing_details_index');
        }

        return $this->render('form/form.html.twig', [
            'master_packing_details' => $mstPackingDetails,
            'form' => $form->createView(),
            'index_path' => 'master_packing_details_index',
            'label_title' => 'label.packing_details',
            'label_button' => 'label.update',
            'mode' => 'edit'
        ]);
    }

    /**
     * @Route("/{id}", name="delete", methods={"DELETE"})
     * @param Request $request
     * @param MstPackingDetails $mstPackingDetails
     * @return Response
     */
    public function delete(Request $request, MstPackingDetails $mstPackingDetails): Response
    {
        if ($this->isCsrfTokenValid('delete'.$mstPackingDetails->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($mstPackingDetails);
            $entityManager->flush();
        }

        return $this->redirectToRoute('master_packing_details_index');
    }
}

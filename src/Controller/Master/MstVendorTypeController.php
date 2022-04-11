<?php

namespace App\Controller\Master;

use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Entity\Master\MstVendorType;
use App\Form\Master\MstVendorTypeType;
use App\Repository\Master\MstVendorTypeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Ramsey\Uuid\Uuid;

/**
 * @Route("/core/master/tred/vendor_type", name="master_vendor_type_")
 * @IsGranted("ROLE_SYS_CONTENT_USER")
 */
class MstVendorTypeController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET"})
     * @param MstVendorTypeRepository $mstVendorTypeRepository
     * @param Request $request
     * @return Response
     */
    public function index(MstVendorTypeRepository $mstVendorTypeRepository, Request $request): Response
    {
        $vendor_types = $mstVendorTypeRepository->findAll();
        return $this->render('master/mst_vendor_type/index.html.twig', [
            'mst_vendor_types' => $vendor_types,
            'path_index' => 'master_vendor_type_index',
            'path_add' => 'master_vendor_type_add',
            'path_edit' => 'master_vendor_type_edit',
            'path_show' => 'master_vendor_type_show',
            'label_title' => 'label.vendor_type',
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
        $mstVendorType = new MstVendorType();
        $form = $this->createForm(MstVendorTypeType::class, $mstVendorType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $mstVendorType->setRowId(Uuid::uuid4()->toString());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($mstVendorType);
            $entityManager->flush();
            $this->addFlash('success', 'form.created_successfully');
            return $this->redirectToRoute('master_vendor_type_index');
        }

        return $this->render('form/form.html.twig', [
            'master_vendor_type' => $mstVendorType,
            'form' => $form->createView(),
            'index_path' => 'master_vendor_type_index',
            'label_title' => 'label.vendor_type',
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
        $vendor_type = ucwords($request->query->get('vendor_typeSearch'));

        $mstVendorType = $this->getDoctrine()->getRepository(MstVendorType::class)->getCityListByCountryId($vendor_type, $countryId);
        return $this->render('master/mst_vendor_type/_ajax_listing.html.twig', [
            'mst_cities' => $mstVendorType,
            'country_id' => $countryId,
            'path_add' => 'master_vendor_type_add',
            'path_edit' => 'master_vendor_type_edit',
            'path_show' => 'master_vendor_type_show',
            'label_title' => 'label.vendor_type',
            'mode' => 'add'
        ]);
    }

    /**
     * @Route("/edit/{id}", name="edit", methods={"GET","POST"})
     * @param Request $request
     * @param MstVendorType $mstVendorType
     * @return Response
     */
    public function edit(Request $request, MstVendorType $mstVendorType): Response
    {
        $form = $this->createForm(MstVendorTypeType::class, $mstVendorType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'form.updated_successfully');
            return $this->redirectToRoute('master_vendor_type_index');
        }

        return $this->render('form/form.html.twig', [
            'master_vendor_type' => $mstVendorType,
            'form' => $form->createView(),
            'index_path' => 'master_vendor_type_index',
            'label_title' => 'label.vendor_type',
            'label_button' => 'label.update',
            'mode' => 'edit'
        ]);
    }

    /**
     * @Route("/{id}", name="delete", methods={"DELETE"})
     * @param Request $request
     * @param MstVendorType $mstVendorType
     * @return Response
     */
    public function delete(Request $request, MstVendorType $mstVendorType): Response
    {
        if ($this->isCsrfTokenValid('delete'.$mstVendorType->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($mstVendorType);
            $entityManager->flush();
        }

        return $this->redirectToRoute('master_vendor_type_index');
    }
}

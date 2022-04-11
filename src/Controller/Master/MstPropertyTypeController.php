<?php

namespace App\Controller\Master;

use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Entity\Master\MstPropertyType;
use App\Form\Master\MstPropertyTypeType;
use App\Repository\Master\MstPropertyTypeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Ramsey\Uuid\Uuid;

/**
 * @Route("/core/master/tred/property_type", name="master_property_type_")
 * @IsGranted("ROLE_SYS_CONTENT_USER")
 */
class MstPropertyTypeController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET"})
     * @param MstPropertyTypeRepository $mstPropertyTypeRepository
     * @param Request $request
     * @return Response
     */
    public function index(MstPropertyTypeRepository $mstPropertyTypeRepository, Request $request): Response
    {
        $property_type = $mstPropertyTypeRepository->findAll();
        return $this->render('master/mst_property_type/index.html.twig', [
            'mst_property_types' => $property_type,
            'path_index' => 'master_property_type_index',
            'path_add' => 'master_property_type_add',
            'path_edit' => 'master_property_type_edit',
            'path_show' => 'master_property_type_show',
            'label_title' => 'label.property_type',
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
        $mstPropertyType = new MstPropertyType();
        $form = $this->createForm(MstPropertyTypeType::class, $mstPropertyType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $mstPropertyType->setRowId(Uuid::uuid4()->toString());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($mstPropertyType);
            $entityManager->flush();
            $this->addFlash('success', 'form.created_successfully');
            return $this->redirectToRoute('master_property_type_index');
        }

        return $this->render('form/form.html.twig', [
            'master_property_type' => $mstPropertyType,
            'form' => $form->createView(),
            'index_path' => 'master_property_type_index',
            'label_title' => 'label.property_type',
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
        $property_type = ucwords($request->query->get('property_typeSearch'));

        $mstPropertyType = $this->getDoctrine()->getRepository(MstPropertyType::class)->getCityListByCountryId($property_type, $countryId);
        return $this->render('master/mst_property_type/_ajax_listing.html.twig', [
            'mst_cities' => $mstPropertyType,
            'country_id' => $countryId,
            'path_add' => 'master_property_type_add',
            'path_edit' => 'master_property_type_edit',
            'path_show' => 'master_property_type_show',
            'label_title' => 'label.property_type',
            'mode' => 'add'
        ]);
    }

    /**
     * @Route("/edit/{id}", name="edit", methods={"GET","POST"})
     * @param Request $request
     * @param MstPropertyType $mstPropertyType
     * @return Response
     */
    public function edit(Request $request, MstPropertyType $mstPropertyType): Response
    {
        $form = $this->createForm(MstPropertyTypeType::class, $mstPropertyType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'form.updated_successfully');
            return $this->redirectToRoute('master_property_type_index');
        }

        return $this->render('form/form.html.twig', [
            'master_property_type' => $mstPropertyType,
            'form' => $form->createView(),
            'index_path' => 'master_property_type_index',
            'label_title' => 'label.property_type',
            'label_button' => 'label.update',
            'mode' => 'edit'
        ]);
    }

    /**
     * @Route("/{id}", name="delete", methods={"DELETE"})
     * @param Request $request
     * @param MstPropertyType $mstPropertyType
     * @return Response
     */
    public function delete(Request $request, MstPropertyType $mstPropertyType): Response
    {
        if ($this->isCsrfTokenValid('delete'.$mstPropertyType->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($mstPropertyType);
            $entityManager->flush();
        }

        return $this->redirectToRoute('master_property_type_index');
    }
}

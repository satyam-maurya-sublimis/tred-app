<?php

namespace App\Controller\Master;

use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Entity\Master\MstPropertyIn;
use App\Form\Master\MstPropertyInType;
use App\Repository\Master\MstPropertyInRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Ramsey\Uuid\Uuid;

/**
 * @Route("/core/master/tred_project/properties_in", name="master_properties_in_")
 * @IsGranted("ROLE_SYS_CONTENT_USER")
 */
class MstPropertyInController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET"})
     * @param MstPropertyInRepository $mstPropertyInRepository
     * @param Request $request
     * @return Response
     */
    public function index(MstPropertyInRepository $mstPropertyInRepository, Request $request): Response
    {
        $properties_in = $mstPropertyInRepository->findAll();
        return $this->render('master/mst_property_in/index.html.twig', [
            'mst_properties_in' => $properties_in,
            'path_index' => 'master_properties_in_index',
            'path_add' => 'master_properties_in_add',
            'path_edit' => 'master_properties_in_edit',
            'path_show' => 'master_properties_in_show',
            'label_title' => 'label.property_in',
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
        $mstPropertyIn = new MstPropertyIn();
        $form = $this->createForm(MstPropertyInType::class, $mstPropertyIn);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $mstPropertyIn->setRowId(Uuid::uuid4()->toString());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($mstPropertyIn);
            $entityManager->flush();
            $this->addFlash('success', 'form.created_successfully');
            return $this->redirectToRoute('master_properties_in_index');
        }

        return $this->render('form/form.html.twig', [
            'master_properties_in' => $mstPropertyIn,
            'form' => $form->createView(),
            'index_path' => 'master_properties_in_index',
            'label_title' => 'label.property_in',
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
        $property_in = ucwords($request->query->get('property_inSearch'));

        $mstPropertyIn = $this->getDoctrine()->getRepository(MstPropertyIn::class)->getCityListByCountryId($property_in, $countryId);
        return $this->render('master/mst_property_in/_ajax_listing.html.twig', [
            'mst_cities' => $mstPropertyIn,
            'country_id' => $countryId,
            'path_add' => 'master_properties_in_add',
            'path_edit' => 'master_properties_in_edit',
            'path_show' => 'master_properties_in_show',
            'label_title' => 'label.properties_in',
            'mode' => 'add'
        ]);
    }

    /**
     * @Route("/edit/{id}", name="edit", methods={"GET","POST"})
     * @param Request $request
     * @param MstPropertyIn $mstPropertyIn
     * @return Response
     */
    public function edit(Request $request, MstPropertyIn $mstPropertyIn): Response
    {
        $form = $this->createForm(MstPropertyInType::class, $mstPropertyIn);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'form.updated_successfully');
            return $this->redirectToRoute('master_properties_in_index');
        }

        return $this->render('form/form.html.twig', [
            'master_property_in' => $mstPropertyIn,
            'form' => $form->createView(),
            'index_path' => 'master_properties_in_index',
            'label_title' => 'label.property_in',
            'label_button' => 'label.update',
            'mode' => 'edit'
        ]);
    }

    /**
     * @Route("/{id}", name="delete", methods={"DELETE"})
     * @param Request $request
     * @param MstPropertyIn $mstPropertyIn
     * @return Response
     */
    public function delete(Request $request, MstPropertyIn $mstPropertyIn): Response
    {
        if ($this->isCsrfTokenValid('delete'.$mstPropertyIn->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($mstPropertyIn);
            $entityManager->flush();
        }

        return $this->redirectToRoute('master_properties_in_index');
    }
}

<?php

namespace App\Controller\Master;

use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Entity\Master\MstDeviceType;
use App\Form\Master\MstDeviceTypeType;
use App\Repository\Master\MstDeviceTypeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Ramsey\Uuid\Uuid;

/**
 * @Route("/core/master/general/device_type", name="master_device_type_")
 * @IsGranted("ROLE_SYS_CONTENT_USER")
 */
class MstDeviceTypeController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET"})
     * @param MstDeviceTypeRepository $mstDeviceTypeRepository
     * @param Request $request
     * @return Response
     */
    public function index(MstDeviceTypeRepository $mstDeviceTypeRepository, Request $request): Response
    {
        $device_type = $mstDeviceTypeRepository->findAll();
        return $this->render('master/mst_device_type/index.html.twig', [
            'device_types' => $device_type,
            'path_index' => 'master_device_type_index',
            'path_add' => 'master_device_type_add',
            'path_edit' => 'master_device_type_edit',
            'path_show' => 'master_device_type_show',
            'label_title' => 'label.device_type',
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
        $mstDeviceType = new MstDeviceType();
        $form = $this->createForm(MstDeviceTypeType::class, $mstDeviceType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $mstDeviceType->setRowId(Uuid::uuid4()->toString());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($mstDeviceType);
            $entityManager->flush();
            $this->addFlash('success', 'form.created_successfully');
            return $this->redirectToRoute('master_device_type_index');
        }

        return $this->render('form/form.html.twig', [
            'master_device_type' => $mstDeviceType,
            'form' => $form->createView(),
            'index_path' => 'master_device_type_index',
            'label_title' => 'label.device_type',
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
        $device_type = ucwords($request->query->get('device_typeSearch'));

        $mstDeviceType = $this->getDoctrine()->getRepository(MstDeviceType::class)->getCityListByCountryId($device_type, $countryId);
        return $this->render('master/mst_device_type/_ajax_listing.html.twig', [
            'mst_cities' => $mstDeviceType,
            'country_id' => $countryId,
            'path_add' => 'master_device_type_add',
            'path_edit' => 'master_device_type_edit',
            'path_show' => 'master_device_type_show',
            'label_title' => 'label.device_type',
            'mode' => 'add'
        ]);
    }

    /**
     * @Route("/edit/{id}", name="edit", methods={"GET","POST"})
     * @param Request $request
     * @param MstDeviceType $mstDeviceType
     * @return Response
     */
    public function edit(Request $request, MstDeviceType $mstDeviceType): Response
    {
        $form = $this->createForm(MstDeviceTypeType::class, $mstDeviceType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'form.updated_successfully');
            return $this->redirectToRoute('master_device_type_index');
        }

        return $this->render('form/form.html.twig', [
            'master_device_type' => $mstDeviceType,
            'form' => $form->createView(),
            'index_path' => 'master_device_type_index',
            'label_title' => 'label.device_type',
            'label_button' => 'label.update',
            'mode' => 'edit'
        ]);
    }

    /**
     * @Route("/{id}", name="delete", methods={"DELETE"})
     * @param Request $request
     * @param MstDeviceType $mstDeviceType
     * @return Response
     */
    public function delete(Request $request, MstDeviceType $mstDeviceType): Response
    {
        if ($this->isCsrfTokenValid('delete'.$mstDeviceType->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($mstDeviceType);
            $entityManager->flush();
        }

        return $this->redirectToRoute('master_device_type_index');
    }
}

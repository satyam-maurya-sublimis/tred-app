<?php

namespace App\Controller\Master;

use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Entity\Master\MstBrand;
use App\Form\Master\MstBrandType;
use App\Repository\Master\MstBrandRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Ramsey\Uuid\Uuid;

/**
 * @Route("/core/master/tred/brand", name="master_brand_")
 * @IsGranted("ROLE_SYS_CONTENT_USER")
 */
class MstBrandController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET"})
     * @param MstBrandRepository $brandRepository
     * @param Request $request
     * @return Response
     */
    public function index(MstBrandRepository $brandRepository, Request $request): Response
    {
        $brand = $brandRepository->findAll();
        return $this->render('master/mst_brand/index.html.twig', [
            'mst_brands' => $brand,
            'path_index' => 'master_brand_index',
            'path_add' => 'master_brand_add',
            'path_edit' => 'master_brand_edit',
            'path_show' => 'master_brand_show',
            'label_title' => 'label.brand',
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
        $brand = new MstBrand();
        $form = $this->createForm(MstBrandType::class, $brand);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $brand->setRowId(Uuid::uuid4()->toString());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($brand);
            $entityManager->flush();
            $this->addFlash('success', 'form.created_successfully');
            return $this->redirectToRoute('master_brand_index');
        }

        return $this->render('form/form.html.twig', [
            'master_brand' => $brand,
            'form' => $form->createView(),
            'index_path' => 'master_brand_index',
            'label_title' => 'label.brand',
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
        $brand = ucwords($request->query->get('brandSearch'));

        $brand = $this->getDoctrine()->getRepository(MstBrand::class)->getCityListByCountryId($brand, $countryId);
        return $this->render('master/mst_brand/_ajax_listing.html.twig', [
            'mst_cities' => $brand,
            'country_id' => $countryId,
            'path_add' => 'master_brand_add',
            'path_edit' => 'master_brand_edit',
            'path_show' => 'master_brand_show',
            'label_title' => 'label.brand',
            'mode' => 'add'
        ]);
    }

    /**
     * @Route("/edit/{id}", name="edit", methods={"GET","POST"})
     * @param Request $request
     * @param MstBrand $brand
     * @return Response
     */
    public function edit(Request $request, MstBrand $brand): Response
    {
        $form = $this->createForm(MstBrandType::class, $brand);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'form.updated_successfully');
            return $this->redirectToRoute('master_brand_index');
        }

        return $this->render('form/form.html.twig', [
            'master_brand' => $brand,
            'form' => $form->createView(),
            'index_path' => 'master_brand_index',
            'label_title' => 'label.brand',
            'label_button' => 'label.update',
            'mode' => 'edit'
        ]);
    }

    /**
     * @Route("/{id}", name="delete", methods={"DELETE"})
     * @param Request $request
     * @param MstBrand $brand
     * @return Response
     */
    public function delete(Request $request, MstBrand $brand): Response
    {
        if ($this->isCsrfTokenValid('delete'.$brand->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($brand);
            $entityManager->flush();
        }

        return $this->redirectToRoute('master_brand_index');
    }
}

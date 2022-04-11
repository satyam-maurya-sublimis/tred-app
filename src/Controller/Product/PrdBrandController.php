<?php

namespace App\Controller\Product;

use App\Entity\Product\PrdBrand;
use App\Form\Product\PrdBrandType;
use App\Repository\Product\PrdBrandRepository;
use Exception;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Ramsey\Uuid\Uuid;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/core/master/general/brand" , name="product_brand_")
 * @IsGranted("ROLE_SYS_CONTENT_USER")
 */
class PrdBrandController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET"})
     * @param PrdBrandRepository $prdBrandRepository
     * @return Response
     */
    public function index(PrdBrandRepository $prdBrandRepository): Response
    {
        return $this->render('product/prd_brand/index.html.twig', [
            'prd_brands' => $prdBrandRepository->findAll(),
            'path_index' => 'product_brand_index',
            'path_add' => 'product_brand_add',
            'path_edit' => 'product_brand_edit',
            'path_show' => 'product_brand_show',
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
        $PrdBrand = new PrdBrand();
        $form = $this->createForm(PrdBrandType::class, $PrdBrand);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $PrdBrand->setRowId(Uuid::uuid4()->toString());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($PrdBrand);
            $entityManager->flush();
            $this->addFlash('success', 'form.created_successfully');
            return $this->redirectToRoute('product_brand_index');
        }

        return $this->render('form/form.html.twig', [
            'PrdBrand' => $PrdBrand,
            'form' => $form->createView(),
            'index_path' => 'product_brand_index',
            'label_title' => 'label.brand',
            'label_button' => 'label.create',
            'mode' => 'add'
        ]);
    }

    /**
     * @Route("/{id}/edit", name="edit", methods={"GET","POST"})
     * @param Request $request
     * @param PrdBrand $PrdBrand
     * @return Response
     */
    public function edit(Request $request, PrdBrand $PrdBrand): Response
    {
        $form = $this->createForm(PrdBrandType::class, $PrdBrand);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'form.updated_successfully');
            return $this->redirectToRoute('product_brand_index');
        }

        return $this->render('form/form.html.twig', [
            'PrdBrand' => $PrdBrand,
            'form' => $form->createView(),
            'index_path' => 'product_brand_index',
            'label_title' => 'label.brand',
            'label_button' => 'label.update',
            'mode' => 'edit'
        ]);
    }

    /**
     * @Route("/{id}", name="show", methods={"GET","POST"})
     * @param PrdBrand $PrdBrand
     * @return Response
     */
    public function show(PrdBrand $PrdBrand): Response
    {
        return $this->render('product/prd_brand/show.html.twig', [
            'data' => $PrdBrand,
            'label_title' => 'label.brand',
            'label_button' => 'label.update',
            'label_log' => 'label.log',
            'path_index' => 'product_brand_index',
            'path_edit' => 'product_brand_edit',
        ]);
    }
}

<?php

namespace App\Controller\Master;

use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Entity\Master\MstProductUse;
use App\Form\Master\MstProductUseType;
use App\Repository\Master\MstProductUseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Ramsey\Uuid\Uuid;

/**
 * @Route("/core/master/tred_product/product_use", name="master_product_use_")
 * @IsGranted("ROLE_SYS_CONTENT_USER")
 */
class MstProductUseController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET"})
     * @param MstProductUseRepository $mstProductUseRepository
     * @param Request $request
     * @return Response
     */
    public function index(MstProductUseRepository $mstProductUseRepository, Request $request): Response
    {
        $product_use = $mstProductUseRepository->findAll();
        return $this->render('master/mst_product_use/index.html.twig', [
            'mst_product_uses' => $product_use,
            'path_index' => 'master_product_use_index',
            'path_add' => 'master_product_use_add',
            'path_edit' => 'master_product_use_edit',
            'path_show' => 'master_product_use_show',
            'label_title' => 'label.product_use',
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
        $mstProductUse = new MstProductUse();
        $form = $this->createForm(MstProductUseType::class, $mstProductUse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $mstProductUse->setRowId(Uuid::uuid4()->toString());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($mstProductUse);
            $entityManager->flush();
            $this->addFlash('success', 'form.created_successfully');
            return $this->redirectToRoute('master_product_use_index');
        }

        return $this->render('form/form.html.twig', [
            'master_product_use' => $mstProductUse,
            'form' => $form->createView(),
            'index_path' => 'master_product_use_index',
            'label_title' => 'label.product_use',
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
        $product_use = ucwords($request->query->get('product_useSearch'));

        $mstProductUse = $this->getDoctrine()->getRepository(MstProductUse::class)->getCityListByCountryId($product_use, $countryId);
        return $this->render('master/mst_product_use/_ajax_listing.html.twig', [
            'mst_cities' => $mstProductUse,
            'country_id' => $countryId,
            'path_add' => 'master_product_use_add',
            'path_edit' => 'master_product_use_edit',
            'path_show' => 'master_product_use_show',
            'label_title' => 'label.product_use',
            'mode' => 'add'
        ]);
    }

    /**
     * @Route("/edit/{id}", name="edit", methods={"GET","POST"})
     * @param Request $request
     * @param MstProductUse $mstProductUse
     * @return Response
     */
    public function edit(Request $request, MstProductUse $mstProductUse): Response
    {
        $form = $this->createForm(MstProductUseType::class, $mstProductUse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'form.updated_successfully');
            return $this->redirectToRoute('master_product_use_index');
        }

        return $this->render('form/form.html.twig', [
            'master_product_use' => $mstProductUse,
            'form' => $form->createView(),
            'index_path' => 'master_product_use_index',
            'label_title' => 'label.product_use',
            'label_button' => 'label.update',
            'mode' => 'edit'
        ]);
    }

    /**
     * @Route("/{id}", name="delete", methods={"DELETE"})
     * @param Request $request
     * @param MstProductUse $mstProductUse
     * @return Response
     */
    public function delete(Request $request, MstProductUse $mstProductUse): Response
    {
        if ($this->isCsrfTokenValid('delete'.$mstProductUse->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($mstProductUse);
            $entityManager->flush();
        }

        return $this->redirectToRoute('master_product_use_index');
    }
}

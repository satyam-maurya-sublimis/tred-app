<?php

namespace App\Controller\Master;

use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Entity\Master\MstConsumptionCategory;
use App\Form\Master\MstConsumptionCategoryType;
use App\Repository\Master\MstConsumptionCategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Ramsey\Uuid\Uuid;

/**
 * @Route("/core/master/tred/consumption_category", name="master_consumption_category_")
 * @IsGranted("ROLE_SYS_CONTENT_USER")
 */
class MstConsumptionCategoryController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET"})
     * @param MstConsumptionCategoryRepository $mstConsumptionCategoryRepository
     * @param Request $request
     * @return Response
     */
    public function index(MstConsumptionCategoryRepository $mstConsumptionCategoryRepository, Request $request): Response
    {
        $consumption_category = $mstConsumptionCategoryRepository->findAll();
        return $this->render('master/mst_consumption_category/index.html.twig', [
            'mst_consumption_categories' => $consumption_category,
            'path_index' => 'master_consumption_category_index',
            'path_add' => 'master_consumption_category_add',
            'path_edit' => 'master_consumption_category_edit',
            'path_show' => 'master_consumption_category_show',
            'label_title' => 'label.consumption_category',
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
        $mstConsumptionCategory = new MstConsumptionCategory();
        $form = $this->createForm(MstConsumptionCategoryType::class, $mstConsumptionCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $mstConsumptionCategory->setRowId(Uuid::uuid4()->toString());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($mstConsumptionCategory);
            $entityManager->flush();
            $this->addFlash('success', 'form.created_successfully');
            return $this->redirectToRoute('master_consumption_category_index');
        }

        return $this->render('form/form.html.twig', [
            'master_consumption_category' => $mstConsumptionCategory,
            'form' => $form->createView(),
            'index_path' => 'master_consumption_category_index',
            'label_title' => 'label.consumption_category',
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
        $consumption_category = ucwords($request->query->get('consumption_categorySearch'));

        $mstConsumptionCategory = $this->getDoctrine()->getRepository(MstConsumptionCategory::class)->getCityListByCountryId($consumption_category, $countryId);
        return $this->render('master/mst_consumption_category/_ajax_listing.html.twig', [
            'mst_cities' => $mstConsumptionCategory,
            'country_id' => $countryId,
            'path_add' => 'master_consumption_category_add',
            'path_edit' => 'master_consumption_category_edit',
            'path_show' => 'master_consumption_category_show',
            'label_title' => 'label.consumption_category',
            'mode' => 'add'
        ]);
    }

    /**
     * @Route("/edit/{id}", name="edit", methods={"GET","POST"})
     * @param Request $request
     * @param MstConsumptionCategory $mstConsumptionCategory
     * @return Response
     */
    public function edit(Request $request, MstConsumptionCategory $mstConsumptionCategory): Response
    {
        $form = $this->createForm(MstConsumptionCategoryType::class, $mstConsumptionCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'form.updated_successfully');
            return $this->redirectToRoute('master_consumption_category_index');
        }

        return $this->render('form/form.html.twig', [
            'master_consumption_category' => $mstConsumptionCategory,
            'form' => $form->createView(),
            'index_path' => 'master_consumption_category_index',
            'label_title' => 'label.consumption_category',
            'label_button' => 'label.update',
            'mode' => 'edit'
        ]);
    }

    /**
     * @Route("/{id}", name="delete", methods={"DELETE"})
     * @param Request $request
     * @param MstConsumptionCategory $mstConsumptionCategory
     * @return Response
     */
    public function delete(Request $request, MstConsumptionCategory $mstConsumptionCategory): Response
    {
        if ($this->isCsrfTokenValid('delete'.$mstConsumptionCategory->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($mstConsumptionCategory);
            $entityManager->flush();
        }

        return $this->redirectToRoute('master_consumption_category_index');
    }
}

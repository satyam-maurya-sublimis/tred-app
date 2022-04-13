<?php

namespace App\Controller\Master;

use Doctrine\Persistence\ManagerRegistry;
use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Entity\Master\MstSubscriptionCategory;
use App\Form\Master\MstSubscriptionCategoryType;
use App\Repository\Master\MstSubscriptionCategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Ramsey\Uuid\Uuid;

/**
 * @Route("/core/master/general/subscription-type", name="master_subscription_type_")
 * @IsGranted("ROLE_SYS_CONTENT_USER")
 */
class MstSubscriptionCategoryController extends AbstractController
{
    private ManagerRegistry $managerRegistry;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
    }
    /**
     * @Route("/", name="index", methods={"GET"})
     * @param MstSubscriptionCategoryRepository $mstSubscriptionCategoryRepository
     * @param Request $request
     * @return Response
     */
    public function index(MstSubscriptionCategoryRepository $mstSubscriptionCategoryRepository, Request $request): Response
    {
        $subscription_type = $mstSubscriptionCategoryRepository->findAll();
        return $this->render('master/mst_subscription_category/index.html.twig', [
            'mst_subscription_types' => $subscription_type,
            'path_index' => 'master_subscription_type_index',
            'path_add' => 'master_subscription_type_add',
            'path_edit' => 'master_subscription_type_edit',
            'path_show' => 'master_subscription_type_show',
            'label_title' => 'label.subscription_type',
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
        $mstSubscriptionCategory = new MstSubscriptionCategory();
        $form = $this->createForm(MstSubscriptionCategoryType::class, $mstSubscriptionCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $mstSubscriptionCategory->setRowId(Uuid::uuid4()->toString());
            $entityManager = $this->managerRegistry->getManager();
            $entityManager->persist($mstSubscriptionCategory);
            $entityManager->flush();
            $this->addFlash('success', 'form.created_successfully');
            return $this->redirectToRoute('master_subscription_type_index');
        }

        return $this->render('form/form.html.twig', [
            'master_subscription_type' => $mstSubscriptionCategory,
            'form' => $form->createView(),
            'index_path' => 'master_subscription_type_index',
            'label_title' => 'label.subscription_type',
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
        $subscription_type = ucwords($request->query->get('subscription_typeSearch'));

        $mstSubscriptionCategory = $this->managerRegistry->getRepository(MstSubscriptionCategory::class)->getCityListByCountryId($subscription_type, $countryId);
        return $this->render('master/mst_subscription_type/_ajax_listing.html.twig', [
            'mst_cities' => $mstSubscriptionCategory,
            'country_id' => $countryId,
            'path_add' => 'master_subscription_type_add',
            'path_edit' => 'master_subscription_type_edit',
            'path_show' => 'master_subscription_type_show',
            'label_title' => 'label.subscription_type',
            'mode' => 'add'
        ]);
    }

    /**
     * @Route("/edit/{id}", name="edit", methods={"GET","POST"})
     * @param Request $request
     * @param MstSubscriptionCategory $mstSubscriptionCategory
     * @return Response
     */
    public function edit(Request $request, MstSubscriptionCategory $mstSubscriptionCategory): Response
    {
        $form = $this->createForm(MstSubscriptionCategoryType::class, $mstSubscriptionCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->managerRegistry->getManager()->flush();
            $this->addFlash('success', 'form.updated_successfully');
            return $this->redirectToRoute('master_subscription_type_index');
        }

        return $this->render('form/form.html.twig', [
            'master_subscription_type' => $mstSubscriptionCategory,
            'form' => $form->createView(),
            'index_path' => 'master_subscription_type_index',
            'label_title' => 'label.subscription_type',
            'label_button' => 'label.update',
            'mode' => 'edit'
        ]);
    }

    /**
     * @Route("/{id}", name="delete", methods={"DELETE"})
     * @param Request $request
     * @param MstSubscriptionCategory $mstSubscriptionCategory
     * @return Response
     */
    public function delete(Request $request, MstSubscriptionCategory $mstSubscriptionCategory): Response
    {
        if ($this->isCsrfTokenValid('delete'.$mstSubscriptionCategory->getId(), $request->request->get('_token'))) {
            $entityManager = $this->managerRegistry->getManager();
            $entityManager->remove($mstSubscriptionCategory);
            $entityManager->flush();
        }

        return $this->redirectToRoute('master_subscription_type_index');
    }
}

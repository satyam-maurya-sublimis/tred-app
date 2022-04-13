<?php

namespace App\Controller\Master;

use Doctrine\Persistence\ManagerRegistry;
use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Entity\Master\MstAreaInCity;
use App\Form\Master\MstAreaInCityType;
use App\Repository\Master\MstAreaInCityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Ramsey\Uuid\Uuid;

/**
 * @Route("/core/master/place/area_city", name="master_area_city_")
 * @IsGranted("ROLE_SYS_CONTENT_USER")
 */
class MstAreasInCityController extends AbstractController
{
    private ManagerRegistry $managerRegistry;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
    }
    /**
     * @Route("/", name="index", methods={"GET"})
     * @param MstAreaInCityRepository $mstAreaInCityRepository
     * @param Request $request
     * @return Response
     */
    public function index(MstAreaInCityRepository $mstAreaInCityRepository, Request $request): Response
    {
        $area_city = $mstAreaInCityRepository->findAll();
        return $this->render('master/mst_area_city/index.html.twig', [
            'mst_area_cities' => $area_city,
            'path_index' => 'master_area_city_index',
            'path_add' => 'master_area_city_add',
            'path_edit' => 'master_area_city_edit',
            'path_show' => 'master_area_city_show',
            'label_title' => 'label.area_city',
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
        $mstAreaInCity = new MstAreaInCity();
        $form = $this->createForm(MstAreaInCityType::class, $mstAreaInCity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $mstAreaInCity->setRowId(Uuid::uuid4()->toString());
            $entityManager = $this->managerRegistry->getManager();
            $entityManager->persist($mstAreaInCity);
            $entityManager->flush();
            $this->addFlash('success', 'form.created_successfully');
            return $this->redirectToRoute('master_area_city_index');
        }

        return $this->render('master/mst_area_city/form.html.twig', [
            'master_area_city' => $mstAreaInCity,
            'form' => $form->createView(),
            'index_path' => 'master_area_city_index',
            'label_title' => 'label.area_city',
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
        $area_city = ucwords($request->query->get('area_citySearch'));

        $mstAreaInCity = $this->managerRegistry->getRepository(MstAreaInCity::class)->getCityListByCountryId($area_city, $countryId);
        return $this->render('master/mst_area_city/_ajax_listing.html.twig', [
            'mst_cities' => $mstAreaInCity,
            'country_id' => $countryId,
            'path_add' => 'master_area_city_add',
            'path_edit' => 'master_area_city_edit',
            'path_show' => 'master_area_city_show',
            'label_title' => 'label.area_city',
            'mode' => 'add'
        ]);
    }

    /**
     * @Route("/edit/{id}", name="edit", methods={"GET","POST"})
     * @param Request $request
     * @param MstAreaInCity $mstAreaInCity
     * @return Response
     */
    public function edit(Request $request, MstAreaInCity $mstAreaInCity): Response
    {
        $form = $this->createForm(MstAreaInCityType::class, $mstAreaInCity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->managerRegistry->getManager()->flush();
            $this->addFlash('success', 'form.updated_successfully');
            return $this->redirectToRoute('master_area_city_index');
        }

        return $this->render('master/mst_area_city/form.html.twig', [
            'master_area_city' => $mstAreaInCity,
            'form' => $form->createView(),
            'index_path' => 'master_area_city_index',
            'label_title' => 'label.area_city',
            'label_button' => 'label.update',
            'mode' => 'edit'
        ]);
    }

    /**
     * @Route("/{id}", name="delete", methods={"DELETE"})
     * @param Request $request
     * @param MstAreaInCity $mstAreaInCity
     * @return Response
     */
    public function delete(Request $request, MstAreaInCity $mstAreaInCity): Response
    {
        if ($this->isCsrfTokenValid('delete'.$mstAreaInCity->getId(), $request->request->get('_token'))) {
            $entityManager = $this->managerRegistry->getManager();
            $entityManager->remove($mstAreaInCity);
            $entityManager->flush();
        }

        return $this->redirectToRoute('master_area_city_index');
    }
}

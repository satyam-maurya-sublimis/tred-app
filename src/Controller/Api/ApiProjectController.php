<?php

namespace App\Controller\Api;

use App\Entity\Transaction\TrnProject;
use App\Service\CommonHelper;
use Doctrine\Persistence\ManagerRegistry;
use Ramsey\Uuid\Nonstandard\Uuid;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api", name="api_")
 */
class ApiProjectController extends ApiController
{
    private ManagerRegistry $managerRegistry;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
    }

    /**
     * @Route("/project/cities", name="project_cities", methods={"GET"})
     * @param CommonHelper $commonHelper
     * @return JsonResponse
     */
    public function cities (Request $request, CommonHelper  $commonHelper): JsonResponse
    {
        $em = $this->managerRegistry->getManager();
        $trnProjectCities = $em->getRepository(TrnProject::class)->getProjectCities();
        return new JsonResponse($trnProjectCities);
    }

    /**
     * @Route("/project", name="project", methods={"GET"})
     * @Route("/project/{id}", name="show", methods={"GET"})
     * @param CommonHelper $commonHelper
     * @return JsonResponse
     */
    public function index (Request $request, CommonHelper  $commonHelper): JsonResponse
    {
        $id = $request->attributes->get("id");
        $filter['mstCity'] = $request->query->get('city');
        $em = $this->managerRegistry->getManager();
        $trn_projects = $em->getRepository(TrnProject::class)->getProject($id, $filter);
        $data = $commonHelper->getProjectData($trn_projects);
        return new JsonResponse($data);
    }

}

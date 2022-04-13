<?php

namespace App\Controller\Master;

use Doctrine\Persistence\ManagerRegistry;
use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Entity\Master\MstDaysOfWeek;
use App\Form\Master\MstDaysOfWeekType;
use App\Repository\Master\MstDaysOfWeekRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Ramsey\Uuid\Uuid;
use Knp\Component\Pager\PaginatorInterface;


/**
 * @Route("/core/master/general/days_of_week", name="master_days_of_week_")
 * @IsGranted("ROLE_SYS_CONTENT_USER")
 */

class MstDaysOfWeekController extends AbstractController
{
    private ManagerRegistry $managerRegistry;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
    }
    /**
     * @Route("/", name="index", methods={"GET"})
     * @param MstDaysOfWeekRepository $mstDaysOfWeekRepository
     * @param Request $request
     * @param PaginatorInterface $paginator
     * @return Response
     */
    public function index(MstDaysOfWeekRepository $mstDaysOfWeekRepository, Request $request, PaginatorInterface $paginator): Response
    {
        $queryBuilder = $mstDaysOfWeekRepository->findAll();
        $pagination = $paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page', 1),
            10
        );
        return $this->render('master/mst_days_of_week/index.html.twig', [
            'days_of_weeks' => $pagination,
            'path_index' => 'master_days_of_week_index',
            'path_add' => 'master_days_of_week_add',
            'path_edit' => 'master_days_of_week_edit',
            'path_show' => 'master_days_of_week_show',
            'label_title' => 'label.days_of_week',
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
        $mstDaysOfWeek = new MstDaysOfWeek();
        $form = $this->createForm(MstDaysOfWeekType::class, $mstDaysOfWeek);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $mstDaysOfWeek->setRowId(Uuid::uuid4()->toString());
            $entityManager = $this->managerRegistry->getManager();
            $entityManager->persist($mstDaysOfWeek);
            $entityManager->flush();
            $this->addFlash('success', 'form.created_successfully');
            return $this->redirectToRoute('master_days_of_week_index');
        }
        return $this->render('form/form.html.twig', [
            'mst_days_of_week' => $mstDaysOfWeek,
            'form' => $form->createView(),
            'index_path' => 'master_days_of_week_index',
            'label_title' => 'label.days_of_week',
            'label_button' => 'label.create',
            'mode' => 'add'
        ]);
    }

    /**
     * @Route("/edit/{id}", name="edit", methods={"GET","POST"})
     * @param Request $request
     * @param MstDaysOfWeek $mstDaysOfWeek
     * @return Response
     */
    public function edit(Request $request, MstDaysOfWeek $mstDaysOfWeek): Response
    {
        $form = $this->createForm(MstDaysOfWeekType::class, $mstDaysOfWeek);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->managerRegistry->getManager()->flush();
            $this->addFlash('success', 'form.updated_successfully');
            return $this->redirectToRoute('master_days_of_week_index');
        }
        return $this->render('form/form.html.twig', [
            'mst_days_of_week' => $mstDaysOfWeek,
            'form' => $form->createView(),
            'index_path' => 'master_days_of_week_index',
            'label_title' => 'label.days_of_week',
            'label_button' => 'label.update',
            'mode' => 'edit'
        ]);
    }

    /**
     * @Route("/delete/{id}", name="delete", methods={"DELETE"})
     * @param Request $request
     * @param MstDaysOfWeek $mstDaysOfWeek
     * @return Response
     */
    public function delete(Request $request, MstDaysOfWeek $mstDaysOfWeek): Response
    {
        if ($this->isCsrfTokenValid('delete'.$mstDaysOfWeek->getId(), $request->request->get('_token'))) {
            $entityManager = $this->managerRegistry->getManager();
            $entityManager->remove($mstDaysOfWeek);
            $entityManager->flush();
        }

        return $this->redirectToRoute('master_days_of_week_index');
    }
}

<?php

namespace App\Controller\Master;

use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Entity\Master\MstDeliveryTime;
use App\Form\Master\MstDeliveryTimeType;
use App\Repository\Master\MstDeliveryTimeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Ramsey\Uuid\Uuid;
use Knp\Component\Pager\PaginatorInterface;


/**
 * @Route("/core/master/general/delivery_time", name="master_delivery_time_")
 * @IsGranted("ROLE_SYS_CONTENT_USER")
 */

class MstDeliveryTimeController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET"})
     * @param MstDeliveryTimeRepository $mstDeliveryTimeRepository
     * @param Request $request
     * @param PaginatorInterface $paginator
     * @return Response
     */
    public function index(MstDeliveryTimeRepository $mstDeliveryTimeRepository, Request $request, PaginatorInterface $paginator): Response
    {
        $queryBuilder = $mstDeliveryTimeRepository->findAll();
        $pagination = $paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page', 1),
            10
        );
        return $this->render('master/mst_delivery_time/index.html.twig', [
            'delivery_times' => $pagination,
            'path_index' => 'master_delivery_time_index',
            'path_add' => 'master_delivery_time_add',
            'path_edit' => 'master_delivery_time_edit',
            'path_show' => 'master_delivery_time_show',
            'label_title' => 'label.delivery_time',
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
        $mstDeliveryTime = new MstDeliveryTime();
        $form = $this->createForm(MstDeliveryTimeType::class, $mstDeliveryTime);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $mstDeliveryTime->setRowId(Uuid::uuid4()->toString());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($mstDeliveryTime);
            $entityManager->flush();
            $this->addFlash('success', 'form.created_successfully');
            return $this->redirectToRoute('master_delivery_time_index');
        }
        return $this->render('form/form.html.twig', [
            'mst_delivery_time' => $mstDeliveryTime,
            'form' => $form->createView(),
            'index_path' => 'master_delivery_time_index',
            'label_title' => 'label.delivery_time',
            'label_button' => 'label.create',
            'mode' => 'add'
        ]);
    }

    /**
     * @Route("/edit/{id}", name="edit", methods={"GET","POST"})
     * @param Request $request
     * @param MstDeliveryTime $mstDeliveryTime
     * @return Response
     */
    public function edit(Request $request, MstDeliveryTime $mstDeliveryTime): Response
    {
        $form = $this->createForm(MstDeliveryTimeType::class, $mstDeliveryTime);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'form.updated_successfully');
            return $this->redirectToRoute('master_delivery_time_index');
        }
        return $this->render('form/form.html.twig', [
            'mst_delivery_time' => $mstDeliveryTime,
            'form' => $form->createView(),
            'index_path' => 'master_delivery_time_index',
            'label_title' => 'label.delivery_time',
            'label_button' => 'label.update',
            'mode' => 'edit'
        ]);
    }

    /**
     * @Route("/delete/{id}", name="delete", methods={"DELETE"})
     * @param Request $request
     * @param MstDeliveryTime $mstDeliveryTime
     * @return Response
     */
    public function delete(Request $request, MstDeliveryTime $mstDeliveryTime): Response
    {
        if ($this->isCsrfTokenValid('delete'.$mstDeliveryTime->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($mstDeliveryTime);
            $entityManager->flush();
        }

        return $this->redirectToRoute('master_delivery_time_index');
    }
}

<?php

namespace App\Controller\Cms;

use App\Entity\Cms\CmsAdvertisement;
use App\Entity\Cms\CmsPage;
use App\Form\Cms\CmsAdvertisementType;
use App\Repository\Cms\CmsAdvertisementRepository;
use App\Service\CommonHelper;
use App\Service\FileUploaderHelper;
use Ramsey\Uuid\Uuid;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/core/cms/advertisement", name="cms_advertisement_")
 * @IsGranted("ROLE_SYS_CONTENT_USER")
 */
class CmsAdvertisementController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET"})
     * @param CmsAdvertisementRepository $cmsAdvertisementRepository
     * @param Request $request
     * @return Response
     */
    public function index(CmsAdvertisementRepository $cmsAdvertisementRepository, Request $request): Response
    {
        $page_id = $request->query->get('page_id');
        if(!$page_id) {
            return $this->redirectToRoute('cms_page_index');
        }
        $advertisements = $cmsAdvertisementRepository->findBy(['cmsPage' => $page_id]);
        return $this->render('cms/cms_advertisement/index.html.twig', [
            'cms_advertisements' => $advertisements,
            'path_index' => 'cms_advertisement_index',
            'path_add' => 'cms_advertisement_add',
            'path_edit' => 'cms_advertisement_edit',
            'path_show' => 'cms_advertisement_show',
            'label_title' => 'label.advertisement',
        ]);
    }

    /**
     * @Route("/add", name="add", methods={"GET","POST"})
     * @param Request $request
     * @param CmsAdvertisementRepository $cmsAdvertisementRepository
     * @param CommonHelper $helper
     * @param FileUploaderHelper $fileUploaderHelper
     * @return Response
     * @throws \Exception
     */
    public function new(Request $request, CmsAdvertisementRepository $cmsAdvertisementRepository, CommonHelper $helper, FileUploaderHelper $fileUploaderHelper): Response
    {
        $page_id = $request->query->get('page_id');
        if(!$page_id) {
            return $this->redirectToRoute('cms_page_index');
        }
        $cmsAdvertisement = new CmsAdvertisement();

        $cmsPage = $this->getDoctrine()->getRepository(CmsPage::class)->find($page_id);
        $option = array('image_required' => false);
        $sequenceNo = $cmsAdvertisementRepository->findOneBySeqNo($page_id);
        $cmsAdvertisement->setPosition(($sequenceNo[1] + 1));
        $cmsAdvertisement->setCmsPage($cmsPage);
        $form = $this->createForm(CmsAdvertisementType::class, $cmsAdvertisement, $option);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $cmsAdvertisement->setRowId(Uuid::uuid4()->toString());
            $mediaType = $form['advertisementMediaType']->getData();
            if ($mediaType == 'image')
            {
                if ($form['advertisementImageSetName']->getData() != ""){
                    $slugName = $helper->slugify($form['advertisementImageSetName']->getData());
                }else{
                    $slugName = explode(".", $form['advertisementDesktopImage']->getData()->getClientOriginalName())[0];
                    $cmsAdvertisement->setAdvertisementImageSetName($slugName);
                    $slugName = $helper->slugify($slugName);
                }

                $advertisementDesktopFile = $form['advertisementDesktopImage']->getData();
                $advertisementTabletFile = $form['advertisementTabletImage']->getData();
                $advertisementMobileFile = $form['advertisementMobileImage']->getData();

                if($advertisementDesktopFile != ''){
                    $newFilename = $fileUploaderHelper->uploadPublicFile($advertisementDesktopFile, $slugName, $existingAdvertisementDesktopImage = null);
                    $cmsAdvertisement->setAdvertisementDesktopImage($newFilename);
                    $cmsAdvertisement->setAdvertisementDesktopImagePath($this->getParameter('public_file_folder'));
                }
                if($advertisementTabletFile != ''){
                    $newFilename = $fileUploaderHelper->uploadPublicFile($advertisementTabletFile, $slugName, $existingAdvertisementTabletImage = null);
                    $cmsAdvertisement->setAdvertisementTabletImage($newFilename);
                    $cmsAdvertisement->setAdvertisementTabletImagePath($this->getParameter('public_file_folder'));
                }
                if($advertisementMobileFile != ''){
                    $newFilename = $fileUploaderHelper->uploadPublicFile($advertisementMobileFile, $slugName, $existingAdvertisementMobileImage = null);
                    $cmsAdvertisement->setAdvertisementMobileImage($newFilename);
                    $cmsAdvertisement->setAdvertisementMobileImagePath($this->getParameter('public_file_folder'));
                }

                $cmsAdvertisement->setAdvertisementImageSetName($form['advertisementImageSetName']->getData());
                $cmsAdvertisement->setAdvertisementImageAlt($form['advertisementImageAlt']->getData());
                $cmsAdvertisement->setAdvertisementImageTitle($form['advertisementImageTitle']->getData());
            #    $cmsAdvertisement->setAdvertisementImagePath($this->getParameter('public_file_folder'));
            }
            if ($mediaType == 'video')
            {
                $cmsAdvertisement->setAdvertisementVideo($form['advertisementVideo']->getData());
                $cmsAdvertisement->setAdvertisementVideoPath($form['advertisementVideoPath']->getData());
            }
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($cmsAdvertisement);
            $entityManager->flush();
            $this->addFlash('success', 'form.created_successfully');
            return $this->redirectToRoute('cms_advertisement_index', $request->query->all());
        }

        return $this->render('cms/cms_advertisement/form.html.twig', [
            'cms_advertisement' => $cmsAdvertisement,
            'form' => $form->createView(),
            'index_path' => 'cms_advertisement_index',
            'label_title' => 'label.advertisement',
            'label_button' => 'label.create',
            'mode' => 'add'
        ]);
    }

    /**
     * @Route("/{id}", name="show", methods={"GET"})
     * @param CmsAdvertisement $cmsAdvertisement
     * @param Request $request
     * @return Response
     */
    public function show(CmsAdvertisement $cmsAdvertisement, Request $request): Response
    {
        $page_id = $request->query->get('page_id');
        if(!$page_id) {
            return $this->redirectToRoute('cms_page_index');
        }
        return $this->render('cms/cms_advertisement/show.html.twig', [
            'data' => $cmsAdvertisement,
            'index_path' => 'cms_advertisement_delete',
            'label_button' => 'label.delete',
            'path_index' => 'cms_advertisement_index',
            'path_edit' => 'cms_advertisement_edit',
            'path_delete' => 'cms_advertisement_delete',
            'label_title' => 'label.advertisement',
        ]);
    }

    /**
     * @Route("/edit/{id}", name="edit", methods={"GET","POST"})
     * @param Request $request
     * @param CmsAdvertisement $cmsAdvertisement
     * @param CommonHelper $helper
     * @param FileUploaderHelper $fileUploaderHelper
     * @return Response
     * @throws \Exception
     */
    public function edit(Request $request, CmsAdvertisement $cmsAdvertisement, CommonHelper $helper, FileUploaderHelper $fileUploaderHelper): Response
    {
        $page_id = $request->query->get('page_id');
        if(!$page_id) {
            return $this->redirectToRoute('cms_page_index');
        }
        $existingAdvertisementDesktopImage = $cmsAdvertisement->getAdvertisementDesktopImage();
        $existingAdvertisementTabletImage = $cmsAdvertisement->getAdvertisementTabletImage();
        $existingAdvertisementMobileImage = $cmsAdvertisement->getAdvertisementMobileImage();
        $option = array('image_required' => false);
        $form = $this->createForm(CmsAdvertisementType::class, $cmsAdvertisement, $option);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $mediaType = $form['advertisementMediaType']->getData();
            if ($mediaType == 'image')
            {
                $advertisementDesktopFile = $form['advertisementDesktopImage']->getData();
                $advertisementTabletFile = $form['advertisementTabletImage']->getData();
                $advertisementMobileFile = $form['advertisementMobileImage']->getData();

                if ($form['advertisementImageSetName']->getData() != ""){
                    $slugName = $helper->slugify($form['advertisementImageSetName']->getData());
                }else{
                    $slugName = explode(".", $form['advertisementDesktopImage']->getData()->getClientOriginalName())[0];
                    $cmsAdvertisement->setAdvertisementImageSetName($slugName);
                    $slugName = $helper->slugify($slugName);
                }
                if($advertisementDesktopFile){

                    // If there is already a media remove it
                    if($existingAdvertisementDesktopImage != ''){
                        $newFilename = $fileUploaderHelper->uploadPublicFile($advertisementDesktopFile, $slugName, $existingAdvertisementDesktopImage);
                    } else {
                        $newFilename = $fileUploaderHelper->uploadPublicFile($advertisementDesktopFile, $slugName, $existingAdvertisementDesktopImage = null);
                    }

                    $cmsAdvertisement->setAdvertisementDesktopImage($newFilename);
                    $cmsAdvertisement->setAdvertisementDesktopImagePath($this->getParameter('public_file_folder'));
                }

                if ($advertisementTabletFile){

                    if($existingAdvertisementTabletImage != ''){
                        $newFilename = $fileUploaderHelper->uploadPublicFile($advertisementTabletFile, $slugName, $existingAdvertisementTabletImage);
                    } else {
                        $newFilename = $fileUploaderHelper->uploadPublicFile($advertisementTabletFile, $slugName, $existingAdvertisementTabletImage = null);
                    }
                    $cmsAdvertisement->setAdvertisementTabletImage($newFilename);
                    $cmsAdvertisement->setAdvertisementTabletImagePath($this->getParameter('public_file_folder'));
                }


                if($advertisementMobileFile){
                    if($existingAdvertisementMobileImage != ''){
                        $newFilename = $fileUploaderHelper->uploadPublicFile($advertisementMobileFile, $slugName, $existingAdvertisementTabletImage);
                    } else {
                        $newFilename = $fileUploaderHelper->uploadPublicFile($advertisementMobileFile, $slugName, $existingAdvertisementTabletImage = null);
                    }
                    $cmsAdvertisement->setAdvertisementMobileImage($newFilename);
                    $cmsAdvertisement->setAdvertisementMobileImagePath($this->getParameter('public_file_folder'));
                }


//                    $cmsAdvertisement->setAdvertisementImage($newFilename);
                    //$cmsAdvertisement->setAdvertisementImageSetName($form['advertisementImageSetName']->getData());
                    $cmsAdvertisement->setAdvertisementImageAlt($form['advertisementImageAlt']->getData());
                    $cmsAdvertisement->setAdvertisementImageTitle($form['advertisementImageTitle']->getData());
//                    $cmsAdvertisement->setAdvertisementImagePath($this->getParameter('public_file_folder'));
                    $cmsAdvertisement->setAdvertisementVideo('');
                    $cmsAdvertisement->setAdvertisementVideoPath('');
            }
            if ($mediaType == 'video')
            {
                if($existingAdvertisementDesktopImage != '') {
                    $fileUploaderHelper->removeFile($existingAdvertisementDesktopImage);
                    $cmsAdvertisement->setAdvertisementDesktopImage('');
                }
                if($existingAdvertisementTabletImage != '') {
                    $fileUploaderHelper->removeFile($existingAdvertisementTabletImage);
                    $cmsAdvertisement->setAdvertisementTabletImage('');
                }
                if($existingAdvertisementMobileImage != '') {
                    $fileUploaderHelper->removeFile($existingAdvertisementMobileImage);
                    $cmsAdvertisement->setAdvertisementMobileImage('');
                }
                $cmsAdvertisement->setAdvertisementImageSetName('');
                $cmsAdvertisement->setAdvertisementImageAlt('');
                $cmsAdvertisement->setAdvertisementImageTitle('');
                $cmsAdvertisement->setAdvertisementImagePath('');
                $cmsAdvertisement->setAdvertisementVideo($form['advertisementVideo']->getData());
                $cmsAdvertisement->setAdvertisementVideoPath($form['advertisementVideoPath']->getData());
            }

            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'form.updated_successfully');
            return $this->redirectToRoute('cms_advertisement_index', $request->query->all());
        }

        return $this->render('cms/cms_advertisement/form.html.twig', [
            'cms_advertisement' => $cmsAdvertisement,
            'form' => $form->createView(),
            'index_path' => 'cms_advertisement_index',
            'label_title' => 'label.advertisement',
            'label_button' => 'label.update',
            'path_delete' => 'cms_advertisement_delete',
            'mode' => 'edit'
        ]);
    }

    /**
     * @Route("/{id}", name="delete", methods={"DELETE"})
     * @param Request $request
     * @param CmsAdvertisement $cmsAdvertisement
     * @return Response
     */
    public function delete(Request $request, CmsAdvertisement $cmsAdvertisement): Response
    {
        $page_id = $request->query->get('page_id');
        if(!$page_id) {
            return $this->redirectToRoute('cms_page_index');
        }
        if ($this->isCsrfTokenValid('delete'.$cmsAdvertisement->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($cmsAdvertisement);
            $entityManager->flush();
        }

        return $this->redirectToRoute('cms_advertisement_index', $request->query->all());
    }
}

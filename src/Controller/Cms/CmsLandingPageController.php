<?php

namespace App\Controller\Cms;

use App\Entity\Cms\CmsLandingPage;
use App\Entity\Cms\CmsPage;
use App\Form\Cms\CmsLandingPageType;
use App\Repository\Cms\CmsLandingPageRepository;
use App\Service\CommonHelper;
use App\Service\FileUploaderHelper;
use Ramsey\Uuid\Uuid;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/core/cms/landing-page", name="cms_landing_page_")
 * @IsGranted("ROLE_APP_USER")
 */
class CmsLandingPageController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET"})
     * @param CmsLandingPageRepository $cmsLandingPageRepository
     * @param Request $request
     * @return Response
     */
    public function index(CmsLandingPageRepository $cmsLandingPageRepository, Request $request): Response
    {
        $landing_pages = $cmsLandingPageRepository->findBy(['isActive' => 1]);
        return $this->render('cms/cms_landing_page/index.html.twig', [
            'cms_landing_pages' => $landing_pages,
            'path_index' => 'cms_landing_page_index',
            'path_add' => 'cms_landing_page_add',
            'path_edit' => 'cms_landing_page_edit',
            'path_show' => 'cms_landing_page_show',
            'label_title' => 'label.landing_page',
            'path_banner' => 'cms_landing_page_banner_index',
        ]);
    }

    /**
     * @Route("/add", name="add", methods={"GET","POST"})
     * @param Request $request
     * @param CmsLandingPageRepository $cmsLandingPageRepository
     * @param CommonHelper $helper
     * @param FileUploaderHelper $fileUploaderHelper
     * @return Response
     * @throws \Exception
     */
    public function new(Request $request, CmsLandingPageRepository $cmsLandingPageRepository, CommonHelper $helper, FileUploaderHelper $fileUploaderHelper): Response
    {
        $cmsLandingPage = new CmsLandingPage();
        $option = array('image_required' => true);
        $form = $this->createForm(CmsLandingPageType::class, $cmsLandingPage, $option);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($form->get('cmsLandingPageContents') as $key=>$content) {
                $cmsContent = $cmsLandingPage->getCmsLandingPageContents()[$key];
                $mediaType = $content['pageMediaType']->getData();
                if ($mediaType == 'image' ){
                    $contentFile = $content['pageImage']->getData();
                    if ($contentFile) {
                        $newFilename = $fileUploaderHelper->uploadPublicFile($contentFile, $content['pageImageName']->getData(),$existingArticleContentFile = null);
                        $cmsContent->setPageImage($newFilename);
                        $cmsContent->setPageImageName($content['pageImageName']->getData());
                        $cmsContent->setPageImageAlt($content['pageImageAlt']->getData());
                        $cmsContent->setPageImageTitle($content['pageImageTitle']->getData());
                        $cmsContent->setPageImagePath($this->getParameter('public_file_folder'));
                    }
                }
                if ($mediaType == 'video' ){
                    $video = $content['pageVideo']->getData();
                    if ($video) {
                        $cmsContent->setPageVideo($content['pageVideo']->getData());
                        $cmsContent->setPageVideoPath($content['pageVideoPath']->getData());
                    }
                }
            }
            $cmsLandingPage->setRowId(Uuid::uuid4()->toString());
            $mediaType = $form['cmsLandingPageMediaType']->getData();
            if ($form['mstProductType']->getData()){
                $cmsLandingPage->setCmsLandingPageSlugName($form['mstProductType']->getData()->getProductTypeSlugName());
            }else{
                $cmsLandingPage->setCmsLandingPageSlugName($form['mstProductCategory']->getData()->getProductCategorySlugName());
            }
            if ($mediaType == 'image')
            {
                $landing_pageFile = $form['cmsLandingPageImage']->getData();
                $cmsLandingPage->setCmsLandingPageImageName($form['cmsLandingPageImageName']->getData());
                $cmsLandingPage->setCmsLandingPageImageAlt($form['cmsLandingPageImageAlt']->getData());
                $cmsLandingPage->setCmsLandingPageImageTitle($form['cmsLandingPageImageTitle']->getData());
                if ($landing_pageFile)
                {
                    $newFilename = $fileUploaderHelper->uploadPublicFile($landing_pageFile, $helper->slugify($form['cmsLandingPageImageName']->getData()), $existingBannerImage = null);
                    $cmsLandingPage->setCmsLandingPageImage($newFilename);
                    $cmsLandingPage->setCmsLandingPageImagePath($this->getParameter('public_file_folder'));
                }
            }
            if ($mediaType == 'video')
            {
                $cmsLandingPage->setCmsLandingPageVideo($form['cmsLandingPageVideo']->getData());
                $cmsLandingPage->setCmsLandingPageVideoPath($form['cmsLandingPageVideoPath']->getData());
            }
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($cmsLandingPage);
            $entityManager->flush();
            $this->addFlash('success', 'form.created_successfully');
            return $this->redirectToRoute('cms_landing_page_index');
        }

        return $this->render('cms/cms_landing_page/form.html.twig', [
            'cms_landing_page' => $cmsLandingPage,
            'form' => $form->createView(),
            'index_path' => 'cms_landing_page_index',
            'label_title' => 'label.landing_page',
            'label_button' => 'label.create',
            'mode' => 'add'
        ]);
    }

    /**
     * @Route("/{id}", name="show", methods={"GET"})
     * @param CmsLandingPage $cmsLandingPage
     * @param Request $request
     * @return Response
     */
    public function show(CmsLandingPage $cmsLandingPage, Request $request): Response
    {
        return $this->render('cms/cms_landing_page/show.html.twig', [
            'data' => $cmsLandingPage,
            'index_path' => 'cms_landing_page_delete',
            'label_button' => 'label.delete',
            'path_index' => 'cms_landing_page_index',
            'path_edit' => 'cms_landing_page_edit',
            'path_delete' => 'cms_landing_page_delete',
            'label_title' => 'label.landing_page',
        ]);
    }

    /**
     * @Route("/edit/{id}", name="edit", methods={"GET","POST"})
     * @param Request $request
     * @param CmsLandingPage $cmsLandingPage
     * @param CommonHelper $helper
     * @param FileUploaderHelper $fileUploaderHelper
     * @return Response
     * @throws \Exception
     */
    public function edit(Request $request, CmsLandingPage $cmsLandingPage, CommonHelper $helper, FileUploaderHelper $fileUploaderHelper): Response
    {

        $existingImage = $cmsLandingPage->getCmsLandingPageImage();
        $option = array('image_required' => false);
        $form = $this->createForm(CmsLandingPageType::class, $cmsLandingPage,$option);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            foreach ($form->get('cmsLandingPageContents') as $key=>$content) {
                $cmsContent = $cmsLandingPage->getCmsLandingPageContents()[$key];
                $mediaType = $content['pageMediaType']->getData();
                $existingContentFile = $cmsContent->getPageImage();
                if ($mediaType == 'image' ){
                    $contentFile = $content['pageImage']->getData();
                    if ($contentFile) {
                        $newFilename = $fileUploaderHelper->uploadPublicFile($contentFile, $content['pageImageName']->getData(),$existingContentFile);
                        $cmsContent->setPageImage($newFilename);
                        $cmsContent->setPageImageName($content['pageImageName']->getData());
                        $cmsContent->setPageImageAlt($content['pageImageAlt']->getData());
                        $cmsContent->setPageImageTitle($content['pageImageTitle']->getData());
                        $cmsContent->setPageImagePath($this->getParameter('public_file_folder'));
                    }
                }
                if ($mediaType == 'video' ){
                    $video = $content['pageVideo']->getData();
                    if ($video) {
                        $cmsContent->setPageVideo($content['pageVideo']->getData());
                        $cmsContent->setPageVideoPath($content['pageVideoPath']->getData());
                    }
                }
            }

            $mediaType = $form['cmsLandingPageMediaType']->getData();
                if ($form['mstProductType']->getData()){
                    $cmsLandingPage->setCmsLandingPageSlugName($form['mstProductType']->getData()->getProductTypeSlugName());
                }else{
                    $cmsLandingPage->setCmsLandingPageSlugName($form['mstProductCategory']->getData()->getProductCategorySlugName());
                }
            if ($mediaType == 'image')
            {
                $landing_pageFile = $form['cmsLandingPageImage']->getData();
                $cmsLandingPage->setCmsLandingPageImageName($form['cmsLandingPageImageName']->getData());
                $cmsLandingPage->setCmsLandingPageImageAlt($form['cmsLandingPageImageAlt']->getData());
                $cmsLandingPage->setCmsLandingPageImageTitle($form['cmsLandingPageImageTitle']->getData());
                if ($landing_pageFile)
                {
                    // If there is already a media remove it
                    if($existingImage != '')
                    {
                        $newFilename = $fileUploaderHelper->uploadPublicFile($landing_pageFile, $helper->slugify($form['cmsLandingPageImageName']->getData()), $existingImage);
                    } else {
                        $newFilename = $fileUploaderHelper->uploadPublicFile($landing_pageFile, $helper->slugify($form['cmsLandingPageImageName']->getData()), $existingImage = null);
                    }
                    $cmsLandingPage->setCmsLandingPageImage($newFilename);
                    $cmsLandingPage->setCmsLandingPageImagePath($this->getParameter('public_file_folder'));
                }
            }
            if ($mediaType == 'video')
            {
                if($existingImage != '') {
                    $fileUploaderHelper->removeFile($existingImage);
                    $cmsLandingPage->setCmsLandingPageImage('');
                    $cmsLandingPage->setCmsLandingPageImagePath('');
                    $cmsLandingPage->setCmsLandingPageImageName('');
                    $cmsLandingPage->setCmsLandingPageImageAlt('');
                    $cmsLandingPage->setCmsLandingPageImageTitle('');
                }
                $cmsLandingPage->setCmsLandingPageVideo($form['cmsLandingPageVideo']->getData());
                $cmsLandingPage->setCmsLandingPageVideoPath($form['cmsLandingPageVideoPath']->getData());
            }

            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'form.updated_successfully');
            return $this->redirectToRoute('cms_landing_page_index');
        }

        return $this->render('cms/cms_landing_page/form.html.twig', [
            'cms_landing_page' => $cmsLandingPage,
            'form' => $form->createView(),
            'index_path' => 'cms_landing_page_index',
            'label_title' => 'label.landing_page',
            'label_button' => 'label.update',
            'path_delete' => 'cms_landing_page_delete',
            'mode' => 'edit'
        ]);
    }

}

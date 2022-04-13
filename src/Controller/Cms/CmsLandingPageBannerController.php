<?php

namespace App\Controller\Cms;

use App\Entity\Cms\CmsBanner;
use App\Entity\Cms\CmsLandingPage;
use App\Entity\Cms\CmsPage;
use App\Form\Cms\CmsLandingPageBannerType;
use App\Repository\Cms\CmsBannerRepository;
use App\Service\CommonHelper;
use App\Service\FileUploaderHelper;
use Ramsey\Uuid\Uuid;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/core/cms/landing-page-banner", name="cms_landing_page_banner_")
 * @IsGranted("ROLE_APP_USER")
 */
class CmsLandingPageBannerController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET"})
     * @param CmsBannerRepository $cmsBannerRepository
     * @param Request $request
     * @return Response
     */
    public function index(CmsBannerRepository $cmsBannerRepository, Request $request): Response
    {
        $lp_page_id = $request->query->get('lp_page_id');
        if(!$lp_page_id) {
            return $this->redirectToRoute('cms_landing_page_index');
        }
        $banners = $cmsBannerRepository->findBy(['cmsLandingPage' => $lp_page_id]);
        return $this->render('cms/cms_landing_page_banner/index.html.twig', [
            'cms_banners' => $banners,
            'path_index' => 'cms_landing_page_banner_index',
            'path_add' => 'cms_landing_page_banner_add',
            'path_edit' => 'cms_landing_page_banner_edit',
            'path_show' => 'cms_landing_page_banner_show',
            'label_title' => 'label.banner',
        ]);
    }

    /**
     * @Route("/add", name="add", methods={"GET","POST"})
     * @param Request $request
     * @param CmsBannerRepository $cmsBannerRepository
     * @param CommonHelper $helper
     * @param FileUploaderHelper $fileUploaderHelper
     * @return Response
     * @throws \Exception
     */
    public function new(Request $request, CmsBannerRepository $cmsBannerRepository, CommonHelper $helper, FileUploaderHelper $fileUploaderHelper): Response
    {
        $lp_page_id = $request->query->get('lp_page_id');
        if(!$lp_page_id) {
            return $this->redirectToRoute('cms_landing_page_index');
        }
        $cmsBanner = new CmsBanner();
        $cmsLandingPage = $this->getDoctrine()->getRepository(CmsLandingPage::class)->find($lp_page_id);
        $option = array('image_required' => true);
        $sequenceNo = $cmsBannerRepository->findOneBySeqNoLp($lp_page_id);
        $cmsBanner->setSequenceNo(($sequenceNo[1] + 1));
        $cmsBanner->setCmsLandingPage($cmsLandingPage);
        $form = $this->createForm(CmsLandingPageBannerType::class, $cmsBanner, $option);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $cmsBanner->setRowId(Uuid::uuid4()->toString());

            $mediaType = $form['bannerMediaType']->getData();
            if ($mediaType == 'image')
            {
                $bannerDesktopFile = $form['bannerDesktopImage']->getData();
                $bannerTabletFile = $form['bannerTabletImage']->getData();
                $bannerMobileFile = $form['bannerMobileImage']->getData();
                $bannerTabletFileSetName = $form['bannerImageSetName']->getData().'-tablet';
                $bannerMobileFileSetName = $form['bannerImageSetName']->getData().'-mobile';
                $cmsBanner->setBannerImageSetName($form['bannerImageSetName']->getData());
                $cmsBanner->setBannerImageAlt($form['bannerImageAlt']->getData());
                $cmsBanner->setBannerImageTitle($form['bannerImageTitle']->getData());
                if ($bannerDesktopFile)
                {
                    $newFilename = $fileUploaderHelper->uploadPublicFile($bannerDesktopFile, $helper->slugify($form['bannerImageSetName']->getData()), $existingBannerImage = null);
                    $cmsBanner->setBannerDesktopImage($newFilename);
                    $cmsBanner->setBannerDesktopImagePath($this->getParameter('public_file_folder'));
                }
                if ($bannerTabletFile)
                {
                    $newFilename = $fileUploaderHelper->uploadPublicFile($bannerTabletFile, $helper->slugify($bannerTabletFileSetName), $existingBannerImage = null);
                    $cmsBanner->setBannerTabletImage($newFilename);
                    $cmsBanner->setBannerTabletImagePath($this->getParameter('public_file_folder'));
                }
                if ($bannerMobileFile)
                {
                    $newFilename = $fileUploaderHelper->uploadPublicFile($bannerMobileFile, $helper->slugify($bannerMobileFileSetName), $existingBannerImage = null);
                    $cmsBanner->setBannerMobileImage($newFilename);
                    $cmsBanner->setBannerMobileImagePath($this->getParameter('public_file_folder'));
                }
            }
            if ($mediaType == 'video')
            {
                $cmsBanner->setBannerVideo($form['bannerVideo']->getData());
                $cmsBanner->setBannerVideoPath($form['bannerVideoPath']->getData());
            }
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($cmsBanner);
            $entityManager->flush();
            $this->addFlash('success', 'form.created_successfully');
            return $this->redirectToRoute('cms_landing_page_index', $request->query->all());
        }

        return $this->render('cms/cms_landing_page_banner/form.html.twig', [
            'cms_banner' => $cmsBanner,
            'form' => $form->createView(),
            'index_path' => 'cms_landing_page_banner_index',
            'label_title' => 'label.banner',
            'label_button' => 'label.create',
            'mode' => 'add'
        ]);
    }

    /**
     * @Route("/{id}", name="show", methods={"GET"})
     * @param CmsBanner $cmsBanner
     * @param Request $request
     * @return Response
     */
    public function show(CmsBanner $cmsBanner, Request $request): Response
    {
        $lp_page_id = $request->query->get('lp_page_id');
        if(!$lp_page_id) {
            return $this->redirectToRoute('cms_landing_page_index');
        }
        return $this->render('cms/cms_landing_page_banner/show.html.twig', [
            'data' => $cmsBanner,
            'index_path' => 'cms_landing_page_banner_delete',
            'label_button' => 'label.delete',
            'path_index' => 'cms_landing_page_banner_index',
            'path_edit' => 'cms_landing_page_banner_edit',
            'path_delete' => 'cms_landing_page_banner_delete',
            'label_title' => 'label.banner',
        ]);
    }

    /**
     * @Route("/edit/{id}", name="edit", methods={"GET","POST"})
     * @param Request $request
     * @param CmsBanner $cmsBanner
     * @param CommonHelper $helper
     * @param FileUploaderHelper $fileUploaderHelper
     * @return Response
     * @throws \Exception
     */
    public function edit(Request $request, CmsBanner $cmsBanner, CommonHelper $helper, FileUploaderHelper $fileUploaderHelper): Response
    {
        $lp_page_id = $request->query->get('lp_page_id');
        if(!$lp_page_id) {
            return $this->redirectToRoute('cms_landing_page_index');
        }
        $existingBannerDesktopImage = $cmsBanner->getBannerDesktopImage();
        $existingBannerTabletImage = $cmsBanner->getBannerTabletImage();
        $existingBannerMobileImage = $cmsBanner->getBannerMobileImage();
        $option = array('image_required' => false);
        $form = $this->createForm(CmsLandingPageBannerType::class, $cmsBanner, $option);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $mediaType = $form['bannerMediaType']->getData();
            if ($mediaType == 'image')
            {
                $bannerDesktopFile = $form['bannerDesktopImage']->getData();
                $bannerTabletFile = $form['bannerTabletImage']->getData();
                $bannerMobileFile = $form['bannerMobileImage']->getData();
                $bannerTabletFileSetName = $form['bannerImageSetName']->getData().'-tablet';
                $bannerMobileFileSetName = $form['bannerImageSetName']->getData().'-mobile';
                if ($bannerDesktopFile)
                {
                    // If there is already a media remove it
                    if($existingBannerDesktopImage != '')
                    {
                        $newFilename = $fileUploaderHelper->uploadPublicFile($bannerDesktopFile, $helper->slugify($form['bannerImageSetName']->getData()), $existingBannerDesktopImage);
                    } else {
                        $newFilename = $fileUploaderHelper->uploadPublicFile($bannerDesktopFile, $helper->slugify($form['bannerImageSetName']->getData()), $existingBannerDesktopImage = null);
                    }
                    $cmsBanner->setBannerDesktopImage($newFilename);
                    $cmsBanner->setBannerImageSetName($form['bannerImageSetName']->getData());
                    $cmsBanner->setBannerImageAlt($form['bannerImageAlt']->getData());
                    $cmsBanner->setBannerImageTitle($form['bannerImageTitle']->getData());
                    $cmsBanner->setBannerDesktopImagePath($this->getParameter('public_file_folder'));
                }
                if ($bannerTabletFile)
                {
                    // If there is already a media remove it
                    if($existingBannerTabletImage != '')
                    {
                        $newFilename = $fileUploaderHelper->uploadPublicFile($bannerTabletFile, $helper->slugify($bannerTabletFileSetName), $existingBannerTabletImage);
                    } else {
                        $newFilename = $fileUploaderHelper->uploadPublicFile($bannerTabletFile, $helper->slugify($bannerTabletFileSetName), $existingBannerTabletImage = null);
                    }
                    $cmsBanner->setBannerTabletImage($newFilename);
                    $cmsBanner->setBannerTabletImagePath($this->getParameter('public_file_folder'));
                }
                if ($bannerMobileFile)
                {
                    // If there is already a media remove it
                    if($existingBannerMobileImage != '')
                    {
                        $newFilename = $fileUploaderHelper->uploadPublicFile($bannerMobileFile, $helper->slugify($bannerMobileFileSetName), $existingBannerMobileImage);
                    } else {
                        $newFilename = $fileUploaderHelper->uploadPublicFile($bannerMobileFile, $helper->slugify($bannerMobileFileSetName), $existingBannerMobileImage = null);
                    }
                    $cmsBanner->setBannerMobileImage($newFilename);
                    $cmsBanner->setBannerMobileImagePath($this->getParameter('public_file_folder'));
                }
            }
            if ($mediaType == 'video')
            {
                if($existingBannerDesktopImage != '') {
                    $fileUploaderHelper->removeFile($existingBannerDesktopImage);
                    $cmsBanner->setBannerDesktopImage('');
                    $cmsBanner->setBannerImageSetName('');
                    $cmsBanner->setBannerImageAlt('');
                    $cmsBanner->setBannerImageTitle('');
                    $cmsBanner->setBannerDesktopImagePath('');
                }
                if($existingBannerTabletImage != '') {
                    $fileUploaderHelper->removeFile($existingBannerTabletImage);
                    $cmsBanner->setBannerTabletImage('');
                    $cmsBanner->setBannerTabletImagePath('');
                }
                if($existingBannerMobileImage != '') {
                    $fileUploaderHelper->removeFile($existingBannerMobileImage);
                    $cmsBanner->setBannerMobileImage('');
                    $cmsBanner->setBannerMobileImagePath('');
                }
                $cmsBanner->setBannerVideo($form['bannerVideo']->getData());
                $cmsBanner->setBannerVideoPath($form['bannerVideoPath']->getData());
            }

            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'form.updated_successfully');
            return $this->redirectToRoute('cms_landing_page_index', $request->query->all());
        }

        return $this->render('cms/cms_landing_page_banner/form.html.twig', [
            'cms_banner' => $cmsBanner,
            'form' => $form->createView(),
            'index_path' => 'cms_landing_page_banner_index',
            'label_title' => 'label.banner',
            'label_button' => 'label.update',
            'path_delete' => 'cms_landing_page_banner_delete',
            'mode' => 'edit'
        ]);
    }

    /**
     * @Route("/{id}", name="delete", methods={"DELETE"})
     * @param Request $request
     * @param CmsBanner $cmsBanner
     * @return Response
     */
    public function delete(Request $request, CmsBanner $cmsBanner): Response
    {
        $lp_page_id = $request->query->get('lp_page_id');
        if(!$lp_page_id) {
            return $this->redirectToRoute('cms_landing_page_index');
        }
        if ($this->isCsrfTokenValid('delete'.$cmsBanner->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($cmsBanner);
            $entityManager->flush();
        }

        return $this->redirectToRoute('cms_landing_page_index', $request->query->all());
    }
}

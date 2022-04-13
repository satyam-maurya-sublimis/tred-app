<?php

namespace App\Controller\Portal;

use App\Entity\Cms\CmsLandingPage;
use App\Entity\Form\FormFurnitureEnquiry;
use App\Entity\Master\MstFurnitureCategory;
use App\Entity\Master\MstFurnitureUniqueValuePreposition;
use App\Entity\Master\MstLeadStatus;
use App\Entity\Master\MstProductCategory;
use App\Entity\Master\MstProductSubType;
use App\Entity\Master\MstProductType;
use App\Entity\Organization\OrgCompany;
use App\Entity\Transaction\TrnFurniture;
use App\Entity\Transaction\TrnFurnitureProductCatalog;
use App\Form\Form\FormFurnitureEnquiryType;
use App\Form\Portal\FurnitureTredExpertsType;
use App\Service\CommonHelper;
use App\Service\Mailer;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
/**
 * @Route("/furniture", name="portal_furniture_")
 */
class FurnitureController extends AbstractController
{
    private ManagerRegistry $managerRegistry;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
    }

    /**
     * @Route("/home-furniture", name="home")
     * @param Request $request
     * @return Response
     */
    public function furnitureHome(Request $request): Response
    {
        $slugName  = 'home-furniture';
        $mstProductType = $this->managerRegistry->getRepository(MstProductType::class)->findOneBy(["isActive"=>1,"productTypeSlugName"=>$slugName]);
        $mstProductSubType = $this->managerRegistry->getRepository(MstProductSubType::class)->getProductSubTypeByProductTypeId($mstProductType->getId());
        $mstFurnitureUniqueValuePreposition = $this->managerRegistry->getRepository(MstFurnitureUniqueValuePreposition::class)->findBy(["isActive"=>1,"mstProductType"=>$mstProductType]);
        $mstFurnitureProductCatalogue = $this->managerRegistry->getRepository(TrnFurnitureProductCatalog::class)->findBy(["isActive"=>1,"mstProductType"=>$mstProductType]);
        return $this->render('portal/page/furniture/home_furniture/index.html.twig', [
            'mstProductType'=> $mstProductType,
            'mstProductSubType'=> $mstProductSubType,
            'mstFurnitureUniqueValuePreposition'=> $mstFurnitureUniqueValuePreposition,
            'mstFurnitureProductCatalogues'=> $mstFurnitureProductCatalogue,
        ]);
    }

    /**
     * @Route("/home-furniture/{brand}/{title}/best-seller/{id}", name="catalogue_home_furniture_detail")
     * @param Request $request
     * @return Response
     */
    public function furnitureHomeCatalogue(Request $request): Response
    {
        $id = $request->attributes->get('id');
        $trnFurnitureProductCatalogue = $this->managerRegistry->getRepository(TrnFurnitureProductCatalog::class)->find($id);
        return $this->render('portal/page/furniture/home_furniture/catalogue.html.twig', [
            'mstProducType'=> $trnFurnitureProductCatalogue->getMstProductType(),
            'trnFurnitureProductCatalogue'=> $trnFurnitureProductCatalogue,
        ]);
    }

    /**
     * @Route("/office-furniture/{brand}/{title}/best-seller/{id}", name="catalogue_office_furniture_detail")
     * @param Request $request
     * @return Response
     */
    public function furnitureOfficeCatalogue(Request $request): Response
    {
        $id = $request->attributes->get('id');
        $trnFurnitureProductCatalogue = $this->managerRegistry->getRepository(TrnFurnitureProductCatalog::class)->find($id);
        return $this->render('portal/page/furniture/home_furniture/catalogue.html.twig', [
            'mstProducType'=> $trnFurnitureProductCatalogue->getMstProductType(),
            'trnFurnitureProductCatalogue'=> $trnFurnitureProductCatalogue,
        ]);
    }


    /**
     * @Route("/catalogue/{id}", name="catalogue_detail")
     * @param Request $request
     * @return Response
     */
    public function furnitureCatalogue(Request $request): Response
    {
        $id = $request->attributes->get('id');
        $trnFurnitureProductCatalogue = $this->managerRegistry->getRepository(TrnFurnitureProductCatalog::class)->find($id);
        return $this->render('portal/page/furniture/home_furniture/catalogue.html.twig', [
            'mstProducType'=> $trnFurnitureProductCatalogue->getMstProductType(),
            'trnFurnitureProductCatalogue'=> $trnFurnitureProductCatalogue,
        ]);
    }

    /**
     * @Route("/home-furniture/{slugName}", name="home_category")
     * @param Request $request
     * @return Response
     */
    public function furnitureHomeCategory(Request $request): Response
    {
        $categorySlugName = $request->attributes->get('slugName');
        $mstProductSubType = $this->managerRegistry->getRepository(MstProductSubType::class)->findOneBy(['isActive'=>1,'productSubTypeSlugName'=>$categorySlugName]);
        $mstFurnitureCategory = $this->managerRegistry->getRepository(MstFurnitureCategory::class)->getFurnitureCategoryProductSubTypeId($mstProductSubType->getId());
        return $this->render('portal/page/furniture/home_furniture/category.html.twig', [
            'mstFurnitureCategory'=> $mstFurnitureCategory,
            'mstProductSubType'=> $mstProductSubType,
        ]);
    }

    /**
     * @Route("/home-furniture/{mstProductSubTypeSlugName}/{mstFurnitureCategorySlugName}", name="home_product")
     * @param Request $request
     * @return Response
     */
    public function furnitureHomeProduct(Request $request): Response
    {
        $productSubTypeSlugName = $request->attributes->get('mstProductSubTypeSlugName');
        $mstFurnitureCategorySlugName = $request->attributes->get('mstFurnitureCategorySlugName');
        $mstProductSubType = $this->managerRegistry->getRepository(MstProductSubType::class)->findOneBy(['isActive'=>1,'productSubTypeSlugName'=>$productSubTypeSlugName]);
        $mstFurnitureCategory = $this->managerRegistry->getRepository(MstFurnitureCategory::class)->findOneBy(['isActive'=>1,'furnitureCategorySlugName'=>$mstFurnitureCategorySlugName]);
        $trnFurniture = $this->managerRegistry->getRepository(TrnFurniture::class)->findBy(['isActive'=>1,'mstProductSubType'=>$mstProductSubType,'mstFurnitureCategory'=> $mstFurnitureCategory]);
        return $this->render('portal/page/furniture/home_furniture/listing.html.twig', [
            'mstProductSubType'=> $mstProductSubType,
            'mstFurnitureCategory'=> $mstFurnitureCategory,
            'trnFurniture'=> $trnFurniture,
        ]);

    }

    /**
     * @Route("/home-furniture/{mstProductSubTypeSlugName}/{mstFurnitureCategorySlugName}/{id}", name="home_product_detail")
     * @param Request $request
     * @return Response
     */
    public function furnitureHomeProductDetail(Request $request): Response
    {
        $productSubTypeSlugName = $request->attributes->get('mstProductSubTypeSlugName');
        $mstFurnitureCategorySlugName = $request->attributes->get('mstFurnitureCategorySlugName');
        $id = $request->attributes->get('id');
        $mstProductSubType = $this->managerRegistry->getRepository(MstProductSubType::class)->findOneBy(['isActive'=>1,'productSubTypeSlugName'=>$productSubTypeSlugName]);
        $mstFurnitureCategory = $this->managerRegistry->getRepository(MstFurnitureCategory::class)->findOneBy(['isActive'=>1,'furnitureCategorySlugName'=>$mstFurnitureCategorySlugName]);
        $trnFurniture = $this->managerRegistry->getRepository(TrnFurniture::class)->find($id);
        return $this->render('portal/page/furniture/home_furniture/detail.html.twig', [
            'mstProductSubType'=> $mstProductSubType,
            'mstFurnitureCategory'=> $mstFurnitureCategory,
            'trnFurniture'=> $trnFurniture,
        ]);
    }

    /**
     * @Route("/office-furniture", name="office")
     * @param Request $request
     * @return Response
     */
    public function furnitureOffice(Request $request): Response
    {
        $slugName  = 'office-furniture';
        $mstProductType = $this->managerRegistry->getRepository(MstProductType::class)->findOneBy(["isActive"=>1,"productTypeSlugName"=>$slugName]);
        $mstProductSubType = $this->managerRegistry->getRepository(MstProductSubType::class)->getProductSubTypeByProductTypeId($mstProductType->getId());
        $mstFurnitureUniqueValuePreposition = $this->managerRegistry->getRepository(MstFurnitureUniqueValuePreposition::class)->findBy(["isActive"=>1,"mstProductType"=>$mstProductType]);
        $mstFurnitureProductCatalogue = $this->managerRegistry->getRepository(TrnFurnitureProductCatalog::class)->findBy(["isActive"=>1,"mstProductType"=>$mstProductType]);
        return $this->render('portal/page/furniture/office_furniture/index.html.twig', [
            'mstProductType'=> $mstProductType,
            'mstProductSubType'=> $mstProductSubType,
            'mstFurnitureUniqueValuePreposition'=> $mstFurnitureUniqueValuePreposition,
            'mstFurnitureProductCatalogues'=> $mstFurnitureProductCatalogue,
        ]);
    }

    /**
     * @Route("/office-furniture/{slugName}", name="office_category")
     * @param Request $request
     * @return Response
     */
    public function furnitureOfficeCategory(Request $request): Response
    {
        $categorySlugName = $request->attributes->get('slugName');
        $mstProductSubType = $this->managerRegistry->getRepository(MstProductSubType::class)->findOneBy(['isActive'=>1,'productSubTypeSlugName'=>$categorySlugName]);
        $mstFurnitureCategory = $this->managerRegistry->getRepository(MstFurnitureCategory::class)->getFurnitureCategoryProductSubTypeId($mstProductSubType->getId());
        return $this->render('portal/page/furniture/office_furniture/category.html.twig', [
            'mstFurnitureCategory'=> $mstFurnitureCategory,
            'mstProductSubType'=> $mstProductSubType,
        ]);
    }

    /**
     * @Route("/office-furniture/{mstProductSubTypeSlugName}/{mstFurnitureCategorySlugName}", name="office_product")
     * @param Request $request
     * @return Response
     */
    public function furnitureOfficeProduct(Request $request): Response
    {
        $productSubTypeSlugName = $request->attributes->get('mstProductSubTypeSlugName');
        $mstFurnitureCategorySlugName = $request->attributes->get('mstFurnitureCategorySlugName');
        $mstProductSubType = $this->managerRegistry->getRepository(MstProductSubType::class)->findOneBy(['isActive'=>1,'productSubTypeSlugName'=>$productSubTypeSlugName]);
        $mstFurnitureCategory = $this->managerRegistry->getRepository(MstFurnitureCategory::class)->findOneBy(['isActive'=>1,'furnitureCategorySlugName'=>$mstFurnitureCategorySlugName]);
        $trnFurniture = $this->managerRegistry->getRepository(TrnFurniture::class)->findBy(['isActive'=>1,'mstProductSubType'=>$mstProductSubType,'mstFurnitureCategory'=> $mstFurnitureCategory]);
        return $this->render('portal/page/furniture/office_furniture/listing.html.twig', [
            'mstProductSubType'=> $mstProductSubType,
            'mstFurnitureCategory'=> $mstFurnitureCategory,
            'trnFurniture'=> $trnFurniture,
        ]);

    }

    /**
     * @Route("/office-furniture/{mstProductSubTypeSlugName}/{mstFurnitureCategorySlugName}/{id}", name="office_product_detail")
     * @param Request $request
     * @return Response
     */
    public function furnitureOfficeProductDetail(Request $request): Response
    {
        $productSubTypeSlugName = $request->attributes->get('mstProductSubTypeSlugName');
        $mstFurnitureCategorySlugName = $request->attributes->get('mstFurnitureCategorySlugName');
        $id = $request->attributes->get('id');
        $mstProductSubType = $this->managerRegistry->getRepository(MstProductSubType::class)->findOneBy(['isActive'=>1,'productSubTypeSlugName'=>$productSubTypeSlugName]);
        $mstFurnitureCategory = $this->managerRegistry->getRepository(MstFurnitureCategory::class)->findOneBy(['isActive'=>1,'furnitureCategorySlugName'=>$mstFurnitureCategorySlugName]);
        $trnFurniture = $this->managerRegistry->getRepository(TrnFurniture::class)->find($id);
        return $this->render('portal/page/furniture/office_furniture/detail.html.twig', [
            'mstProductSubType'=> $mstProductSubType,
            'mstFurnitureCategory'=> $mstFurnitureCategory,
            'trnFurniture'=> $trnFurniture,
        ]);
    }

    /**
     * @Route("/lead-form", name="lead_form", methods={"GET","POST"} , priority="10")
     * @param Request $request
     * @param Mailer $mailer
     * @return Response
     */
    public function furnitureLeadForm(Request $request, Mailer $mailer): Response
    {
        $formEnquiry = new FormFurnitureEnquiry();
        $orgCompany = $this->managerRegistry->getRepository(OrgCompany::class)->find(1);
        $mstLeadStatus = $this->managerRegistry->getRepository(MstLeadStatus::class)->find(1);
        $mstProductCategory = $this->managerRegistry->getRepository(MstProductCategory::class)->findOneBy(['productCategorySlugName'=>"furniture","isActive"=>1]);
        $formEnquiry->setMstProductCategory($mstProductCategory);
        $formEnquiry->setOrgCompany($orgCompany);
        $formEnquiry->setMstLeadStatus($mstLeadStatus);
        $form = $this->createForm(FurnitureTredExpertsType::class, $formEnquiry);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $filter = $request->query->get('filter');
            if (isset($filter['mstProductType'])){
                $mstProductType =  $this->managerRegistry->getRepository(MstProductType::class)->find($filter['mstProductType']);
                $formEnquiry->setMstProductType($mstProductType);
            }
            if (isset($filter['mstProductSubType'])){
                $mstProductSubType =  $this->managerRegistry->getRepository(MstProductSubType::class)->find($filter['mstProductSubType']);
                $formEnquiry->setMstProductSubType($mstProductSubType);
                $formEnquiry->setMstProductType($mstProductSubType->getMstProductType()[0]);
            }
            if (isset($filter['mstFurnitureCategory'])){
                $mstFurnitureCategory =  $this->managerRegistry->getRepository(MstFurnitureCategory::class)->find($filter['mstFurnitureCategory']);
                $formEnquiry->setMstFurnitureCategory($mstFurnitureCategory);
                $formEnquiry->setMstProductType($mstFurnitureCategory->getMstProductType());
                $formEnquiry->setMstProductSubType($mstFurnitureCategory->getMstProductSubType());
            }
            $name = explode(" ",$form->get("furnitureEnquiryFullName")->getData());
            $formEnquiry->setMstState($form->get("mstCity")->getData()->getMstState());
            $formEnquiry->setFurnitureEnquiryFirstName($name[0]);
            $formEnquiry->setFurnitureEnquiryCreateTime(new \DateTime());
            if (isset($name[1])){
                $formEnquiry->setFurnitureEnquiryLastName($name[1]);
            }else{
                $formEnquiry->setFurnitureEnquiryLastName("-");
            }
            $entityManager = $this->managerRegistry->getManager();
            $entityManager->persist($formEnquiry);
            $entityManager->flush();
            $mailer->mailerFurnitureEnquiry($formEnquiry);
            return $this->redirectToRoute('portal_furniture_enquiry_success');
        }
        return $this->render('portal/page/furniture/lead.html.twig', [
            "form"=>$form->createView()
        ]);

    }

    /**
     * @Route("/form-enquiry-success", name="enquiry_success", methods={"GET"}, priority="10")
     * @return Response
     */
    public function formEnquirySuccess(Request $request): Response
    {
        return $this->render('portal/page/furniture/thank-you.html.twig', [
        ]);
    }

    /**
     * @Route("/lead-form-detail", name="lead_form_detail", methods={"GET","POST"} , priority="10")
     * @param Request $request
     * @param Mailer $mailer
     * @return Response
     */
    public function furnitureLeadFormDetail(Request $request, Mailer $mailer): Response
    {
        $formEnquiry = new FormFurnitureEnquiry();
        $orgCompany = $this->managerRegistry->getRepository(OrgCompany::class)->find(1);
        $mstLeadStatus = $this->managerRegistry->getRepository(MstLeadStatus::class)->find(1);
        $mstProductCategory = $this->managerRegistry->getRepository(MstProductCategory::class)->findOneBy(['productCategorySlugName'=>"furniture","isActive"=>1]);
        $formEnquiry->setMstProductCategory($mstProductCategory);
        $formEnquiry->setOrgCompany($orgCompany);
        $formEnquiry->setMstLeadStatus($mstLeadStatus);
        $form = $this->createForm(FurnitureTredExpertsType::class, $formEnquiry);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $filter = $request->get('filter');
            if (isset($filter['trnFurniture'])){
                $trnFurniture =  $this->managerRegistry->getRepository(TrnFurniture::class)->find($filter['trnFurniture']);
                $formEnquiry->setTrnFurniture($trnFurniture);
                $formEnquiry->setMstFurnitureCategory($trnFurniture->getMstFurnitureCategory());
                $formEnquiry->setMstProductType($trnFurniture->getMstProductType());
                $formEnquiry->setMstProductSubType($trnFurniture->getMstProductSubType());
            }
            $name = explode(" ",$form->get("furnitureEnquiryFullName")->getData());
            $formEnquiry->setMstState($form->get("mstCity")->getData()->getMstState());
            $formEnquiry->setFurnitureEnquiryFirstName($name[0]);
            $formEnquiry->setFurnitureEnquiryCreateTime(new \DateTime());
            if (isset($name[1])){
                $formEnquiry->setFurnitureEnquiryLastName($name[1]);
            }else{
                $formEnquiry->setFurnitureEnquiryLastName("-");
            }
            $entityManager = $this->managerRegistry->getManager();
            $entityManager->persist($formEnquiry);
            $entityManager->flush();
            $mailer->mailerFurnitureEnquiry($formEnquiry);
            return $this->redirectToRoute('portal_furniture_enquiry_success');
        }
        return $this->render('portal/page/furniture/detail_lead.html.twig', [
            "form"=>$form->createView()
        ]);

    }

    /**
     * @Route("/lead-form-catalogue", name="lead_form_catalog", methods={"GET","POST"} , priority="10")
     * @param Request $request
     * @param Mailer $mailer
     * @return Response
     */
    public function furnitureLeadFormCatalog(Request $request, Mailer $mailer): Response
    {
        $formEnquiry = new FormFurnitureEnquiry();
        $orgCompany = $this->managerRegistry->getRepository(OrgCompany::class)->find(1);
        $mstLeadStatus = $this->managerRegistry->getRepository(MstLeadStatus::class)->find(1);
        $mstProductCategory = $this->managerRegistry->getRepository(MstProductCategory::class)->findOneBy(['productCategorySlugName'=>"furniture","isActive"=>1]);
        $formEnquiry->setMstProductCategory($mstProductCategory);
        $formEnquiry->setOrgCompany($orgCompany);
        $formEnquiry->setMstLeadStatus($mstLeadStatus);
        $form = $this->createForm(FurnitureTredExpertsType::class, $formEnquiry);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $filter = $request->query->get('filter');
            if (isset($filter['trnFurnitureProductCatalog'])){
                $trnFurnitureProductCatalog =  $this->managerRegistry->getRepository(TrnFurnitureProductCatalog::class)->find($filter['trnFurnitureProductCatalog']);
                $formEnquiry->setTrnFurnitureProductCatalog($trnFurnitureProductCatalog);
                $formEnquiry->setMstProductType($trnFurnitureProductCatalog->getMstProductType());
            }
            $name = explode(" ",$form->get("furnitureEnquiryFullName")->getData());
            $formEnquiry->setMstState($form->get("mstCity")->getData()->getMstState());
            $formEnquiry->setFurnitureEnquiryFirstName($name[0]);
            $formEnquiry->setFurnitureEnquiryCreateTime(new \DateTime());
            if (isset($name[1])){
                $formEnquiry->setFurnitureEnquiryLastName($name[1]);
            }else{
                $formEnquiry->setFurnitureEnquiryLastName("-");
            }
            $entityManager = $this->managerRegistry->getManager();
            $entityManager->persist($formEnquiry);
            $entityManager->flush();
            $mailer->mailerFurnitureProductCataglogEnquiry($formEnquiry);
            return $this->redirectToRoute('portal_furniture_enquiry_success');
        }
        return $this->render('portal/page/furniture/catalog_lead.html.twig', [
            "form"=>$form->createView()
        ]);

    }
}

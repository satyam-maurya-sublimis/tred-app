<?php

namespace App\Twig\Transaction;

use App\Entity\Transaction\TrnProject;
use App\Service\CommonHelper;
use App\Twig\Common\CommonExtension;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class TrnProjectExtension extends AbstractExtension
{
    private $em;
    private $commonHelper;
    private $session;
    private $params;

    public function __construct(EntityManagerInterface $em, CommonHelper $commonHelper, RequestStack $session, ParameterBagInterface $params)
    {
        $this->em = $em;
        $this->commonHelper = $commonHelper;
        $this->session = $session;
        $this->params = $params;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('get_project_cities', [$this, 'getProjectCities']),
            new TwigFunction('get_project', [$this, 'getProject']),
            new TwigFunction('get_project_product_type', [$this, 'getProjectProductType']),
            new TwigFunction('get_project_project_type', [$this, 'getProjectProjectType']),
            new TwigFunction('get_project_status_by_project', [$this, 'getProjectStatus']),
            new TwigFunction('get_project_vendor_by_project', [$this, 'getProjectVendor']),
            new TwigFunction('get_project_possession_by_project', [$this, 'getProjectPossession']),
            new TwigFunction('get_other_project', [$this, 'getOtherProject']),
        ];
    }

    public function getProjectCities()
    {
        return $this->em->getRepository(TrnProject::class)->getProjectCities();
    }

    public function getProject($id = null,$filters = array())
    {
        $trn_projects = $this->em->getRepository(TrnProject::class)->getProject($id,$filters);
//        return $this->commonHelper->getProjectData($trn_projects);
        return $trn_projects;
    }

    public function getProjectProductType()
    {
        return $this->em->getRepository(TrnProject::class)->getProjectProductType();
    }

    public function getProjectProjectType()
    {
        return $this->em->getRepository(TrnProject::class)->getProjectProjectType();
    }

    public function getProjectStatus()
    {
        $projectStatuses = $this->em->getRepository(TrnProject::class)->getProjectStatus();
        $prjStatus = [];
        foreach($projectStatuses as $projectStatus){
            $mstProductCategory = $projectStatus->getMstProductCategory();
            $mstProductType = $projectStatus->getMstProductType();
            $mstProjectStatus = $projectStatus->getMstProjectStatus();
//            $mstProjectType = $projectStatus->getMstPropertyType();
            $prjStatus[$mstProductCategory->getId()][$mstProductType->getId()]["name"] = $mstProductType->getProductType();
            $prjStatus[$mstProductCategory->getId()][$mstProductType->getId()]["slugName"] = $mstProductType->getProductTypeSlugName();
            $prjStatus[$mstProductCategory->getId()][$mstProductType->getId()]["data"][$mstProjectStatus->getId()] = $mstProjectStatus->getPropertyType();
//            $prjStatus[$projectStatus['id']]['name'] = $projectStatus['projectType'];
//            $prjStatus[$projectStatus['id']]['slugName'] = $projectStatus['slugName'];
//            $prjStatus[$projectStatus['id']]["data"][$projectStatus['id_1']] = $projectStatus['propertyType'];
        }
        return $prjStatus;

    }

    public function getProjectVendor(){
        return $this->em->getRepository(TrnProject::class)->getProjectVendor();
    }

    public function getProjectPossession(){
        return $this->em->getRepository(TrnProject::class)->getProjectPossession();
    }

    public function getOtherProject($trnVendorPartnerDetails,$projectId)
    {
        $vendorId = $trnVendorPartnerDetails->getId();
        return $this->em->getRepository(TrnProject::class)->getOtherProject($vendorId, $projectId);
    }
    public function getWigetSearch()
    {
        $filters = [];
        if ($this->session->has('city')){
            $city = $this->session->get('city');
        }else{
            $city = $this->params->get('city');
            $this->session->set('city',$city);
        }
        $filters['mstCity'] = ucfirst($city);
        $trn_projects = $this->getProject($id = null,$filters);
        $search = [];
        $wsearch = [];
        foreach($trn_projects as $trnProject){
            foreach($trnProject->getTrnProjectRoomConfigurations() as $keys=>$trnProjectRoomConfigurations){
                $propertyTransactionCategory = $trnProjectRoomConfigurations->getMstPropertyTransactionCategory()->getPropertyTransactionCategory();
                $propertyTransactionCategoryId = $trnProjectRoomConfigurations->getMstPropertyTransactionCategory()->getId();
                $search[$propertyTransactionCategoryId]["propertyTransactionCategoryId"] = $propertyTransactionCategoryId;
                $search[$propertyTransactionCategoryId]["propertyTransactionCategory"] = $propertyTransactionCategory;
                $search[$propertyTransactionCategoryId]["productCategory"][$trnProject->getMstProductCategory()->getId()]=$trnProject->getMstProductCategory()->getProductCategory();
                $search[$propertyTransactionCategoryId]["productType"][$trnProject->getMstProductType()->getId()]=$trnProject->getMstProductType()->getProductType();
                $search[$propertyTransactionCategoryId]["productSubType"][$trnProject->getMstProductSubType()->getId()]=$trnProject->getMstProductSubType()->getProductSubType();
                $search[$propertyTransactionCategoryId]["projectStatus"][$trnProject->getMstProjectStatus()->getId()] = $trnProject->getMstProjectStatus()->getPropertyType();
                $search[$propertyTransactionCategoryId]["city"][$trnProject->getMstCity()->getId()] = $trnProject->getMstCity()->getCity();
                $search[$propertyTransactionCategoryId]["cityLocation"][$trnProject->getMstPincode()->getId()] = $trnProject->getMstPincode()->getOfficeName();
                $search[$propertyTransactionCategoryId]["roomConfigurations"][$trnProjectRoomConfigurations->getMstRoomConfiguration()->getId()] = $trnProjectRoomConfigurations->getMstRoomConfiguration()->getRoomConfiguration();
                if ($propertyTransactionCategory == "Buy"){
                    $search[$propertyTransactionCategoryId]["budget"][] =[
                        'amount'=>$trnProjectRoomConfigurations->getAgreementAmount(),
                        'denomination'=>$trnProjectRoomConfigurations->getMstDenomination()->getDenomination()
                    ];
                }else{
//                    $search[$propertyTransactionCategoryId]["budget"][$trnProjectRoomConfigurations->getMstRoomConfiguration()->getId()] = $trnProjectRoomConfigurations->getRentPerMonth();
                    $search[$propertyTransactionCategoryId]["budget"][] = $trnProjectRoomConfigurations->getRentPerMonth();
                }
//                if ($trnProject->getMstProductCategory()->getProductCategory() == "Commercial"){
//                    $search[99]["propertyTransactionCategoryId"]    = $propertyTransactionCategoryId;
//                    $search[99]["propertyTransactionCategory"] = $propertyTransactionCategory;
//                    $search[99]["productCategory"][$trnProject->getMstProductCategory()->getId()]=$trnProject->getMstProductCategory()->getProductCategory();
//                    $search[99]["productType"][$trnProject->getMstProductType()->getId()]=$trnProject->getMstProductType()->getProductType();
//                    $search[99]["productSubType"][$trnProject->getMstProductSubType()->getId()]=$trnProject->getMstProductSubType()->getProductSubType();
//                    $search[99]["projectStatus"][$trnProject->getMstProjectStatus()->getId()] = $trnProject->getMstProjectStatus()->getPropertyType();
//                    $search[99]["city"][$trnProject->getMstCity()->getId()] = $trnProject->getMstCity()->getCity();
//                    $search[99]["cityLocation"][$trnProject->getMstPincode()->getId()] = $trnProject->getMstPincode()->getOfficeName();
//                    $search[99]["roomConfigurations"][$trnProjectRoomConfigurations->getMstRoomConfiguration()->getId()] = $trnProjectRoomConfigurations->getMstRoomConfiguration()->getRoomConfiguration();
//                }else{
//                    $wsearch = $search;
//                }
            }
        }
        $wsearch = $search;
        ksort($wsearch);
        return $wsearch;
    }
//    public function getPossessionDate($projects){
////        $possessionDates = [];
////        foreach($projects as $project){
////            if($project->getPossessionDate()){
////                $possessionDates[] = $project->getPossessionDate()->format("y-m-d");
////            }elseif ($project->getPossessionMonth() && $project->getPossessionYear()){
////                $month = $project->getPossessionMonth();
////                $year = $project->getPossessionYear();;
////                $newDate = date("Y-m-d", strtotime($year."-".$month."-"."01"));
////                $possessionDates[] = $newDate;
////            }
////        }
////        foreach($possessionDates as $possessionDate ) {
//            $validPossessionDate = [];
//            $today = date('Y-m-d', strtotime("today"));
////            $projectDate = date('Y-m-d', strtotime($possessionDate));
//            $months3 = date('Y-m-d', strtotime("today +3 months"));
//            $months6 = date('Y-m-d', strtotime("today +6 months"));
//            $year1 = date('Y-m-d', strtotime("today +1 year"));
//            $year2 = date('Y-m-d', strtotime("today +2 year"));
//            $year3 = date('Y-m-d', strtotime("today +3 year"));
////            if ($projectDate <= $months3 ){
////                $validPossessionDate["3m"]= $today."|".$months3;
////            }elseif($projectDate <= $months6){
////                $validPossessionDate["6m"]= $today."|".$months6;
////            }elseif($projectDate <= $year1){
////                $validPossessionDate["1y"]= $today."|".$year1;
////            }elseif($projectDate <= $year2){
////                $validPossessionDate["2y"]= $today."|".$year2;
////            }elseif($projectDate <= $year3){
////                $validPossessionDate["3y"]= $today."|".$year3;
////            }elseif($projectDate > $year3){
////                $validPossessionDate["3y+"]= $year3."|";
////            }
//                $validPossessionDate["3m"]= $today."|".$months3;
//                $validPossessionDate["6m"]= $today."|".$months6;
//                $validPossessionDate["1y"]= $today."|".$year1;
//                $validPossessionDate["2y"]= $today."|".$year2;
//                $validPossessionDate["3y"]= $today."|".$year3;
//                $validPossessionDate["3y+"]= $year3."|";
////        }
//        return $validPossessionDate;
//    }
    public function getPossessionDateFormat($value){
        $format = "";
        if ($value == "3m" ){
            $format= "3 Months";
        }elseif($value == "6m"){
            $format= "6 Months";
        }elseif($value == "1y"){
            $format= "1 Year";
        }elseif($value == "2y"){
            $format= "2 Year";
        }elseif($value == "3y"){
            $format= "3 Year";
        }elseif($value == "3y+"){
            $format= "3+ Year";
        }
        return $format;
    }
}

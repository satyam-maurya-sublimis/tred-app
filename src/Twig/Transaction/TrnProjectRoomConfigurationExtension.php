<?php

namespace App\Twig\Transaction;

use App\Entity\Transaction\TrnProject;
use App\Entity\Transaction\TrnProjectRoomConfiguration;
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

class TrnProjectRoomConfigurationExtension extends AbstractExtension
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
            new TwigFunction('get_wiget_search', [$this, 'getWigetSearch']),
            new TwigFunction('get_available_date', [$this, 'getAvailableDate']),
            new TwigFunction('get_available_date_format', [$this, 'getAvailableDateFormat']),
            new TwigFunction('get_room_configurations', [$this, 'getRoomConfiguration']),
            new TwigFunction('get_additional_detail_type', [$this, 'getAdditionalDetailType']),
        ];
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
        $trnProjectRoomConfigurations = $this->em->getRepository(TrnProjectRoomConfiguration::class)->getRoomConfiguration($filters);
        $search = [];
        $wsearch = [];
        foreach($trnProjectRoomConfigurations as $trnProjectRoomConfiguration){
                $propertyTransactionCategory = $trnProjectRoomConfiguration->getMstPropertyTransactionCategory()->getPropertyTransactionCategory();
                $propertyTransactionCategoryId = $trnProjectRoomConfiguration->getMstPropertyTransactionCategory()->getId();
                $search[$propertyTransactionCategoryId]["propertyTransactionCategoryId"] = $propertyTransactionCategoryId;
                $search[$propertyTransactionCategoryId]["propertyTransactionCategory"] = $propertyTransactionCategory;
                $search[$propertyTransactionCategoryId]["productCategory"][$trnProjectRoomConfiguration->getTrnProject()->getMstProductCategory()->getId()]=$trnProjectRoomConfiguration->getTrnProject()->getMstProductCategory()->getProductCategory();
                $search[$propertyTransactionCategoryId]["productType"][$trnProjectRoomConfiguration->getTrnProject()->getMstProductType()->getId()]=$trnProjectRoomConfiguration->getTrnProject()->getMstProductType()->getProductType();
                $search[$propertyTransactionCategoryId]["productSubType"][$trnProjectRoomConfiguration->getTrnProject()->getMstProductSubType()->getId()]=$trnProjectRoomConfiguration->getTrnProject()->getMstProductSubType()->getProductSubType();
                $search[$propertyTransactionCategoryId]["projectStatus"][$trnProjectRoomConfiguration->getTrnProject()->getMstProjectStatus()->getId()] = $trnProjectRoomConfiguration->getTrnProject()->getMstProjectStatus()->getPropertyType();
                $search[$propertyTransactionCategoryId]["city"][$trnProjectRoomConfiguration->getTrnProject()->getMstCity()->getId()] = $trnProjectRoomConfiguration->getTrnProject()->getMstCity()->getCity();
                $search[$propertyTransactionCategoryId]["cityLocation"][$trnProjectRoomConfiguration->getTrnProject()->getMstPincode()->getId()] = $trnProjectRoomConfiguration->getTrnProject()->getMstPincode()->getOfficeName();
                $search[$propertyTransactionCategoryId]["roomConfigurations"][$trnProjectRoomConfiguration->getMstRoomConfiguration()->getId()] = $trnProjectRoomConfiguration->getMstRoomConfiguration()->getRoomConfiguration();
                if ($propertyTransactionCategory == "Buy"){
                    $search[$propertyTransactionCategoryId]["budget"][] =[
                        'amount'=>$trnProjectRoomConfiguration->getAgreementAmount(),
                        'denomination'=>$trnProjectRoomConfiguration->getMstDenomination()->getDenomination()
                    ];
                }else{
                    $search[$propertyTransactionCategoryId]["budget"][] = $trnProjectRoomConfiguration->getRentPerMonth();
                }
        }
        $wsearch = $search;
        ksort($wsearch);
        return $wsearch;
    }
    public function getAvailableDate(){
        $validPossessionDate = [];
        $today = date('Y-m-d', strtotime("today"));
        $day15 = date('Y-m-d', strtotime("today +15 Days"));
        $day30 = date('Y-m-d', strtotime("today +30 Days"));
        $day31 = date('Y-m-d', strtotime("today +31 Days"));
        $validPossessionDate["0d"]= "|".$today;
        $validPossessionDate["15d"]= $today."|".$day15;
        $validPossessionDate["30d"]= $day15."|".$day30;
        $validPossessionDate["31d"]= $day31."|";
        return $validPossessionDate;
    }
    public function getAvailableDateFormat($value){
        $format = "";
        if ($value == "0d" ){
            $format= "Immediate";
        }elseif($value == "15d"){
            $format= "Within 15 Days";
        }elseif($value == "30d"){
            $format= "Within 30 Days";
        }elseif($value == "31d"){
            $format= "After 30 Days";
        }
        return $format;
    }

    public function getRoomConfiguration($filters = array())
    {
        $trn_room_configurations = $this->em->getRepository(TrnProjectRoomConfiguration::class)->getRoomConfiguration($filters);
        return $trn_room_configurations;
    }
    public function getAdditionalDetailType($value)
    {
        $trn_additional_detail_type = $this->commonHelper->additionalDetailType();
        return array_search($value,$trn_additional_detail_type,true);
    }
}

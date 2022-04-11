<?php

namespace App\Repository\Transaction;

use App\Entity\Transaction\TrnProjectRoomConfiguration;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TrnProjectRoomConfiguration|null find($id, $lockMode = null, $lockVersion = null)
 * @method TrnProjectRoomConfiguration|null findOneBy(array $criteria, array $orderBy = null)
 * @method TrnProjectRoomConfiguration[]    findAll()
 * @method TrnProjectRoomConfiguration[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TrnProjectRoomConfigurationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TrnProjectRoomConfiguration::class);
    }

    public function getRoomConfiguration($filters = array()) {
        $order = "ASC";
        $dql = $this->createQueryBuilder('c')
            ->innerJoin('c.trnProject','a')
        ;

        if (isset($filters["sort"])){
            if ($filters["sort"]=="High"){
                $order = "DESC";
            }
            if ($filters["sort"]=="Low"){
                $order = "ASC";
            }
            if ($filters["sort"]=="Tred"){
                $dql->andWhere('a.isTredRecommended = :tred')
                    ->setParameter('tred', 1);
            }
        }
        if (isset($filters['slugName'])){
            $dql->innerJoin('a.mstProductType','e')
                ->andWhere('e.productTypeSlugName = :slugName')
                ->andWhere('e.isActive = :active')
                ->setParameter('slugName', $filters['slugName']);
        }
        if (isset($filters['productCategory'])){
            $dql->innerJoin('a.mstProductCategory','f')
                ->andWhere('f.productCategorySlugName = :cslugName')
                ->andWhere('f.isActive = :active')
                ->setParameter('cslugName', $filters['productCategory']);
        }
        if (isset($filters['propertyTypeId'])){
            $dql->innerJoin('a.mstPropertyType','g')
                ->andWhere('g.id = :id')
                ->andWhere('g.isActive = :active')
                ->setParameter('id', $filters['propertyTypeId']);
        }
        if (isset($filters['productSubType'])){
            $dql->innerJoin('a.mstProductSubType','h')
                ->andWhere('h.id in (:productSubType)')
                ->andWhere('h.isActive = :active')
                ->setParameter('productSubType', $filters['productSubType']);
        }
        if (isset($filters['projectStatus'])){
            $dql->innerJoin('a.mstProjectStatus','i')
                ->andWhere('i.id in (:projectStatus)')
                ->andWhere('i.isActive = :active')
                ->setParameter('projectStatus', $filters['projectStatus']);
        }
        if (isset($filters['projectPostedBy'])){
            $dql->innerJoin('a.trnVendorPartnerDetails','l')
                ->innerJoin('l.mstVendorType','m')
                ->andWhere('m.id in (:projectPostedBy)')
                ->andWhere('m.isActive = :active')
                ->andWhere('l.isActive = :active')
                ->setParameter('projectPostedBy', $filters['projectPostedBy']);
        }
        if (isset($filters['projectAmenities'])){
            $dql->innerJoin('a.trnProjectAmenities','n')
                ->innerJoin('n.mstProjectAmenities','o')
                ->andWhere('o.id in (:projectAmenities)')
                ->andWhere('o.isActive = :active')
                ->andWhere('n.isActive = :active')
                ->setParameter('projectAmenities', $filters['projectAmenities']);
        }
        if (isset($filters['noOfBathRooms'])){
            $dql->andWhere('c.noOfBathRooms in (:noOfBathRooms)')
                ->setParameter('noOfBathRooms', $filters['noOfBathRooms']);
        }
        if (isset($filters['propertyAreaValue'])){
            if ($filters['propertyAreaRange'] == "Min"){
                $dql->andWhere('c.areaValue <= :propertyAreaValue')
                    ->setParameter('propertyAreaValue', $filters['propertyAreaValue']);
            }elseif ($filters['propertyAreaRange'] == "Max"){
                $dql->andWhere('c.areaValue >= :propertyAreaValue')
                    ->setParameter('propertyAreaValue', $filters['propertyAreaValue']);
            }
        }

        if (isset($filters['priceRangeMin']) && isset($filters['priceRangeMax']) ){
            if (isset($filters['propertyTransactionCategoryId'])){
                if ($filters['propertyTransactionCategoryId'] == 1){
                    $dql->andWhere('c.agreementAmount between :minPrice and :maxPrice')
                        ->setParameter('minPrice', $filters['priceRangeMin'])
                        ->setParameter('maxPrice', $filters['priceRangeMax']);
                }else if($filters['propertyTransactionCategoryId'] == 2) {
                    $dql->andWhere('c.rentPerMonth between :minPrice and :maxPrice')
                        ->setParameter('minPrice', $filters['priceRangeMin'])
                        ->setParameter('maxPrice', $filters['priceRangeMax']);
                }
            }else{
                $dql->andWhere('c.agreementAmount between :minPrice and :maxPrice')
                    ->setParameter('minPrice', $filters['priceRangeMin'])
                    ->setParameter('maxPrice', $filters['priceRangeMax']);
            }
        }
        if (isset($filters['propertyTransactionCategoryId'])){
            $dql->innerJoin('c.mstPropertyTransactionCategory','q')
                ->andWhere('q.id = :propertyTransactionCategoryId')
                ->andWhere('q.isActive = :active')
                ->setParameter('propertyTransactionCategoryId', $filters['propertyTransactionCategoryId']);
        }

        if (isset($filters['projectPossessions'])){
            foreach($filters['projectPossessions'] as $possessionDateKey=>$possessionDate ){
                $dateDataKey = explode("~",$possessionDate);
                $dateDataValue = explode("|",$dateDataKey[1]);
                if ($dateDataKey[0] !='3y+'){
                    $fromDate = $dateDataValue[0];
                    $toDate = $dateDataValue[1];
                }else{
                    $fromDate = $dateDataValue[0];
                }
                if(isset($toDate)){
                    if ($possessionDateKey == 0){
                        $dql->andWhere('a.actualPossessionDate between :fromDate and :toDate')
                            ->setParameter('fromDate', $fromDate)
                            ->setParameter('toDate', $toDate);
                    }else{
                        $dql->orWhere('a.actualPossessionDate between :fromDate and :toDate')
                            ->setParameter('fromDate', $fromDate)
                            ->setParameter('toDate', $toDate);
                    }
                }else{
                    if ($possessionDateKey == 0){
                        $dql->andWhere('a.actualPossessionDate > :fromDate')
                            ->setParameter('fromDate', $fromDate);
                    }else{
                        $dql->orWhere('a.actualPossessionDate > :fromDate')
                            ->setParameter('fromDate', $fromDate);
                    }

                }
            }
        }

        if (isset($filters['projectAvailabilityFromDate'])){
            foreach($filters['projectAvailabilityFromDate'] as $availiableDate ){
                $fromDateLabel = explode("~",$availiableDate);
                $fromDateSwitchCase = explode("|",$fromDateLabel[1]);
                if ($fromDateSwitchCase[0] == "L"){
                    $dql->andWhere('c.availabilityFromDate < :availabilityFromDate')
                        ->setParameter('availabilityFromDate', $fromDateSwitchCase[1]);
                }elseif($fromDateSwitchCase[0] == "G"){
                    $dql->andWhere('c.availabilityFromDate > :availabilityFromDate')
                        ->setParameter('availabilityFromDate', $fromDateSwitchCase[1]);
                }else{
                    $dql->andWhere('c.availabilityFromDate between :availabilityFromDateFirstDate and :availabilityFromDateSecondDate ')
                        ->setParameter('availabilityFromDateavailabilityFromDateFirstDate', $fromDateSwitchCase[1])
                        ->setParameter('availabilityFromDateSecondDate', $fromDateSwitchCase[2]);
                }
            }
        }
        if (isset($filters['projectRoomConfigurations'])){
            $dql->innerJoin('c.mstRoomConfiguration','t')
                ->andWhere('t.id in (:projectRoomConfigurations)')
                ->andWhere('t.isActive = :active')
                ->setParameter('projectRoomConfigurations', $filters['projectRoomConfigurations']);
        }
        if (isset($filters['mstPincode'])){
            $dql->innerJoin('a.mstPincode','r')
                ->andWhere('r.id in (:mstpincode)')
                ->setParameter('mstpincode', $filters['mstPincode']);
        }

        if (isset($filters['isTopVendorPartners'])) {
            if (isset($filters['trnVendorPartnersId'])) {
                $dql->innerJoin('a.trnVendorPartnerDetails', 'd')
                    ->andWhere('d.id = :trnVendorPartnerDetailsId')
                    ->setParameter('trnVendorPartnerDetailsId', $filters['trnVendorPartnersId']);
            }
        }else{
            if (isset($filters['mstCity'])) {
                $dql->innerJoin('a.mstCity', 'd')
                    ->andWhere('d.city = :city')
                    ->setParameter('city', $filters['mstCity']);
            }
        }
        if (isset($filters['propertyTransactionCategoryId'])){
            if ($filters['propertyTransactionCategoryId'] == 1){
                if (isset($filters["sort"]) && $filters["sort"]=="Recent"){
                    $order = "DESC";
                    return $dql
                        ->andWhere('c.isActive = :active')
                        ->andWhere('a.isActive = :active')
                        ->setParameter('active', 1)
                        ->orderby('c.createdOn',$order)
                        ->getQuery()
                        ->getResult();
                }else{
                    return $dql
                        ->andWhere('c.isActive = :active')
                        ->andWhere('a.isActive = :active')
                        ->setParameter('active', 1)
                        ->orderby('c.agreementAmount',$order)
                        ->getQuery()
                        ->getResult();
                }
            }else{
                if (isset($filters["sort"]) && $filters["sort"]=="Recent"){
                    $order = "DESC";
                    return $dql
                        ->andWhere('c.isActive = :active')
                        ->andWhere('a.isActive = :active')
                        ->setParameter('active', 1)
                        ->orderby('c.createdOn', $order)
                        ->getQuery()
                        ->getResult();
                }else {
                    return $dql
                        ->andWhere('c.isActive = :active')
                        ->andWhere('a.isActive = :active')
                        ->setParameter('active', 1)
                        ->orderby('c.rentPerMonth', $order)
                        ->getQuery()
                        ->getResult();
                }
            }
        }else{
            if (isset($filters["sort"]) && $filters["sort"]=="Recent"){
                $order = "DESC";
                return $dql
                    ->andWhere('c.isActive = :active')
                    ->andWhere('a.isActive = :active')
                    ->setParameter('active', 1)
                    ->orderby('c.createdOn', $order)
                    ->getQuery()
                    ->getResult();
            }else {
                return $dql
                    ->andWhere('c.isActive = :active')
                    ->andWhere('a.isActive = :active')
                    ->setParameter('active', 1)
                    ->orderby('c.agreementAmount', $order)
                    ->getQuery()
                    ->getResult();
            }
        }
    }

    public function getUniqueRoomConfiguration($filters = array()) {
        $dql = $this->createQueryBuilder('c')
            ->innerJoin('c.trnProject','a')
        ;
        return $dql
            ->andWhere('c.isActive = :active')
            ->andWhere('a.isActive = :active')
            ->andWhere('a.id = :projectId')
//            ->andWhere('a.createdBy = c.createdBy')
            ->setParameter('active', 1)
            ->setParameter('projectId', $filters['trnProjectId'])
            ->getQuery()
            ->getResult();
    }

    public function getRoomConfigurationByTopAgent($filters = array()) {
        $dql = $this->createQueryBuilder('c')
            ->innerJoin('c.trnProject','a')
        ;
        return $dql
            ->andWhere('c.isActive = :active')
            ->andWhere('a.isActive = :active')
            ->andWhere('a.id = :projectId')
            ->setParameter('active', 1)
            ->setParameter('projectId', $filters['trnProjectId'])
            ->getQuery()
            ->getResult();
    }

    public function getRoomConfigurationByTopVendorPartners($filters = array()) {
        $dql = $this->createQueryBuilder('c')
            ->innerJoin('c.trnProject','a')
            ->innerJoin('a.trnVendorPartnerDetails','b')
        ;
        return $dql
            ->andWhere('c.isActive = :active')
            ->andWhere('a.isActive = :active')
            ->andWhere('b.isActive = :active')
            ->andWhere('b.id = :trnVendorPartnerDetailId')
            ->setParameter('active', 1)
            ->setParameter('trnVendorPartnerDetailId', $filters['trnVendorPartnerDetailsId'])
            ->getQuery()
            ->getResult();
    }
}

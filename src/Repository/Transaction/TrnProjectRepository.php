<?php

namespace App\Repository\Transaction;

use App\Entity\Transaction\TrnProject;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TrnProject|null find($id, $lockMode = null, $lockVersion = null)
 * @method TrnProject|null findOneBy(array $criteria, array $orderBy = null)
 * @method TrnProject[]    findAll()
 * @method TrnProject[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TrnProjectRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TrnProject::class);
    }

    public function getProjectDetails($projectId) {
        $dql = $this->createQueryBuilder('c')
            ->select('og.company', 'pc.productCategory', 'pc.productCategory', 'pt.productType',
                'pst.productSubType', 'vpd.vendorPartnerName', 'mc.country', 'ms.state', 'mct.city',
                'mac.area', 'ps.propertyType as projectStatus', 'mpt.projectType as propertyType', 'c.possessionOn')
            ->innerJoin('c.orgCompany', 'og')
            ->innerJoin('c.mstProductCategory', 'pc')
            ->innerJoin('c.mstProductType', 'pt')
            ->innerJoin('c.mstProductSubType', 'pst')
            ->innerJoin('c.trnVendorPartnerDetails', 'vpd')
            ->innerJoin('c.mstCountry', 'mc')
            ->innerJoin('c.mstState', 'ms')
            ->innerJoin('c.mstCity', 'mct')
            ->innerJoin('c.mstAreaInCity', 'mac')
            ->innerJoin('c.mstProjectStatus', 'ps')
            ->innerJoin('c.mstPropertyType', 'mpt')
            ->where('c.id = :val')
            ->setParameter('val', $projectId)
            ->getQuery()
            ->getResult()
        ;
        return $dql;
    }

    public function getProject($projectId = null,$filters = array()) {
        $dql = $this->createQueryBuilder('c')
            ->innerJoin('c.trnProjectRoomConfigurations','a');

        if($projectId){
            $dql->andWhere('c.id = :val')
                ->setParameter('val', $projectId);

        }
        $order = "ASC";
        if($filters){
            if (isset($filters["sort"])){
                if ($filters["sort"]=="High"){
                    $order = "DESC";
                }
                if ($filters["sort"]=="Low"){
                    $order = "ASC";
                }
                if ($filters["sort"]=="Tred"){
                    $dql->andWhere('c.isTredRecommended = :tred')
                        ->setParameter('tred', 1);
                }
//                if ($filters["sort"]=="Recent"){
//                    $dql->andWhere('c.isNewProperty = :newproperty')
//                        ->setParameter('newproperty', 1);
//                }
            }
//            if (isset($filters['slugName'])){
//                $dql->innerJoin('c.mstPropertyType','mpt')
//                    ->andWhere('mpt.slugName = :slugName')
//                    ->andWhere('mpt.isActive = :active')
//                    ->setParameter('slugName', $filters['slugName']);
//            }
            if (isset($filters['slugName'])){
                $dql->innerJoin('c.mstProductType','e')
                    ->andWhere('e.productTypeSlugName = :slugName')
                    ->andWhere('e.isActive = :active')
                    ->setParameter('slugName', $filters['slugName']);
            }
            if (isset($filters['productCategory'])){
                $dql->innerJoin('c.mstProductCategory','f')
                    ->andWhere('f.productCategorySlugName = :cslugName')
                    ->andWhere('f.isActive = :active')
                    ->setParameter('cslugName', $filters['productCategory']);
            }
            if (isset($filters['propertyTypeId'])){
                $dql->innerJoin('c.mstPropertyType','g')
                    ->andWhere('g.id = :id')
                    ->andWhere('g.isActive = :active')
                    ->setParameter('id', $filters['propertyTypeId']);
            }
            if (isset($filters['productSubType'])){
                $dql->innerJoin('c.mstProductSubType','h')
                    ->andWhere('h.id in (:productSubType)')
                    ->andWhere('h.isActive = :active')
                    ->setParameter('productSubType', $filters['productSubType']);
            }
            if (isset($filters['projectStatus'])){
                $dql->innerJoin('c.mstProjectStatus','i')
                    ->andWhere('i.id in (:projectStatus)')
                    ->andWhere('i.isActive = :active')
                    ->setParameter('projectStatus', $filters['projectStatus']);
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
                            $dql->andWhere('c.actualPossessionDate between :fromDate and :toDate')
                                ->setParameter('fromDate', $fromDate)
                                ->setParameter('toDate', $toDate);
                        }else{
                            $dql->orWhere('c.actualPossessionDate between :fromDate and :toDate')
                                ->setParameter('fromDate', $fromDate)
                                ->setParameter('toDate', $toDate);
                        }
                    }else{
                        if ($possessionDateKey == 0){
                            $dql->andWhere('c.actualPossessionDate > :fromDate')
                                ->setParameter('fromDate', $fromDate);
                        }else{
                            $dql->orWhere('c.actualPossessionDate > :fromDate')
                                ->setParameter('fromDate', $fromDate);
                        }

                    }
                }
            }
            if (isset($filters['projectPostedBy'])){
                $dql->innerJoin('c.trnVendorPartnerDetails','l')
                    ->innerJoin('l.mstVendorType','m')
                    ->andWhere('m.id in (:projectPostedBy)')
                    ->andWhere('m.isActive = :active')
                    ->andWhere('l.isActive = :active')
                    ->setParameter('projectPostedBy', $filters['projectPostedBy']);
            }
//            if (isset($filters['projectAmenities'])){
//                $dql->innerJoin('c.mstAmenities','ma')
//                    ->andWhere('ma.id in (:projectAmenities)')
//                    ->andWhere('ma.isActive = :active')
//                    ->setParameter('projectAmenities', $filters['projectAmenities']);
//            }
            if (isset($filters['projectAmenities'])){
                $dql->innerJoin('c.trnProjectAmenities','n')
                    ->innerJoin('n.mstProjectAmenities','o')
                    ->andWhere('o.id in (:projectAmenities)')
                    ->andWhere('o.isActive = :active')
                    ->andWhere('n.isActive = :active')
                    ->setParameter('projectAmenities', $filters['projectAmenities']);
            }
            if (isset($filters['noOfBathRooms'])){
                $dql->andWhere('a.noOfBathRooms in (:noOfBathRooms)')
                    ->setParameter('noOfBathRooms', $filters['noOfBathRooms']);
            }
            if (isset($filters['propertyAreaValue'])){
                if ($filters['propertyAreaRange'] == "Min"){
                    $dql->andWhere('a.areaValue <= :propertyAreaValue')
                        ->setParameter('propertyAreaValue', $filters['propertyAreaValue']);
                }elseif ($filters['propertyAreaRange'] == "Max"){
                    $dql->andWhere('a.areaValue >= :propertyAreaValue')
                        ->setParameter('propertyAreaValue', $filters['propertyAreaValue']);
                }
            }
            if (isset($filters['priceRangeMin']) && isset($filters['priceRangeMax']) ){
                if (isset($filters['propertyTransactionCategoryId'])){
                    if ($filters['propertyTransactionCategoryId'] == 1){
                        $dql->andWhere('a.agreementAmount between :minPrice and :maxPrice')
                            ->setParameter('minPrice', $filters['priceRangeMin'])
                            ->setParameter('maxPrice', $filters['priceRangeMax']);
                    }else if($filters['propertyTransactionCategoryId'] == 2) {
                        $dql->andWhere('a.rentPerMonth between :minPrice and :maxPrice')
                            ->setParameter('minPrice', $filters['priceRangeMin'])
                            ->setParameter('maxPrice', $filters['priceRangeMax']);
                    }
                }else{
                    $dql->andWhere('a.agreementAmount between :minPrice and :maxPrice')
                        ->setParameter('minPrice', $filters['priceRangeMin'])
                        ->setParameter('maxPrice', $filters['priceRangeMax']);
                }
            }

            if (isset($filters['propertyTransactionCategoryId'])){
                $dql->innerJoin('a.mstPropertyTransactionCategory','q')
                    ->andWhere('q.id = :propertyTransactionCategoryId')
                    ->andWhere('q.isActive = :active')
                    ->setParameter('propertyTransactionCategoryId', $filters['propertyTransactionCategoryId']);
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
        }
        if (isset($filters['projectRoomConfigurations'])){
            $dql->innerJoin('c.mstRoomConfiguration','t')
                ->andWhere('t.id in (:projectRoomConfigurations)')
                ->andWhere('t.isActive = :active')
                ->setParameter('projectRoomConfigurations', $filters['projectRoomConfigurations']);
            $dql->innerJoin('a.mstRoomConfiguration','s')
                ->andWhere('s.id in (:projectRoomConfigurations)')
                ->andWhere('s.isActive = :active')
                ->setParameter('projectRoomConfigurations', $filters['projectRoomConfigurations']);
        }
        if (isset($filters['mstPincode'])){
            $dql->innerJoin('c.mstPincode','r')
                ->andWhere('r.id in (:mstpincode)')
                ->setParameter('mstpincode', $filters['mstPincode']);
        }
        if (isset($filters['mstCity'])){
            $dql->innerJoin('c.mstCity','d')
                ->andWhere('d.city = :city')
                ->setParameter('city', $filters['mstCity']);
        }
        if (isset($filters['propertyTransactionCategoryId'])){
            if ($filters['propertyTransactionCategoryId'] == 1){
                if (isset($filters["sort"]) && $filters["sort"]=="Recent"){
                    $order = "DESC";
                    return $dql
                        ->andWhere('c.isActive = :active')
                        ->andWhere('a.isActive = :active')
                        ->setParameter('active', 1)
                        ->orderby('a.createdOn',$order)
                        ->getQuery()
                        ->getResult();
                }else{
                    return $dql
                        ->andWhere('c.isActive = :active')
                        ->andWhere('a.isActive = :active')
                        ->setParameter('active', 1)
                        ->orderby('a.agreementAmount',$order)
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
                        ->orderby('a.createdOn', $order)
                        ->getQuery()
                        ->getResult();
                }else {
                    return $dql
                        ->andWhere('c.isActive = :active')
                        ->andWhere('a.isActive = :active')
                        ->setParameter('active', 1)
                        ->orderby('a.rentPerMonth', $order)
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
                    ->orderby('a.createdOn', $order)
                    ->getQuery()
                    ->getResult();
            }else {
                return $dql
                    ->andWhere('c.isActive = :active')
                    ->andWhere('a.isActive = :active')
                    ->setParameter('active', 1)
                    ->orderby('a.agreementAmount', $order)
                    ->getQuery()
                    ->getResult();
            }
        }
    }

    public function getProjectCities() {

        return $this->createQueryBuilder('c')
            ->select('mct.id','mct.city')
            ->innerJoin('c.mstCity', 'mct')
            ->where('c.isActive = :active')
            ->setParameter('active', 1)
            ->orderBy('mct.id','asc')
            ->groupBy('mct.id')
            ->getQuery()
            ->getResult()
            ;
    }

    public function getProjectProductType(){
        return $this->createQueryBuilder('p')
            ->select('prd.id','prd.productType')
            ->innerJoin('p.mstProductType', 'prd')
            ->where('p.isActive = :active')
            ->where('prd.isActive = :active')
            ->setParameter('active', 1)
            ->orderBy('prd.id','asc')
            ->groupBy('prd.id')
            ->getQuery()
            ->getResult()
            ;
    }

    public function getProjectProjectType(){
        return $this->createQueryBuilder('p')
            ->select('prj.id','prj.projectType')
            ->innerJoin('p.mstPropertyType', 'prj')
            ->where('p.isActive = :active')
            ->where('prj.isActive = :active')
            ->setParameter('active', 1)
            ->orderBy('prj.id','asc')
            ->groupBy('prj.id')
            ->getQuery()
            ->getResult()
            ;
    }

    public function getProjectStatus(){
        return $this->createQueryBuilder('p')
//            ->select('
//                mpc.id as productCategoryID,
//                mpc.productCategory as productCategory,
//                mpt.id as productTypeID,
//                mpt.productType as productType,
//                prj.id as projectTypeID,
//                prj.projectType,
//                prj.slugName,
//                sts.id as projectStatusID,
//                sts.propertyType as projectStatus
//                ')
//            ->select('prj.id,prj.projectType,prj.slugName,sts.id as id_1,sts.propertyType')
//            ->innerJoin('p.mstProjectStatus', 'sts')
//            ->innerJoin('p.mstPropertyType', 'prj')
            ->innerJoin('p.mstProductCategory', 'mpc')
            ->innerJoin('p.mstProductType', 'mpt')
            ->innerJoin('p.mstProjectStatus', 'sts')
            ->innerJoin('p.mstPropertyType', 'prj')
            ->where('p.isActive = :active')
            ->where('sts.isActive = :active')
            ->where('mpc.isActive = :active')
            ->where('mpt.isActive = :active')
            ->where('mpt.isActive = :active')
            ->setParameter('active', 1)
            ->orderBy('prj.id,sts.id','asc')
            //->groupBy('prj.id,sts.id')
            ->getQuery()
            ->getResult()
            ;
    }
    public function getProjectVendor()
    {
        return $this->createQueryBuilder('p')
            ->select('vdr.id,vdr.vendorType')
            ->innerJoin('p.trnVendorPartnerDetails', 'vpd')
            ->innerJoin('vpd.mstVendorType', 'vdr')
            ->where('p.isActive = :active')
            ->where('vpd.isActive = :active')
            ->where('vdr.isActive = :active')
            ->setParameter('active', 1)
            ->orderBy('vdr.id','asc')
            ->groupBy('vdr.id')
            ->getQuery()
            ->getResult()
            ;
    }
    public function getProjectPossession()
    {
        return $this->createQueryBuilder('p')
            ->select('pos.id,pos.possession')
            ->innerJoin('p.mstPossession', 'pos')
            ->where('p.isActive = :active')
            ->where('pos.isActive = :active')
            ->setParameter('active', 1)
            ->orderBy('pos.id','asc')
            ->groupBy('pos.id')
            ->getQuery()
            ->getResult()
            ;
    }

    public function gettestProject($filters)
    {
        $order = "ASC";
        if (isset($filters["sort"])){
            if ($filters["sort"]=="High"){
                $order = "DESC";
            }
            if ($filters["sort"]=="Low"){
                $order = "ASC";
            }
        }
        return $this->createQueryBuilder('c')
            ->innerJoin('c.trnProjectRoomConfigurations','tprc')
            ->andWhere('tprc.isActive = :active')
            ->andWhere('c.isActive = :active')
            ->setParameter('projectRoomConfigurations', $filters)
            ->setParameter('active', 1)
            ->orderby('tprc.agreementAmount',$order)
            ->getQuery()
            ->getResult()
            ;
    }

    public function getOtherProject($vendorId,$projectId)
    {
        return $this->createQueryBuilder('p')
            ->innerJoin('p.trnVendorPartnerDetails', 'vpd')
            ->where('p.isActive = :active')
            ->andwhere('vpd.isActive = :active')
            ->andwhere('vpd.id = :id')
            ->andWhere('p.id <> :projectId')
            ->setParameter('active', 1)
            ->setParameter('id', $vendorId)
            ->setParameter('projectId', $projectId)
            ->getQuery()
            ->getResult()
            ;
    }

    public function getProjectByPincodeandName($projectName,$mstPincodeID)
    {
        $qb = $this->createQueryBuilder('tp');
        return $qb->innerJoin('tp.mstPincode', 'p')
            ->andwhere('p.id = :mstPincodeId')
            ->andwhere($qb->expr()->like('tp.projectName', ':projectName'))
            ->setParameter('mstPincodeId', $mstPincodeID)
            ->setParameter(':projectName', '%' . $projectName.'%')
            ->getQuery()
            ->getResult();
    }

    public function getProjectRoomConfiguration($filters)
    {
        $dql = $this->createQueryBuilder('c')
            ->innerJoin('c.trnProjectRoomConfigurations','a');
        if (isset($filters['slugName'])){
            $dql->innerJoin('c.mstProductType','e')
                ->andWhere('e.productTypeSlugName = :slugName')
                ->andWhere('e.isActive = :active')
                ->setParameter('slugName', $filters['slugName']);
        }
        if (isset($filters['productCategory'])){
            $dql->innerJoin('c.mstProductCategory','f')
                ->andWhere('f.productCategorySlugName = :cslugName')
                ->andWhere('f.isActive = :active')
                ->setParameter('cslugName', $filters['productCategory']);
        }
        if (isset($filters['projectRoomConfigurations'])){
            $dql->innerJoin('a.mstRoomConfiguration','t')
                ->andWhere('t.id in (:roomConfiguration)')
                ->andWhere('t.isActive = :active')
                ->setParameter('roomConfiguration', $filters['projectRoomConfigurations']);
        }
        if (isset($filters['mstCity'])){
            $dql->innerJoin('c.mstCity','d')
                ->andWhere('d.city = :city')
                ->setParameter('city', $filters['mstCity']);
        }
        if (isset($filters['propertyTransactionCategoryId'])){
            $dql->innerJoin('a.mstPropertyTransactionCategory','z')
                ->andWhere('z.id = :mstPropertyTransactionCategoryId')
                ->setParameter('mstPropertyTransactionCategoryId', $filters['propertyTransactionCategoryId']);
        }else{
            $dql->innerJoin('a.mstPropertyTransactionCategory','z')
                ->andWhere('z.id = :mstPropertyTransactionCategoryId')
                ->setParameter('mstPropertyTransactionCategoryId', 1);
        }
        return $dql
            ->andWhere('c.isActive = :active')
            ->andWhere('a.isActive = :active')
            ->setParameter('active', 1)
            ->orderby('a.agreementAmount','ASC')
            ->getQuery()
            ->getResult();
    }

    // /**
    //  * @return TrnProject[] Returns an array of TrnProject objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TrnProject
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

<?php

namespace App\Repository;

use App\Entity\Pedido;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Pedido|null find($id, $lockMode = null, $lockVersion = null)
 * @method Pedido|null findOneBy(array $criteria, array $orderBy = null)
 * @method Pedido[]    findAll()
 * @method Pedido[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Pedido::class);
    }

     /**
      * @return Pedido[] Returns an array of Producto objects
     */

    public function getPedido($id_pedido)
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = 'SELECT o.id_order, o.date_add as creation_date, a.firstname, a.lastname, a.postcode, i.id_image, osl.name ,  od.product_quantity, od.product_name, a.city, a.address1, od.unit_price_tax_incl as product_price, od.total_price_tax_incl, od.total_price_tax_excl, c.name as country FROM ps_orders o INNER JOIN ps_address a ON o.id_address_delivery = a.id_address INNER JOIN ps_country_lang c ON a.id_country = c.id_country  INNER JOIN ps_order_detail od ON o.id_order = od.id_order INNER JOIN ps_image i ON i.id_product = od.product_id INNER JOIN ps_order_state_lang osl ON osl.id_order_state = o.current_state WHERE od.id_order = :id_pedido';
        $stmt = $conn->prepare($sql);
        $stmt->execute(array('id_pedido' => $id_pedido));

        $row = $stmt->fetchAssociative();
        if($row) {
            $row['url_image'] = '/img/p/'.implode('/', str_split((string) $row['id_image'])).'/'.$row['id_image'].'.jpg';
        }

        /*PARA OBTENER LA URL IMAGEN PRODUCTO DE PRESTASHOP */

        return $row;
    }


    public function getPedidos($request)
    {
        $conn = $this->getEntityManager()->getConnection();




        $sql = 'SELECT o.id_order, o.date_add as creation_date, a.firstname, a.lastname, a.postcode, i.id_image, osl.name ,  od.product_quantity, od.product_name, a.city, a.address1, round(od.unit_price_tax_incl,2) as product_price, round(od.total_price_tax_incl,2) as total_price_tax_incl, round(od.total_price_tax_excl,2) as total_price_tax_excl, c.name as country FROM ps_orders o INNER JOIN ps_address a ON o.id_address_delivery = a.id_address INNER JOIN ps_country_lang c ON a.id_country = c.id_country  INNER JOIN ps_order_detail od ON o.id_order = od.id_order INNER JOIN ps_image i ON i.id_product = od.product_id INNER JOIN ps_order_state_lang osl ON osl.id_order_state = o.current_state  WHERE 1 = 1 ';



        foreach($request as $key => $value){
            if(empty($value)) continue;

           if($key == 'date_add'){
                $sql .= ' AND a.' . $key .' LIKE  :'.$key;

            }else{
                $sql .= ' AND ' . $key .' = :'.$key;

            }
/*
            if($key == 'date_add'){
                $value = date("Y-m-d", strtotime($value) );
                $sql .= ' AND a.' . $key .' LIKE  "%'.$value.'%"';

            }else{
                $sql .= ' AND ' . $key .' = "'.intval($value).'"';

            }*/

        }

        $stmt = $conn->prepare($sql);


     foreach($request as $key => $value){
            if(empty($value)) continue;
            if($key == 'date_add'){

                $value = date("Y-m-d", strtotime($value) );
                $stmt->bindValue(':'.$key, '%'.$value.'%');

            }else{
                $stmt->bindValue(':'.$key, $value);

            }


        }



        $stmt->execute();

        $row = $stmt->fetchAllAssociative();

        $res['data'] = $row;
        /*PARA OBTENER LA URL IMAGEN PRODUCTO DE PRESTASHOP */
        return $res;
    }


    public function getEstados()
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = 'SELECT id_order_state, name from ps_order_state_lang';
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        $res = $stmt->fetchAllKeyValue();

        /*PARA OBTENER LA URL IMAGEN PRODUCTO DE PRESTASHOP */

        return $res;
    }


    /*
    public function findOneBySomeField($value): ?Producto
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

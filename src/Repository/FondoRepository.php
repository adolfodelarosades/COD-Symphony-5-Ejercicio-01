<?php

namespace App\Repository;

use App\Entity\Fondo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Fondo|null find($id, $lockMode = null, $lockVersion = null)
 * @method Fondo|null findOneBy(array $criteria, array $orderBy = null)
 * @method Fondo[]    findAll()
 * @method Fondo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FondoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Fondo::class);
    }

    /**
     * @return Fondo[]
     */
    public function findAllWithAutoresAndEditoriales(): array
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT f, a, e
            FROM App\Entity\Fondo f
            JOIN f.autores a
            JOIN f.editorial e
            '
        );

        // returns an array of Product objects
        return $query->getResult();
    }


   /**
     * @return Fondo[] Returns an array of Fondo objects
     */
    public function findAllWithAutoresAndEditorialesBuilder()
    {
        $qb = $this->createQueryBuilder('f')
            ->join('f.autores', 'a')
            ->addSelect('a')
            ->join('f.editorial', 'e')
            ->addSelect('e');
            // ->setMaxResults(10)

        $query = $qb->getQuery();
        return $query->getResult();
    }

    /**
     * @return Fondo[] Returns an array of Fondo objects
     */
    public function findAllWithAutoresAndEditorialesPaginado(int $page, string $orderBy, string $order, int $itemsPerPage = 10)
    {
        $startAt = ($page - 1) * $itemsPerPage;
        $qb = $this->createQueryBuilder('f')
            ->leftJoin('f.autores', 'a')
            ->addSelect('a')
            ->leftJoin('f.editorial', 'e')
            ->addSelect('e')
            //->orderBy('f.' . $orderBy, $order)
            ->setFirstResult($startAt)
            ->setMaxResults($itemsPerPage)
        ;

        switch ($orderBy) {
            case 'editorial':
                $field = 'e.nombre';
                break;
            case 'autor':
                $field = 'a.nombre';
                break;
            default: 
                $field = 'f.' . $orderBy;
        }

        $qb = $qb->orderBy($field, $order);
        
        $query = $qb->getQuery();

        return new Paginator($query);
    }


    /**
     * @return array Returns an array asociativo
     */
    public function findAllWithAutoresAndEditorialesSQL()
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
        SELECT f0_.id AS id, 
               f0_.titulo AS titulo, 
               f0_.isbn AS isbn, 
               f0_.edicion AS edicion, 
               f0_.publicacion AS publicacion, 
               f0_.categoria AS categoria, 
               f0_.editorial_id AS editorial_id,
               
               a1_.id AS id_autor, 
               a1_.nombre AS nombre_autor, 
               a1_.tipo AS tipo, 
               
               e2_.id AS id_editorial, 
               e2_.nombre AS nombre_editorial
               
        FROM fondo f0_ 
            INNER JOIN fondo_autor f3_ ON f0_.id = f3_.fondo_id 
            INNER JOIN autor a1_ ON a1_.id = f3_.autor_id 
            INNER JOIN editorial e2_ ON f0_.editorial_id = e2_.id
        ORDER BY id
        ;
        ';
        dump($sql);
        $stmt = $conn->prepare($sql);
        $result = $stmt->executeQuery();

        // returns an array of arrays (i.e. a raw data set)
        return $result->fetchAllAssociative();
    }
    // /**
    //  * @return Fondo[] Returns an array of Fondo objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Fondo
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

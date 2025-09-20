<?php

namespace App\Repository;

use App\Entity\MessagesSent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<MessagesSent>
 *
 * @method MessagesSent|null find($id, $lockMode = null, $lockVersion = null)
 * @method MessagesSent|null findOneBy(array $criteria, array $orderBy = null)
 * @method MessagesSent[]    findAll()
 * @method MessagesSent[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MessagesSentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MessagesSent::class);
    }

    public function save(MessagesSent $entity, bool $flush = false): void
    {
        $this->_em->persist($entity);

        if ($flush) {
            $this->_em->flush();
        }
    }

    public function remove(MessagesSent $entity, bool $flush = false): void
    {
        $this->_em->remove($entity);

        if ($flush) {
            $this->_em->flush();
        }
    }
}

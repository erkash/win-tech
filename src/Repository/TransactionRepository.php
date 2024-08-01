<?php

namespace App\Repository;

use App\Entity\Transaction;
use App\Enum\TransactionReasonEnum;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Exception;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Transaction>
 *
 * @method Transaction|null find($id, $lockMode = null, $lockVersion = null)
 * @method Transaction|null findOneBy(array $criteria, array $orderBy = null)
 * @method Transaction[]    findAll()
 * @method Transaction[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TransactionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Transaction::class);
    }

    /**
     * @throws Exception
     */
    public function findRefundsLast7Days(): ?int
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
            SELECT SUM(amount) AS total_refund
            FROM transaction
            WHERE reason = :reason
            AND created_at >= NOW() - INTERVAL 7 DAY
        ';

        $stmt = $conn->prepare($sql);
        $result = $stmt->executeQuery(['reason' => TransactionReasonEnum::REFUND]);
        $result = $result->fetchAssociative();

        // Return the total refund amount or null if no refunds are found
        return $result ? (int) $result['total_refund'] : null;
    }
}

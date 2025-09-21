<?php
// src/Repository/StockRepository.php

namespace App\Repository;

use App\Entity\Stock;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Stock>
 */
class StockRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Stock::class);
    }
    /**
     * Retorna os produtos mais vendidos no último mês,
     * já juntando com estoque e produto.
     */
    public function findTopSoldProductsWithComparison(\DateTime $startDate, \DateTime $endDate, int $limit = 10): array
{
    $conn = $this->getEntityManager()->getConnection();

    // Query base (reutilizável)
    $sql = "
        SELECT 
            p.id AS product_id,
            p.name AS product_name,
            SUM(oi.quantity) AS sold_quantity,
            s.quantity AS stock_quantity,
            s.unit AS stock_unit
        FROM order_item oi
        INNER JOIN product p ON oi.product_id = p.id
        LEFT JOIN stock s ON s.product_id = p.id
        INNER JOIN `order` o ON oi.order_id = o.id
        WHERE o.order_date BETWEEN :start AND :end
          AND p.has_ingredients = 0
          AND (s.quantity IS NULL OR s.quantity < 50)
        GROUP BY p.id, p.name, s.quantity, s.unit
        ORDER BY sold_quantity DESC
        LIMIT $limit
    ";

    // --- Período atual
    $stmt = $conn->prepare($sql);
    $current = $stmt->executeQuery([
        'start' => $startDate->format('Y-m-d H:i:s'),
        'end'   => $endDate->format('Y-m-d H:i:s'),
    ])->fetchAllAssociative();

    // --- Mesmo período no ano anterior
    $previousStart = (clone $startDate)->modify('-1 year');
    $previousEnd   = (clone $endDate)->modify('-1 year');

    $stmtPrev = $conn->prepare($sql);
    $previous = $stmtPrev->executeQuery([
        'start' => $previousStart->format('Y-m-d H:i:s'),
        'end'   => $previousEnd->format('Y-m-d H:i:s'),
    ])->fetchAllAssociative();

    return [
        'current_period' => $current,
        'previous_year'  => $previous,
    ];
}


}
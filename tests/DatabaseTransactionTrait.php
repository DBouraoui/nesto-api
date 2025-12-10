<?php

namespace App\Tests;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Ce Trait gère l'ouverture d'une transaction avant le test
 * et son annulation (rollback) après, pour isoler les données.
 * * Nécessite que la classe de test soit une extension de WebTestCase
 * ou de KernelTestCase pour avoir accès au Container/Kernel.
 */
trait DatabaseTransactionTrait
{
    private ?EntityManagerInterface $entityManager = null;

    /**
     * Doit être appelé dans le setUp() de la classe de test.
     */
    protected function startTransaction(): void
    {
        // Récupérer le Container de services du Kernel
        // (WebTestCase fournit static::getContainer())
        /** @var ContainerInterface $container */
        $container = self::getContainer();

        // Récupérer l'EntityManager
        /** @var EntityManagerInterface $em */
        $em = $container->get('doctrine')->getManager();
        $this->entityManager = $em;

        // Commencer une transaction DB
        $this->entityManager->getConnection()->beginTransaction();
    }

    /**
     * Doit être appelé dans le tearDown() de la classe de test.
     */
    protected function rollbackTransaction(): void
    {
        if ($this->entityManager === null) {
            return;
        }

        // Annuler la transaction (rollback)
        if ($this->entityManager->getConnection()->isTransactionActive()) {
            $this->entityManager->getConnection()->rollBack();
        }

        // Nettoyer l'état de l'EntityManager et la propriété
        $this->entityManager->clear();
        $this->entityManager = null;
    }
}

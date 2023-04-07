<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * AbstractWebTest
 *
 */
abstract class AbstractWebTest extends WebTestCase
{
    protected static ?KernelBrowser $webClient = null;

    protected ?object $entityManager;

    protected ?DatabaseMockManager $databaseMockManager = null;

    protected function setUp(): void
    {
        if (self::$webClient === null) {
            self::$webClient = static::createClient(["environment" => "test"]);
        }

        if ($this->databaseMockManager == null) {
            $this->databaseMockManager = new DatabaseMockManager(self::$kernel,self::$webClient);
        }

        self::$webClient->disableReboot();
        self::$webClient->enableProfiler();

        $this->entityManager = self::$webClient->getContainer()->get("doctrine.orm.entity_manager");
        $this->entityManager->getConnection()->beginTransaction();
    }

    protected function tearDown(): void
    {
        if ($this->entityManager->getConnection()->isTransactionActive()) {
            $this->entityManager->getConnection()->rollback();
        }
    }

    protected function getService(string $serviceName): object
    {
        return self::$webClient->getContainer()->get($serviceName);
    }
}
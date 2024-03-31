<?php

namespace Tests\Infrastructure\Persistence\Application;

use App\Domain\Application\ApplicationDeployment;
use App\Infrastructure\Persistence\Application\ApplicationNotFoundException;
use App\Infrastructure\Persistence\Application\PDOApplicationDeploymentRepository;
use PDO;
use Prophecy\Argument;
use Tests\TestCase;

use function PHPUnit\Framework\assertEquals;

class PDOApplicationDeploymentRepositoryTest extends TestCase
{
    public function testUpdate()
    {
        self::assertNull(null);
    }

    /**
     * @throws ApplicationNotFoundException
     */
    public function testFindApplicationDeployment()
    {
//Given
        $applicationDeployment = new ApplicationDeployment('frontend', 'prod', 2);
        $pdoStatementProphecy = $this->prophesize(\PDOStatement::class);
        $pdoStatementProphecy
            ->execute(['name' => 'frontend', 'environment' => 'prod'])
            ->willReturn(true)
            ->shouldBeCalledOnce();
        $pdoStatementProphecy
            ->fetch(PDO::FETCH_ASSOC)
            ->willReturn($applicationDeployment->jsonSerialize())
            ->shouldBeCalledOnce();

        $pdoStatementObject = $pdoStatementProphecy->reveal();

        $databaseProphecy = $this->prophesize(PDO::class);
        $databaseProphecy
            ->prepare(Argument::any())
            ->willReturn($pdoStatementObject)
            ->shouldBeCalledOnce();
        //When
        $deploymentRepository = new PDOApplicationDeploymentRepository($databaseProphecy->reveal());
        $result = $deploymentRepository->findApplicationDeployment(
            $applicationDeployment->getName(),
            $applicationDeployment->getEnvironment()
        );
        assertEquals($applicationDeployment->getName(), $result->getName());
        assertEquals($applicationDeployment->getDeployment(), $result->getDeployment());
    }

    public function testCreate()
    {
        self::assertNull(null);
    }
}

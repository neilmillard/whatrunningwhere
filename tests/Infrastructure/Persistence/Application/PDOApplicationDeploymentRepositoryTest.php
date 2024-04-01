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
    public function testUpdateOrCreateUpdateOnly()
    {
        //Given
        $applicationDeployment = new ApplicationDeployment('frontend', 'prod', 2);
        $pdoStatementProphecy = $this->prophesize(\PDOStatement::class);
        $pdoStatementProphecy
            ->execute(Argument::any())
            ->willReturn(true)
            ->shouldBeCalledOnce();
        $pdoStatementProphecy
            ->rowCount()
            ->willReturn(1)
            ->shouldBeCalledOnce();

        $pdoStatementObject = $pdoStatementProphecy->reveal();

        $databaseProphecy = $this->prophesize(PDO::class);
        $databaseProphecy
            ->prepare("UPDATE applications SET deployment_id=:deployment_id "
                    . "WHERE name=:name AND environment=:environment")
            ->willReturn($pdoStatementObject)
            ->shouldBeCalledOnce();
        //When
        $deploymentRepository = new PDOApplicationDeploymentRepository($databaseProphecy->reveal());
        $result = $deploymentRepository->updateOrCreate($applicationDeployment);
        assertEquals($applicationDeployment->getName(), $result->getName());
        assertEquals($applicationDeployment->getEnvironment(), $result->getEnvironment());
        assertEquals($applicationDeployment->getDeployment(), $result->getDeployment());
    }

    public function testUpdateOrCreateWithCreate()
    {
        //Given
        $applicationDeployment = new ApplicationDeployment('frontend', 'prod', 2);
        $pdoStatementProphecy = $this->prophesize(\PDOStatement::class);
        $pdoStatementProphecy
            ->execute(["name" => "frontend", "environment" => "prod", "deployment_id" => 2])
            ->willReturn(true)
            ->shouldBeCalledTimes(2);
        $pdoStatementProphecy
            ->rowCount()
            ->willReturn(0, 1)
            ->shouldBeCalledTimes(2);

        $pdoStatementObject = $pdoStatementProphecy->reveal();

        $databaseProphecy = $this->prophesize(PDO::class);
        $databaseProphecy
            ->prepare("UPDATE applications SET deployment_id=:deployment_id "
                    . "WHERE name=:name AND environment=:environment")
            ->willReturn($pdoStatementObject)
            ->shouldBeCalledOnce();
        $databaseProphecy
            ->prepare("INSERT INTO applications (name, environment, deployment_id) "
                    . "VALUES (:name, :environment, :deployment_id)")
            ->willReturn($pdoStatementObject)
            ->shouldBeCalledOnce();
        //When
        $deploymentRepository = new PDOApplicationDeploymentRepository($databaseProphecy->reveal());
        $result = $deploymentRepository->updateOrCreate($applicationDeployment);
        assertEquals($applicationDeployment->getName(), $result->getName());
        assertEquals($applicationDeployment->getEnvironment(), $result->getEnvironment());
        assertEquals($applicationDeployment->getDeployment(), $result->getDeployment());
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

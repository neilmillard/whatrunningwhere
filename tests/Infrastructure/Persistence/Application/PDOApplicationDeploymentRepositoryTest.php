<?php

namespace Tests\Infrastructure\Persistence\Application;

use App\Domain\Application\ApplicationDeployment;
use App\Domain\Deployment\Deployment;
use App\Infrastructure\Persistence\Application\ApplicationNotFoundException;
use App\Infrastructure\Persistence\Application\PDOApplicationDeploymentRepository;
use PDO;
use Prophecy\Argument;
use Tests\TestCase;

use function PHPUnit\Framework\assertEquals;

class PDOApplicationDeploymentRepositoryTest extends TestCase
{
    /**
     * @throws ApplicationNotFoundException
     */
    public function testFindApplicationDeployment()
    {
        //Given
        $applicationDeployment = new ApplicationDeployment('frontend', 'prod', 2);
        $deployment = new Deployment(
            1,
            'bill.gates',
            $applicationDeployment->getName(),
            '0.1.0',
            $applicationDeployment->getEnvironment(),
            $applicationDeployment->getDeployment()
        );

        $pdoStatementProphecy = $this->prophesize(\PDOStatement::class);
        $pdoStatementProphecy
            ->execute(['name' => 'frontend', 'environment' => 'prod'])
            ->willReturn(true)
            ->shouldBeCalledOnce();
        $pdoStatementProphecy
            ->fetch(PDO::FETCH_ASSOC)
            ->willReturn($deployment->jsonSerialize())
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

    public function testFindApplicationDeployments()
    {
        //Given
        $applicationDeployment = new ApplicationDeployment('frontend', 'test', 1);
        $deployment = new Deployment(
            1,
            'bill.gates',
            $applicationDeployment->getName(),
            '0.1.0',
            $applicationDeployment->getEnvironment(),
            $applicationDeployment->getDeployment()
        );
        $applicationDeployment2 = new ApplicationDeployment('frontend', 'prod', 2);
        $deployment2 = new Deployment(
            2,
            'bill.gates',
            $applicationDeployment2->getName(),
            '0.1.0',
            $applicationDeployment2->getEnvironment(),
            $applicationDeployment2->getDeployment()
        );

        $pdoStatementProphecy = $this->prophesize(\PDOStatement::class);
        $pdoStatementProphecy
            ->execute(['name' => 'frontend'])
            ->willReturn(true)
            ->shouldBeCalledOnce();
        $pdoStatementProphecy
            ->fetchAll(PDO::FETCH_ASSOC)
            ->willReturn([$deployment->jsonSerialize(), $deployment2->jsonSerialize()])
            ->shouldBeCalledOnce();

        $pdoStatementObject = $pdoStatementProphecy->reveal();

        $databaseProphecy = $this->prophesize(PDO::class);
        $databaseProphecy
            ->prepare(Argument::any())
            ->willReturn($pdoStatementObject)
            ->shouldBeCalledOnce();
        //When
        $deploymentRepository = new PDOApplicationDeploymentRepository($databaseProphecy->reveal());
        $results = $deploymentRepository->findApplicationDeployments(
            $applicationDeployment->getName()
        );
        assertEquals($applicationDeployment->getName(), $results[0]->getName());
        assertEquals($applicationDeployment->getDeployment(), $results[0]->getDeployment());
        assertEquals($applicationDeployment2->getName(), $results[1]->getName());
        assertEquals($applicationDeployment2->getDeployment(), $results[1]->getDeployment());
    }
}

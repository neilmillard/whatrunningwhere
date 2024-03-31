<?php

namespace Tests\Infrastructure\Persistence\Deployment;

use App\Domain\Deployment\Deployment;
use App\Infrastructure\Persistence\Deployment\SqLiteDeploymentRepository;
use PDO;
use Prophecy\Argument;
use Tests\TestCase;

use function PHPUnit\Framework\assertEquals;

class SqLiteDeploymentRepositoryTest extends TestCase
{
    public function testCreate()
    {
        //Given
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
            ->prepare(Argument::any())
            ->willReturn($pdoStatementObject)
            ->shouldBeCalledOnce();
        $databaseProphecy
            ->lastInsertId()
            ->willReturn(1)
            ->shouldBeCalledOnce();


        //When
        $deploymentRepository = new SqLiteDeploymentRepository($databaseProphecy->reveal());
        $deployment = new Deployment(1, 'bill.gates', 'Frontend', '1.0.0', 'prod', null);

        $result = $deploymentRepository->create($deployment);
        //Then
        assertEquals(1, $result->getId());
    }

    public function testFindAll()
    {
        //Given
        $deployment = new Deployment(1, 'bill.gates', 'Frontend', '1.0.0', 'prod', null);

        $pdoStatementProphecy = $this->prophesize(\PDOStatement::class);
        $pdoStatementProphecy
            ->execute(Argument::any())
            ->willReturn(true)
            ->shouldBeCalledOnce();
        $pdoStatementProphecy
            ->fetchAll(PDO::FETCH_ASSOC)
            ->willReturn([$deployment->jsonSerialize()])
            ->shouldBeCalledOnce();

        $pdoStatementObject = $pdoStatementProphecy->reveal();

        $databaseProphecy = $this->prophesize(PDO::class);
        $databaseProphecy
            ->prepare(Argument::any())
            ->willReturn($pdoStatementObject)
            ->shouldBeCalledOnce();

        //When
        $deploymentRepository = new SqLiteDeploymentRepository($databaseProphecy->reveal());

        $result = $deploymentRepository->findAll();
        //Then
        assertEquals($deployment->jsonSerialize(), $result[0]->jsonSerialize());
    }

    public function testFindDeploymentOfId()
    {
        //Given
        $deployment = new Deployment(1, 'bill.gates', 'Frontend', '1.0.0', 'prod', 2);

        $pdoStatementProphecy = $this->prophesize(\PDOStatement::class);
        $pdoStatementProphecy
            ->execute(['id' => 2])
            ->willReturn(true)
            ->shouldBeCalledOnce();
        $pdoStatementProphecy
            ->fetch(PDO::FETCH_ASSOC)
            ->willReturn($deployment->jsonSerialize())
            ->shouldBeCalledOnce();

        $pdoStatementObject = $pdoStatementProphecy->reveal();

        $databaseProphecy = $this->prophesize(PDO::class);
        $databaseProphecy
            ->prepare("SELECT * FROM deployments WHERE id=:id")
            ->willReturn($pdoStatementObject)
            ->shouldBeCalledOnce();

        //When
        $deploymentRepository = new SqLiteDeploymentRepository($databaseProphecy->reveal());

        $result = $deploymentRepository->findDeploymentOfId(2);
        self::assertNotNull($result);
        //Then
        assertEquals($deployment->jsonSerialize(), $result->jsonSerialize());
    }

    public function testGetDeploymentResult()
    {
        //Given
        $deployment = new Deployment(1, 'bill.gates', 'Frontend', '1.0.0', 'prod', 2);
        $databaseProphecy = $this->prophesize(PDO::class);

        //When
        $deploymentRepository = new SqLiteDeploymentRepository($databaseProphecy->reveal());
        $result = $deploymentRepository->getDeploymentResult($deployment->jsonSerialize());
        assertEquals($deployment->jsonSerialize(), $result->jsonSerialize());
    }

    public function testFindApplications()
    {
        //Given
        $deployment = new Deployment(1, 'bill.gates', 'Frontend', '1.0.0', 'prod', 2);
        $pdoStatementProphecy = $this->prophesize(\PDOStatement::class);
        $pdoStatementProphecy
            ->execute(Argument::any())
            ->willReturn(true)
            ->shouldBeCalledOnce();
        $pdoStatementProphecy
            ->fetchAll(PDO::FETCH_COLUMN, 0)
            ->willReturn([$deployment->getApplication()])
            ->shouldBeCalledOnce();

        $pdoStatementObject = $pdoStatementProphecy->reveal();

        $databaseProphecy = $this->prophesize(PDO::class);
        $databaseProphecy
            ->prepare(Argument::any())
            ->willReturn($pdoStatementObject)
            ->shouldBeCalledOnce();
        //When
        $deploymentRepository = new SqLiteDeploymentRepository($databaseProphecy->reveal());
        $result = $deploymentRepository->findApplications();
        assertEquals($deployment->getApplication(), $result[0]);
    }
}

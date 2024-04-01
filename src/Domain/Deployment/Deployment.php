<?php

namespace App\Domain\Deployment;

use JsonSerializable;

/**
 * @OA\Schema (
 *     title="Deployment",
 *     description="A single Deployment Event Model"
 * )
 */
class Deployment implements JsonSerializable
{
    /**
     * @var int|null
     * @OA\Property (type="integer", format="int64", readOnly=true, example=1)
     */
    private ?int $id;

    /**
     * @var string
     * @OA\Property (type="string", example="bill.gates")
     */
    private string $who;

    /**
     * @var string
     * @OA\Property (type="string", example="frontend")
     */
    private string $application;

    /**
     * @var string
     * @OA\Property (type="string", example="1.2.3")
     */
    private string $version;

    /**
     * @var string
     * @OA\Property (type="string", example="production")
     */
    private string $environment;

    /**
     * @var int
     * @OA\Property (type="integer", format="int64", readOnly=true, example=1711992834)
     */
    private int $time;

    public function __construct(
        int $time,
        string $who,
        string $application,
        string $version,
        string $environment,
        ?int $id
    ) {
        $this->id = $id;
        $this->who = strtolower($who);
        $this->application = strtolower($application);
        $this->version = $version;
        $this->environment = strtolower($environment);
        $this->time = $time;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $lastInsertId): void
    {
        $this->id = $lastInsertId;
    }

    public function getWho(): string
    {
        return $this->who;
    }

    public function getApplication(): string
    {
        return $this->application;
    }

    public function getVersion(): string
    {
        return $this->version;
    }

    public function getEnvironment(): string
    {
        return $this->environment;
    }

    public function getTime(): int
    {
        return $this->time;
    }

    #[\ReturnTypeWillChange]
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'who' => $this->who,
            'application' => $this->application,
            'version' => $this->version,
            'environment' => $this->environment,
            'time' => $this->time,
        ];
    }
    public function __toString()
    {
        return json_encode($this->jsonSerialize());
    }
}

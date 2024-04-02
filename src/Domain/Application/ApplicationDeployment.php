<?php

namespace App\Domain\Application;

/**
 * @OA\Schema (
 *     title="ApplicationDeployment",
 *     description="A single ApplicationDeployment Event Model"
 * )
 */
class ApplicationDeployment
{
    /**
     * @var string
     * @OA\Property (type="string", example="frontend")
     */
    private string $name;
    /**
     * @var string
     * @OA\Property (type="string", example="development")
     */
    private string $environment;
    /**
     * @var int
     * @OA\Property (type="integer", format="int64", example=1)
     */
    private int $deployment_id;

    public function __construct(string $name, string $environment, int $deployment_id)
    {
        $this->name = strtolower($name);
        $this->environment = strtolower($environment);
        $this->deployment_id = $deployment_id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEnvironment(): string
    {
        return $this->environment;
    }

    public function getDeployment(): int
    {
        return $this->deployment_id;
    }

    public function jsonSerialize(): array
    {
        return [
            'name' => $this->name,
            'environment' => $this->environment,
            'deployment_id' => $this->deployment_id,
        ];
    }
}

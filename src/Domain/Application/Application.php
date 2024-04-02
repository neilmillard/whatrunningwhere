<?php

namespace App\Domain\Application;

use App\Domain\Deployment\Deployment;
use JsonSerializable;

/**
 * @OA\Schema (
 *     title="Application",
 *     description="A single Application Model"
 * )
 */
class Application implements JsonSerializable
{
    /**
     * @var string
     * @OA\Property (type="string", example="frontend")
     */
    private string $application;

    /**
     * @var array<string,Deployment>
     * @OA\Property (type="array",
     *     @OA\Items(type="object",
     *       @OA\AdditionalProperties(
     *         @OA\Property(type="string", example="development",
     *           @OA\Property(type="object", ref="#/components/schemas/Deployment")
     *         )
     *       )
     *     )
     * )
     */
    private array $deployments;

    public function __construct(string $application)
    {
        $this->application = $application;
    }

    public function addDeployment(Deployment $deployment): bool
    {
        if ($deployment->getApplication() == $this->application) {
            $this->deployments[$deployment->getEnvironment()] = $deployment;
        } else {
            return false;
        }
        return true;
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize(): array
    {
        return [
            'name' => $this->application,
            'deployed' => $this->deployments,
        ];
    }
}

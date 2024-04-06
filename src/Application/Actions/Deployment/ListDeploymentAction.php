<?php

namespace App\Application\Actions\Deployment;

use Psr\Http\Message\ResponseInterface as Response;

class ListDeploymentAction extends DeploymentAction
{
    /**
     * @inheritDoc
     * @OA\Get (
     *      tags={"deployment"},
     *      path="/deployments",
     *      @OA\Parameter(
     *          name="from",
     *          in="query",
     *          description="Timestamp of earliest entry defaults to -7 days",
     *          required=false,
     *          @OA\Schema(type="int")
     *      ),
     *      @OA\Parameter(
     *           name="to",
     *           in="query",
     *           description="Timestamp of latest entry, Default now",
     *           required=false,
     *           @OA\Schema(type="int")
     *      ),
     *      operationId="listDeployments",
     *      @OA\Response(
     *          response=200,
     *          description="A list of all deployment event records",
     *          @OA\Property(
     *              title="data",
     *              type="array",
     *              @OA\Property(
     *                  title="from",
     *                  type="int",
     *                  example="1711810636"
     *              ),
     *              @OA\Property(
     *                  title="to",
     *                  type="int",
     *                  example="1711810636"
     *              ),
     *              @OA\Property(
     *                  title="deployments",
     *                  type="array",
     *                  @OA\Property(
     *                      type="array",
     *                      title="deployments",
     *                      @OA\Items(ref="#/components/schemas/Deployment")
     *                  )
     *              )
     *          )
     *      )
     *  )
     */
    protected function action(): Response
    {
        $params = $this->request->getQueryParams();
        $dateFrom = $params['from'] ?? strtotime('-7 days');
        $dateTo = $params['to'] ?? time();
        $deployments = $this->deploymentRepository->findAll($dateFrom, $dateTo);
        $this->logger->info("Deployments list was viewed.");
        $data = ['from' => $dateFrom,
                 'to' => $dateTo,
                'deployments' => $deployments];
        return $this->respondWithData($data);
    }
}

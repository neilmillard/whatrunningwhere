<?php
// DeploymentModel.php
class DeploymentModel {
    private $deployments = [];
    public function createDeployment($time, $application, $version, $environment) {
        // Create a new deployment and add it to the deployments array
        $deployment = [
            'time' => $time,
            'application' => $application,
            'version' => $version,
            'environment' => $environment
        ];
        $this->deployments[] = $deployment;
    }
    public function getDeployments() {
        // Return the current deployed versions
        return $this->deployments;
    }
}
?>
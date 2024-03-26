<?php
// DeploymentController.php
require_once 'DeploymentModel.php';
class DeploymentController {
    private $deploymentModel;
    public function __construct() {
        $this->deploymentModel = new DeploymentModel();
    }
    public function createDeployment($data) {
        // Validate and sanitize the input data
        $time = isset($data['time']) ? trim($data['time']) : '';
        $application = isset($data['application']) ? trim($data['application']) : '';
        $version = isset($data['version']) ? trim($data['version']) : '';
        $environment = isset($data['environment']) ? trim($data['environment']) : '';
        // Create a new deployment
        $this->deploymentModel->createDeployment($time, $application, $version, $environment);
    }
    public function displayDeployments() {
        // Get the current deployed versions
        $deployments = $this->deploymentModel->getDeployments();
        // Include the deployment list view
        require 'deployment_list.php';
    }
}
?>
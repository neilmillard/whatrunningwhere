<?php
// index.php
// Include the DeploymentController
require_once 'DeploymentController.php';
// Create an instance of the DeploymentController
$deploymentController = new DeploymentController();
// Check if a deployment form submission is made
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $deploymentController->createDeployment($_POST);
}
// Display the current deployed versions
$deploymentController->displayDeployments();
?>
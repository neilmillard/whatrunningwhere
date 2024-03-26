<?php
// deployment_list.php
?>
<h2>Current Deployed Versions</h2>
<table>
    <tr>
        <th>Time</th>
        <th>Application</th>
        <th>Version</th>
        <th>Environment</th>
    </tr>
    <?php foreach ($deployments as $deployment): ?>
        <tr>
            <td><?php echo $deployment['time']; ?></td>
            <td><?php echo $deployment['application']; ?></td>
            <td><?php echo $deployment['version']; ?></td>
            <td><?php echo $deployment['environment']; ?></td>
        </tr>
    <?php endforeach; ?>
</table>
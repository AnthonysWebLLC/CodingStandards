<style type="text/css">
	success {
		color: green;
	}
	failure {
		color: red;
	}
</style>

<h2>
	Coding Standards Full Report
	[Generated <?php echo $reportDateTime; ?>]
	(<?php echo sprintf('%01.2f', $secondsRan); ?>s)
</h2>

<?php
foreach ($checkResults as $checkName => $checkResult) { ?>
	<br />
	<h3>
		<?php echo $checkName; ?>
		(<?php echo sprintf('%01.2f', $checkResult['secondsRan']); ?>s)
	</h3>
	<pre><?php echo $checkResult['output']; ?></pre>
	<?php
}

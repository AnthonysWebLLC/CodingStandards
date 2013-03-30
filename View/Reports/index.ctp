<?php /* Todo: Add Report types to view when we have more than one type */ ?>
<div class="reports view">
	<h2>Coding Standard Reports Index</h2>
	<table cellpadding="0" cellspacing="0">
		<tr>
			<?php
				/*
				<th>
					Type
				</th>
				*/
			?>
			<th>
				Datetime
			</th>
		</tr>
<?php foreach ($reports as $report) { ?>
			<tr>
				<?php
					/*
					<td>
						<?php echo $report['type']; ?>
					</td>
					*/
				?>
				<td>
					<?php echo $this->Html->link($report['datetime'], array('controller' => 'reports', 'action' => 'view', $report['type'], $report['datetime'])); ?>
				</td>
			</tr>
	<?php
}
?>
	</table>
</div>
<?php echo $this->element('menu');

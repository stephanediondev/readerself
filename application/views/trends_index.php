<div id="content">
	<h1><i class="icon icon-bar-chart"></i><?php echo $this->lang->line('trends'); ?></h1>

	<p>From your <strong><?php echo $subscriptions_total; ?> subscriptions,</strong> over the last 30 days <strong>you read <?php echo $read_items_30; ?> items</strong> and <strong>starred <?php echo $starred_items_30; ?> items</strong>.</p>

	<?php if($date_first_read) { ?>
		<p>Since <strong><?php echo $date_first_read; ?></strong> you have read a total of <strong><?php echo $read_items_total; ?></strong> items.</p>
	<?php } ?>

	<div style="margin-top:20px;">
		<?php echo $tables; ?>
		<p>* the last 30 days</p>
	</div>

</div>

<main class="mdl-layout__content mdl-color--grey-100">
	<div class="mdl-grid">
		<div class="mdl-card mdl-cell mdl-cell--12-col">
			<div class="mdl-card__title mdl-color-text--white mdl-color--teal">
				<h1 class="mdl-card__title-text"><i class="material-icons md-18">insert_chart</i><?php echo $this->lang->line('statistics'); ?></h1>
			</div>
			<div class="mdl-card__supporting-text mdl-color-text--black">
				<p><em>*<?php echo $this->lang->line('last_30_days'); ?></em></p>

				<p>From your <strong><?php echo $subscriptions_total; ?> subscriptions,</strong> over the <strong>last 30 days</strong> you <strong>read <?php echo $read_items_30; ?> items</strong>.</p>
				<?php if($date_first_read) { ?>
					<p>Since <strong><?php echo $date_first_read_nice; ?></strong> you have <strong>read a total of <?php echo $read_items_total; ?> items</strong>.</p>
				<?php } ?>
			</div>
		</div>

		<?php echo $tables; ?>
	</div>
</main>

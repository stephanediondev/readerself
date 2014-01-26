	<nav>
	</nav>
</header>
<main>
	<section>
		<section>

	<article class="title">
		<h2><i class="icon icon-gears"></i><?php echo $this->lang->line('settings'); ?></h2>
	</article>

	<h2><i class="icon icon-wrench"></i><?php echo $this->lang->line('update'); ?></h2>

	<?php echo validation_errors(); ?>

	<?php echo form_open(current_url()); ?>

	<?php foreach($settings as $stg) { ?>
		<p>
		<?php echo form_label($this->lang->line('stg_'.$stg->stg_code), $stg->stg_code); ?>
		<?php if($stg->stg_type == 'boolean') { ?>
			<?php echo form_dropdown($stg->stg_code, array(0 => $this->lang->line('no'), 1 => $this->lang->line('yes')), set_value($stg->stg_code, $stg->stg_value), 'id="'.$stg->stg_code.'"'); ?>
		<?php } else { ?>
			<?php echo form_input($stg->stg_code, set_value($stg->stg_code, $stg->stg_value), 'id="'.$stg->stg_code.'"'); ?>
		<?php } ?>
		<?php if($stg->stg_note) { ?> <em><?php echo $stg->stg_note; ?></em><?php } ?>
		</p>
	<?php } ?>

	<p>
	<button type="submit"><?php echo $this->lang->line('send'); ?></button>
	</p>

	<?php echo form_close(); ?>
		</section>
	</section>
</main>

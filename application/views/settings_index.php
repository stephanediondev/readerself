<main class="mdl-layout__content mdl-color--grey-100">
	<div class="mdl-grid">
		<div class="mdl-card mdl-cell mdl-cell--12-col">
			<div class="mdl-card__title mdl-color-text--white mdl-color--teal">
				<h1 class="mdl-card__title-text"><i class="material-icons md-18">settings</i><?php echo $this->lang->line('settings'); ?></h1>
			</div>
		</div>

		<div class="mdl-card mdl-cell mdl-cell--4-col allow_notifications">
			<div class="mdl-card__title">
				<h1 class="mdl-card__title-text"><?php echo $this->lang->line('allow_notifications'); ?></h1>
			</div>
			<div class="mdl-card__actions mdl-card--border">
				<a class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon" href="#"><i class="material-icons md-18">add</i></a></li>
			</div>
		</div>

		<div class="mdl-card mdl-cell mdl-cell--4-col">
			<div class="mdl-card__title">
				<h1 class="mdl-card__title-text">Bookmarklet</h1>
			</div>
			<div class="mdl-card__actions mdl-card--border">
				<a class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon" href="javascript:void(function(){window.open('<?php echo base_url(); ?>?u='+encodeURIComponent(window.location.href),'_blank');}());" title="<?php echo $this->config->item('title'); ?> (bookmarklet)"><i class="material-icons md-18">add</i></a></li>
			</div>
		</div>

		<div class="mdl-card mdl-cell mdl-cell--4-col registerContentHandler">
			<div class="mdl-card__title">
				<h1 class="mdl-card__title-text"><?php echo $this->lang->line('registerContentHandler'); ?></h1>
			</div>
			<div class="mdl-card__actions mdl-card--border">
				<a class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon" href="#"><i class="material-icons md-18">add</i></a></li>
			</div>
		</div>

		<?php if($this->member->mbr_administrator == 1) { ?>
			<div class="mdl-card mdl-cell mdl-cell--12-col">
				<div class="mdl-card__title">
					<h1 class="mdl-card__title-text"><?php echo $this->lang->line('update'); ?></h1>
				</div>
				<div class="mdl-card__supporting-text mdl-color-text--grey">
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
					<button type="submit" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon mdl-color--pink mdl-color-text--white">
						<i class="material-icons md-24">done</i>
					</button>
					</p>
	
					<?php echo form_close(); ?>
				</div>
			</div>
		<?php } ?>
	</div>
</main>

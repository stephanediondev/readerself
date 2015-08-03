<div class="mdl-tooltip" for="tip_back"><?php echo $this->lang->line('back'); ?></div>

<main class="mdl-layout__content mdl-color--grey-100">
	<div class="mdl-grid">
		<div class="mdl-card mdl-cell mdl-cell--12-col">
			<div class="mdl-card__title mdl-color-text--white mdl-color--teal">
				<h1 class="mdl-card__title-text"><i class="material-icons md-18">bookmark</i><?php echo $this->lang->line('subscriptions'); ?></h1>
			</div>
			<div class="mdl-card__actions mdl-card--border">
				<a id="tip_back" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon" href="<?php echo base_url(); ?>subscriptions"><i class="material-icons md-18">arrow_back</i></a>
			</div>
		</div>

		<div class="mdl-card mdl-cell mdl-cell--4-col">
			<div class="mdl-card__title">
				<h1 class="mdl-card__title-text"><?php echo $this->lang->line('add'); ?></h1>
			</div>
			<div class="mdl-card__supporting-text mdl-color-text--grey">
				<?php echo validation_errors(); ?>

				<?php echo form_open(current_url()); ?>

				<?php if($error) { ?>
					<p><?php echo $error; ?></p>
				<?php } ?>

				<p>
				<?php echo form_label($this->lang->line('url'), 'url'); ?>
				<?php if(count($feeds) > 0) { ?>
					<?php echo form_dropdown('url', $feeds, set_value('url', ''), 'id="url" class="select required numeric"'); ?>
					<?php echo form_hidden('analyze_done', '1'); ?>
				<?php } else { ?>
					<?php echo form_input('url', set_value('url', $this->input->get('u')), 'id="url" class="input-xlarge required"'); ?>
				<?php } ?>
				</p>

				<?php if($this->config->item('folders')) { ?>
					<p>
					<?php echo form_label($this->lang->line('folder'), 'folder'); ?>
					<?php echo form_dropdown('folder', $folders, set_value('folder', ''), 'id="folder" class="select required numeric"'); ?>
					</p>
				<?php } ?>

				<p>
				<?php echo form_label($this->lang->line('priority'), 'priority'); ?>
				<?php echo form_dropdown('priority', array(0 => $this->lang->line('no'), 1 => $this->lang->line('yes')), set_value('priority', 0), 'id="priority" class="select numeric"'); ?>
				</p>

				<p>
				<?php echo form_label($this->lang->line('direction'), 'direction'); ?>
				<?php echo form_dropdown('direction', array('' => '-', 'ltr' => $this->lang->line('direction_ltr'), 'rtl' => $this->lang->line('direction_rtl')), set_value('direction', ''), 'id="direction" class="select numeric"'); ?>
				</p>

				<p>
				<button type="submit" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon mdl-color--pink mdl-color-text--white">
					<i class="material-icons md-24">done</i>
				</button>
				</p>

				<?php echo form_close(); ?>
			</div>
		</div>
	</div>
</main>

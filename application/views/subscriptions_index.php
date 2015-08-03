<div class="mdl-layout__drawer">
	<nav class="mdl-navigation">
		<ul>
		<?php if($last_added) { ?>
			<?php foreach($last_added as $added) { ?>
				<li<?php if($added->sub_direction) { ?> dir="<?php echo $added->sub_direction; ?>"<?php } else if($added->fed_direction) { ?> dir="<?php echo $added->fed_direction; ?>"<?php } ?>><a style="background-image:url(https://www.google.com/s2/favicons?domain=<?php echo $added->fed_host; ?>&amp;alt=feed);" class="favicon mdl-navigation__link" href="<?php echo base_url(); ?>subscriptions/read/<?php echo $added->sub_id; ?>"><?php echo $added->fed_title; ?></a></li>
			<?php } ?>
		<?php } ?>
		</ul>
	</nav>
</div>

<main class="mdl-layout__content mdl-color--grey-100">
	<div class="mdl-grid">
		<div class="mdl-card mdl-cell mdl-cell--12-col">
			<div class="mdl-card__title">
				<h1 class="mdl-card__title-text"><?php echo $this->lang->line('subscriptions'); ?> (<?php echo $position; ?>)</h1>
			</div>
			<div class="mdl-card__actions mdl-card--border">
				<a href="javascript:void(function(){window.open('<?php echo base_url(); ?>?u='+encodeURIComponent(window.location.href),'_blank');}());"><i class="icon icon-bookmark"></i><?php echo $this->config->item('title'); ?> (bookmarklet)</a>
				<a href="#"><i class="icon icon-save"></i><?php echo $this->lang->line('registerContentHandler'); ?></a>
				<a href="<?php echo base_url(); ?>subscriptions/create"><i class="icon icon-plus"></i><?php echo $this->lang->line('add'); ?></a>
				<a href="<?php echo base_url(); ?>subscriptions/import"><i class="icon icon-download-alt"></i><?php echo $this->lang->line('import'); ?></a>
				<a href="<?php echo base_url(); ?>subscriptions/export"><i class="icon icon-upload-alt"></i><?php echo $this->lang->line('export'); ?></a>
			</div>
		</div>

<?php if($subscriptions) { ?>
	<?php foreach($subscriptions as $sub) { ?>
		<div<?php if($sub->sub_direction) { ?> dir="<?php echo $sub->sub_direction; ?>"<?php } else if($sub->fed_direction) { ?> dir="<?php echo $sub->fed_direction; ?>"<?php } ?> class="mdl-card mdl-cell mdl-cell--6-col">
			<div class="mdl-card__title">
				<h1 class="mdl-card__title-text"><a style="background-image:url(https://www.google.com/s2/favicons?domain=<?php echo $sub->fed_host; ?>&amp;alt=feed);" class="favicon" href="<?php echo base_url(); ?>subscriptions/read/<?php echo $sub->sub_id; ?>"><?php echo $sub->fed_title; ?><?php if($sub->sub_title) { ?> / <em><?php echo $sub->sub_title; ?></em><?php } ?></a></h1>
				<div class="mdl-card__title-infos">
					<?php if($this->config->item('folders')) { ?>
						<?php if($sub->flr_title) { ?><a href="<?php echo base_url(); ?>folders/read/<?php echo $sub->flr_id; ?>"><i class="icon icon-folder-close"></i><?php echo $sub->flr_title; ?></a><?php } else { ?><i class="icon icon-folder-close"></i><em><?php echo $this->lang->line('no_folder'); ?></em><?php } ?>
					<?php } ?>
					<?php if($sub->fed_url) { ?>
						<a href="<?php echo $sub->fed_url; ?>" target="_blank"><i class="icon icon-external-link"></i><?php echo $sub->fed_url; ?></a>
					<?php } ?>
					<?php if($this->config->item('tags') && $sub->categories) { ?>
						<i class="icon icon-tags"></i><?php echo implode(', ', $sub->categories); ?>
					<?php } ?>
					<?php if($sub->fed_lasterror) { ?>
						<i class="icon icon-bell"></i><?php echo $sub->fed_lasterror; ?>
					<?php } ?>
				</div>
			</div>
		<div class="mdl-card__supporting-text mdl-color-text--grey">
			<?php echo $sub->fed_description; ?>
		</div>
		<div class="mdl-card__actions mdl-card--border">
			<a class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon subscribe" href="<?php echo base_url(); ?>subscriptions/subscribe/<?php echo $sub->sub_id; ?>"><i class="material-icons md-18">bookmark</i></a>
			<a class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon priority" href="<?php echo base_url(); ?>subscriptions/priority/<?php echo $sub->sub_id; ?>"><?php if($sub->sub_priority == 0) { ?><i class="material-icons md-18">chat_bubble_outline</i><?php } ?><?php if($sub->sub_priority == 1) { ?><i class="material-icons md-18">announcement</i><?php } ?></a>
			<a class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon" href="<?php echo base_url(); ?>subscriptions/update/<?php echo $sub->sub_id; ?>"><i class="material-icons md-18">mode_edit</i></a>
		</div>
	</div>
	<?php } ?>
	<div class="paging">
		<?php echo $pagination; ?>
	</div>
<?php } ?>
	</div>
</main>

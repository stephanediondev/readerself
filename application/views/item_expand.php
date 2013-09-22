<div class="item-content-result">
	<?php echo $itm->itm_content; ?>
</div>
<?php if($itm->itm_latitude && $itm->itm_longitude) { ?>
	<div class="item-geolocation">
		<p><a target="_blank" href="http://maps.google.com/maps?q=<?php echo $itm->itm_latitude; ?>,<?php echo $itm->itm_longitude; ?>&oe=UTF-8&ie=UTF-8"><i class="icon icon-map-marker"></i><?php echo $itm->itm_latitude; ?>,<?php echo $itm->itm_longitude; ?></a><?php if($this->session->userdata('latitude') && $this->session->userdata('longitude')) { ?> (<?php echo haversineGreatCircleDistance($itm->itm_latitude, $itm->itm_longitude, $this->session->userdata('latitude'), $this->session->userdata('longitude')); ?>)<?php } ?></p>
		<a target="_blank" href="http://maps.google.com/maps?q=<?php echo $itm->itm_latitude; ?>,<?php echo $itm->itm_longitude; ?>&oe=UTF-8&ie=UTF-8"><img src="http://maps.googleapis.com/maps/api/staticmap?center=<?php echo $itm->itm_latitude; ?>,<?php echo $itm->itm_longitude; ?>&markers=color:red|<?php echo $itm->itm_latitude; ?>,<?php echo $itm->itm_longitude; ?>&zoom=12&size=540x200&sensor=false" alt=""></a>
	</div>
<?php } ?>
<?php if($itm->enclosures) { ?>
	<div class="item-enclosures">
		<?php foreach($itm->enclosures as $enr) { ?>
			<?php $filename = substr($enr->enr_link, strrpos($enr->enr_link, '/') + 1); ?>
			<?php if(stristr($filename, '?')) { ?><?php $filename = substr($filename, 0, strpos($filename, '?'));?><?php } ?>

			<?php if(stristr($enr->enr_type, 'image/') && $filename != '') { ?>
				<p><a target="_blank" href="<?php echo $enr->enr_link; ?>"><i class="icon icon-picture"></i><?php echo $filename; ?></a></p>
				<?php if($enr->enr_length == 0 || $enr->enr_length <= 1048576) { ?><a target="_blank" href="<?php echo $enr->enr_link; ?>"><img src="<?php echo $enr->enr_link; ?>" alt=""></a><?php } ?>

			<?php } else if(stristr($enr->enr_type, 'audio/') && $filename != '') { ?>
				<p><a target="_blank" href="<?php echo $enr->enr_link; ?>"><i class="icon icon-volume-up"></i><?php echo $filename; ?></a></p>
				<audio controls>
					<source src="<?php echo $enr->enr_link; ?>" type="<?php echo $enr->enr_type; ?>">
				</audio>

			<?php } else if($enr->enr_type == 'video/vimeo' && $filename != '') { ?>
				<p><a target="_blank" href="http://vimeo.com/<?php echo $filename; ?>"><i class="icon icon-youtube-play"></i><?php echo $filename; ?> (Vimeo)</a></p>
				<iframe allowfullscreen src="<?php echo $enr->enr_link; ?>" width="<?php echo $enr->enr_width; ?>" height="<?php echo $enr->enr_height; ?>"></iframe>

			<?php } else if($enr->enr_type == 'video/dailymotion' && $filename != '') { ?>
				<p><a target="_blank" href="http://www.dailymotion.com/video/<?php echo $filename; ?>"><i class="icon icon-youtube-play"></i><?php echo $filename; ?> (Dailymotion)</a></p>
				<iframe allowfullscreen src="<?php echo $enr->enr_link; ?>" width="<?php echo $enr->enr_width; ?>" height="<?php echo $enr->enr_height; ?>"></iframe>

			<?php } else if($enr->enr_type == 'video/youtube' && $filename != '') { ?>
				<p><a target="_blank" href="http://www.youtube.com/watch?v=<?php echo $filename; ?>"><i class="icon icon-youtube-play"></i><?php echo $filename; ?> (Youtube)</a></p>
				<iframe allowfullscreen src="http://www.youtube.com/embed/<?php echo $filename; ?>?rel=0" width="<?php echo $enr->enr_width; ?>" height="<?php echo $enr->enr_height; ?>"></iframe>

			<?php } else if(stristr($enr->enr_type, 'video/') && $filename != '') { ?>
				<p><a target="_blank" href="<?php echo $enr->enr_link; ?>"><i class="icon icon-youtube-play"></i><?php echo $filename; ?></a></p>
				<video width="<?php echo $enr->enr_width; ?>" height="<?php echo $enr->enr_height; ?>" controls>
					<source src="<?php echo $enr->enr_link; ?>" type="<?php echo $enr->enr_type; ?>">
				</video>
			<?php } ?>
		<?php } ?>
	</div>
<?php } ?>

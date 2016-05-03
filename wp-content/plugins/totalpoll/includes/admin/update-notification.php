<div class="updated fade">
    <p>
	<?php _e('There is an new update for TotalPoll plugin.', TP_TD); ?>
	<a href="<?php echo TP_WEBSITE; ?>?changelog&current=<?php echo TP_VERSION; ?>&new=<?php echo $last_version; ?>" target="_blank"><?php _e('Check it out!', TP_TD); ?></a> | 
	<a href="#" onclick="jQuery(this).parents('.updated').slideUp();jQuery.get('<?php echo admin_url('?tp_dismiss_update=' . $last_version) ?>')">
	    <?php _e('Dismiss', TP_TD); ?>
	</a>
    </p>
</div>
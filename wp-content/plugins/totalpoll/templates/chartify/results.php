<?php
if ( !defined('ABSPATH') ) exit; // Shhh
$tp_ch_canvas = uniqid('results-');
?>
<div class="tp-results">
    <div class="canvas-holder">
        <?php $tp_ch_width = tp_preset_options('charts', 'size', false, false, 300); ?>
        <div style="margin: 0 auto;<?php echo !empty($tp_ch_width) ? 'max-width: ' . $tp_ch_width . 'px;' : ''; ?>">
            <canvas id="<?php echo $tp_ch_canvas; ?>" class="tp-chart"></canvas>
        </div>
    </div>
    <div class="map-holder"></div>
</div>

<div class="tp-buttons">
    <?php if ( display_poll_buttons() ): ?>
        <form method="post">
	    <?php other_poll_buttons(); ?>
    	<input type="hidden" name="tp_poll_id" value="<?php echo get_poll_id(); ?>">
	    <?php if ( user_can_vote() ): ?>
		<button name="tp_action" value="back" class="tp-btn tp-back-btn"><?php _e('Back', TP_TD); ?></button>
	    <?php endif; ?>
        </form>
    <?php endif; ?>
</div>

<script type="text/javascript">
    setTimeout(function(){  
        
        chartify.init(<?php printf("'%s', '%s', %s, %s, '%s', %s", 
            $tp_ch_canvas, 
            tp_preset_options('charts', 'type', false, false, 'pie'), 
            json_encode(tp_ch_get_data()), 
            json_encode(tp_ch_get_options_array()), 
            $poll->misc->show_results,
            json_encode( array(
                'novotes' => _n('%s Vote', '%s Votes', 0, TP_TD),
                'vote' => _n('%s Vote', '%s Votes', 1, TP_TD),
                'votes' => _n('%s Vote', '%s Votes', 2, TP_TD),
            ))
        ); ?>);
                
    }, 800);
</script>

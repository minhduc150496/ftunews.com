<?php
if ( !defined('ABSPATH') ) exit; // Shhh
add_action('tp_poll_enqueue_assets', 'tp_ch_enqueue_charts');

// Enqueue Charts
function tp_ch_enqueue_charts() {
    wp_register_script('chart-js', tp_get_template_url('chart.min.js'));
    wp_enqueue_script('tp-chartify-template', tp_get_template_url('chartify-template.min.js'), array('jquery', 'chart-js'));
}

function tp_ch_get_data() {
    global $poll;
    $results = array();
    foreach( get_poll_choices() as $index => $choice ):
        if(isset($poll->template->preset->settings->colors->choice[$index])) {
            $color = $poll->template->preset->settings->colors->choice[$index];
            $highlight = isset($poll->template->preset->settings->colors->highlight[$index]) ? $poll->template->preset->settings->colors->highlight[$index] : adjustBrightness($color, '20');
        }else{
            $color = tp_ch_generate_color();
            $highlight = adjustBrightness($color, '20');
        }
        
        $results[$index] = array(
            'value' => $choice->votes,
            'percentage' => $choice->votes_percentage,
            'color' => $color,
            'highlight' => $highlight,
            'label' => empty($choice->text) ? empty($choice->label) ? '' : $choice->label : $choice->text
        );
    endforeach;
    
    return $results;
}

// Adjust the brightness
function adjustBrightness($hex, $steps) {
    // Steps should be between -255 and 255. Negative = darker, positive = lighter
    $steps = max(-255, min(255, $steps));

    // Format the hex color string
    $hex = str_replace('#', '', $hex);
    if (strlen($hex) == 3) {
        $hex = str_repeat(substr($hex,0,1), 2).str_repeat(substr($hex,1,1), 2).str_repeat(substr($hex,2,1), 2);
    }

    // Get decimal values
    $r = hexdec(substr($hex,0,2));
    $g = hexdec(substr($hex,2,2));
    $b = hexdec(substr($hex,4,2));

    // Adjust number of steps and keep it inside 0 to 255
    $r = max(0,min(255,$r + $steps));
    $g = max(0,min(255,$g + $steps));  
    $b = max(0,min(255,$b + $steps));

    $r_hex = str_pad(dechex($r), 2, '0', STR_PAD_LEFT);
    $g_hex = str_pad(dechex($g), 2, '0', STR_PAD_LEFT);
    $b_hex = str_pad(dechex($b), 2, '0', STR_PAD_LEFT);

    return '#'.$r_hex.$g_hex.$b_hex;
}

// Hex color generator
function tp_ch_generate_color() {
    $colors = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'A', 'B', 'C', 'D', 'E', 'F');
    $color = '#';
    for($i=1;$i<=6;$i++) {
        $color .= $colors[array_rand($colors)];
    }
    
    return $color;
}

// Colors & Highlights custom fields
function tp_ch_pie_colors($args)
{
    global $poll;
    $html = $color_generator = '';
    
    foreach( $poll->choices as $index => $choice ):
        // Colors
        $color = array_key_exists($index, $args->colors) ? $args->colors[$index] : tp_ch_generate_color();
        $field_color = array(
            'type' => 'color',
            'label' => sprintf($args->label, $index+1),
            'default' => isset($poll->template->preset->settings->colors->choice[$index]) ? $poll->template->preset->settings->colors->choice[$index] : $color,
        );
        
        // Highlights
        $field_highlight = array(
            'type' => 'color',
            'label' => sprintf($args->labelHighlight, $index+1),
            'default' => isset($poll->template->preset->settings->colors->highlight[$index]) ? $poll->template->preset->settings->colors->highlight[$index] : adjustBrightness($color, '20'),
        );
        $html .= '<div class="settings-field-container">';
        $html .= TotalPoll('customizer')->field('colors', "choice", json_decode(json_encode($field_color), false), "[$index]"); // Color
        $html .= '<br /><br />';
        $html .= TotalPoll('customizer')->field('colors', "highlight", json_decode(json_encode($field_highlight), false), "[$index]"); // Highlight
        $html .= '</div>';
    endforeach;
    
    return $html;
}

// Get charts options array
function tp_ch_get_options_array() {
    global $poll;
    
    $charts_options = array();
    
    foreach(tp_preset_options('charts') as $index => $value) {
        $charts_options[$index] = tp_preset_options('charts', $index);
    }
    
    return $charts_options;
}

?>
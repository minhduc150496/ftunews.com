<?php
if ( !defined('ABSPATH') ) exit; // Shhh

$settings = array(
    'sections' => array(
        'general' => array(
            'label' => __('General settings', TP_TD),
            'fields' => array(
		'choicesPerRow' => array(
		    'type' => 'text',
		    'label' => __('Choices per row', TP_TD),
		    'default' => '1',
		),
                'containerBorder' => array(
                    'type' => 'color',
                    'label' => __('Container border', TP_TD),
                    'default' => '#DDDDDD',
                ),
                'containerBackground' => array(
                    'type' => 'color',
                    'label' => __('Container background', TP_TD),
                    'default' => '#FFFFFF',
                ),
                'warningBackground' => array(
                    'type' => 'color',
                    'label' => __('Warning messages background', TP_TD),
                    'default' => '#FFF9E8',
                ),
                'warningBorder' => array(
                    'type' => 'color',
                    'label' => __('Warning messages border', TP_TD),
                    'default' => '#E8D599',
                ),
                'warningColor' => array(
                    'type' => 'color',
                    'label' => __('Warning messages text color', TP_TD),
                    'default' => '#333333',
                ),
                'questionBackground' => array(
                    'type' => 'color',
                    'label' => __('Question background', TP_TD),
                    'default' => '#EEEEEE',
                ),
                'questionColor' => array(
                    'type' => 'color',
                    'label' => __('Question color', TP_TD),
                    'default' => '#333333',
                ),
                'choiceColor' => array(
                    'type' => 'color',
                    'label' => __('Choice color', TP_TD),
                    'default' => '#333333',
                ),
                'choiceInputBackground' => array(
                    'type' => 'color',
                    'label' => __('Checkbox background', TP_TD),
                    'default' => '#EEEEEE',
                ),
                'animationDuration' => array(
                    'type' => 'text',
                    'label' => __('Animation duration (ms)', TP_TD),
                    'default' => '1000',
                ),
                'borderRadius' => array(
                    'type' => 'text',
                    'label' => __('Border radius (px)', TP_TD),
                    'default' => '2',
                )
            )
        ),
        'buttons' => array(
            'label' => __('Buttons', TP_TD),
            'fields' => array(
                'background' => array(
                    'type' => 'color',
                    'label' => __('Background', TP_TD),
                    'default' => '#EEEEEE',
                    'states' => array( 'hover' => array( 'label' => 'Hover', 'default' => '#E5E5E5' ) ),
                ),
                'primaryBackground' => array(
                    'type' => 'color',
                    'label' => __('Primary background', TP_TD),
                    'default' => '#1E73BE',
                    'states' => array( 'hover' => array( 'label' => 'Hover', 'default' => '#308DDF' ) ),
                ),
                'color' => array(
                    'type' => 'color',
                    'label' => __('Default color', TP_TD),
                    'default' => '#333333',
                    'states' => array( 'hover' => array( 'label' => 'Hover', 'default' => '#333333' ) ),
                ),
                'primaryColor' => array(
                    'type' => 'color',
                    'label' => __('Primary color', TP_TD),
                    'default' => '#FFFFFF',
                    'states' => array( 'hover' => array( 'label' => 'Hover', 'default' => '#FFFFFF' ) ),
                ),
                'borderColor' => array(
                    'type' => 'color',
                    'label' => __('Border color', TP_TD),
                    'default' => '#CCCCCC',
                    'states' => array( 'hover' => array( 'label' => 'Hover', 'default' => '#CCCCCC' ) ),
                ),
                'primaryBorderColor' => array(
                    'type' => 'color',
                    'label' => __('Border color', TP_TD),
                    'default' => '#1B66A8',
                    'states' => array( 'hover' => array( 'label' => 'Hover', 'default' => '#1E73BE' ) ),
                ),
            )
        ),
        'charts' => array(
            'label' => __('Charts', TP_TD),
            'fields' => array(
                'type' => array(
                    'type' => 'select',
                    'label' => __('Chart Type', TP_TD),
                    'default' => 'doughnut',
                    'options' => array(
                        array(
                            'value' => 'Pie',
                            'label' => __('Pie', TP_TD)
                        ),
                        array(
                            'value' => 'Doughnut',
                            'label' => __('Doughnut', TP_TD)
                        ),
                        array(
                            'value' => 'PolarArea',
                            'label' => __('Polar Area', TP_TD)
                        )
                    )
                ),
                'size' => array(
                    'type' => 'text',
                    'label' => __('Canvas Max Size (px)', TP_TD),
                    'default' => '',
                ),
                'animation' => array(
                    'type' => 'select',
                    'label' => __('Chart Animation', TP_TD),
                    'default' => 'true',
                    'options' => array(
                        array(
                            'value' => 'true',
                            'label' => __('Enabled', TP_TD)
                        ),
                        array(
                            'value' => 'false',
                            'label' => __('Disabled', 'custon-domain-name')
                        )
                    )
                ),
                'animationEasing' => array(
                    'type' => 'select',
                    'label' => __('Animation Easing', TP_TD),
                    'default' => 'easeOutQuart',
                    'options' => array(
                        array(
                            'value' => 'linear',
                            'label' => __('linear','custon-domain-name')
                        ),array(
                            'value' => 'swing',
                            'label' => __('swing','custon-domain-name')
                        ),array(
                            'value' => 'easeOutQuad',
                            'label' => __('easeOutQuad','custon-domain-name')
                        ),array(
                            'value' => 'easeOutCubic',
                            'label' => __('easeOutCubic','custon-domain-name')
                        ),array(
                            'value' => 'easeOutQuart',
                            'label' => __('easeOutQuart','custon-domain-name')
                        ),array(
                            'value' => 'easeOutQuint',
                            'label' => __('easeOutQuint','custon-domain-name')
                        ),array(
                            'value' => 'easeOutExpo',
                            'label' => __('easeOutExpo','custon-domain-name')
                        ),array(
                            'value' => 'easeOutSine',
                            'label' => __('easeOutSine','custon-domain-name')
                        ),array(
                            'value' => 'easeOutCirc',
                            'label' => __('easeOutCirc','custon-domain-name')
                        ),array(
                            'value' => 'easeOutElastic',
                            'label' => __('easeOutElastic','custon-domain-name')
                        ),array(
                            'value' => 'easeOutBack',
                            'label' => __('easeOutBack','custon-domain-name')
                        ),array(
                            'value' => 'easeOutBounce',
                            'label' => __('easeOutBounce','custon-domain-name')
                        )
                    )
                ),
                'map' => array(
                    'type' => 'select',
                    'label' => __('Legend Map', TP_TD),
                    'default' => 'below',
                    'options' => array(
                        array(
                            'value' => 'none',
                            'label' => __('Labels On Tooltip', TP_TD)
                        ),
                        array(
                            'value' => 'below',
                            'label' => __('Below', TP_TD)
                        ),
                        array(
                            'value' => 'float',
                            'label' => __('Float', 'custon-domain-name')
                        )
                    )
                ),
                'mapBackground' => array(
                    'type' => 'color',
                    'label' => __('Map Backround', TP_TD),
                    'default' => '#F4F4F4',
                    'states' => array( 'hover' => array( 'label' => __('Hover', TP_TD), 'default' => '#FAFAFA' ) ),
                ),
                'hoverBorder' => array(
                    'type' => 'color',
                    'label' => __('Map Hover Border', TP_TD),
                    'default' => '#6D6D6D'
                ),
                'tooltipBgColor' => array(
                    'type' => 'color',
                    'label' => __('Tooltip Fill Color', TP_TD),
                    'default' => '#4D4D4D'
                ),
                'tooltipFontColor' => array(
                    'type' => 'color',
                    'label' => __('Tooltip Font Color', TP_TD),
                    'default' => '#FFFFFF'
                )
            )
        ),
        'colors' => array(
            'label' => __('Chart Colors', TP_TD),
            'fields' => array(
                'pieColors' => array(
                    'type' => 'tp_ch_pie_colors',
                    'label' => __('Color for choice #%s', TP_TD),
                    'labelHighlight' => __('Highlight color for choice #%s', TP_TD),
                    'colors' => array( '#FFCE80', '#CC363A', '#76CCFF' )
                )
            )
        ),
	'typography' => array(
	    'label' => __('Typography', TP_TD),
	    'fields' => array(
		'lineHeight' => array(
		    'type' => 'text',
		    'label' => __('Line height', TP_TD),
		    'default' => 'inherit',
		),
		'fontFamily' => array(
		    'type' => 'text',
		    'label' => __('Font Family', TP_TD),
		    'default' => 'inherit',
		),
		'fontSize' => array(
		    'type' => 'text',
		    'label' => __('Font Size', TP_TD),
		    'default' => '1rem',
		),
	    )
	),
    )
);


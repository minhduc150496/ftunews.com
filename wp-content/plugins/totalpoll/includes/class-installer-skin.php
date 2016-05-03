<?php

if ( !defined('ABSPATH') )
    exit; // Shhh

/**
 * Installer Skin.
 * 
 * @since 2.0.0
 * @package TotalPoll\Installer\Skin
 */

class TP_Installer_Skin extends Theme_Installer_Skin {

    /**
     * Return URL.
     * @since 2.0.0
     * @return void
     */
    public function after()
    {
	if ( $this->upgrader->type == 'template' ):
	    $this->feedback('<a href="' . self_admin_url('edit.php?post_type=poll&page=tp-templates-manager') . '" title="' . esc_attr__('Return to Templates Installer', TP_TD) . '" target="_parent">' . __('Return to Templates', TP_TD) . '</a>');
	else:
	    $this->feedback('<a href="' . self_admin_url('edit.php?post_type=poll&page=tp-addons-manager') . '" title="' . esc_attr__('Return to Addons Installer', TP_TD) . '" target="_parent">' . __('Return to Addons', TP_TD) . '</a>');
	endif;
    }

}

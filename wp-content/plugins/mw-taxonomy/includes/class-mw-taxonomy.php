<?php

class MW_taxonomy{

	protected $version;
	protected $option_name;
	protected $plugin_include_path;

	public function __construct(){
		$this->version = '0.0.1';
		$this->option_name = 'mw_taxonomy';
		$this->plugin_include_path = plugin_dir_path( __FILE__ );
	}

	public function run(){
		$this->actions();
		$this->load_files();
		if(is_admin()){
			require_once $this->plugin_include_path . 'controller/class-mw-taxonomy-admin.php';
			$admin = new MW_taxonomy_admin( $this->version, $this->option_name, $this->plugin_include_path );
		}
	}
	
	function load_files(){
		require $this->plugin_include_path . 'model/class-mw-taxonomy-widget.php';
		require $this->plugin_include_path . 'model/class-mw-taxonomy-register.php';
		
	}

	function actions(){
		add_action( 'init', array( $this, 'reg_taxonomies' ) );
		add_action( 'widgets_init', array( $this, 'register_my_widgets' ) );
	}
	
	function register_my_widgets(){
		register_widget( 'MW_taxonomy_widget' );
	}
	
	function reg_taxonomies(){
		$options = get_option( $this->option_name, false );
		if( is_array( $options ) ){
			foreach( $options as $slug => $values ){
				$tax_reg = new MW_taxonomy_register( $this->version, $slug, $values );
				$tax_reg->register();
			}
		}
	}
	


}

?>
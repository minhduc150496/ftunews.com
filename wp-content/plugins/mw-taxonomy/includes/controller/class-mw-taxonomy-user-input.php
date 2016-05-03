<?php
class MW_taxonomy_user_input{

	function get_selected_post_type_values(){
		$selected_post_type = array();
		if( isset( $_POST['mw_selected_post_type'] ) ){
			if( is_array( $_POST['mw_selected_post_type'] ) ){
				foreach( $_POST['mw_selected_post_type'] as $input ){
					$selected_post_type[] =  sanitize_text_field( $input );
				}
			}
			else{
				$selected_post_type[0] =  sanitize_text_field( $_POST['mw_selected_post_type'] )."<br>";
			}
		}
		return $selected_post_type;
	}

	function get_values_from_post( $mode = '' ){
		$error = '';
		$slug = '';
		$values = array(
			"name" => "",
			"singular_name" => "",
			"description" => "",
			"post_type" => "",
			"hierarchical" => ""
		);
		
		// check and sanitize all post data from user
		// send back error codes if something is missing
		if( isset( $_POST ) ){
			
			// check field name
			if( isset( $_POST['mw_taxonomy_name'] ) and $_POST['mw_taxonomy_name'] ){
				$values['name'] = sanitize_text_field( $_POST['mw_taxonomy_name'] );
			}
			else{
				$error[] = __( 'No name is set' );
			}

			// check field singular_name
			if( isset( $_POST['mw_taxonomy_singular_name'] ) and $_POST['mw_taxonomy_singular_name'] ){
				$values['singular_name'] = sanitize_text_field( $_POST['mw_taxonomy_singular_name'] );
			}
			else{
				$error[] = __( 'No singular name is set' );
			}
			
			// check field description
			if( isset( $_POST['mw_taxonomy_description'] ) and $_POST['mw_taxonomy_description'] ){
				$values['description'] = sanitize_text_field( $_POST['mw_taxonomy_description'] );
			}
			
			// check post type
			$post_type_set = false;
			if( isset( $_POST['mw_taxonomy_post_type'] ) and is_array( $_POST['mw_taxonomy_post_type'] ) ){
				foreach( $_POST['mw_taxonomy_post_type'] as $post_type ){
					$post_type_arr[] = sanitize_text_field( $post_type );
					$post_type_set = true;
				}
				$values['post_type'] = $post_type_arr;
			}
			if( ! $post_type_set ){
				$error[] = __( 'No post type set' );
			}

			// check if hierarchical value is ok
			if( $_POST['mw_taxonomy_hierarchical'] == 'Yes' or $_POST['mw_taxonomy_hierarchical'] == 'No' ){
				$values['hierarchical'] = sanitize_text_field( $_POST['mw_taxonomy_hierarchical'] );
			}
			else{
				$error[] = __( 'Hierarchical value not set' );
			}
			

			// sanitize slug
			if( $mode == 'update' or $mode == 'delete' ){
				$slug = sanitize_text_field( $_POST['mw_taxonomy_slug'] );
//				print "\$slug: $slug<br>";
			}
			$ret = array( $error, $slug, $values );
		}
		return $ret;
	}
	
	function get_post_slug(){
		return sanitize_text_field( $_POST['mw_taxonomy_slug'] );		
	}
	
	function get_values_from_get( $taxonomies ){
		$update_slug = sanitize_text_field( $_GET['tax'] );
		$arr = "";
		foreach( $taxonomies as $slug => $values ){
			if( $slug == $update_slug ){
				$arr = $values;
				break;
			}
		}
		return array( $update_slug, $arr );
	}

}

?>
<?php
class MW_taxonomy_reserved_terms{

	private $reserved_terms = 'attachment, attachment_id, author, author_name, calendar, cat, category, category__and, category__in, category__not_in, category_name, comments_per_page, comments_popup, customize_messenger_channel, customized, cpage, day, debug, error, exact, feed, fields, hour, link_category, m, minute, monthnum, more, name, nav_menu, nonce, nopaging, offset, order, orderby, p, page, page_id, paged, pagename, pb, perm, post, post__in, post__not_in, post_format, post_mime_type, post_status, post_tag, post_type, posts, posts_per_archive_page, posts_per_page, preview, robots, s, search, second, sentence, showposts, static, subpost, subpost_id, tag, tag__and, tag__in, tag__not_in, tag_id, tag_slug__and, tag_slug__in, taxonomy, tb, term, theme, type, w, withcomments, withoutcomments, year';
	private $reserved_terms_array;
	
	function __construct(){
		$this->reserved_terms_array = explode( ', ', $this->reserved_terms );
	}
		
	public function is_a_reserved_term( $term ){
		return in_array( $term, $this->reserved_terms_array );
	}
	
}

?>
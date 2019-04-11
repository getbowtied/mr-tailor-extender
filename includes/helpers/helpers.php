<?php

function mt_theme_warning() {
	?>

	<div class="error">
		<p>Mr. Tailor Extender plugin couldn't find the Block Editor (Gutenberg) on this site. 
			It requires WordPress 5+ or Gutenberg installed as a plugin.</p>
	</div>

	<?php
}

function is_mt_wp_version( $operator = '>', $version = '4.0' ) {

	global $wp_version;

	return version_compare( $wp_version, $version, $operator );
}

function mt_bool_to_string( $bool ) {
	$bool = is_bool( $bool ) ? $bool : ( 'yes' === $bool || 1 === $bool || 'true' === $bool || '1' === $bool );

	return true === $bool ? 'yes' : 'no';
}

function mt_string_to_bool( $string ) {
	return is_bool( $string ) ? $string : ( 'yes' === $string || 1 === $string || 'true' === $string || '1' === $string );
}
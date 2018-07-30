( function( blocks, components, editor, i18n, element ) {

	var el = element.createElement;

	/* Blocks */
	var registerBlockType   = blocks.registerBlockType;

	var AlignmentToolbar	= editor.AlignmentToolbar;
	var BlockControls       = editor.BlockControls;

	var TextControl 		= components.TextControl;

	/* Register Block */
	registerBlockType( 'getbowtied/socials', {
		title: i18n.__( 'Social Media Profiles' ),
		icon: 'share',
		category: 'shopkeeper',
		attributes: {
			items_align: {
				type: 	 'string',
				default: 'left'
			},
		},

		edit: function( props ) {

			var attributes = props.attributes;

			return [
				el(
					BlockControls,
					{ 
						key: 'social-media-controls'
					},
					el(
						AlignmentToolbar, 
						{
							key: 'social-media-alignment',
							value: attributes.items_align,
							onChange: function( newAlignment ) {
								props.setAttributes( { items_align: newAlignment } );
							}
						} 
					),
				),
				el( 
					'div',
					{ 
						key: 'wp-block-gbt-social-media',
						className: 'wp-block-gbt-social-media'
					},
					el(
						'h4',
						{
							key: 'wp-block-gbt-social-media-h4',
							className: 'wp-block-gbt-social-media-h4',
						},
						el(
							'span',
							{
								key: 'wp-block-gbt-social-media-dashicon',
								className: 'dashicon dashicons-share',
							},
						),
						i18n.__('Social Media Icons')
					),
					el(
						'p',
						{
							key: 'wp-block-gbt-social-media-p',
							className: 'wp-block-gbt-social-media-p',
						},
						i18n.__('Setup profile links under Appearance > Customize > Social Media')
					),
				),
			];
		},

		save: function() {
			return '';
		},
	} );

} )(
	window.wp.blocks,
	window.wp.components,
	window.wp.editor,
	window.wp.i18n,
	window.wp.element,
	jQuery
);
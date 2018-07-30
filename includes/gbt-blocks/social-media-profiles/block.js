( function( blocks, components, editor, i18n, element ) {

	var el = element.createElement;

	/* Blocks */
	var registerBlockType   = blocks.registerBlockType;

	var AlignmentToolbar	= editor.AlignmentToolbar;
	var BlockControls       = editor.BlockControls;
	var InspectorControls   = editor.InspectorControls;

	var TextControl 		= components.TextControl;
	var ColorPalette	    = components.ColorPalette;
	var PanelColor			= components.PanelColor;
	var RangeControl		= components.RangeControl;

	/* Register Block */
	registerBlockType( 'getbowtied/mt-socials', {
		title: i18n.__( 'Social Media Profiles' ),
		icon: 'share',
		category: 'mrtailor',
		attributes: {
			fontSize: {
				type: 	 'number',
				default: '24'
			},
			fontColor: {
				type: 	 'string',
				default: '#000'
			},
			items_align: {
				type: 	 'string',
				default: 'left'
			},
		},

		edit: function( props ) {

			var attributes = props.attributes;

			var colors = [
				{ name: 'red', 				color: '#d02e2e' },
				{ name: 'orange', 			color: '#f76803' },
				{ name: 'yellow', 			color: '#fbba00' },
				{ name: 'green', 			color: '#43d182' },
				{ name: 'blue', 			color: '#2594e3' },
				{ name: 'light-gray', 		color: '#eeeeee' },
				{ name: 'dark-gray', 		color: '#abb7c3' },
				{ name: 'black', 			color: '#000' 	 },
			];

			return [
				el( 
					InspectorControls, 
					{ 
						key: 'socials-settings'
					},
					el(
						'hr',
						{
							key: 'socials-hr'
						}
					),
					el(
						RangeControl,
						{
							key: "socials-font-size",
							value: attributes.fontSize,
							allowReset: false,
							initialPosition: 24,
							min: 10,
							max: 36,
							label: i18n.__( 'Icons Font Size' ),
							onChange: function( newNumber ) {
								props.setAttributes( { fontSize: newNumber } );
							},
						}
					),
					el(
						PanelColor,
						{
							key: 'socials-color-panel',
							title: i18n.__( 'Icons Color' ),
							colorValue: attributes.fontColor,
						},
						el(
							ColorPalette, 
							{
								key: 'socials-color-pallete',
								colors: colors,
								value: attributes.fontColor,
								onChange: function( newColor) {
									props.setAttributes( { fontColor: newColor } );
								},
							} 
						),
					),
				),
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
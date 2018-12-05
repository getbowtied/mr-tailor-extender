( function( blocks, components, editor, i18n, element ) {

	const el = element.createElement;

	/* Blocks */
	const registerBlockType   	= blocks.registerBlockType;

	const AlignmentToolbar		= editor.AlignmentToolbar;
	const BlockControls       	= editor.BlockControls;
	const InspectorControls   	= editor.InspectorControls;
	const ColorSettings			= editor.PanelColorSettings;

	const TextControl 			= components.TextControl;
	const RangeControl			= components.RangeControl;

	/* Register Block */
	registerBlockType( 'getbowtied/mt-social-media-profiles', {
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
			align: {
				type: 	 'string',
				default: 'left'
			},
		},

		edit: function( props ) {

			let attributes = props.attributes;

			return [
				el( 
					InspectorControls, 
					{ 
						key: 'gbt_18_mt_socials_settings'
					},
					el(
						'div',
						{
							className: 'main-inspector-wrapper',
						},
						el(
							RangeControl,
							{
								key: "gbt_18_mt_socials_font_size",
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
							ColorSettings,
							{
								key: 'gbt_18_mt_socials_icons_color',
								title: i18n.__( 'Icons Color' ),
								colorSettings: [
									{ 
										label: i18n.__( 'Icons Color' ),
										value: attributes.fontColor,
										onChange: function( newColor) {
											props.setAttributes( { fontColor: newColor } );
										},
									},
								]
							},
						),
					),
				),
				el(
					BlockControls,
					{ 
						key: 'gbt_18_mt_socials_alignment_controls'
					},
					el(
						AlignmentToolbar, 
						{
							key: 'gbt_18_mt_socials_alignment',
							value: attributes.align,
							onChange: function( newAlignment ) {
								props.setAttributes( { align: newAlignment } );
							}
						} 
					),
				),
				el( 
					'div',
					{ 
						key: 		'gbt_18_mt_editor_social_media_wrapper',
						className: 	'gbt_18_mt_editor_social_media_wrapper'
					},
					el(
						'h4',
						{
							key: 		'gbt_18_mt_editor_social_media_title',
							className: 	'gbt_18_mt_editor_social_media_title',
						},
						el(
							'span',
							{
								key: 		'gbt_18_mt_editor_social_media_icon',
								className: 	'gbt_18_mt_editor_social_media_icon dashicon dashicons-share',
							},
						),
						i18n.__('Social Media Icons')
					),
					el(
						'p',
						{
							key: 		'gbt_18_mt_editor_social_media_setup',
							className: 	'gbt_18_mt_editor_social_media_setup',
						},
						i18n.__('Setup profile links under Appearance > Customize > Social Media')
					),
				),
			];
		},

		save: function() {
			return null;
		},
	} );

} )(
	window.wp.blocks,
	window.wp.components,
	window.wp.editor,
	window.wp.i18n,
	window.wp.element
);
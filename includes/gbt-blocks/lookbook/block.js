( function( blocks, i18n, element ) {

	var el = element.createElement;

	/* Blocks */
	var registerBlockType   = wp.blocks.registerBlockType;
		
	var InspectorControls 	= wp.editor.InspectorControls;
	var RichText			= wp.editor.RichText;
	var BlockControls		= wp.editor.BlockControls;
	var MediaUpload			= wp.editor.MediaUpload;

	var TextControl 		= wp.components.TextControl;
	var ToggleControl		= wp.components.ToggleControl;
	var RangeControl		= wp.components.RangeControl;
	var SelectControl		= wp.components.SelectControl;
	var Button				= wp.components.Button;
	var PanelBody			= wp.components.PanelBody;
	var ColorPalette		= wp.components.ColorPalette;
	var PanelColor			= wp.components.PanelColor;

	/* Register Block */
	registerBlockType( 'getbowtied/mt-lookbook', {
		title: i18n.__( 'Lookbook' ),
		icon: 'book',
		category: 'mrtailor',
		supports: {
			align: [ 'center', 'wide', 'full' ],
		},
		attributes: {
			title: {
				type: 'string',
				default: 'Lookbook Title',
			},
			subtitle: {
				type: 'string',
				default: 'Lookbook Subtitle',
			},
			titleColor: {
				type: 'string',
				default: '#fff'
			},
			subtitleColor: {
				type: 'string',
				default: '#fff'
			},
			bgColor: {
				type: 'string',
				default: '#e4e4e4'
			},
			imgURL: {
	            type: 'string',
	            attribute: 'src',
	        },
	        imgID: {
	            type: 'number',
	        },
	        imgAlt: {
	            type: 'string',
	            attribute: 'alt',
	        },
			products: {
				type: 'string',
				default: '',
			},
			columns: {
				type: 'number',
				default: '2',
			},
			height: {
				type: 'number',
				default: '400',
			},
			orderBy: {
				type: 'string',
				default: 'date',
			},
			order: {
				type: 'string',
				default: 'asc',
			},
		},

		edit: function( props ) {

			var attributes = props.attributes;

			var orderby_options = [
				{ value: 'none', 	label: 'None' 	},
				{ value: 'ID', 		label: 'ID' 	},
				{ value: 'title', 	label: 'Title' 	},
				{ value: 'date', 	label: 'Date' 	},
				{ value: 'rand', 	label: 'Rand' 	},
			];

			var colors = [
				{ name: 'red', 				color: '#d02e2e' },
				{ name: 'orange', 			color: '#f76803' },
				{ name: 'yellow', 			color: '#fbba00' },
				{ name: 'green', 			color: '#43d182' },
				{ name: 'blue', 			color: '#2594e3' },
				{ name: 'white', 			color: '#ffffff' },
				{ name: 'dark-gray', 		color: '#abb7c3' },
				{ name: 'black', 			color: '#000' 	 },
			];

			return [
				el(
					InspectorControls,
					{ key: 'inspector' },
					el( 'hr', { key: 'lookbook-hr' } ),
					el(
						TextControl,
						{
							key: 'lookbook-products-option',
              				label: i18n.__( 'Products' ),
              				type: 'text',
              				help: i18n.__('Insert product IDs between commas. Example: 12,56,76'),
              				value: attributes.products,
              				onChange: function( newIds ) {
              					props.setAttributes( { products: newIds } );
							},
						},
					),
					el( 
						PanelBody,
						{ 
							key: 'lookbook-display-panel',
							title: 'Display Settings',
							initialOpen: false
						},
						el(
							RangeControl,
							{
								key: "lookbook-height",
								value: attributes.height,
								allowReset: false,
								initialPosition: 400,
								min: 200,
								max: 1000,
								label: i18n.__( 'Height' ),
								onChange: function( newNumber ) {
									props.setAttributes( { height: newNumber } );
								},
							}
						),
						el(
							TextControl,
							{
								key: "lookbook-columns",
								type: "number",
								value: attributes.columns,
								min: 2,
								max: 3,
								label: i18n.__( 'Columns' ),
								onChange: function( newNumber ) {
									props.setAttributes( { columns: newNumber } );
								},
							}
						),
					),
					el( 
						PanelBody,
						{ 
							key: 'lookbook-colors-panel',
							title: 'Colors',
							initialOpen: false
						},
						el(
							PanelColor,
							{
								key: 'lookbook-title-color-panel',
								title: i18n.__( 'Title Color' ),
								colorValue: attributes.titleColor,
							},
							el(
								ColorPalette, 
								{
									key: 'lookbook-title-color-pallete',
									colors: colors,
									value: attributes.titleColor,
									onChange: function( newColor) {
										props.setAttributes( { titleColor: newColor } );
									},
								} 
							),
						),
						el(
							PanelColor,
							{
								key: 'lookbook-subtitle-color-panel',
								title: i18n.__( 'Subtitle Color' ),
								colorValue: attributes.subtitleColor,
							},
							el(
								ColorPalette, 
								{
									key: 'lookbook-subtitle-color-pallete',
									colors: colors,
									value: attributes.subtitleColor,
									onChange: function( newColor) {
										props.setAttributes( { subtitleColor: newColor } );
									},
								} 
							),
						),
						el(
							PanelColor,
							{
								key: 'lookbook-bg-color-panel',
								title: i18n.__( 'Background Color' ),
								colorValue: attributes.titleColor,
							},
							el(
								ColorPalette, 
								{
									key: 'lookbook-bg-color-pallete',
									colors: colors,
									value: attributes.bgColor,
									onChange: function( newColor) {
										props.setAttributes( { bgColor: newColor } );
									},
								} 
							),
						),
					),
					el( 
						PanelBody,
						{ 
							key: 'lookbook-order-panel',
							title: 'Products Order',
							initialOpen: false
						},
						el(
							SelectControl,
							{
								key: 'lookbook-orderby',
								options: orderby_options,
	              				label: i18n.__( 'Order By' ),
	              				value: attributes.orderBy,
	              				onChange: function( newOrderBy ) {
	              					props.setAttributes( { orderBy: newOrderBy } );
								},
							}
						),
						el(
							SelectControl,
							{
								key: 'lookbook-order',
								options: [{ value: 'asc', label: 'Ascending' }, { value: 'desc', label: 'Descending' }],
	              				label: i18n.__( 'Order' ),
	              				value: attributes.order,
	              				onChange: function( newOrder ) {
	              					props.setAttributes( { order: newOrder } );
								},
							}
						),
					),
				),
				el(
					'div', 
					{ 
						key: 'wp-block-gbt-lookbook',
						className: 'wp-block-gbt-lookbook',
					},
					el(
						'div', 
						{ 
							key: 'lookbook-media-wrapper',
							id: 'lookbook-wrapper',
							className: 'lookbook-media-wrapper',
						},
						el(
							MediaUpload,
							{
								key: 'lookbook-image-upload',
								type: 'image',
								formattingControls: [ 'align' ],
								buttonProps: { className: 'components-button button button-large' },
		              			value: attributes.imgID,
								onSelect: function( img ) {
									props.setAttributes( {
										imgID: img.id,
										imgURL: img.url,
										imgAlt: img.alt,
									} );
								},
		              			render: function( img ) { 
		              				return [
			              				! attributes.imgID && el(
			              					Button, 
			              					{ 
			              						key: 'lookbook-add-image-button',
			              						className: 'button add-image',
			              						onClick: img.open
			              					},
			              					i18n.__( 'Add Image' )
		              					), 
		              					!! attributes.imgID && el(
		              						Button, 
											{
												key: 'lookbook-remove-image-button',
												className: 'button remove-image',
												onClick: function() {
													img.close;
													props.setAttributes({
										            	imgID: null,
										            	imgURL: null,
										            	imgAlt: null,
										            });
												}
											},
											i18n.__( 'Remove Image' )
										), 
		              				];
		              			},
							},
						),
						el(
							'div',
							{
								key: 'lookbook-content-wrapper',
								className: 'lookbook-content-wrapper',
								style:
								{
									backgroundImage: 'url(' + attributes.imgURL + ')',
									backgroundColor: attributes.bgColor,
									height: attributes.height + 'px'
								},
							},
							el(
								'div',
								{
									key: 'lookbook-text-wrapper',
									className: 'lookbook-text-wrapper',
								},
								el(
									'div',
									{
										key: 'lookbook-title-wrapper'
									},
									el(
										RichText, 
										{
											key: 'lookbook-title',
											className: 'lookbook-title',
											formattingControls: [],
											tagName: 'h3',
											format: 'string',
											style: { color: attributes.titleColor },
											value: attributes.title,
											placeholder: i18n.__( 'Add Title' ),
											onChange: function( newTitle) {
												props.setAttributes( { title: newTitle } );
											}
										}
									),
								),
								el(
									'div',
									{
										key: 'lookbook-subtitle-wrapper',
									},
									el(
										RichText, 
										{
											key: 'lookbook-subtitle',
											className: 'lookbook-subtitle',
											tagName: 'h4',
											format: 'string',
											style: { color: attributes.subtitleColor },
											value: attributes.subtitle,
											formattingControls: [],
											placeholder: i18n.__( 'Add Subtitle' ),
											onChange: function( newSubtitle) {
												props.setAttributes( { subtitle: newSubtitle } );
											}
										}
									),

								),
							),
						),
					),
				),
			];
		},
		save: function( props ) {
			return '';
		},
	} );

} )(
	window.wp.blocks,
	window.wp.i18n,
	window.wp.element,
);
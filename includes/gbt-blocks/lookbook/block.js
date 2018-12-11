( function( blocks, components, editor, i18n, element ) {

	"use strict";

	const el = element.createElement;

	/* Blocks */
	const registerBlockType   	= blocks.registerBlockType;

	const InspectorControls 	= editor.InspectorControls;
	const ColorSettings			= editor.PanelColorSettings;
	const MediaUpload			= editor.MediaUpload;
	const RichText				= editor.RichText;

	const TextControl 			= components.TextControl;
	const RangeControl			= components.RangeControl;
	const Button 				= components.Button;
	const SVG 					= components.SVG;
	const Path 					= components.Path;
	
	const apiFetch 				= wp.apiFetch;

	/* Register Block */
	registerBlockType( 'getbowtied/mt-lookbook', {
		title: i18n.__( 'Lookbook' ),
		icon:
			el( SVG, { xmlns:'http://www.w3.org/2000/svg', viewBox:'0 0 24 24' },
				el( Path, { d:'M4 6H2v14c0 1.1.9 2 2 2h14v-2H4V6zm16-4H8c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm-3 2v5l-1-.75L15 9V4h2zm3 12H8V4h5v9l3-2.25L19 13V4h1v12z' } ),
			),
		category: 'mrtailor',
		supports: {
			align: [ 'center', 'wide', 'full' ],
		},
		attributes: {
			productIDs: {
				type: 'string',
				default: '',
			},
		/* Products source */
			queryProducts: {
				type: 'string',
				default: '',
			},
			queryProductsLast: {
				type: 'string',
				default: '',
			},
		/* loader */
			isLoading: {
				type: 'bool',
				default: false,
			},
		/* Manually pick products */
			querySearchString: {
				type: 'string',
				default: '',
			},
			querySearchResults: {
				type: 'array',
				default: [],
			},
			querySearchNoResults: {
				type: 'bool',
				default: false,
			},
			querySearchSelected: {
				type: 'array',
				default: [],
			},
			selectedIDS: {
				type: 'string',
				default: '',
			},
			/* Colors */
			titleColor: {
				type: 'string',
				default: '#fff'
			},
			subtitleColor: {
				type: 'string',
				default: '#fff'
			},
			backgroundColor: {
				type: 'string',
				default: '#464646'
			},
			productColor: {
				type: 'string',
				default: '#fff'
			},
			/* Title & Subtitle */
			title: {
				type: 'string',
				default: 'Lookbook Title',
			},
			subtitle: {
				type: 'string',
				default: 'Lookbook Subtitle',
			},
			/* Lookbook Image */
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
	        /* Columns */
	        columns: {
				type: 'number',
				default: '3',
			},
			/* Heoght */
			height: {
				type: 'number',
				default: '750',
			},
		},
		edit: function( props ) {

			let attributes = props.attributes;

		//==============================================================================
		//	Helper functions
		//==============================================================================
			
			function _searchResultClass(theID){
				const index = toArray(attributes.selectedIDS).indexOf(theID);
				if ( index == -1) {
					return 'single-result';
				} else {
					return 'single-result selected';
				}
			}

			function _sortByKeys(keys, products) {
				let sorted =[];
				for ( let i = 0; i < keys.length; i++ ) {
					for ( let j = 0; j < products.length; j++ ) {
						if ( keys[i] == products[j].id ) {
							sorted.push(products[j]);
							break;
						}
					}
				}

				return sorted;
			}

			function _destroyQuery() {
				props.setAttributes({ queryOrder: ''});
				props.setAttributes({ queryProducts: ''});
				props.setAttributes({ querySearchString: ''});
				props.setAttributes({ querySearchResults: []});
				props.setAttributes({ querySearchSelected: []});
			}

			function _destroyTempAtts() {
				props.setAttributes({ querySearchString: ''});
				props.setAttributes({ querySearchResults: []});
			}

			function _isChecked( needle, haystack ) {
				const idx = haystack.indexOf(needle.toString());
				if ( idx != - 1) {
					return true;
				}
				return false;
			}

			function _isDonePossible() {
				return ( (attributes.queryProducts.length == 0) || (attributes.queryProducts === attributes.queryProductsLast) );
			}

			function _isLoading() {
				if ( attributes.isLoading  === true ) {
					return 'is-busy';
				} else {
					return '';
				}
			}

			function _isLoadingText(){
				if ( attributes.isLoading  === false ) {
					return i18n.__('Update');
				} else {
					return i18n.__('Updating');
				}
			}

			function toArray(s) {
                let ret = [];
                if ( s.length > 0 ) {
                    ret = s.split(",");
                }
                for ( let i = 0; i < ret.length; i++) {
                    if ( ret[i] == '') {
                        ret.splice(i, 1);
                    } else {
                        ret[i] = Number(ret[i]);
                    }
                }
                return ret;
            }

		//==============================================================================
		//	Show products functions
		//==============================================================================
			function getQuery( query ) {
				return '/wc/v2/products' + query;
			}

			function getProducts() {
				const query = attributes.queryProducts;
				props.setAttributes({ queryProductsLast: query});

				if (query != '') {
					apiFetch({ path: query }).then(function (products) {
						props.setAttributes({ isLoading: false});
						let IDs = '';
						for ( let i = 0; i < products.length; i++) {
							IDs += products[i].id + ',';
						}
						props.setAttributes({ productIDs: IDs});
					});
				}
			}

			function _queryOrder(value) {
				let query = attributes.queryProducts;
				const idx = query.indexOf('&orderby');
				if ( idx > -1) {
					query = query.substring(idx, -25);
				}

				switch ( value ) {
					case 'date_desc':
						query +='&orderby=date&order=desc';
					break;
					case 'date_asc':
						query +='&orderby=date&order=asc';
					break;
					case 'title_desc':
						query +='&orderby=title&order=desc';
					break;
					case 'title_asc':
						query +='&orderby=title&order=asc';
					break;
					default: 
						
					break;
				}
				props.setAttributes({ queryProducts: query });
			}

			function _getQueryOrder() {
				if ( attributes.queryOrder.length < 1) return '';
				let order = '';
				switch ( attributes.queryOrder ) {
					case 'date_desc':
						order = '&orderby=date&order=desc';
					break;
					case 'date_asc':
						order = '&orderby=date&order=asc';
					break;
					case 'title_desc':
						order = '&orderby=title&order=desc';
					break;
					case 'title_asc':
						order = '&orderby=title&order=asc';
					break;
					default: 
						
					break;
				}

				return order;
			}

		//==============================================================================
		//	Display ajax results
		//==============================================================================
			function renderSearchResults() {
				let productElements = [];

				if ( attributes.querySearchNoResults === true) {
					return el('span', {className: 'no-results'}, i18n.__('No products matching.'));
				}
				let products = attributes.querySearchResults;
				for (let i = 0; i < products.length; i++ ) {
					let img = '';
					if ( typeof products[i].images[0].src !== 'undefined' && products[i].images[0].src != '' ) {
						img = el('span', { className: 'img-wrapper', dangerouslySetInnerHTML: { __html: '<span class="img" style="background-image: url(\''+products[i].images[0].src+'\')"></span>'}});
					} else {
						img = el('span', { className: 'img-wrapper', dangerouslySetInnerHTML: { __html: '<span class="img" style="background-image: url(\''+getbowtied_pbw.woo_placeholder_image+'\')"></span>'}});
					}
					productElements.push(
						el(
							'span', 
							{
								key: 		'item-' + products[i].id +i,
								className: _searchResultClass(products[i].id),
								title: products[i].name,
								'data-index': i,
							}, 
							img,
							el(
								'label', 
								{
									className: 'title-wrapper'
								},
								el(
									'input',
									{
										key: 'selection-input-key',
										type: 'checkbox',
										value: i,
										onChange: function onChange(evt) {
											const _this = evt.target;
											let qSR = toArray(attributes.selectedIDS);
											let index = qSR.indexOf(products[evt.target.value].id);
											if (index == -1) {
												qSR.push(products[evt.target.value].id);
											} else {
												qSR.splice(index,1);
											}
											props.setAttributes({ selectedIDS: qSR.join(',') });
											
											let query = getQuery('?include=' + qSR.join(',') + '&orderby=include');
											if ( qSR.length > 0 ) {
												props.setAttributes({queryProducts: query});
											} else {
												props.setAttributes({queryProducts: '' });
											}
											apiFetch({ path: query }).then(function (products) {
												props.setAttributes({ querySearchSelected: products});
											});
										},
									},
								),
								products[i].name,
								el('span',{ className: 'dashicons dashicons-yes'}),
								el('span',{ className: 'dashicons dashicons-no-alt'}),
							),
						)
					);
				}
				return productElements;
			}

			function renderSearchSelected() {
				let productElements = [];
				const products = attributes.querySearchSelected;

				for ( let i = 0; i < products.length; i++ ) {
					let img= '';
					if ( typeof products[i].images[0].src !== 'undefined' && products[i].images[0].src != '' ) {
						img = el('span', { className: 'img-wrapper', dangerouslySetInnerHTML: { __html: '<span class="img" style="background-image: url(\''+products[i].images[0].src+'\')"></span>'}});
					} else {
						img = el('span', { className: 'img-wrapper', dangerouslySetInnerHTML: { __html: '<span class="img" style="background-image: url(\''+getbowtied_pbw.woo_placeholder_image+'\')"></span>'}});
					}
					productElements.push(
						el(
							'span', 
							{
								key: 		'item-' + products[i].id,
								className:'single-result', 
								title: products[i].name,
							}, 
							img, 
							el(
								'label', 
								{
									className: 'title-wrapper'
								},
								el(
									'input',
									{
										type: 'checkbox',
										value: i,
										onChange: function onChange(evt) {
											const _this = evt.target;

											
											let qSS = toArray(attributes.selectedIDS);
											console.log(qSS);

											if ( qSS.length < 1 && attributes.querySearchSelected.length > 0) {
												for ( let i = 0; i < attributes.querySearchSelected.length; i++ ) {
													qSS.push(attributes.querySearchSelected[i].id);
												}
											}
											let index = qSS.indexOf(products[evt.target.value].id);
											if (index != -1) {
												qSS.splice(index,1);
											}
											props.setAttributes({ selectedIDS: qSS.join(',') });
											
											let query = getQuery('?include=' + qSS.join(',') + '&orderby=include');
											if ( qSS.length > 0 ) {
												props.setAttributes({queryProducts: query});
											} else {
												props.setAttributes({queryProducts: ''});
											}
											apiFetch({ path: query }).then(function (products) {
												props.setAttributes({ querySearchSelected: products});
											});
										},
									},
								),
								products[i].name,
								el('span',{ className: 'dashicons dashicons-no-alt'})
							),
						)
					);
				}
				return productElements;
			}

		//==============================================================================
		//	Main controls 
		//==============================================================================
			return [
				el(
					InspectorControls,
					{
					},
					el(
						'div',
						{
							className: 'main-inspector-wrapper',
						},
					/* Pick specific producs */
						el(
							'div',
							{
								className: 'products-ajax-search-wrapper',
							},
							el(
								TextControl,
								{
									key: 'query-panel-string',
			          				type: 'search',
			          				className: 'products-ajax-search',
			          				value: attributes.querySearchString,
			          				placeholder: i18n.__( 'Search for products to display'),
			          				onChange: function( newQuery ) {
			          					props.setAttributes({ querySearchString: newQuery});
			          					if (newQuery.length < 3) return;

								        const query = getQuery('?per_page=10&search=' + newQuery);
								        apiFetch({ path: query }).then(function (products) {
								        	if ( products.length == 0) {
								        		props.setAttributes({ querySearchNoResults: true});
								        	} else {
								        		props.setAttributes({ querySearchNoResults: false});
								        	}
											props.setAttributes({ querySearchResults: products});
										});

									},
								},
							),
						),
						attributes.querySearchResults.length > 0 && attributes.querySearchString != '' && el(
							'div',
							{ 
								className: 'products-ajax-search-results',
							},
							renderSearchResults(),
						),
						attributes.querySearchSelected.length > 0 && el(
							'div',
							{
								className: 'products-selected-results-wrapper',
							},
							el(
								'label',
								{},
								i18n.__('Selected Products:'),
							),
							el(
								'div',
								{
									className: 'products-selected-results',
								},
								renderSearchSelected(),
							),
						),
					/* Load all products */
						el(
							'button',
							{
								className: 'render-results components-button is-button is-default is-primary is-large ' + _isLoading(),
								disabled: _isDonePossible(),
								onClick: function onChange(e) {
									props.setAttributes({ isLoading: true });
									_destroyTempAtts();
									getProducts();
								},
							},
							_isLoadingText(),
						),
					),
					el(
						RangeControl,
						{
							key: "gbt_18_mt_lookbook_height",
							value: attributes.height,
							allowReset: false,
							initialPosition: 750,
							min: 200,
							max: 1000,
							label: i18n.__( 'Height' ),
							onChange: function( newNumber ) {
								props.setAttributes( { height: newNumber } );
							},
						}
					),
					el(
						RangeControl,
						{
							key: "gbt_18_mt_lookbook_columns",
							value: attributes.columns,
							allowReset: false,
							initialPosition: 3,
							min: 2,
							max: 3,
							label: i18n.__( 'Columns' ),
							onChange: function( newNumber ) {
								props.setAttributes( { columns: newNumber } );
							},
						}
					),
					el(
						ColorSettings,
						{
							key: 'gbt_18_mt_lookbook_color_settings',
							title: i18n.__( 'Colors' ),
							initialOpen: false,
							colorSettings: [
								{ 
									label: i18n.__( 'Title Color' ),
									value: attributes.titleColor,
									onChange: function( newColor) {
										props.setAttributes( { titleColor: newColor } );
									},
								},
								{ 
									label: i18n.__( 'Subtitle Color' ),
									value: attributes.subtitleColor,
									onChange: function( newColor) {
										props.setAttributes( { subtitleColor: newColor } );
									},
								},
								{ 
									label: i18n.__( 'Product Title Color' ),
									value: attributes.productColor,
									onChange: function( newColor) {
										props.setAttributes( { productColor: newColor } );
									},
								},
								{ 
									label: i18n.__( 'Background Color' ),
									value: attributes.backgroundColor,
									onChange: function( newColor) {
										props.setAttributes( { backgroundColor: newColor } );
									},
								}
							]
						},
					),
				),
				el(
					'div', 
					{ 
						key: 'gbt_18_mt_lookbook',
						className: 'gbt_18_mt_lookbook',
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
									backgroundColor: attributes.backgroundColor,
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
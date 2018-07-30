( function( blocks, i18n, element ) {

	var el = element.createElement;

	/* Blocks */
	var registerBlockType   = wp.blocks.registerBlockType;

	var InspectorControls 	= wp.editor.InspectorControls;

	var TextControl 		= wp.components.TextControl;
	var RadioControl        = wp.components.RadioControl;
	var SelectControl		= wp.components.SelectControl;
	var ToggleControl		= wp.components.ToggleControl;
	var RangeControl		= wp.components.RangeControl;

	var categories_list = [];

	function escapeHtml(text) {
	  	return text
	    	.replace("&amp;", '&')
	    	.replace("&lt;", '<')
	    	.replace("&gt;", '>')
	     	.replace("&quot;", '"')
	    	.replace("&#039;", "'");
	}

	async function getCategories(categories_list) { 
	 	const categories = await wp.apiRequest( { path: '/wp/v2/categories?per_page=-1' } );

	 	var i;
	 	categories_list[0] = {value: '0', label: "All Categories"};
	 	for(i = 0; i < categories.length; i++) {
	 		var category = {value: categories[i]['id'], label: escapeHtml(categories[i]['name'])};
	 		categories_list[i+1] = category;
	 	}
	 } 

	getCategories(categories_list);

	/* Register Block */
	registerBlockType( 'getbowtied/latest-posts-grid', {
		title: i18n.__( 'Latest Posts Grid' ),
		icon: 'grid-view',
		category: 'shopkeeper',
		supports: {
			align: [ 'center', 'wide', 'full' ],
		},
		attributes: {
			number: {
				type: 'number',
				default: '12'
			},
			category: {
				type: 'string',
				default: 'All Categories'
			},
			categories : {
				type: 'array',
				default: categories_list
			},
			columns: {
				type: 'number',
				default: '3'
			},
			grid: {
				type: 'string',
				default: ''
			}
		},

		edit: function( props ) {

			var attributes = props.attributes;

			function getLatestPosts( category, number, columns ) {

				category = category || attributes.category;
				number   = number   || attributes.number;
				columns  = columns  || attributes.columns;

				var data = {
					action 		: 'getbowtied_render_backend_latest_posts_grid',
					attributes  : {
						'category' : category,
						'number'   : number,
						'columns'  : columns,
					}
				};

				jQuery.post( 'admin-ajax.php', data, function(response) { 
					response = jQuery.parseJSON(response);
					props.setAttributes( { grid: response } );
				});	
			}

			return [
				el(
					InspectorControls,
					{
						key: 'latest-posts-inspector'
					},
					el('hr', {} ),
					el(
						RangeControl,
						{
							key: "latest-posts-columns",
							value: attributes.columns,
							allowReset: false,
							initialPosition: 3,
							min: 1,
							max: 4,
							label: i18n.__( 'Columns' ),
							onChange: function( newColumns ) {
								props.setAttributes( { columns: newColumns } );
								getLatestPosts( null, null, newColumns);
							},
						}
					),
					el(
						RangeControl,
						{
							key: "latest-posts-number",
							value: attributes.number,
							allowReset: false,
							label: i18n.__( 'Number of Posts' ),
							onChange: function( newNumber ) {
								props.setAttributes( { number: newNumber } );
								getLatestPosts( null, newNumber, null);
							},
						}
					),
					el(
						SelectControl,
						{
							key: "latest-posts-category",
							options: attributes.categories,
              				label: i18n.__( 'Category' ),
              				value: attributes.category,
              				onChange: function( newCat ) {
              					props.setAttributes( { category: newCat } );
              					getLatestPosts( newCat, null, null);
							},
						}
					),
				),
				eval( attributes.grid ),
				attributes.grid == '' && getLatestPosts( 'All Categories', '12', '3')
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
	jQuery
);
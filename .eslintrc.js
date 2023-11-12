module.exports = {
	root: true,
	extends: [ 'plugin:@wordpress/eslint-plugin/recommended' ],
	globals: {
		jQuery: true,
		supv: true,
		ajaxurl: true,
	},
	rules: {
		'jsdoc/no-undefined-types': [
			1,
			{
				definedTypes: [
					'VERSION',
				],
			},
		],
	},
};

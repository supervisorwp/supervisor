module.exports = function( grunt ) {
	grunt.initConfig(
		{
			uglify: {
				options: {
					ASCIIOnly: true,
					screwIE8: false,
				},
				main: {
					files: {
						'assets/js/supervisor.min.js': [ 'assets/js/*.js', '!assets/js/*.min.js' ],
					},
				},
			},

			sass: {
				main: {
					files: [ {
						expand: true,
						cwd: 'assets/scss/',
						src: [ '*.scss' ],
						dest: 'assets/css',
						ext: '.css',
					} ],
				},
			},

			cssmin: {
				main: {
					files: [ {
						expand: true,
						src: [ 'assets/css/*.css', '!assets/css/*.min.css' ],
						ext: '.min.css',
					} ],
				},
			},

			watch: {
				src: {
					files: [ 'assets/scss/**/*.scss', 'assets/js/*.js', '!assets/js/*.min.js' ],
					tasks: [ 'default' ],
				},
			},

			eslint: {
				main: [ '.eslintrc.js', 'Gruntfile.js', 'assets/js/**/*.js', '!assets/js/**/*.min.js' ],
			},
		}
	);

	grunt.loadNpmTasks( 'grunt-contrib-uglify' );
	grunt.loadNpmTasks( 'grunt-contrib-sass' );
	grunt.loadNpmTasks( 'grunt-contrib-cssmin' );
	grunt.loadNpmTasks( 'grunt-contrib-watch' );
	grunt.loadNpmTasks( 'grunt-eslint' );

	grunt.registerTask( 'default', [ 'uglify:main', 'sass:main', 'cssmin:main' ] );
	grunt.registerTask( 'lint', [ 'eslint:main' ] );
};

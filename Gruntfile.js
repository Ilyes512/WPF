module.exports = function(grunt) {

	grunt.initConfig({
		uglify: {
			options: {
				mangle: true,
				compress: true
				//sourceMap: true
			},
			target: {
				files: {
					'js/wpf.min.js': [
						'bower_components/fastclick/lib/fastclick.js',
						'src/js/foundation/foundation.js',
						'src/js/foundation/foundation.topbar.js',
						'src/js/foundation/foundation.alert.js',
						'src/js/app.js'
					],
					'js/vendor/modernizr.min.js': 'bower_components/modernizr/modernizr.js'
				}
			}
		},
		sass: {
			foundation: {
				options: {
					//sourcemap: true,
					style: 'compressed', // Can be nested, compact, compressed, expanded
					quiet: true
				},
				files: {
					'style.css': 'src/scss/style.scss',
					'css/admin-bar.min.css': 'src/scss/wp/admin-bar.scss'
				}
			}
		},
		watch: {
			scripts: {
				files: ['src/js/**/*.js'],
				tasks: ['uglify']
			},
			sass: 	{
				files: ['src/scss/**/*.scss'],
				tasks: ['sass', 'cmq', 'cssmin']
			}
		},
		copy: {
			bower: {
				files: [
					{src: 'bower_components/jquery/jquery.min.js', dest: 'js/vendor/jquery.min.js'},
					{src: 'bower_components/jquery-placeholder/jquery.placeholder.min.js', dest: 'js/vendor/jquery.placeholder.min.js'},
					{src: 'bower_components/font-awesome/fonts/*', dest: 'fonts/', flatten: true, expand: true},
					{src: 'bower_components/font-awesome/scss/*', dest: 'src/scss/font-awesome', flatten: true, expand: true}
				]
			}
		},
		cmq: {
			options: {
			 	log: true
			},
			mediaquery: {
				files: {
					'': 'style.css'
				}
			}
		},
		cssmin: {
			options: {
				//report: 'gzip'
			},
			style: {
				files: {
					'style.css': 'style.css'
				}
			}
		}
	});

	grunt.loadNpmTasks('grunt-contrib-uglify');
	grunt.loadNpmTasks('grunt-contrib-watch');
	grunt.loadNpmTasks('grunt-contrib-copy');
	grunt.loadNpmTasks('grunt-contrib-sass');
	grunt.loadNpmTasks('grunt-combine-media-queries');
	grunt.loadNpmTasks('grunt-contrib-cssmin');

	grunt.registerTask('default', ['watch']);
	// Basicly the same as what "watch" will do automatically for you
	grunt.registerTask('css', ['sass', 'cmq', 'cssmin']);
	grunt.registerTask('build', ['uglify', 'copy', 'sass', 'cmq', 'cssmin']);
};


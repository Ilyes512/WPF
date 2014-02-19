module.exports = function(grunt) {

	grunt.initConfig({
		uglify: {
			options: {
				mangle: true,
				compress: true,
				sourceMap: true
			},
			target: {
				files: {
					"js/foundation.min.js": ["bower_components/fastclick/lib/fastclick.js", "src/foundation/foundation.js", "src/foundation/foundation.topbar.js", "src/foundation/foundation.alert.js"],
					"js/vendor/modernizr.min.js": "bower_components/modernizr/modernizr.js"
				}
			}
		},
		sass: {
			foundation: {
				options: {
					outputStyle: "compressed", // nested, expanded, compact, compressed
					sourceComments: "map",
					sourceMap: "style.css.map"
				},
				files: {
					"style.css": "src/scss/style.scss"
				}
			}
		},
		watch: {
			scripts: {
				files: ["src/foundation/*.js"],
				tasks: ["uglify"]
			},
			sass: 	{
				files: ["src/scss/**/*.scss"],
				tasks: ["sass"]
			}
		},
		copy: {
			bower: {
				files: [
					{src: "bower_components/jquery/jquery.min.js", dest: "js/vendor/jquery.min.js"},
					{src: "bower_components/jquery-placeholder/jquery.placeholder.min.js", dest: "js/vendor/jquery.placeholder.min.js"}
				]
			}
		}
	});

	grunt.loadNpmTasks('grunt-contrib-uglify');
	grunt.loadNpmTasks('grunt-contrib-watch');
	grunt.loadNpmTasks('grunt-contrib-copy');
	grunt.loadNpmTasks('grunt-sass');

	grunt.registerTask("default", ["uglify", "sass", "copy"]);
};
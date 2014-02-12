module.exports = function(grunt) {

	grunt.initConfig({
		concat: {
			options: {
				seperator: ";"
			},
			target: {
				src: ["src/foundation/foundation.js", "src/foundation/foundation.topbar.js", "src/foundation/foundation.placeholder.js", "src/foundation/foundation.alerts.js"],
				dest: "src/foundation.js"
			}
		},
		uglify: {
			options: {
				mangle: true,
				compress: true,
				sourceMap: true
			},
			target: {
				src: "src/foundation.js",
				dest: "js/foundation.min.js"
			}
		},
		clean: ["js/foundation.min.js", "src/foundation.js", "style.css"],
		sass: {
			foundation: {
				options: {
					outputStyle: "nested", // nested, expanded, compact, compressed
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
				tasks: ["concat", "uglify"]
			},
			sass: {
				files: ["src/scss/**/*.scss"],
				tasks: ["sass"]
			}
		}
	});

	grunt.loadNpmTasks('grunt-contrib-concat');
	grunt.loadNpmTasks('grunt-contrib-uglify');
	grunt.loadNpmTasks('grunt-contrib-clean');
	grunt.loadNpmTasks('grunt-contrib-watch');
	grunt.loadNpmTasks('grunt-sass');

	grunt.registerTask("default", ["concat", "uglify", "sass"]);
	grunt.registerTask("reset", ["clean", "default"]);
};
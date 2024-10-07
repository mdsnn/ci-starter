module.exports = function (grunt) {
  // 25/9/24 MS: Project configuration.
  grunt.initConfig({
    pkg: grunt.file.readJSON("package.json"),

    // 25/9/24 MS: Uglify task for minifying JavaScript
    uglify: {
      options: {
        mangle: false,
      },
      my_target: {
        files: {
          "public/dist/output.min.js": ["public/src/app.js"],
        },
      },
    },
    // 25/9/24 MS: Watch task to monitor changes
    watch: {
      scripts: {
        files: ["public/src/app.js"], // 25/9/24 MS: Files to watch 25-09-24-MS
        tasks: ["uglify"], // 25/9/24 MS: Tasks to run on changes 25-09-24-MS
        options: {
          spawn: false, // 25/9/24 MS: Prevents restarting the watch process on each change
        },
      },
    },
  });

  // Load the plugins
  grunt.loadNpmTasks("grunt-contrib-uglify");
  grunt.loadNpmTasks("grunt-contrib-watch");

  // Default task(s).
  grunt.registerTask("default", ["uglify"]);
};

var gulp = require('gulp');
var livereload = require('gulp-livereload');

var paths = {
	php:['couponxl/**/**/*.php','couponxl-child/*.php'],
    js: ['couponxl/js/*.js', 'couponxl-child/js/*.js'],
    css: ['couponxl-child/*.css']
};

gulp.task('watch', function () {
    livereload.listen();
    gulp.watch([paths.php,paths.js,paths.css]).on('change', livereload.changed);
});

gulp.task('reload', function(){
	livereload.changed;
});

gulp.task('default', ['watch']);
'use strict';

const gulp = require('gulp');
const sass = require('gulp-sass')(require('sass'));

function buildSass() {
	return gulp.src('./resources/assets/scss/**/*.scss')
		.pipe(sass().on('error', sass.logError))
		.pipe(gulp.dest('./resources/assets/css'));
};

function watch() {
	return gulp.watch('./sass/**/*.scss', ['sass']);
}

exports.build = buildSass;
exports.watch = watch;

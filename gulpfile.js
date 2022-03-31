'use strict'

const gulp = require('gulp')
const sass = require('gulp-sass')(require('sass'))

function buildSass() {
	return gulp.src('./resources/assets/scss/**/*.scss')
		.pipe(sass.sync().on('error', sass.logError))
		.pipe(gulp.dest('./resources/assets/css'))
}

function watchScss() {
	return gulp.watch('./resources/assets/scss/**/*.scss', gulp.series('build'))
}

exports.build = buildSass
exports.watch = watchScss

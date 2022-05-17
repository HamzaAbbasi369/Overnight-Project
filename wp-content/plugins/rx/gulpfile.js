'use strict';

var gulp         = require('gulp'),
    compass      = require('gulp-compass'),
    rename       = require('gulp-rename'),
    cssmin       = require('gulp-cssmin'),
    minify       = require('gulp-minify');



gulp.task('compass', function () {
    return gulp.src('assets/scss/rx_style.scss')
        .pipe(compass({
            config_file: 'config.rb',
            sourcemap: true,
            css: 'assets/css',
            sass: 'assets/scss'
        }))
        .pipe(gulp.dest('assets/css'))
        .pipe(cssmin())
        .pipe(rename({suffix: '.min'}))
        .pipe(gulp.dest('assets/css'));
});

gulp.task('compress-js', function () {
    gulp.src(['assets/js/**/*.js', '!assets/js/**/*.min.js'])
        .pipe(minify({
            ext: {
                min: '.min.js'
            }
        }))
        .pipe(gulp.dest('assets/js'));
});

// TASK WATCH

gulp.task('watch', function () {
    gulp.watch('assets/scss/**/*.scss', ['compass']); // Наблюдение за sass файлами в папке sass
    // gulp.watch('assets/js/**/*.js', ['compress-js']); // Наблюдение за JS файлами в папке js

});


// TASK BUILD

gulp.task('default', ['compass', 'compress-js'], function() {});

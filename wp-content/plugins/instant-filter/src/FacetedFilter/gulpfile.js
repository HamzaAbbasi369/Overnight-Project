'use strict';

var gulp         = require('gulp'),
    compass      = require('gulp-compass'),
    rename       = require('gulp-rename'),
    cssmin       = require('gulp-cssmin'),
    minify       = require('gulp-minify');

gulp.task('compass', function(){
    return gulp.src('view/scss/ong-filter.scss')
        .pipe(compass({
            config_file: 'config.rb',
            sourcemap: true,
            css: 'view/css',
            sass: 'view/scss'
        }))
        .pipe(gulp.dest('view/css'))
        .pipe(cssmin())
        .pipe(rename({suffix: '.min'}))
        .pipe(gulp.dest('view/css'));
});

    gulp.task('compress-js', function() {
        gulp.src(['view/js/**/*.js', '!view/js/**/*.min.js'])
            .pipe(minify({
                ext: {
                    min:'.min.js'
                }
            }))
            .pipe(gulp.dest('view/js'))
    });

    // gulp.task('css-compress', function () {
    //     gulp.src('custom/style.css')
    //         .pipe(cssmin())
    //         .pipe(rename({suffix: '.min'}))
    //         .pipe(gulp.dest('custom'));
    // });

// TASK BUILD
gulp.task('watch', function () {
    gulp.watch('view/scss/**/*.scss', ['compass']);

});
gulp.task('default', ['compass', 'compress-js'], function() {});

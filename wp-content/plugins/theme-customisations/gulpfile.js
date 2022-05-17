var gulp         = require('gulp'),
    compass      = require('gulp-compass'),
    rename       = require('gulp-rename'),
    cssmin       = require('gulp-cssmin'),
    minify       = require('gulp-minify');




gulp.task('compass', function(){
    return gulp.src('custom/style.scss')
        .pipe(compass({
            config_file: 'config.rb',
            sourcemap: true,
            css: 'custom',
            sass: 'custom'
        }))
        .pipe(gulp.dest('custom'))
        .pipe(cssmin())
        .pipe(rename({suffix: '.min'}))
        .pipe(gulp.dest('custom'));
});

gulp.task('compress-js', function() {
    gulp.src(['custom/**/*.js', '!custom/**/*.min.js'])
        .pipe(minify({
            ext: {
                min: '.min.js'
            }
        }))
        .pipe(gulp.dest('custom'));
});

    // gulp.task('css-compress', function () {
    //     gulp.src('custom/style.css')
    //         .pipe(cssmin())
    //         .pipe(rename({suffix: '.min'}))
    //         .pipe(gulp.dest('custom'));
    // });

// TASK WATCH

gulp.task('watch', function () {
    gulp.watch('custom/**/*.scss', ['compass']); // Наблюдение за sass файлами в папке sass
    // gulp.watch('custom/**/*.js', ['compress-js']); // Наблюдение за JS файлами в папке js

});

// TASK BUILD

gulp.task('watch', function () {
    gulp.watch('custom/*.scss', ['compass']);
    gulp.watch('custom/*.js', ['compress-js']);

});

gulp.task('default', ['compass', 'compress-js'], function() {});

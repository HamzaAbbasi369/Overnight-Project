var gulp         = require('gulp'), // Подключаем Gulp
    rename       = require('gulp-rename'),
    cssmin       = require('gulp-cssmin'),
    minify       = require('gulp-minify');


    gulp.task('compress', function() {
            gulp.src('assets/js/*.js')
                .pipe(minify({
                    ext:{
                        // src:'-debug.js',
                        min:'.min.js'
                    },
                    // exclude: ['tasks'],
                    ignoreFiles: ['.min.js', 'svi-frontend-new.js', 'bootstrap-checkbox.min.js']
                }))
                .pipe(gulp.dest('assets/js'))
    });

    gulp.task('css-compress', function () {
        gulp.src('assets/css/**/*.css')
            .pipe(cssmin())
            .pipe(rename({suffix: '.min'}))
            .pipe(gulp.dest('assets/css'));
    });

// TASK BUILD

        gulp.task('build', ['compress', 'css-compress'], function() {});

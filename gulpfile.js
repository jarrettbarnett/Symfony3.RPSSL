var gulp = require('gulp'),
    gutil = require('gulp-util'),
    sass = require('gulp-sass'),
    connect = require('gulp-connect'),
    uglify = require('gulp-uglify'),
    concat = require('gulp-concat');
    cleanCSS = require('gulp-clean-css');

var jsSources = [
        'app/Resources/assets/js/main.js'
    ],
    sassSources = [
        'app/Resources/assets/scss/all.scss',
    ],
    sassDir = [
        'app/Resources/assets/scss/*.scss'
    ],
    htmlSources = [
        '**/*.html'
    ],
    jsDir = 'web/assets/js';
    cssDir = 'web/assets/css';

// gulp log
gulp.task('log', function() {
    gutil.log('== Go Gulp Go ==')
});

// gulp sass
gulp.task('sass', function() {
    gulp.src(sassSources)
    .pipe(sass({style: 'expanded'}))
        .on('error', gutil.log)
    .pipe(cleanCSS())
    .pipe(concat('styles.css'))
    .pipe(gulp.dest(cssDir))
});

// gulp js
gulp.task('js', function() {
    gulp.src(jsSources)
        .pipe(uglify())
        .pipe(concat('script.js'))
        .pipe(gulp.dest(jsDir))
});

// gulp watch
gulp.task('watch', function() {
    gulp.watch(jsSources, ['js']);
    gulp.watch(sassSources, ['sass']);
    gulp.watch(sassDir, ['sass']);
});

gulp.task('default', ['js', 'sass', 'watch']);
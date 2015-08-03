// Require plugins
var gulp = require('gulp');
var plumber = require('gulp-plumber');
var sass = require('gulp-sass');
var autoprefixer = require('gulp-autoprefixer');
var minifycss = require('gulp-minify-css');
var jshint = require('gulp-jshint');
var uglify = require('gulp-uglify');
var imagemin = require('gulp-imagemin');
var rename = require('gulp-rename');
var concat = require('gulp-concat');
var notify = require('gulp-notify');
var livereload = require('gulp-livereload');
var newer = require('gulp-newer');
var del = require('del');
var bower = require('gulp-bower');

var onError = function (err) {
    console.log(err);

    notify.onError({
        title:    "Gulp",
        subtitle: "ERROR",
        message:  "<%= error.plugin %>: <%= error.message %>",
    })(err);

    this.emit('end');
};

// Paths
var paths = {
    admin: {
        src: {
            css: 'assets/scss/admin-options.scss',
            js: 'assets/js/admin.js'
        },
        dist: {
            root: 'dist/admin',
            css: 'dist/admin/css/',
            js: 'dist/admin/js/',
            img: 'dist/admin/img/'
        },
        bower: []
    },
    frontend: {
        src: {
            css: 'assets/scss/frontend.scss',
            js: [
                    'assets/js/public.js'
            ],
            img: 'assets/img/*'
        },
        dist: {
            root: 'dist/frontend',
            css: 'dist/frontend/css/',
            js: 'dist/frontend/js/',
            img: 'dist/frontend/img/'
        },
        bower: [
            'bower_components/socialjs/src/jquery.socialjs.js'
        ]
    }
};

// Admin styles
gulp.task('admin-styles', function() {
    return gulp.src(paths.admin.src.css)
        .pipe(plumber({ errorHandler: onError }))
        .pipe(sass({ style: 'expanded' }))
        .pipe(autoprefixer('last 3 version', 'ie 8', 'ie 9'))
        .pipe(rename({ suffix: '.min' }))
        .pipe(minifycss())
        .pipe(gulp.dest(paths.admin.dist.css))
        .pipe(notify({ message: 'Admin styles complete' }));
});

//Frontend styles
gulp.task('frontend-styles', function() {
    return gulp.src(paths.frontend.src.css)
        .pipe(plumber({ errorHandler: onError }))
        .pipe(sass({ style: 'expanded' }))
        .pipe(autoprefixer('last 3 version', 'ie 8', 'ie 9'))
        .pipe(rename({ suffix: '.min' }))
        .pipe(minifycss())
        .pipe(gulp.dest(paths.frontend.dist.css))
        .pipe(notify({ message: 'Frontend styles complete' }));
});

// Admin scripts
gulp.task('admin-scripts', function() {

    //Concat bower dependencies and our custom source files
    var jspaths = paths.admin.bower.concat(paths.admin.src.js);

    return gulp.src(jspaths)
        .pipe(plumber({ errorHandler: onError }))
        .pipe(concat('admin.js'))
        .pipe(rename({ suffix: '.min' }))
        .pipe(uglify({ compress: false }))
        .pipe(gulp.dest(paths.admin.dist.js))
        .pipe(notify({ message: 'Frontend scripts task complete' }));
});


// Frontend scripts
gulp.task('frontend-scripts', function() {

    //Concat bower dependencies and our custom source files
    var jspaths = paths.frontend.bower.concat(paths.frontend.src.js);

    return gulp.src(jspaths)
        .pipe(plumber({ errorHandler: onError }))
        .pipe(concat('frontend.js'))
        .pipe(rename({ suffix: '.min' }))
        .pipe(uglify({ compress: false }))
        .pipe(gulp.dest(paths.frontend.dist.js))
        .pipe(notify({ message: 'Frontend scripts task complete' }));
});

// JSHINT Scripts
gulp.task('jshint', function() {
    return gulp.src(paths.frontend.src.js)
        .pipe(plumber({ errorHandler: onError }))
        .pipe(jshint('.jshintrc'))
        .pipe(notify(function (file) {
            if (file.jshint.success) {
                // Don't show anything if successful
                return false;
            }

            var errors = file.jshint.results.map(function (data) {
                if (data.error) {
                    return "(" + data.error.line + ':' + data.error.character + ') ' + data.error.reason;
                }
            }).join("\n");
            return file.relative + " (" + file.jshint.results.length + " errors)\n" + errors;
        }));
});


// Images
gulp.task('images', function() {
    return gulp.src(paths.frontend.src.img)
        .pipe(plumber({ errorHandler: onError }))
        .pipe(newer(paths.frontend.dist.img))
        .pipe(imagemin({
            optimizationLevel: 3,
            progressive: true,
            svgoPlugins: [
                {removeViewBox: false},
                {removeUselessStrokeAndFill: false},
                {removeEmptyAttrs: false}
            ],
            interlaced: true
        }))
        .pipe(gulp.dest(paths.frontend.dist.img))
        .pipe(notify({ message: "Minified: <%= file.relative %>" }));
});

// Clean
gulp.task('clean', function(cb) {
    del([
        paths.frontend.dist.root
    ], cb);
});

// Install bower components
gulp.task('bower', ['clean'], function() {
    return bower({ cmd: 'install'});
});

// Default task
gulp.task('default', ['clean', 'bower'], function() {
    gulp.start('styles', 'scripts', 'images');
});


// Watch
gulp.task('watch', ['default'], function() {

    // Watch .scss files
    gulp.watch(paths.frontend.src.css, ['styles']);

    // Watch .js files
    gulp.watch(paths.frontend.src.js, ['scripts', 'jshint']);

    // Watch image files
    gulp.watch(paths.frontend.src.img, ['images']);

    // Watch this file for changes (Might be better to just run scripts)
    gulp.watch('gulpfile.js', ['default']);

    // Create LiveReload server
    livereload.listen();

    // Watch files in the frontend directory and reload browser when files change
    gulp.watch([paths.frontend.dist.root]).on('change', livereload.changed);

});

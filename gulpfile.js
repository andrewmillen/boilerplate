var gulp = require('gulp');
var sass = require('gulp-sass');
var watch = require('gulp-watch');
var sourcemaps = require('gulp-sourcemaps');
var autoprefixer = require('gulp-autoprefixer');
var notify = require('gulp-notify');
var imagemin = require('gulp-imagemin');
var concat = require('gulp-concat');
var rename = require('gulp-rename');
var uglify = require('gulp-uglify');
var browserSync = require('browser-sync');
var reload = browserSync.reload;


// Browser sync 
gulp.task('browser-sync', function() {
    var files = [ 'sass/**/*.scss', 'js/**/*.js', '**/*.php' ]; 
    browserSync.init(files, {
    proxy: "boilerplate.dev", // Change this!
    notify: false
    });
});

// Compile sass
gulp.task('sass', function(){
  return gulp.src('sass/**/*.scss')
    .pipe(sass({outputStyle: 'compressed'}).on('error', sass.logError))
    .pipe(autoprefixer())
    .pipe(gulp.dest('./assets/css'))
    .pipe(reload({stream:true}))
    .pipe(notify("Sass Compiled"))
    .pipe(sourcemaps.init())
    .pipe(sourcemaps.write('./'));
});

// Concatenate and minify javascript
gulp.task('js', function(){
    return gulp.src(['js/**/*.js'])
        .pipe(concat('scripts.js'))
        .pipe(gulp.dest('./assets/js'))
        .pipe(rename('scripts.min.js'))
        .pipe(uglify())
        .pipe(gulp.dest('./assets/js'))
        .pipe(notify("JS Minified"))
});

// Minify images
// NOT included in gulp default
gulp.task('images', () =>
    gulp.src('img/*')
        .pipe(imagemin())
        .pipe(gulp.dest('img'))
);

// Default task
gulp.task('default', ['sass', 'browser-sync'], function() {
  gulp.watch('sass/**/*.scss', ['sass']);
  gulp.watch('js/**/*.js', ['js']);
});

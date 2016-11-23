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


// Compile sass
gulp.task('sass', function(){
  return gulp.src('sass/**/*.scss')
    .pipe(sass({outputStyle: 'compressed'}))
    .pipe(autoprefixer())
    .pipe(gulp.dest('./css'))
    .pipe(notify("Sass Compiled"))
    .pipe(sourcemaps.init())
    .pipe(sourcemaps.write('./'))
});

// Concatenate and minify javascript
gulp.task('js', function(){
    return gulp.src(['js/*.js']) // Doesn't include subfolders!
        .pipe(concat('scripts.js'))
        .pipe(gulp.dest('./js/compiled'))
        .pipe(rename('scripts.min.js'))
        .pipe(uglify())
        .pipe(gulp.dest('./js/compiled'))
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
gulp.task('default', function() {
  gulp.watch('sass/**/*.scss', ['sass']);
  gulp.watch('js/**/*.js', ['js']);
});
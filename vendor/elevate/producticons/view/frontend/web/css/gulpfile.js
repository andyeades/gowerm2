// Deep Breaths //
//////////////////

var devip = require('dev-ip');

// Gulp
var gulp = require('gulp');

// BrowserSync
var browserSync = require('browser-sync').create();

// Sass/CSS stuff
var sass        = require('gulp-sass');
var prefix      = require('gulp-autoprefixer');
var cssNano     = require('gulp-cssnano');
//var bless  = require('gulp-bless');

var imagemin    = require('gulp-imagemin');

// Stats and Things
var size        = require('gulp-size');
var util        = require('gulp-util');
var plumber     = require('gulp-plumber');
var sourcemaps  = require('gulp-sourcemaps');
var mmq         = require('gulp-merge-media-queries');

// compile all your Sass
gulp.task('sass', function (){
  gulp.src(['./*.scss'])
    .pipe(plumber())
    .pipe(sass().on('error', sass.logError))
    .pipe(sass({
      includePaths: ['.'],
      outputStyle: 'expanded'}))
    .pipe(prefix("last 2 version", "> 1%"))
    .pipe(cssNano())
    .pipe(gulp.dest('../'));
    //.pipe(browserSync.stream({match: '**/**.css'}));
});

gulp.task('mmq', function() {
  gulp.src('main.css')
    .pipe(mmq({
      log: true
    }))
    .pipe(gulp.dest('./'));
});

// compile all your Sass
gulp.task('sass-production', function (){
  gulp.src(['./scss_source/**/*.scss'])
    .pipe(plumber())
    .pipe(sass().on('error', sass.logError))
    .pipe(sass({
      includePaths: ['.'],
      outputStyle: 'expanded'}))
    .pipe(prefix("last 2 version", "> 1%"))
    .pipe(mmq({
      log: true
    }))
    .pipe(cssNano())
    .pipe(gulp.dest('./'))

});

// compile all your Sass
gulp.task('sass-production-dev', function (){
  gulp.src(['./scss_source/**/*.scss'])
    .pipe(plumber())
    .pipe(sass().on('error', sass.logError))
    .pipe(sass({
      includePaths: ['.'],
      outputStyle: 'expanded'}))
    .pipe(prefix("last 2 version", "> 1%"))
    .pipe(sourcemaps.write({sourceRoot: '../src'}))
    .pipe(mmq({
      log: true
    }))
    //.pipe(cssNano())
    .pipe(gulp.dest('./'))
    .pipe(browserSync.stream());

});

// compile all your Sass
gulp.task('sass-dev', function (){
  gulp.src(['./src/**/*.scss'])
    .pipe(plumber())
    .pipe(sourcemaps.init())
    .pipe(sass().on('error', sass.logError))
    .pipe(sass({
      includePaths: ['.'],
      outputStyle: 'expanded'}))
    .pipe(prefix("last 2 version", "> 1%"))
    .pipe(sourcemaps.write({sourceRoot: '../src'}))
    .pipe(gulp.dest('./dist'))
   // .pipe(bless({
    //  imports: false,
     // log: true,
     // suffix: '-part'
    //}))
    .pipe(browserSync.stream());
});

gulp.task('ie-stylesheet', function() {
  gulp.src(['./dist/elevate-happybeds3.css'])
    //.pipe(bless({
    //  imports: false,
    //  log: true,
    //  suffix: '-part'
   // }))
  .pipe(gulp.dest('./iecss'))
  .pipe(browserSync.stream());
});

gulp.task('sass-online', function (){
  gulp.src(['./src/**/*.scss'])
    .pipe(plumber())
    .pipe(sass().on('error', sass.logError))
    .pipe(sass({
      includePaths: ['.'],
      outputStyle: 'expanded'}))
    .pipe(prefix("last 2 version", "> 1%", "ie 8", "ie 7"))
    .pipe(cssNano())
    .pipe(sourcemaps.write('.'))
    .pipe(gulp.dest('./dist'))
});

// Stats and Things
gulp.task('stats', function () {
  gulp.src('./prod/**/*')
    .pipe(size())
    .pipe(gulp.dest('./prod'));
});

gulp.task('image-min', function() {
  gulp.src('../images/**/*')
    .pipe(imagemin())
    .pipe(gulp.dest('dist/images'))
});

//// Static server
gulp.task('browser-sync', function() {
  browserSync.init({
    online: true,
    injectChanges: true,
    proxy: "pas.test"
  });
});

gulp.task('browser-sync-2', function() {
  browserSync.init({
    online: true,
    injectChanges: true,
    debugInfo: true,
    logLevel: 'debug',
    proxy: "pas.test"
  });
});

gulp.task('default', function(){
  // Sass Watch Function
  gulp.watch("./src/**/*.scss", ['sass']);
  gulp.watch("./dist/*.css").on('change', browserSync.reload);
});

gulp.task('old-default', function(){
  // Sass Watch Function
  gulp.watch("./src/**/*.scss", ['sass']);
  gulp.watch("./dist/*.css").on('change', browserSync.reload);
});

gulp.task('online-watch', function(){
  // Sass Watch Function
  gulp.watch("./src/**/*.scss", ['sass-online']);
});

gulp.task('watch', function(){
  browserSync.init({
    //files: ["**/**/**/**/**/**/*.php", "*.php","*.phtml"],
    proxy: "pas.test",
    online: true,
    debugInfo: true,
    logLevel: 'debug',
    injectChanges: true,
    browser: ["google chrome"],
    ws: true, //enable web sockets
  });
  gulp.watch("./src/**/*.scss", ['sass']);
});

gulp.task('test-watch', ['browser-sync-2'], function(){
  gulp.watch("./src/**/*.scss", ['sass-dev']);
  gulp.watch(".")
});

gulp.task('production-watch', ['browser-sync-2'], function(){
  gulp.watch("./scss_source/**/*.scss", ['sass-production-dev']);
  gulp.watch(".")
});
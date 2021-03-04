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
var cssNano     = require('cssnano');
//var bless  = require('gulp-bless');

var imagemin    = require('gulp-imagemin');

// Stats and Things
var size        = require('gulp-size');
var plumber     = require('gulp-plumber');
var sourcemaps  = require('gulp-sourcemaps');
var postcss     = require('gulp-postcss');
var mqpacker    = require('css-mqpacker');

var autoprefixer = require('autoprefixer');

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
    .pipe(gulp.dest('../dist'));
    //.pipe(browserSync.stream({match: '**/**.css'}));
});

// compile all your Sass
gulp.task('sass-production', function (){
  gulp.src(['./css/**/*.scss'])
    .pipe(plumber())
    .pipe(sass().on('error', sass.logError))
    .pipe(sass({
      includePaths: ['.'],
      outputStyle: 'expanded'}))
    .pipe(postcss([
        autoprefixer(),
        mqpacker
    ]))
    .pipe(gulp.dest('./dist/'))

});

// compile all your Sass
gulp.task('sass-production-dev', function (){
  gulp.src(['./css/**/*.scss'])
    .pipe(plumber())
    .pipe(sass().on('error', sass.logError))
    .pipe(sass({
      includePaths: ['.'],
      outputStyle: 'expanded'}))
    .pipe(prefix("last 2 version", "> 1%"))
    .pipe(sourcemaps.write({sourceRoot: '../src'}))
    .pipe(gulp.dest('./dist/'))
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
    proxy: "m2pg.test"
  });
});

gulp.task('browser-sync-2', function() {
  browserSync.init({
    online: true,
    injectChanges: true,
    debugInfo: true,
    logLevel: 'debug',
    proxy: "m2pg.test"
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
  gulp.watch("./css/**/*.scss", ['sass-dev']);
  gulp.watch(".")
});

gulp.task('production-watch', ['browser-sync-2'], function(){
  gulp.watch("./css/**/*.scss", ['sass-production-dev']);
  gulp.watch(".")
});
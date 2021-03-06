/*global -$ */
'use strict';
// generated on 2015-04-21 using generator-gulp-webapp 0.3.0
var gulp = require('gulp');
var $ = require('gulp-load-plugins')();
var connect = require('gulp-connect-php');
var httpProxy = require('http-proxy');
var browserSync = require('browser-sync');
var reload = browserSync.reload;

gulp.task('styles', function () {
  return gulp.src('app/styles/scss/main.scss')
    .pipe($.sourcemaps.init())
    .pipe($.sass({
      outputStyle: 'nested', // libsass doesn't support expanded yet
      precision: 10,
      includePaths: ['.'],
      onError: console.error.bind(console, 'Sass error:')
    }))
    .pipe($.postcss([
      require('autoprefixer-core')({browsers: ['last 2 versions']})
    ]))
    .pipe($.sourcemaps.write())
    .pipe(gulp.dest('.tmp/styles'))
    .pipe(reload({stream: true}));
});

gulp.task('jshint', function () {
  return gulp.src('app/scripts/**/*.js')
    .pipe(reload({stream: true, once: true}))
    .pipe($.jshint())
    .pipe($.jshint.reporter('jshint-stylish'))
    .pipe($.if(!browserSync.active, $.jshint.reporter('fail')));
});

gulp.task('html', ['js','styles'], function () {
  var assets = $.useref.assets({searchPath: ['.tmp', 'app', '.']});

  return gulp.src('app/index.php')
    .pipe(assets)
    .pipe($.if('*.js', $.uglify()))
    .pipe($.if('*.css', $.csso()))
    .pipe(assets.restore())
    .pipe($.useref())
    /*.pipe($.if('*.html', $.minifyHtml({conditionals: true, loose: true})))*/
    .pipe(gulp.dest('dist/'));
});

gulp.task('images', function () {
  return gulp.src('app/images/**/*.{jpg,png}')
    .pipe($.cache($.imagemin({
      progressive: true,
      interlaced: true,
      // don't remove IDs from SVGs, they are often used
      // as hooks for embedding and styling
      svgoPlugins: [{cleanupIDs: false}]
    })))
    .pipe(gulp.dest('dist/images'));
});

gulp.task('js', function() {
  return gulp.src([
      'app/scripts/*.js'
    ])
    /*.pipe(concat('app.js'))*/
    .pipe( gulp.dest('dist/scripts/'))
    .pipe($.uglify())
    .pipe( gulp.dest('dist/scripts/'));
});

gulp.task('fonts', function () {
  return gulp.src(require('main-bower-files')({
    filter: '**/*.{eot,svg,ttf,woff,woff2}'
  }).concat('app/fonts/**/*'))
    .pipe(gulp.dest('.tmp/fonts'))
    .pipe(gulp.dest('dist/fonts'));
});

gulp.task('extras', function () {
   gulp.src([
    'app/*.*',
    '!app/*.html',
    '!app/index.php'
  ], {
    dot: true
  }).pipe(gulp.dest('dist'));

  gulp.src(['app/php/**/*'])
  .pipe(gulp.dest('dist/php'));

  gulp.src(['app/photos/'])
  .pipe(gulp.dest('dist/photos'));

  gulp.src('app/images/**/*.gif').pipe(gulp.dest('dist/images'));

  gulp.src(['bower_components/fineuploader-dist/dist/*.gif'])
  .pipe(gulp.dest('dist/styles/'));

  gulp.src(['bower_components/font-awesome/fonts/*'])
  .pipe(gulp.dest('dist/fonts/'));

  return gulp.src(['app/database/Lazer/*'])
  .pipe(gulp.dest('dist/database/Lazer'));
});

gulp.task('clean', require('del').bind(null, ['.tmp', 'dist']));

gulp.task('serve', ['styles', 'fonts'], function () {
  browserSync({
    notify: false,
    port: 9000,
    server: {
      baseDir: ['.tmp', 'app'],
      routes: {
        '/bower_components': 'bower_components'
      }
    }
  });
  // watch for changes
  gulp.watch([
    'app/*.html',
    'app/*.php',
    'app/scripts/**/*.js',
    'app/photos/**/*',
    '.tmp/fonts/**/*'
  ]).on('change', reload);

  gulp.watch('app/styles/**/*.scss', ['styles']);
  gulp.watch('app/fonts/**/*', ['fonts']);
  gulp.watch('bower.json', ['wiredep', 'fonts']);
});

gulp.task('php-serve', ['styles', 'fonts'], function () {
  connect.server({
      port: 9001,
      base: 'app',
      open: false
  });

  var proxy = httpProxy.createProxyServer({});

  browserSync({
      notify: false,
      port  : 9000,
      server: {
          baseDir   : ['.tmp', 'app'],
          routes    : {
              '/bower_components': 'bower_components'
          },
          middleware: function (req, res, next) {
              var url = req.url;

              if (!url.match(/^\/(styles|fonts|bower_components)\//)) {
                  proxy.web(req, res, { target: 'http://127.0.0.1:9001' });
              } else {
                  next();
              }
          }
      }
  });

  // watch for changes
  gulp.watch([
      'app/*.html',
      'app/*.php',
      'app/scripts/**/*.js',
      'app/images/**/*',
      '.tmp/fonts/**/*'
  ]).on('change', reload);

  gulp.watch('app/styles/**/*.scss', ['styles']);
  gulp.watch('app/fonts/**/*', ['fonts']);
  gulp.watch('bower.json', ['wiredep', 'fonts']);
});

// inject bower components
gulp.task('wiredep', function () {
  var wiredep = require('wiredep').stream;

  gulp.src('app/styles/*.scss')
    .pipe(wiredep({
      ignorePath: /^(\.\.\/)+/
    }))
    .pipe(gulp.dest('app/styles'));

  gulp.src('app/*.html')
    .pipe(wiredep({
      ignorePath: /^(\.\.\/)*\.\./
    }))
    .pipe(gulp.dest('app'));
});

gulp.task('build', [/*'jshint',*/ 'html', 'images', 'fonts', 'extras'], function () {
  return gulp.src('dist/**/*')
    .pipe($.size({title: 'build', gzip: true}))
    .pipe($.zip('dist.zip'))
    .pipe(gulp.dest('.'));
});

gulp.task('default', ['clean'], function () {
  gulp.start('build');
});

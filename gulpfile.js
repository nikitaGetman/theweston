var gulp = require('gulp'),
	sass = require('gulp-sass'),
	connect = require('gulp-connect-php'),
	browserSync = require('browser-sync'),
	concat = require('gulp-concat'),
	uglify =  require('gulp-uglify'),
	cssnano = require('gulp-cssnano'),
	rename = require('gulp-rename'),
	del = require('del'),
	imagemin = require('gulp-imagemin'),
	pngquant = require('imagemin-pngquant'),
	jpegtran = require('imagemin-jpegtran'),
	cache = require('gulp-cache'),
	svgstore = require('gulp-svgstore'),
	svgmin = require('gulp-svgmin'),
	path = require('path'),
	autoprefixer = require('gulp-autoprefixer');

gulp.task('sass', function(){
	return gulp.src('app/sass/*.sass')
	.pipe(sass())
	.pipe(autoprefixer(['last 20 versions', '> 1%', 'ie 8', 'ie 7'], { cascade: true }))
	.pipe(gulp.dest('app/css'))
	.pipe(browserSync.reload({stream: true}))
});

gulp.task('scripts', function(){
	return gulp.src([
		'app/libs/jquery/dist/jquery.min.js',
		'app/libs/fancybox/dist/jquery.fancybox.min.js',
		// 'app/libs/bootstrap-select/dist/js/bootstrap.drop.js',
		'app/libs/jquery.maskedinput/dist/jquery.maskedinput.min.js',
		// 'app/libs/parsleyjs/dist/parsley.js',
		'app/libs/owl.carousel/dist/owl.carousel.js',
		// 'app/libs/bootstrap-select/dist/js/bootstrap-select.min.js',
		// 'app/libs/bootstrap.tab.js',
		// 'app/libs/simplebar/dist/simplebar.js',
		'app/libs/protonet/jqueryinview/jquery.inview.js',
		'app/libs/jquery-lazy/jquery.lazy.js',
		'app/libs/everybody/dist/svg4everybody.min.js'
	])
	.pipe(concat('libs.min.js'))
	.pipe(uglify())
	.pipe(gulp.dest('app/js'));
});

gulp.task('css-libs', ['sass'], function(){
	return gulp.src('app/css/libs.css')
	.pipe(cssnano())
	.pipe(rename({suffix: '.min'}))
	.pipe(gulp.dest('app/css'));
});
gulp.task('svgstore', function () {
    return gulp
        .src('app/images/svg/*.svg')
        .pipe(svgmin({
            plugins: [{
                removeDoctype: true
            }, { removeAttrs: {
			    attrs: '*:(stroke|fill):((?!^none$).)*'
			  }
			}, {
                removeComments: true
            }, {
                removeTitle: true
            }, {
                convertColors: {
                    names2hex: false,
                    rgb2hex: false
                }
            }]
        }))
        .pipe(rename({prefix: 'icon-'}))
        .pipe(svgstore())
        .pipe(gulp.dest('app/'));
});

gulp.task('browser-sync', function(){
	browserSync({
		server: {
			baseDir: 'app'	
		},
		notify: false
	});
});

gulp.task('clean', function(){
	return del.sync('dist');
});

gulp.task('clear', function(){
	return cache.clearAll();
});

gulp.task('img', function(){
	return gulp.src('app/images/**/*')
	.pipe(cache(imagemin({
		interlaced: true,
		progressive: true,
		svgoPlugins: [{removeViewBox: false}],
		use: [pngquant(), jpegtran()]
	})))
	.pipe(gulp.dest('dist/images'));
});

gulp.task('watch', ['browser-sync', 'css-libs', 'svgstore', 'scripts'], function(){
	gulp.watch('app/sass/**/*.sass', ['sass']);
	gulp.watch('app/*.html', browserSync.reload);
	gulp.watch('app/js/**/*.js', browserSync.reload);
});

gulp.task('build', ['clean', 'svgstore', 'img', 'sass', 'scripts'], function(){

	var buildCss = gulp.src([
			'app/css/main.css',
			'app/css/libs.min.css',
		])
		.pipe(gulp.dest('dist/css'));

	var buildFonts = gulp.src('app/fonts/**/*')
		.pipe(gulp.dest('dist/fonts'));

	var buildJs = gulp.src('app/js/**/*')
		.pipe(gulp.dest('dist/js'));

	var buildSVG = gulp.src('app/*.svg')
		.pipe(gulp.dest('dist'));

	var buildHtml = gulp.src('app/*.html')
		.pipe(gulp.dest('dist'));

});
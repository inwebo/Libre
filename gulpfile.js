var gulp = require('gulp');
var exec = require('child_process').exec;

gulp.task('make', function (cb) {
    exec('php -d phar.readonly=0 make.php', function (err, stdout, stderr) {
        console.log(stdout);
        console.log(stderr);
        cb(err);
    });
});
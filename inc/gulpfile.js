const gulp = require("gulp")
const sass = require("gulp-sass")
const watchSass = require("gulp-watch-sass")

gulp.task("sass:watch", () => watchSass([
    "./css/**/*.{scss,css}"
]).pipe(sass())
  .pipe(gulp.dest("./css")));
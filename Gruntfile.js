var path = require('path'),
    url = require('url'),
    Soup = require('soup'),
    chalk = require('chalk'),
    rewriteCSSURLs = require('css-url-rewriter'),
    mozjpeg = require('imagemin-mozjpeg');

var aws_accesskey = 'AKIAJ6ILL7DCV3Y2UOLA',
    aws_secretkey = '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';



module.exports = function(grunt) {

    if (!grunt.option('target')) {
        grunt.fail.fatal('You need add option "--target=". See docs.');
    }
    var target = grunt.option('target');
    grunt.log.oklns('Grunt will run on directory: ' + grunt.option('target'));

    // next 7 days in ISO 8601 format 
    var today = new Date();
    today.setDate(today.getDate() + 7);
    var next7days = today.toISOString();

    grunt.initConfig({
        aws_s3: {
            options: {
                accessKeyId: aws_accesskey,
                secretAccessKey: aws_secretkey,
                region: 'ap-southeast-1',
                uploadConcurrency: 5, // 5 simultaneous uploads
                downloadConcurrency: 5 // 5 simultaneous downloads
            },  
            production: {
                options: {
                    bucket: 'emagbom.plf',
                    differential: true,
                    displayChangesOnly: true,
                },
                files: [{
                    expand: true,
                    cwd: 'app/webroot/templates/' + target + '/',
                    src: ['**/*.{png,jpg,gif,mp4}'],
                    dest: 'templates/' + target + '/',
                    params: {
                        CacheControl: 'public; max-age=604800', // cache 7 days
                        Expires: next7days
                    }
                }]
            }
        },
        imagemin: {                          // Task
            dynamic: {                         // Another target
                options: {                       // Target options
                    optimizationLevel: 1,
                    svgoPlugins: [{ removeViewBox: false }],
                    use: [mozjpeg()]
                },
                files: [{
                    expand: true,                                   // Enable dynamic expansion
                    cwd: 'app/webroot/templates/' + target + '/',   // Src matches are relative to this path
                    src: ['**/*.{png,jpg,gif,mp4}'],                    // Actual patterns to match
                    dest: 'app/webroot/templates/' + target + '/'   // Destination path prefix
                }]
            }
        },
        newer: {
            options: {
                cache: 'app/webroot/templates/.grunt-newer-cache/' + target
            }
        }
     });

    
    grunt.loadNpmTasks('grunt-contrib-imagemin');
    grunt.loadNpmTasks('grunt-newer');
    grunt.loadNpmTasks('grunt-aws-s3');

    grunt.task.run(['newer:imagemin']);
    grunt.task.run(['aws_s3']);

    grunt.registerTask('default', 'My "asyncfoo" task.', function() {
        grunt.task.requires('aws_s3');

        // get files was uploaded AWS S3
        var files = grunt.config.get('aws_s3_changed');

        var changedFolder = [];
        files.forEach(function(v) {
            e = v.split("/");
            if (e.length > 2) {
                changedFolder.push(e[1]);
            }
        });
        changedFolder = changedFolder.filter((x, i, a) => a.indexOf(x) == i); // filter to get unique changed folder 

        // ---- Task change local link to CDN, referrer from plugin https://github.com/callumlocke/grunt-cdnify/blob/master/tasks/cdnify.js
        var html = {'img[data-src]': 'data-src', 'img[src]': 'src'};
        var filesCount = {css: 0, html: 0};
        
        changedFolder.forEach(function(v) {
            var directory = 'app/webroot/templates/' + v + "/";
            files = grunt.file.expand({cwd: directory}, "**/*.{html,css}"); 

            files.forEach(function(file){
                var base = 'https://cdn.smobgame.com/templates/' + v + "/";
                var paths = file.split("/");
                file = directory + file;

                paths.forEach(function(path, i) {
                    if ((i + 1) < paths.length) {
                        base = base + path + "/";
                    }
                })
                
                function isLocalPath(filePath, mustBeRelative) {
                      return typeof filePath === 'string' && filePath.length &&
                        filePath.indexOf('//') === -1 &&
                        filePath.indexOf('data:') !== 0 &&
                        (!mustBeRelative || filePath[0] !== '/');
                }

                rewriteURL = function (origUrl) {
                    return isLocalPath(origUrl) ? url.resolve(base, origUrl) : origUrl;
                };

   
                if (!grunt.file.exists(file)) {
                    return grunt.log.warn('Source file ' + chalk.cyan(path.resolve(file)) + ' not found.');
                }

                if (/\.css$/.test(file)) {
                    // It's a CSS file
                    var oldCSS = grunt.file.read(file);
                    var newCSS = rewriteCSSURLs(oldCSS, rewriteURL);

                    grunt.file.write(file, newCSS);
                    grunt.verbose.writeln(chalk.bold('Wrote CSS file: ') + chalk.cyan(file));
                    filesCount.css++;
                } else {
                    // It's an HTML file
                    var oldHTML = grunt.file.read(file),
                    soup = new Soup(oldHTML);

                    for (var search in html) {
                        if (html.hasOwnProperty(search)) {
                        var attr = html[search];
                            if (attr) {
                              soup.setAttribute(search, attr, rewriteURL);
                            }
                        }
                    }

                    // Update the URLs in any embedded stylesheets
                    soup.setInnerHTML('style', function (css) {
                        return rewriteCSSURLs(css, rewriteURL);
                    });

                    // Write it to disk
                    grunt.file.write(file, soup.toString());
                    grunt.verbose.writeln(chalk.bold('Wrote HTML file: ') + chalk.cyan(file));
                    filesCount.html++;
                }
            });
        });

        if (filesCount.css > 0) {
            grunt.log.ok('Wrote ' + chalk.cyan(filesCount.css.toString()) + ' CSS ' + grunt.util.pluralize(filesCount.css, 'file/files'));
        }
        if (filesCount.html > 0) {
            grunt.log.ok('Wrote ' + chalk.cyan(filesCount.html.toString()) + ' HTML ' + grunt.util.pluralize(filesCount.html, 'file/files'));
        }

        // -- END , Task change local link to CDN
    });



};

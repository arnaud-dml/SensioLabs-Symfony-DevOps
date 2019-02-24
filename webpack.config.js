var Encore = require('@symfony/webpack-encore');

Encore
    .setOutputPath('public/build/')
    .setPublicPath('/build')
    .cleanupOutputBeforeBuild()

    .addEntry('js/app', './assets/js/app.js')
    .addEntry('js/security/login', './assets/js/security/login.js')
    .autoProvidejQuery()

    .addStyleEntry('css/app', './assets/css/app.scss')
    .addStyleEntry('css/security/login', './assets/css/security/login.scss')
    .enableSassLoader()

    .splitEntryChunks()
    .enableSingleRuntimeChunk()
    .enableBuildNotifications()
    .enableSourceMaps(!Encore.isProduction())
    .enableVersioning(Encore.isProduction())
;

module.exports = Encore.getWebpackConfig();

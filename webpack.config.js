var Encore = require('@symfony/webpack-encore');

Encore
    .setOutputPath('public/build/')
    .setPublicPath('/build')
    .cleanupOutputBeforeBuild()

    .addEntry('js/app', './assets/js/app.js')
    .addEntry('js/security/layout', './assets/js/security/layout.js')
    .autoProvidejQuery()

    .addStyleEntry('css/app', './assets/css/app.scss')
    .addStyleEntry('css/security/layout', './assets/css/security/layout.scss')
    .enableSassLoader()

    .splitEntryChunks()
    .enableSingleRuntimeChunk()
    .enableBuildNotifications()
    .enableSourceMaps(!Encore.isProduction())
    .enableVersioning(Encore.isProduction())
;

module.exports = Encore.getWebpackConfig();

const Encore = require('@symfony/webpack-encore');
const CompressionPlugin = require("compression-webpack-plugin");
const zlib = require("zlib");

if (!Encore.isRuntimeEnvironmentConfigured()) {
    Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev');
}

Encore
    .setOutputPath('public/build/')
    .setPublicPath('/build')
    .addEntry('client', './assets/js/client/main.js')
    .addEntry('old', './assets/js/old/main.js')
    .addEntry('purple', './assets/js/purple/main.js')
    .addEntry('white', './assets/js/white/main.js')

    .addEntry('readable', './assets/js/admin/readable.js')
    .addEntry('ea-app', './assets/js/admin/ea-app.js')
    .addStyleEntry('console', './assets/styles/admin/console.css')
    .addStyleEntry('securedArea', './assets/styles/admin/securedArea.css')
    .addStyleEntry('admin', './assets/styles/admin/admin.css')

    .autoProvidejQuery()
    .splitEntryChunks()
    .enableSingleRuntimeChunk()
    .cleanupOutputBeforeBuild()
    .enableBuildNotifications()
    .enableSourceMaps(!Encore.isProduction())
    .enableVersioning(Encore.isProduction())
    .configureBabel((config) => {
        config.plugins.push('@babel/plugin-proposal-class-properties');
    })
    .configureBabelPresetEnv((config) => {
        config.useBuiltIns = 'usage';
        config.corejs = 3;
    })
    .enableSassLoader()
    .enableIntegrityHashes(Encore.isProduction())
    .addPlugin( new CompressionPlugin({
        filename: "[path][base].gz",
        algorithm: "gzip",
        test: /\.js$|\.css$|\.html$/,
        threshold: 10240,
        minRatio: 0.8,
    }))
    .addPlugin(new CompressionPlugin({
        filename: "[path][base].br",
        algorithm: "brotliCompress",
        test: /\.(js|css|html|svg)$/,
        compressionOptions: {
            params: {
                [zlib.constants.BROTLI_PARAM_QUALITY]: 11,
            },
        },
        threshold: 10240,
        minRatio: 0.8,
        deleteOriginalAssets: false,
    }))
;

module.exports = Encore.getWebpackConfig();
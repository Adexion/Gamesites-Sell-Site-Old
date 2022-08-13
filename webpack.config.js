const Encore = require('@symfony/webpack-encore');
const CompressionPlugin = require("compression-webpack-plugin");
const zlib = require("zlib");

if (!Encore.isRuntimeEnvironmentConfigured()) {
    Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev');
}

Encore
    .setOutputPath('public/build/')
    .setPublicPath('/build')
    .addEntry('guild-dark', './assets/js/client/guild/dark/main.js')
    .addEntry('guild-white', './assets/js/client/guild/white/main.js')

    .addEntry('simple-dark', './assets/js/client/simple/dark/main.js')

    .addEntry('multi-dark', './assets/js/client/multi/dark/main.js')
    .addEntry('multi-totalcraft', './assets/js/client/multi/totalcraft/main.js')

    .addEntry('admin', './assets/js/admin/admin.js')

    .addStyleEntry('console', './assets/styles/admin/console.css')
    .addStyleEntry('securedArea', './assets/styles/admin/securedArea.css')

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
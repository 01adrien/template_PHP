var Encore = require('@symfony/webpack-encore');
Encore
    .setOutputPath('public/build/')
    .setPublicPath('/build')
    .addEntry('index', './src/App/assets/js/index.js')
    .splitEntryChunks()
    .enableSingleRuntimeChunk()
    .cleanupOutputBeforeBuild()
    .enableBuildNotifications()
    .enableSourceMaps(!Encore.isProduction())
    .enableVersioning(Encore.isProduction())
    .configureBabel(() => {}, {
        useBuiltIns: 'usage',
        corejs: 3
    })
    .copyFiles({
      from: "src/App/assets/pictures",
      to: "images/[path][name].[hash:8].[ext]",
      pattern: /\.(png|jpg|jpeg|gif)$/
  })
;
module.exports = Encore.getWebpackConfig();
var Encore = require('@symfony/webpack-encore');
Encore
    .setOutputPath('public/build/')
    .setPublicPath('/build')
    .addEntry('index', './src/App/assets/js/index.js')
    .addEntry('categories', './src/App/assets/js/categories.js')
    .addEntry('transactions', './src/App/assets/js/transactions.js')
    .addEntry('upload', './src/App/assets/js/upload.js')
    .addEntry('import', './src/App/assets/js/import.js')
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
      to: "images/[path][name].[ext]",
      pattern: /\.(png|jpg|jpeg|gif|csv)$/
  }).copyFiles({
    from: "src/App/assets/files",
    to: "files/[path][name].[ext]",
    pattern: /\.(csv)$/
})
;
module.exports = Encore.getWebpackConfig();
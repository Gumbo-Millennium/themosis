/**
 * Encore configuration, handles creating all static assets
 *
 * @author Roelof Roos
 */

// webpack.config.js
const Encore = require('@symfony/webpack-encore')
const CopyWebpackPlugin = require('copy-webpack-plugin')
const ImageminPlugin = require('imagemin-webpack-plugin').default
const ImageminMozjpeg = require('imagemin-mozjpeg')
const StyleLintPlugin = require('stylelint-webpack-plugin')

Encore
  // directory where all compiled assets will be stored
  .setOutputPath('dist/')

  // what's the public path to this directory (relative to your project's document root dir)
  .setPublicPath('/dist')

  // empty the outputPath dir before each build
  .cleanupOutputBeforeBuild()

  // contains theme javascript and css
  .addEntry('js/gumbo-millennium', './assets/js/theme.js')
  .addStyleEntry('css/gumbo-millennium', './assets/sass/theme.scss')

  // contains shared information
  .createSharedEntry('js/vendor', ['jquery', 'popper.js', 'bootstrap'])

  // allow sass/scss files to be processed
  .enableSassLoader()

  // Enable source maps on production
  .enableSourceMaps(!Encore.isProduction())

  // Enable PostCSS processing
  .enablePostCssLoader()

  // Add ESLint
  .addLoader({
    enforce: 'pre',
    test: /\.jsx?$/,
    exclude: /(node_modules|var\/data)/,
    loader: 'eslint-loader'
  })

  // Add StyleLint
  .addPlugin(new StyleLintPlugin({
    files: ['assets/sass/**/*.s?(a|c)ss']
  }))

  // Copy images to destination
  .addPlugin(new CopyWebpackPlugin([{
    from: 'assets/images',
    to: 'images'
  }]))

  // Apply imagemin, using mozjpeg for JPEG minification
  .addPlugin(new ImageminPlugin({
    disable: !Encore.isProduction(),
    test: /\.(jpe?g|png|gif|svg)$/i,
    plugins: [
      ImageminMozjpeg({
        quality: 90,
        progressive: true
      })
    ]
  }))

// export the final configuration
module.exports = Encore.getWebpackConfig()

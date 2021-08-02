// this file is normally not needed.
// however, when using a phaser game we need to control webpack loader to
// handle also small files with the XHR loader
// check if this impacts other resources ...

module.exports = {
  chainWebpack: config => {
    /* disable insertion of assets as data urls b/c Phaser doesn't support it */
    const rules = [
      { name: 'images', dir: 'img' },
      { name: 'media',  dir: 'media' }
    ]
    rules.forEach(rule => {
      const ruleConf = config.module.rule(rule.name)

      ruleConf.uses.clear()

      ruleConf
        .use('file-loader')
          .loader('file-loader')
          .options({
            limit: 1,
            name: `${rule.dir}/[name].[hash:8].[ext]`
          })
    })
  },
  devServer: {
    hot: true
  }
}


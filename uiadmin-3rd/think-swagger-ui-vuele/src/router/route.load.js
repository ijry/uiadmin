// function LOAD_PAGES_MAP(name) {
//   return require.ensure([], () => require(`../views/pages/${name}.vue`))
// }

// export const loadPage = function(path) {
//   return LOAD_PAGES_MAP(path)
// }

const LOAD_PAGES_MAP = {
  'zh-CN': name => {
    return r => require.ensure([], () =>
      r(require(`../views/pages/${name}.vue`)),
    'zh-CN')
  }
}

export const loadPage = function(path) {
  return LOAD_PAGES_MAP['zh-CN'](path)
}

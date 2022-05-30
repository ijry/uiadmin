import store from '@/store'
import { isEmpty } from 'tennetcn-ui/lib/utils'
/*
*{name:name,desc:desc,children:[{path:'/login',reqMethod:[]}]}
*/

const resolveMenu = function() {
  var menus = []
  const swaggerInfo = store.getters.swaggerInfo
  console.log(swaggerInfo, 'swaggerInfo')
  const rootPath = getRootPath()
  const paths = swaggerInfo.paths
  var tagMap = {}
  Array.from(Object.keys(paths)).forEach(path => {
    const reqMethod = paths[path]
    const firstMethod = reqMethod[Object.keys(reqMethod)[0]]
    const firstTag = firstMethod.tags[0]
    const title = isEmpty(firstMethod.summary) ? path : firstMethod.summary
    var children = []
    children.push({path: path.substr(1, path.length - 1), key: path, reqMethod: reqMethod, meta: {icon: '', title: title}, routeParam: {firstTag: firstTag, path: path}})

    tagMap[firstTag] = (tagMap[firstTag] || []).concat(children)
  })

  Array.from(swaggerInfo.tags).forEach((tag, pindex) => {
    const hidden = tag.name === 'basic-error-controller'
    const children = tagMap[tag.name]
    children.forEach((child, index) => {
      child.routeParam.pindex = pindex
      child.routeParam.index = index
    })
    console.log(rootPath, 'rootPath')
    const menu = Object.assign({meta: {icon: '', title: tag.name }, path: rootPath, key: tag.name, hidden: hidden}, tag, {children: children, routeParam: {}})
    menus.push(menu)
  })
  store.commit('menus', menus)

  return menus
}

function getRootPath() {
  const theme = store.getters.theme
  if (theme === 'admin') {
    return '/swagger'
  } else if (theme === 'simple') {
    return '/simpleSwagger'
  }
}

export { resolveMenu }

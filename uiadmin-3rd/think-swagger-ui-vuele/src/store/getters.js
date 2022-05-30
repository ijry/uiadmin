const getters = {
  swaggerPath: state => state.swagger.path,
  swaggerInfo: state => state.swagger.info,
  menus: state => state.swagger.menus,
  headers: state => state.swagger.headers,
  theme: state => state.main.theme

}
export default getters

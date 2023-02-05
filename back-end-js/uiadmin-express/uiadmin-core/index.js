
const {MenuItem, menuList} = require('./src/decorator/MenuItem')
const CoreController = require('./src/admin/CoreController')
const XyBuilderList = require('./src/util/builder/XyBuilderList')
const XyBuilderForm = require('./src/util/builder/XyBuilderForm')
const { Controller, Get, RootUrl, Post, Put, Delete} = require('@tuzilow/express-decorator')

module.exports = {
    Controller,
    Get,
    RootUrl,
    Post,
    Put,
    Delete,
    MenuItem,
    menuList,
    UiAdmin: CoreController,
    XyBuilderList,
    XyBuilderForm
}


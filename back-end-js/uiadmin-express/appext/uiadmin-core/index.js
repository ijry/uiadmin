
const {MenuItem, menuList} = require('./decorator/MenuItem')
const CoreController = require('./admin/CoreController')
const XyBuilderList = require('./util/builder/XyBuilderList')
const XyBuilderForm = require('./util/builder/XyBuilderForm')
const { Controller, Get, RootUrl, Post, Put, Delete} = require('@tuzilow/express-decorator')
import { config } from './util/common'

module.exports = {
    config,
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


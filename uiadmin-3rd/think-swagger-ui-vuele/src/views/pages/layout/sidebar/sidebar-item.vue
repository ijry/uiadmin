<template>
  <div v-if="!item.hidden" class="menu-wrapper">
    <app-link  v-if="!item.children" :param="child.routeParam" :to="resolvePath(item.path)">
      <el-menu-item :index="resolvePath(item.path)" :class="{'submenu-title-noDropdown':!isNest}">
        <item v-if="item" :icon="item.meta.icon||item.meta.icon" :title="item.meta.title" />
      </el-menu-item>
    </app-link>
    <el-submenu v-else :index="item.name||item.path">
      <template slot="title">
        <item v-if="item" :icon="item.meta.icon" :title="item.meta.title" />
      </template>

      <template v-for="child in item.children" v-if="!child.hidden">
        <sidebar-item
          v-if="child.children&&child.children.length>0"
          :is-nest="true"
          :item="child"
          :key="child.path"
          :base-path="resolvePath(child.path)"
          class="nest-menu" />
        <app-link v-else :to="resolvePath(child.path)" :param="child.routeParam" :key="child.key">
          <el-menu-item :index="resolvePath(child.path)">
            <item v-if="child" :icon="child.meta.icon" :title="child.meta.title" />
          </el-menu-item>
        </app-link>
      </template>
    </el-submenu>

  </div>
</template>

<script>
import path from 'path'
import item from './item'
import appLink from './link'

export default {
  name: 'sidebarItem',
  components: { item, appLink },
  props: {
    item: {type: Object, required: true},
    isNest: {type: Boolean, default: false},
    basePath: {type: String, default: ''}
  },
  data() {
    return {
    }
  },
  created() {
  },
  methods: {
    resolvePath(routePath) {
      return path.resolve(this.basePath, routePath)
    }
  }
}
</script>

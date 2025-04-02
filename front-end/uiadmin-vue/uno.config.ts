// uno.config.ts
import { defineConfig, presetAttributify, presetUno, presetWind } from 'unocss'
import presetRemToPx from '@unocss/preset-rem-to-px'

export default defineConfig({
    presets: [
      presetUno(),
      presetWind(),
      presetAttributify(),
      presetRemToPx({
        baseFontSize: 16,
      }),
    ],
    // 设置shortcuts,只能使用预设的和自定义的规则
    shortcuts: {
        'wh-full': 'w-full h-full',
        'flex-row-center': 'flex justify-center items-center',
        'flex-row-between': 'flex justify-between items-center',
        'flex-row-evenly': 'flex justify-evenly items-center',
        'flex-row-warp': 'flex flex-wrap',
        'flex-row-end': 'flex justify-end items-center',
        'flex-col-center': 'flex flex-col justify-center items-center',
        'flex-x-center': 'flex justify-center',
        'flex-y-center': 'flex items-center',
        'i-flex-center': 'inline-flex justify-center items-center',
        'i-flex-x-center': 'inline-flex justify-center',
        'i-flex-y-center': 'inline-flex items-center'
    },
    // 主题配置
    theme: {
      // 继承boxShadow
      boxShadow: {
          box: '0 1px 8px 0 rgba(255, 0, 0, 0.1)',
          item: '0 1px 8px 0 rgba(0, 0, 0, 0.1)'
      },
      colors: {
          primary: 'var(--el-color-primary)',
          info: 'var(--el-color-info)',
          success: 'var(--el-color-success)',
          warning: 'var(--el-color-warning)',
          error: 'var(--el-color-error)',
          danger: 'var(--el-color-error)',
      }
  }
})

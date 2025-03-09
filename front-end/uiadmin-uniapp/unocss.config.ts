import presetWeapp from 'unocss-preset-weapp'
import { extractorAttributify, transformerClass } from 'unocss-preset-weapp/transformer'
import transformerDirectives from '@unocss/transformer-directives'
import { presetIcons } from 'unocss'

const { presetWeappAttributify, transformerAttributify } = extractorAttributify()

export default {
    rules: [
        // 自定义一个处理white-space属性的规则
        // 假设我们想要一个以'ws-'开头的类名，后跟'nowrap'、'pre'等来表示不同的white-space值
        [/^whitespace-(.+)$/, ([, type]) => ({
            'white-space': type,
        })],
    ],
  presets: [
    // https://github.com/MellowCo/unocss-preset-weapp
    presetWeapp(),
    // attributify autocomplete
    presetWeappAttributify(),
    // https://icones.js.org/collection/mdi
    presetIcons()
  ],
  shortcuts: [
    {
      'border-base': 'border border-gray-500_10',
      'center': 'flex justify-center items-center',
    },
  ],

  transformers: [
    transformerDirectives({
        enforce: 'pre',
    }),

    // https://github.com/MellowCo/unocss-preset-weapp/tree/main/src/transformer/transformerAttributify
    transformerAttributify(),

    // https://github.com/MellowCo/unocss-preset-weapp/tree/main/src/transformer/transformerClass
    transformerClass(),
  ]
}

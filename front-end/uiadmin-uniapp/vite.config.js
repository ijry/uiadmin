import { defineConfig } from 'vite';
import uni from '@dcloudio/vite-plugin-uni';
// import viteVueUnocss, { unocss, flex, border, sketch, pseudo } from './js_sdk/a-hua-unocss';

export default defineConfig(async ()=> {
	const UnoCss = await import('unocss/vite').then(i => i.default)
	return {
		plugins: [
			uni(),
			UnoCss(),
	   //      viteVueUnocss({
	   //          /** 预设数组；默认[unocss()] */
	   //          presets: [
	   //              /**
	   //               * 默认预设；
	   //               * text-24、uno-text-24、xx-text-24...
	   //               */
	   //              unocss(),

	   //              /** 
	   //               * 弹性盒速写；
	   //               * flex-center、flex-col-center、flex-row-center...
	   //               */
	   //              flex(),
				// 	border(),
				// 	sketch(),
				// 	pseudo()
	   //          ],
				// unit: 'rpx'
	   //      })
		],
	}
});
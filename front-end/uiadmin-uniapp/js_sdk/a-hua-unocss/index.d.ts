
type PresetsItem = PresetResult | ((options: PresetOptions) => PresetResult | Promise<PresetResult>);
type ProductionShortcuts = (Record<string, string> | [RegExp | string, (classNames: string[], colors: Record<string, string>) => string])[];
type Unit = ['rpx', 'px', 'em', 'rem', 'vh', 'vw', 'pt', 'pc', 'in', 'mm', 'cm', 'svh', 'lvh', 'dvh', 'vmin', 'vmax', 'vi', 'vb', 'svmin', 'dvmin', 'svmax', 'dvmax', 'svi', 'dvi', 'svb', 'dvb'];

interface Options {
    presets?: PresetsItem[]
    prefix?: string | string[]
    exclude?: string | string[]

    autoImport?: boolean
    mode?: "development" | "production"

    log?: boolean | ((filePath: string) => boolean)
    unit?: Unit[number] | ((value: string) => string)

    shortcuts?: Record<string, string> | ProductionShortcuts
    rules?: [RegExp | string, (classNames: string[], colors: Record<string, string>) => Record<string, string>][]
    theme?: {
        generator?: boolean
        breakpoints?: Record<string, number | string>
        colors?: Record<string, string | Record<string, string>>
    }
}

type Styles = {
    name: string
    value: Record<string, string | number | undefined>
};

interface OptionsNormalization extends Options {
    prefix?: string[]
    exclude?: string[]
    unit?: (value: string) => string
    log?: (filePath: string) => boolean
    shortcuts?: ProductionShortcuts
}

interface PresetResult extends Pick<OptionsNormalization, 'prefix' | 'exclude' | 'rules' | 'shortcuts'> {
    name: `unocss:presets:${string}`
    transform?: (code: string, id: string) => void | string | Promise<void | string>
    theme?: {
        breakpoints?: Record<string, number | string>
        colors?: Record<string, string | Record<string, string>>
    }
}

interface PresetOptions extends Omit<PresetResult, 'name' | 'transform'> {
    allClassNames: () => string[]
    callback: (styles: Styles | Styles[], notMatchClassNames?: string | string[]) => void
}

/** 弹性盒速写 */
export declare const flex: () => PresetsItem;

/** 响应式断点预设速写 */
export declare const media: () => PresetsItem;

/** 默认预设规则 */
export declare const unocss: () => PresetsItem;

/** 伪类预设规则 */
export declare const pseudo: () => PresetsItem;

/** 函数写法预设规则 */
export declare const sketch: () => PresetsItem;

/** Vite vue 原子化 CSS 预设插件 */
export default function viteVueUnocss(options?: Options): any;

/** 边框速写 */
export declare const border: (options?: { style?: string, color?: string, width?: string | number }) => PresetsItem;

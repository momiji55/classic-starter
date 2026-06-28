/** @type {import('stylelint').Config} */
export default {
  extends: ['stylelint-config-standard'],
  plugins: ['stylelint-order'],
  rules: {
    'order/properties-order': [
      [
        {
          groupName: 'レイアウト・表示モード',
          emptyLineBefore: 'never',
          properties: [
            'display',
            'visibility',
            'position',
            'z-index',
            'inset',
            'top',
            'right',
            'bottom',
            'left',
            'float',
            'clear'
          ]
        },
        {
          groupName: 'Flex / Grid',
          emptyLineBefore: 'never',
          properties: [
            'flex',
            'flex-grow',
            'flex-shrink',
            'flex-basis',
            'flex-flow',
            'flex-direction',
            'flex-wrap',
            'justify-content',
            'align-items',
            'align-content',
            'align-self',
            'justify-self',
            'order',
            'grid',
            'grid-template',
            'grid-template-areas',
            'grid-template-columns',
            'grid-template-rows',
            'grid-auto-flow',
            'grid-auto-columns',
            'grid-auto-rows',
            'grid-area',
            'place-items',
            'place-content',
            'gap',
            'row-gap',
            'column-gap'
          ]
        },
        {
          groupName: 'ボックスサイズ',
          emptyLineBefore: 'never',
          properties: [
            'box-sizing',
            'aspect-ratio',
            'width',
            'min-width',
            'max-width',
            'height',
            'min-height',
            'max-height',
            'inline-size',
            'block-size'
          ]
        },
        {
          groupName: '余白',
          emptyLineBefore: 'never',
          properties: [
            'margin',
            'margin-top',
            'margin-right',
            'margin-bottom',
            'margin-left',
            'margin-block',
            'margin-inline',
            'padding',
            'padding-top',
            'padding-right',
            'padding-bottom',
            'padding-left',
            'padding-block',
            'padding-inline'
          ]
        },
        {
          groupName: 'スクロール・オーバーフロー',
          emptyLineBefore: 'never',
          properties: [
            'overflow',
            'overflow-x',
            'overflow-y',
            'overflow-anchor',
            'clip',
            'resize'
          ]
        },
        {
          groupName: 'リスト・テーブル・置換要素',
          emptyLineBefore: 'never',
          properties: [
            'list-style',
            'table-layout',
            'border-collapse',
            'object-fit',
            'object-position',
            'vertical-align'
          ]
        },
        {
          groupName: '文字',
          emptyLineBefore: 'never',
          properties: [
            'font',
            'font-family',
            'font-size',
            'font-weight',
            'font-style',
            'font-variant',
            'font-feature-settings',
            'line-height',
            'letter-spacing',
            'text-align',
            'text-indent',
            'text-decoration',
            'text-decoration-color',
            'text-decoration-thickness',
            'text-underline-offset',
            'white-space',
            'text-overflow',
            'word-break',
            'overflow-wrap',
            'color'
          ]
        },
        {
          groupName: '背景・境界線・装飾',
          emptyLineBefore: 'never',
          properties: [
            'background',
            'background-color',
            'background-image',
            'background-position',
            'background-size',
            'background-repeat',
            'border',
            'border-top',
            'border-right',
            'border-bottom',
            'border-left',
            'border-width',
            'border-style',
            'border-color',
            'border-top-width',
            'border-right-width',
            'border-bottom-width',
            'border-left-width',
            'border-top-style',
            'border-right-style',
            'border-bottom-style',
            'border-left-style',
            'border-top-color',
            'border-right-color',
            'border-bottom-color',
            'border-left-color',
            'border-radius',
            'outline',
            'opacity',
            'box-shadow',
            'filter',
            'clip-path'
          ]
        },
        {
          groupName: 'アニメーション・変化',
          emptyLineBefore: 'never',
          properties: [
            'cursor',
            'pointer-events',
            'user-select',
            'transform',
            'transform-box',
            'transform-style',
            'backface-visibility',
            'transition',
            'animation'
          ]
        },
        {
          groupName: '擬似要素・補助的なプロパティ',
          emptyLineBefore: 'never',
          properties: [
            'content'
          ]
        }
      ],
      {
        unspecified: 'bottomAlphabetical',
        emptyLineBeforeUnspecified: 'always'
      }
    ]
  }
};

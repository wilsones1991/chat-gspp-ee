export default {
  plugins: {
    tailwindcss: {},
    autoprefixer: {},
    ['postcss-prefix-selector']: {
      prefix: '.twp',
      transform(prefix, selector, prefixedSelector) {
        // Prevent prefixing of body, html, or specific utility selectors
        if (selector.startsWith('html') || selector.startsWith('body')) {
          return selector;
        }
        return prefixedSelector;
      }
    }
  },
}

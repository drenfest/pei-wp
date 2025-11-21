module.exports = (ctx) => ({
  plugins: {
    autoprefixer: {},
    ...(ctx.env === 'production' ? { cssnano: { preset: 'default' } } : {})
  }
});

module.exports = (api) => {
  api.cache(true);

  return {
    presets: [
      [
        '@babel/env',
        {
          useBuiltIns: 'entry',
          corejs: '2',
        },
      ],
    ],
    compact: true,
  };
};

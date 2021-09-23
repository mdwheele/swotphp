module.exports = ({ results }) => results.flatMap(({ input, errors, warnings }) => errors.filter(({ valid }) => !valid)
  .map(({ name, message }) => `##vso[task.logissue type=error;code=${name}]${message} (${input})`)
  .concat(
    warnings.filter(({ valid }) => !valid)
      .map(({ name, message }) => `##vso[task.logissue type=warning;code=${name}]${message} (${input})`)
  )
).join('\n')

module.exports = {
  extends: ['@commitlint/config-conventional'],
  plugins: ['commitlint-plugin-function-rules'],
  rules: {
    "header-max-length": [2, "always", 75],
    "body-max-line-length": [2, "always", 72],
    "type-min-length": [2, 'always', 3],
    "type-enum": [2, 'always', ['feat', 'fix', 'docs', 'style', 'refactor', 'test', 'revert', 'chore']],
  }
}

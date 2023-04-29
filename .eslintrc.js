module.exports = {
    env: {
        browser: true,
        es2021: true
    },
    parserOptions: {
        ecmaVersion: 'latest',
        sourceType: 'module'
    },
    rules: {
        "import/first": "error",
        "import/newline-after-import": "error",
        "import/no-duplicates": "error",
        "comma-dangle": ["error", "always-multiline"],
        "indent": ["error", 4],
        "properties": "always",
        "block-spacing": ["error"],
        "brace-style": "1tbs",
        "comma-style": "last",
        "key-spacing": ["error", { "beforeColon": false }],
        "key-spacing": ["error", { "afterColon": true }]
    }
}

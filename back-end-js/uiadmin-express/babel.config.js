const path = require('path')

module.exports = {
    "plugins": [
      ["@babel/plugin-proposal-decorators", { "legacy": true }],
      ["@babel/plugin-proposal-class-properties", { "loose": true }],
      ["@babel/plugin-proposal-private-methods", { "loose": true }],
      ["@babel/plugin-proposal-private-property-in-object", { "loose": true }]
    ],
    "presets": [
      [
        "@babel/preset-env",
        {
          "corejs": 3,
          "modules": "auto",
          "useBuiltIns": "usage",
          "targets": {
            "chrome": "58",
            "ie": "10"
          }
        }
      ]
    ]
}

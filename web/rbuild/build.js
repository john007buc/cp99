({
    baseUrl:'../scripts',
    mainConfigFile: '../scripts/main.js',
    name:'../scripts/main',
    out:'../scripts/concatenated-modules.js',
    preserveLicenseComments:false,
    paths: {
        "gmap": "empty:",
        "requireLib":"vendor/requirejs/require",
    },
    include: 'requireLib'
   // optimize:'none'

})
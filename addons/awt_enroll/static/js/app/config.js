require.config({
    baseUrl: '../addons/awt_enroll/static/js/app',
    paths: {
        'jquery': '../dist/jquery-1.11.1.min',
        'jquery.ui': '../dist/jquery-ui-1.10.3.min',
        'bootstrap': '../dist/bootstrap.min',
        'tpl':'../dist/tmodjs',
        'jquery.touchslider':'../dist/jquery.touchslider.min',
        'swipe':'../dist/swipe',
        'sweetalert':'../dist/sweetalert/sweetalert.min',
        'sui':'../dist/sui/sm.min',
        'md5': 'https://cdn.bootcss.com/blueimp-md5/2.10.0/js/md5.min'
    },
    shim: {
        'jquery.ui': {
            exports: "$",
            deps: ['jquery']
        },
        'bootstrap': {
            exports: "$",
            deps: ['jquery']
        },  
        'jquery.touchslider': {
            exports: "$",
            deps: ['jquery']
        },
        'sweetalert':{
            exports: "$",
            deps: ['css!../dist/sweetalert/sweetalert.css']
        },
        'sui':{
            exports: "$",
            deps: ['zepto.min']
        }
        
    }
});
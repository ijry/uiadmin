<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>InitAdmin管理后台</title>
    <link rel="stylesheet" href="">
    <style>
        body {
            margin: 0;
        }
        .main {
            width: 100%;
            height: 100vh;
            border: 0;
        }
    </style>
</head>
<body>
    <?php
        function sheme() {
            if ( !empty($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) !== 'off') {
                return 'https';
            } elseif ( isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https' ) {
                return 'https';
            } elseif ( !empty($_SERVER['HTTP_FRONT_END_HTTPS']) && strtolower($_SERVER['HTTP_FRONT_END_HTTPS']) !== 'off') {
                return 'https';
            }
            return 'http';
        }
    ?>
    <iframe class="main" src="<?php echo sheme(); ?>://admin.jiangruyi.com/#/home?api=<?php echo $_GET['api']; ?>&demo=<?php echo $_GET['demo']; ?>"></iframe>
</body>
</html>

<html>
<head>
    <title>UFW Dashboard By MRJAVACI</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
</head>
<body class="bg-dark text-white">
<div class="bod">
    <div class="container">
        <div class="row">
            <div class="col-md-3"></div>
            <div class="col-md-6">

                <?php


                require_once "class.Ufw.php";


                $ufw = new Ufw();
                $none = "";
                if (!$ufw->isEnable()) {
                    echo $ufw->reason;
                    $none = "none";
                }

                ?>
            </div>
            <div class="col-md-3"></div>
        </div>

        <div class="row" style="display:<?=$none?>;">
            <div class="col-md-3"></div>
            <div class="col-md-6"><div class="text-center"><h2>Geçerli Kurallar</h2></div></div>
            <div class="col-md-3"></div>
        </div>
        <div class="row" style="display:<?=$none?>;">
            <div class="col-md-6"></div>
            <div class="col-md-6"></div>
        </div>
    </div>
</div>

</body>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.js" integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU=" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
</html>

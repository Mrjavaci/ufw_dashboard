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
                if (isset($_GET["dil"]) && !empty($_GET["dil"])) {
                    require_once "class.Ufw.php";
                    $ufw = new Ufw($_GET["dil"]);
                    $none = "";

                    if (!$ufw->isEnable()) { //hata durumlarında false dönüyor,
                        // (shell_exec izni olmaması,www kullanıcısının yetksizi olması vs tüm hata durumlarında açıklama reason değişkenine gidiyor)
                        echo $ufw->reason;
                        $none = "none";
                    }

                    $ufwStatus = $ufw->getUfwStatus();
                } else {
                    ?>
                    <div class="text-center">
                        <form action="" method="get" class="form">
                            <label> Dil / Language
                                <select class="selector" name="dil">
                                    <option value=""> Select</option>
                                    <option value="tr"> TÜRKÇE</option>
                                    <option value="en"> English</option>
                                </select>
                            </label>
                        </form>
                    </div>
                    <?php
                }


                ?>
            </div>
            <div class="col-md-3"></div>
        </div>

        <div class="row" style="display:<?= $none ?>;">
            <div class="col-md-3"></div>
            <div class="col-md-6">
                <div class="text-center"><h2>
                        <?php
                        $silText = "Sil";
                        if ($_GET["dil"] == "tr") {
                            echo "Geçerli Kurallar";
                        } else {
                            $silText = "Delete";
                            echo "Active Rules";
                        }
                        ?>
                    </h2></div>
            </div>
            <div class="col-md-3"></div>
        </div>
        <div class="row" style="display:<?= $none ?>;">
            <div class="col-md-1"></div>
            <div class="col-md-10">
                <div class="table-responsive bg-light">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th scope="col">id</th>
                            <th scope="col">To</th>
                            <th scope="col">Action</th>
                            <th scope="col">From</th>
                            <th scope="col">Açıklama</th>
                            <th scope="col"><?= $silText ?></th>

                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        foreach ($ufwStatus as $status) {
                            ?>
                            <tr>
                                <th><?= $status["id"] ?></th>
                                <th><?= $status["to"] ?></th>
                                <th><?= $status["action"] ?></th>
                                <th><?= $status["from"] ?></th>
                                <th><?= $ufw->getAciklama($status) ?></th>
                                <th>
                                    <button class="btn-danger rounded" onclick="sil('<?= $status["id"] ?>')"><?= $silText ?></button>
                                </th>
                            </tr>
                            <?php
                        }
                        ?>
                        </tbody>
                    </table>
                </div>

            </div>
            <div class="col-md-1"></div>
        </div>
    </div>
</div>

</body>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.js" integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU=" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>

<script>
    function sil(id) {
        $.post("islem.php", {"id": id, "islem": "delete"}, function (result) {
            console.log(result);
        });
    }

    $(document).ready(function () {
        $(".selector").change(function () {
            $(".form").submit();

        });
    });

</script>
</html>

<?php
/**
 * Created by PhpStorm.
 * User: javaci
 * Date: 2020-02-12
 * Time: 02:27
 */

if (isset($_POST["islem"])) {
    switch ($_POST["islem"]) {
        case "delete":
            //

         echo shell_exec("yes | sudo ufw delete ". $_POST["id"] ." 2>&1")."--";
            break;
        default:
            //
            break;
    }

}
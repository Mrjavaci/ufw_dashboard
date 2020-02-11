<?php
/**
 * Created by PhpStorm.
 * User: Mrjavaci
 * Date: 2020-02-11
 * Time: 18:24
 */

class Ufw
{
    private $status = false;
    private $isEnable = false;
    public $reason = "";

    public function __construct()
    {
        if ($this->isEnabled('shell_exec')) {
            $this->isEnable = $this->checkIsRoot();
        } else {
            $this->isEnable = false;
            $this->reason = "shell_exec fonksiyonuna izin verilmiyor. Lütfen php.ini dosyanızı düzenleyin.<br>";
        }
    }

    private function isEnabled($func)
    {
        return is_callable($func) && false === stripos(ini_get('disable_functions'), $func);
    }

    public function checkIfUfwEnabled()
    {
        $str = shell_exec("sudo ufw status");
        $re = '/Status: (.*)/m';
        preg_match_all($re, $str, $matches, PREG_SET_ORDER, 0);
        if ($matches[0][1] != "active") {
            $this->reason .= "UFW servisi enable durumda değil. Lütfen UFW servisini aktif edip tekrar deneyiniz. <br>";
            return false;
        } else {
            return true;
        }
    }

    private function checkIsRoot()
    {
        if (empty(shell_exec("sudo php -v"))) {
            $this->reason .= shell_exec("whoami") . " Kullanıcısına root yetkileri (ufw servisi kontrolü için) verilmemiş.<br>";
            $this->reason .= "Lütfen SSH ile bağlanıp \"sudo visodu\" komutu ile sudo config dosyasını açıp aşağıdaki kodu ekleyin. <br>";
            $this->reason .= shell_exec("whoami") . " ALL=NOPASSWD: ALL<br>";
            return false;
        } else {
            return $this->checkIfUfwEnabled();
        }
    }

    /**
     * @return bool
     */
    public function isEnable()
    {
        return $this->isEnable;
    }


}
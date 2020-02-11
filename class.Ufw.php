<?php
/**
 * Created by PhpStorm.
 * User: Mrjavaci
 * Date: 2020-02-11
 * Time: 18:24
 */

class Ufw
{
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

    /**
     * @return array
     *
     */
    public function getUfwStatus()
    {
        $statusList = preg_split('/\r\n|\r|\n/', shell_exec("sudo ufw status numbered"));
        return $this->normalizeArray($this->removeSpaces($statusList));
    }


    private function removeSpaces(array $statusList)
    {
        unset($statusList[0]);
        unset($statusList[1]);
        unset($statusList[2]);
        unset($statusList[3]);
        $arr = array();
        foreach ($statusList as $item) {
            $item2 = str_replace(' ', '', $item);
            if ($item2[0] != "" || isset($item2[0])) {

                array_push($arr, $item2);
            }
        }

        return $arr;
    }

    private function normalizeArray(array $removeSpaces)
    {
        $arr = array();
        foreach ($removeSpaces as $sonuc) {
            $inArray = array();
            if (strpos($sonuc, 'ALLOWIN') !== false || strpos($sonuc, 'allowin') !== false) {
                $sonucArr = explode("ALLOWIN", $sonuc);
                $re = '/\[(.*?)\]/m';
                preg_match_all($re, $sonucArr[0], $matches, PREG_SET_ORDER, 0);
                $inArray["id"] = $matches[0][1];
                if (intval($matches[0][1]) >= 10) {
                    $inArray["to"] = substr($sonucArr[0], 4);
                } else {
                    $inArray["to"] = substr($sonucArr[0], 3);
                }
                $inArray["action"] = "ALLOW";
                $inArray["from"] = $sonucArr[1];

            } else if (strpos($sonuc, 'DENYIN') !== false || strpos($sonuc, 'denyin') !== false) {
                $sonucArr = explode("DENYIN", $sonuc);
                $re = '/\[(.*?)\]/m';
                preg_match_all($re, $sonucArr[0], $matches, PREG_SET_ORDER, 0);
                $inArray["id"] = $matches[0][1];
                if (intval($matches[0][1]) >= 10) {
                    $inArray["to"] = substr($sonucArr[0], 4);
                } else {
                    $inArray["to"] = substr($sonucArr[0], 3);
                }
                $inArray["action"] = "DENY";
                $inArray["from"] = $sonucArr[1];
            } else {
                echo "Read Error";
            }
            array_push($arr, $inArray);
        }
        return $arr;
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

    public function getAciklama(array $status)
    {
        $to = $status["to"];
        $action = $status["action"];
        $from = $status["from"];
        if ($action == "ALLOW" || $action == "allow") {
            if ($from == "Anywhere") {
                return $to . " Portuna her ip adresinden ulaşılabilsin.";
            } else {
                return $to . " Portuna sadece " . $from . " İp adresinden ulaşılabilsin";
            }

        } else {
            if ($from == "Anywhere") {
                return $to . " Portuna hiçbir ip adresinden ulaşılamasın.";
            } else {
                return $to . " Portuna sadece " . $from . " İp adresinden ulaşılamasın.";
            }
        }
    }


}
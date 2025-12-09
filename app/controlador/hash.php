<?php

class Hash {

    private static $pez = '$2y';
    private static $costo = '$10';

    public static function unique_salt() {
        return substr(sha1(mt_rand()), 0, 22);
    }

    public static function hashpass($pass) {
        return crypt($pass, self::$pez . self::$costo . '$' . self::unique_salt());
    }

    public static function verificar($hash, $pass) {
        $salt_completa = substr($hash, 0, 29);
        $nuevo_hash = crypt($pass, $salt_completa);
        return ($hash == $nuevo_hash);
    }

}

?>

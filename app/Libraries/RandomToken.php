<?php

namespace App\Libraries;

class RandomToken
{



    /**
     * 任意の長さのランダムな文字列を返却する
     *
     * @param integer $length
     * @return string
     */
    public static function MakeRandomToken(int $length = 64): string
    {
        $characters = array_merge(
            range(0, 9),
            range("a", "z"),
            range("A", "Z"),
        );
        $characters[] = "_";
        $characters[] = ".";
        $characters[] = "-";

        $random_token = "";
        for ($i = 0; $i < $length; $i++) {
            $random_token .= $characters[mt_rand(0, count($characters) - 1)];
        }
        return $random_token;
    }
}

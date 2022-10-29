<?php

namespace App\Libraries;

use App\Libraries\Traits\RandomTokenMaker;

class RandomToken
{

    use RandomTokenMaker;
    //
    // /**
    //  * 任意の長さのランダムな文字列を返却する
    //  * (※ random_intが利用不可の場合mt_randで代用)
    //  * @param int $length
    //  * @return string
    //  */
    // public static function MakeRandomToken(int $length = 128): string
    // {
    //     try {
    //         // ランダムな文字列のサイズは 32文字以上128文字以下とする
    //         if ( $length < 32  || 512 < $length) {
    //             throw new \Exception("ランダム文字数は32文字以上,512文字以下である必要があります.");
    //         }
    //         $characters = array_merge(
    //             range(0, 9),
    //             range("a", "z"),
    //             range("A", "Z"),
    //         );
    //
    //         $random_token = "";
    //         for ($i = 0; $i < $length; $i++) {
    //             try {
    //                 $random_token .= $characters[random_int(0, count($characters) - 1)];
    //             } catch (\Exception $e) {
    //                 logger()->error($e);
    //                 $random_token .= $characters[mt_rand(0, count($characters) - 1)];
    //             }
    //         }
    //         return $random_token;
    //     } catch (\Throwable $e) {
    //         // エラー発生時はサイズ0の文字列を返却する
    //         logger()->error($e);
    //         return (string)null;
    //     }
    // }
}

<?php
namespace core\models;

use core\traites\Singleton;

class Crypt{

    use Singleton;

    private $crypt_method = 'AES-128-CBC'; //Режим шифрования
    private $hache_algoritm = 'sha256';
    private $hache_length = 32;


    public static function pwd($password){

        return md5(defined('MD5_SALT') ? MD5_SALT . $password : $password);

    }


    public function encrypt($str){ //Шифрование данных

        $ivlen = openssl_cipher_iv_length($this->crypt_method);

        $iv = openssl_random_pseudo_bytes($ivlen);

        /*Шифруем строку*/
        $crypt_text = openssl_encrypt($str, $this->crypt_method, \App::CRYPT('KEY'), $options=OPENSSL_RAW_DATA, $iv);

        $hmac = hash_hmac($this->hache_algoritm, $crypt_text, \App::CRYPT('KEY'), $as_binary = true);

        if(!empty(ini_get('mbstring.func_overload'))){

            return base64_encode( $iv . $hmac . $crypt_text);

        }

        $result = $this->cryptCombine($crypt_text, $iv, $ivlen, $hmac);

        $startDigitPosition = (preg_match('/\d/', $result, $matches) ? strpos($result, $matches[0]) : 0);

        $endDigitPosition = preg_match('/(\d)[^\d]*$/', $result, $matches) ? strrpos($result, $matches[1]) : strlen($result);

        $resultArr = preg_split('//', $result, 0, PREG_SPLIT_NO_EMPTY);

        $lenArr = preg_split('//', (string)strlen($hmac), 2, PREG_SPLIT_NO_EMPTY);

        if(count($lenArr) === 1){

            $tempLen = $lenArr[0];

            $lenArr[0] = 0;

            $lenArr[1] = $tempLen;

        }

        array_splice($resultArr, ++$startDigitPosition, 0, $lenArr[0]);

        array_splice($resultArr, ++$endDigitPosition, 0, $lenArr[1]);

        array_splice($resultArr, ceil(count($resultArr) / 2), 0, count($lenArr));

        $result = implode($resultArr);

        return $result;

        /*Шифруем строку*/

    }

    public function decrypt($str){  //расшифровка данных

        $ivlen = openssl_cipher_iv_length($this->crypt_method);

        if(!empty(ini_get('mbstring.func_overload'))){

            $crypt_data = [];

            $c = base64_decode($str);

            $crypt_data['iv'] = substr($c, 0, $ivlen);

            $crypt_data['hmac'] = substr($c, $ivlen, $this->hache_length);

            $crypt_data['str'] = substr($c, $ivlen + $this->hache_length);

        }else{

            $crypt_data = $this->cryptUnCombine($str, $ivlen);

        }

        $original_plaintext = openssl_decrypt($crypt_data['str'], $this->crypt_method, \App::CRYPT('KEY'), $options=OPENSSL_RAW_DATA, $crypt_data['iv']);

        $calcmac = hash_hmac($this->hache_algoritm, $crypt_data['str'], \App::CRYPT('KEY'), $as_binary = true);

        if (hash_equals($crypt_data['hmac'], $calcmac))// с PHP 5.6+ сравнение, не подверженное атаке по времени
        {
            return $original_plaintext;
        }

        return false;

    }

    protected function cryptUnCombine($str, $ivlen){

        /*Обратное преобразование*/

        $hacheResultArr = preg_split('//', $str, 0, PREG_SPLIT_NO_EMPTY);

        $hacheLength = (int)array_splice($hacheResultArr, floor(count($hacheResultArr) / 2), 1)[0];

        if(!$hacheResultArr){

            return null;

        }

        $res = preg_grep('/\d/', $hacheResultArr);

        $countRes = count($res);

        $correctLength = '';

        if($countRes <= $hacheLength){

            $correctLength = implode($res);

            foreach ($res as $key => $item){

                unset($hacheResultArr[$key]);

            }

        }elseif($countRes === $hacheLength + 1){

            foreach ($res as $key => $item){

                if(count($res) === 2){

                    break;

                }

                $correctLength .= $item;

                unset($res[$key], $hacheResultArr[$key]);

            }

            end($res);

            $key = key($res);

            $correctLength = $res[$key] . $correctLength;

            unset($res[$key], $hacheResultArr[$key]);

        }else{

            next($res);

            $key = $key = key($res);

            $correctLength = $res[$key];

            unset($res[$key], $hacheResultArr[$key]);

            $counter = 0;

            end($res);

            while(true){

                prev($res);

                $key = key($res);

                $correctLength .= $res[$key];

                unset($res[$key], $hacheResultArr[$key]);

                if(++$counter >= $hacheLength - 1){

                    break;

                }

            }

        }

        $str = implode($hacheResultArr);

        if(empty($correctLength)){

            return null;

        }

        /*Обратное преобразование*/

        $crypt_data = [];

        $str = base64_decode($str);

        $hache_position = (int)ceil((strlen($str) / 2 - $correctLength / 2));

        $crypt_data['hmac'] = substr($str, $hache_position, $correctLength);

        $crypt_data['str'] = '';

        $crypt_data['iv'] = '';

        $str = str_replace($crypt_data['hmac'], '', $str);

        $counter = (int)ceil((strlen(\App::CRYPT('KEY')) / (strlen($str) - $ivlen + strlen($crypt_data['hmac']))));

        $progress = 2;

        for($i = 0; $i < strlen($str); $i++){

            if(($ivlen + strlen($crypt_data['str'])) < strlen($str)){

                if($i === $counter && strlen($crypt_data['iv']) < $ivlen){

                    $crypt_data['iv'] .= substr($str, $counter, 1);
                    $progress++;
                    $counter += $progress;

                }else{
                    $crypt_data['str'] .= substr($str, $i, 1);
                }

            }else{

                $crypt_str_len = strlen($crypt_data['str']);

                $crypt_data['str'] .= substr($str, $i, strlen($str) - $ivlen - $crypt_str_len);
                $crypt_data['iv'] .= substr($str, $i + (strlen($str) - $ivlen - $crypt_str_len));

                break;

            }

        }

        return $crypt_data;

    }

    protected function cryptCombine($str, $iv, $ivlen, $hmac){

        $new_str = '';

        $counter = (int)ceil((strlen(\App::CRYPT('KEY')) / (strlen($str) + strlen($hmac))));

        $progress = 1;

        if($counter >= strlen($str) || $counter < 0) $counter = 1;

        for($i = 0; $i < strlen($str); $i++){

            if($counter < strlen($str)){

                if($i === $counter){

                    $new_str .= substr($iv, $progress - 1, 1);
                    $progress++;
                    $counter += $progress;

                }

            }else{

                break;

            }

            $new_str .= substr($str, $i, 1);

        }

        $new_str .= substr($str, $i);
        $new_str .= substr($iv, $progress - 1);


        $new_str_arr[] = substr($new_str, 0, (int)ceil((strlen($new_str) / 2))) . $hmac;
        $new_str_arr[] = substr($new_str, (int)ceil((strlen($new_str) / 2)));

        return base64_encode($new_str_arr[0] . $new_str_arr[1]);

    }

}
<?php
namespace libraries;



use core\base\settings\Settings;

class FileEdit{

    protected $directory;

    protected $uniqueName = true;

    protected $imgArr = [];

    public function addFile($directory = ''){

        $this->setDirectory($directory);

        foreach ($_FILES as $key => $file){

            if(is_array($file['name'])){

                $file_arr = [];

                foreach($file['name'] as $i => $value){

                    if(!empty($file['name'][$i])){

                        $file_arr['name'] = $file['name'][$i];
                        $file_arr['type'] = $file['type'][$i];
                        $file_arr['tmp_name'] = $file['tmp_name'][$i];
                        $file_arr['error'] = $file['error'][$i];
                        $file_arr['size'] = $file['size'][$i];

                        $res_name = $this->createFile($file_arr);

                        if($res_name) $this->imgArr[$key][$i] = $res_name;

                    }

                }

            }else{

                if($file['name']){

                    $res_name = $this->createFile($file);

                    if($res_name) $this->imgArr[$key] = $res_name;

                }

            }

        }

        return $this->getFiles();

    }

    protected function createFile($file){

        $fileNameArr = explode('.', $file['name']);

        $ext = $fileNameArr[count($fileNameArr) - 1];

        unset($fileNameArr[count($fileNameArr) - 1]);

        $fileName = implode('.', $fileNameArr);

        $fileName = (new TextModify())->translit($fileName);

        $fileName = $this->checkFile($fileName, $ext);

        $this->checkResizeFile($file['tmp_name']);

        $fileFullName = $this->directory . $fileName;

        if($this->uploadFile($file['tmp_name'], $fileFullName)){

            if(Settings::get('setWebpImage')){

                $fileFullName = $this->convertToWebP($fileFullName);

            }

            return str_replace($_SERVER['DOCUMENT_ROOT'] . PATH . UPLOAD_DIR, '', $fileFullName);

        }


        return false;

    }

    public function convertToWebP($file){

        $info = getimagesize($file);

        if($info && stripos($info['mime'], 'image') === 0){

            if( $info[2] === IMAGETYPE_JPEG ) {

                $image = imagecreatefromjpeg($file);

            } elseif( $info[2] === IMAGETYPE_GIF ) {

                $image = imagecreatefromgif($file);

            } elseif( $info[2] === IMAGETYPE_PNG ) {

                $image = imagecreatefrompng($file);

                imagepalettetotruecolor($image);

                imagealphablending($image, true);

                imagesavealpha($image, true);

            }

            if(!empty($image)){

                $pathInfo = pathinfo($file);

                $fileNewName = $pathInfo['dirname'] . '/' . $pathInfo['filename'] . '.webp';

                if(imagewebp($image, $fileNewName , 100)){

                    @unlink($file);

                    $file = $fileNewName;

                }

                imagedestroy($image);

            }

        }

        return $file;

    }

    public function checkResizeFile($file){

        $info = getimagesize($file);

        $correctSize = 1903;

        if($info && stripos($info['mime'], 'image') === 0){

            $type = false;

            $other = 'height';

            if($info[0] > $correctSize){

                $type = 'width';

            }elseif ($info[1] > $correctSize){

                $type = 'height';

                $other = 'width';

            }

            if($type){

                $width = $info[0];

                $height = $info[1];

                $image = null;

                $function = null;

                if( $info[2] === IMAGETYPE_JPEG ) {

                    $image = imagecreatefromjpeg($file);

                    $function = 'imagejpeg';

                } elseif( $info[2] === IMAGETYPE_GIF ) {

                    $image = imagecreatefromgif($file);

                    $function = 'imagegif';

                } elseif( $info[2] === IMAGETYPE_PNG ) {

                    $image = imagecreatefrompng($file);

                    $function = 'imagepng';

                }

                if(!empty($image)){

                    $ratio = $correctSize / $$type;

                    $$type = $correctSize;

                    $$other = $$other * $ratio;

                    $newImage = imagecreatetruecolor($width, $height);

                    if($function === 'imagepng'){

                        $transparent = imagecolorallocatealpha($newImage, 0, 0, 0, 127);

                        imagefill($newImage, 0, 0, $transparent);

                        imagesavealpha($newImage, true); // save alphablending setting (important);

                    }

                    imagecopyresampled($newImage, $image, 0, 0, 0, 0, $width, $height, $info[0], $info[1]);

                    $function($newImage, $file);

                }

            }

        }

    }

    protected function uploadFile($tmpName, $dest){

        if(move_uploaded_file($tmpName, $dest)) return true;

        return false;

    }

    public function setDirectory($directory = ''){

        if(!$directory) $this->directory = $_SERVER['DOCUMENT_ROOT'] . PATH . UPLOAD_DIR;
        else
            $this->directory = strpos($directory, $_SERVER['DOCUMENT_ROOT'] . PATH . UPLOAD_DIR) !== false
                ? $directory
                : $_SERVER['DOCUMENT_ROOT'] . PATH . UPLOAD_DIR . $directory;

        if(!file_exists($this->directory)) mkdir($this->directory, 0777, true);

        !preg_match('/\/$/', $this->directory) && $this->directory .= '/';

    }

    public function checkFile($fileName, $ext, $fileLastName = ''){

        if(!file_exists($this->directory . $fileName . $fileLastName . '.' . $ext) || !$this->uniqueName)
            return $fileName . $fileLastName . '.' . $ext;

        return $this->checkFile($fileName, $ext, '_' . hash('crc32', time(). mt_rand(1, 1000)));

    }

    public function setUnique($value){

        $this->uniqueName = $value;

    }

    public function getFiles(){

        return $this->imgArr;

    }


    /**
     * Функция создания thumbnail
     * @param $image - полный путь к изображению
     * @param $settings - параметры (cut => [width|height], resize => [width|size] или [height|size])
     * @param string $prefix - префикс (необязательно, если есть добавляется к названию изображения и формируется новый
     * файл, если нет, то текущий файл перезаписывается)
     * @return bool|string - false - если ошибка создания | имя файла при создании изображения
     */
    public function createThumbnail($image, $settings, $prefix = ''){

        if(!empty($image) && !empty($settings)){
            foreach($settings as $type => $value){
                switch($type){
                    case 'resize':
                        $resize_set = explode("|", $value);
                        return $this->resize($image, $prefix, $resize_set);
                        break;

                    case 'cut':
                        $w_h = explode('|', $value);
                        $thumb_width = (int)trim($w_h[0]);
                        $thumb_height = (int)trim($w_h[1]);
                        if($thumb_width && $thumb_height){
                            return $this->thumbnail($image, $thumb_width, $thumb_height, $prefix);
                        }
                        break;

                    default:
                        return false;
                        break;
                }
            }
        }
    }


    /**
     * @param $image
     * @param $prefix
     * @param $resize
     * @return bool|string
     */
    protected function resize($image, $resize, $prefix = ''){

        $info = getimagesize($image); //получаем размеры картинки и ее тип
        $size = array($info[0], $info[1]); //закидываем размеры в массив

        if($resize){
            foreach ($resize as $key => $value){
                $resize[$key] = trim($value);
            }
            if($resize[0] == 'width'){
                $ratio = $resize[1] / $size[0];
                $height = $size[1] * $ratio;
                $new_size = array($resize[1], $height);
            }elseif($resize[0] == 'height'){
                $ratio = $resize[1] / $size[1];
                $width = $size[0] * $ratio;
                $new_size = array($width, $resize[1]);
            }else{
                return false;
            }
        }

        $thumb = imagecreatetruecolor($new_size[0], $new_size[1]); //возвращает идентификатор изображения, представляющий черное изображение заданного размера

        //В зависимости от расширения картинки вызываем соответствующую функцию
        if ($info['mime'] == 'image/png') {
            $src = imagecreatefrompng($image); //создаём новое изображение из файла

            $transparent = imagecolorallocatealpha($thumb, 0, 0, 0, 127);
            imagefill($thumb, 0, 0, $transparent);
            imagesavealpha($thumb, true); // save alphablending setting (important);

        } else if ($info['mime'] == 'image/jpeg') {
            $src = imagecreatefromjpeg($image);
        } else if ($info['mime'] == 'image/gif') {
            $src = imagecreatefromgif($image);
        } else {
            return false;
        }

        imagecopyresampled($thumb, $src, 0, 0, 0, 0, $new_size[0], $new_size[1], $size[0], $size[1]); //Копирование и изменение размера изображения с ресемплированием

        if($prefix){
            $image_name = substr($image, strrpos($image, '/') + 1);
            $dir_name = substr($image, 0, strrpos($image, '/') + 1);
            $new_image = $dir_name.$prefix."_".$image_name;
        }else{
            $new_image = $image;
        }

        if ($info['mime'] == 'image/png') {
            imagepng($thumb, $new_image); //создаём новое изображение из файла
        } else if ($info['mime'] == 'image/jpeg') {
            imagejpeg($thumb, $new_image);
        } else if ($info['mime'] == 'image/gif') {
            imagegif($thumb, $new_image);
        } else {
            return false;
        }
        return $new_image;
    }

    protected function thumbnail($image, $thumb_width, $thumb_height, $prefix = ''){

        $info = getimagesize($image); //получаем размеры картинки и ее тип
        $size = array($info[0], $info[1]); //закидываем размеры в массив

        $thumb = imagecreatetruecolor($thumb_width, $thumb_height); //возвращает идентификатор изображения, представляющий черное изображение заданного размера

        //В зависимости от расширения картинки вызываем соответствующую функцию
        if ($info['mime'] == 'image/png') {
            $src = imagecreatefrompng($image); //создаём новое изображение из файла

            $transparent = imagecolorallocatealpha($thumb, 0, 0, 0, 127);
            imagefill($thumb, 0, 0, $transparent);
            imagesavealpha($thumb, true); // save alphablending setting (important);

        } else if ($info['mime'] == 'image/jpeg') {
            $src = imagecreatefromjpeg($image);
        } else if ($info['mime'] == 'image/gif') {
            $src = imagecreatefromgif($image);
        } else {
            return false;
        }


        $src_aspect = $size[0] / $size[1]; //отношение ширины к высоте исходника
        $thumb_aspect = $thumb_width / $thumb_height; //отношение ширины к высоте аватарки

        if($src_aspect < $thumb_aspect) {        //узкий вариант (фиксированная ширина)
            $scale = $thumb_width / $size[0];
            $new_size = array($thumb_width, $thumb_width / $src_aspect);
            $src_pos = array(0, ($size[1] * $scale - $thumb_height) / $scale / 2); //Ищем расстояние по высоте от края картинки до начала картины после обрезки
        }else if($src_aspect > $thumb_aspect) { //широкий вариант (фиксированная высота)
            $scale = $thumb_height / $size[1];
            $new_size = array($thumb_height * $src_aspect, $thumb_height);
            $src_pos = array(($size[0] * $scale - $thumb_width) / $scale / 2, 0); //Ищем расстояние по ширине от края картинки до начала картины после обрезки
        }else{
            //другое
            $new_size = array($thumb_width, $thumb_height);
            $src_pos = array(0,0);
        }

        $new_size[0] = max($new_size[0], 1);
        $new_size[1] = max($new_size[1], 1);

        imagecopyresampled($thumb, $src, 0, 0, $src_pos[0], $src_pos[1], $new_size[0], $new_size[1], $size[0], $size[1]); //Копирование и изменение размера изображения с ресемплированием

        $image_name = substr($image, strrpos($image, '/') + 1);
        if($prefix){
            $dir_name = substr($image, 0, strrpos($image, '/') + 1);
            $image_name = $prefix."_".$image_name;
            $new_image = $dir_name.$image_name;
        }else{
            $new_image = $image;
        }

        if ($info['mime'] == 'image/png') {
            imagepng($thumb, $new_image); //создаём новое изображение из файла
        } else if ($info['mime'] == 'image/jpeg') {
            imagejpeg($thumb, $new_image);
        } else if ($info['mime'] == 'image/gif') {
            imagegif($thumb, $new_image);
        } else {
            return false;
        }
        return $image_name;
    }

    public function createJsThumbnail($arr, $prefix = ''){

        $image = $arr['img'];

        $info = getimagesize($image); //получаем размеры картинки и ее тип
        $size = array($info[0], $info[1]); //закидываем размеры в массив

        /*Создаем изображение того же размера что и вырезанное*/
        $arr['w'] = max($arr['w'], 1);
        $arr['h'] = max($arr['h'], 1);

        $new_image_size[0] = $arr['w'];
        $new_image_size[1] = $arr['h'];
        /*Создаем изображение того же размера что и вырезанное*/


        $thumb = imagecreatetruecolor($new_image_size[0], $new_image_size[1]); //возвращает идентификатор изображения, представляющий черное изображение заданного размера

        //В зависимости от расширения картинки вызываем соответствующую функцию
        if ($info['mime'] == 'image/png') {
            $src = imagecreatefrompng($image); //создаём новое изображение из файла

            $transparent = imagecolorallocatealpha($thumb, 0, 0, 0, 127);
            imagefill($thumb, 0, 0, $transparent);
            imagesavealpha($thumb, true); // save alphablending setting (important);

        } else if ($info['mime'] == 'image/jpeg') {
            $src = imagecreatefromjpeg($image);
        } else if ($info['mime'] == 'image/gif') {
            $src = imagecreatefromgif($image);
        } else {
            return false;
        }

        $new_size = array($new_image_size[0], $new_image_size[1]);
        $src_pos = array($arr['x1'], $arr['y1']);

        $new_size[0] = max($new_size[0], 1);
        $new_size[1] = max($new_size[1], 1);

        imagecopyresampled($thumb, $src, 0, 0, $src_pos[0], $src_pos[1], $new_size[0], $new_size[1], $arr['w'], $arr['h']); //Копирование и изменение размера изображения с ресемплированием

        $image_name = substr($image, strrpos($image, '/') + 1);
        if($prefix){
            $image_name = $prefix."_".$image_name;
            //$dir_name = $arr['crop'];
            $dir_name = substr($image, 0, strrpos($image, '/') + 1);
            $new_image = $dir_name.$image_name;
        }else{
            $new_image = $image;
        }

        if ($info['mime'] == 'image/png') {
            imagepng($thumb, $new_image); //создаём новое изображение из файла
        } else if ($info['mime'] == 'image/jpeg') {
            imagejpeg($thumb, $new_image);
        } else if ($info['mime'] == 'image/gif') {
            imagegif($thumb, $new_image);
        } else {
            return false;
        }
        return $image_name;
    }

    public function changeColor($image, $color_rgb, $rename = true){
        $info = getimagesize($image);

        if($info['mime'] != 'image/png') return false;

        set_time_limit(0);

        $thumb = imagecreatetruecolor($info[0], $info[1]);
        $transparent = imagecolorallocatealpha($thumb, 0, 0, 0, 127);
        imagefill($thumb, 0, 0, $transparent);
        imagesavealpha($thumb, true); // save alphablending setting (important);

        $new_img = imagecreatefrompng($image);
        imagesavealpha($new_img, true); // save alphablending setting (important);
        $color_rgb = explode(',', $color_rgb);

        for($i = 0; $i <= $info[0]; $i++){
            for($j = 0; $j <= $info[1]; $j++){
                $color_index = imagecolorat($new_img, $i, $j);
                $color_tran = imagecolorsforindex($new_img, $color_index);
                $alpha = (int)$color_tran['alpha'];
                if($alpha < 127){
                    $color = imagecolorallocatealpha($thumb, trim($color_rgb[0]), trim($color_rgb[1]), trim($color_rgb[2]), $alpha);
                    imagesetpixel($thumb, $i, $j, $color);
                }
            }
        }

        $image_name = substr($image, strrpos($image, '/') + 1);
        if($rename){
            $image_name = "icon_".$image_name;
            $dir_name = substr($image, 0, strrpos($image, '/') + 1);
            $new_image = $dir_name.$image_name;
        }else{
            $new_image = $image;
        }

        imagepng($thumb, $new_image);

        return $image_name;
    }

    public function rgbToHsl( $r, $g, $b ) {
        $oldR = $r;
        $oldG = $g;
        $oldB = $b;
        $r /= 255;
        $g /= 255;
        $b /= 255;
        $max = max( $r, $g, $b );
        $min = min( $r, $g, $b );
        $h = 0;
        $s = 0;
        $l = ( $max + $min ) / 2;
        $d = $max - $min;
        if( $d == 0 ){
            $h = $s = 0; // achromatic
        } else {
            $s = $d / ( 1 - abs( 2 * $l - 1 ) );
            switch( $max ){
                case $r:
                    $h = 60 * fmod( ( ( $g - $b ) / $d ), 6 );
                    if ($b > $g) {
                        $h += 360;
                    }
                    break;
                case $g:
                    $h = 60 * ( ( $b - $r ) / $d + 2 );
                    break;
                case $b:
                    $h = 60 * ( ( $r - $g ) / $d + 4 );
                    break;
            }
        }
        return array( round( $h, 2 ), round( $s, 2 ), round( $l, 2 ) );
    }

    public function hslToRgb( $h, $s, $l ){
        $r = 0;
        $g = 0;
        $b = 0;

        $c = ( 1 - abs( 2 * $l - 1 ) ) * $s;
        $x = $c * ( 1 - abs( fmod( ( $h / 60 ), 2 ) - 1 ) );
        $m = $l - ( $c / 2 );
        if ( $h < 60 ) {
            $r = $c;
            $g = $x;
            $b = 0;
        } else if ( $h < 120 ) {
            $r = $x;
            $g = $c;
            $b = 0;
        } else if ( $h < 180 ) {
            $r = 0;
            $g = $c;
            $b = $x;
        } else if ( $h < 240 ) {
            $r = 0;
            $g = $x;
            $b = $c;
        } else if ( $h < 300 ) {
            $r = $x;
            $g = 0;
            $b = $c;
        } else {
            $r = $c;
            $g = 0;
            $b = $x;
        }
        $r = ( $r + $m ) * 255;
        $g = ( $g + $m ) * 255;
        $b = ( $b + $m  ) * 255;
        return array( floor( $r ), floor( $g ), floor( $b ) );
    }

    public function hsv2rgb($hue,$sat,$val) {;
        $rgb = array(0,0,0);
        //calc rgb for 100% SV, go +1 for BR-range
        for($i=0;$i<4;$i++) {
            if (abs($hue - $i*120)<120) {
                $distance = max(60,abs($hue - $i*120));
                $rgb[$i % 3] = 1 - (($distance-60) / 60);
            }
        }
        //desaturate by increasing lower levels
        $max = max($rgb);
        $factor = 255 * ($val/100);
        for($i=0;$i<3;$i++) {
            //use distance between 0 and max (1) and multiply with value
            $rgb[$i] = round(($rgb[$i] + ($max - $rgb[$i]) * (1 - $sat/100)) * $factor);
        }
        $rgb['html'] = sprintf('#%02X%02X%02X', $rgb[0], $rgb[1], $rgb[2]);
        return $rgb;
    }

}
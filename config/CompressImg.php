<?php 

class CompressImg{
  
    public static function convertToWebP($source) {
        $image = imagecreatefromstring(file_get_contents($source));
        ob_start();

        imagejpeg($image , null , 100);

        $cont = ob_get_contents();

        ob_end_clean();

        imagedestroy($image);

        $content = imagecreatefromstring($cont);

        imagewebp($content , self::nameToWepb($source));

        unlink($source);
        imagedestroy($content);
        return self::nameToWepb($source) ;
    }


    public static function nameToWepb($imageFilePath){

        $updatedPath = preg_replace('/\..+$/', '.webp', $imageFilePath);

        return $updatedPath;
    }
    
    
}


?>
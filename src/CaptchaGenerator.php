<?php


namespace Chenjiangbin\CaptchaPuzzle;


class CaptchaGenerator
{

    use AttrData;

    public function create()
    {
        // 拼接图片路径
        $bgPath = realpath(__DIR__ . DIRECTORY_SEPARATOR . $this->src);
        $src    = $this->loadImage($bgPath);
        if (!$src) {
            throw new CaptchaException('读取不到图片');
        }

        // 获取原图宽高
        $bgWidth  = imagesx($src);
        $bgHeight = imagesy($src);


        // 创建拼图块图像（与设定大小一致），并设置透明背景
        $bg = imagecreatetruecolor($this->targetWidth, $this->targetHeight);
        imagesavealpha($bg, true);
        $transparent = imagecolorallocatealpha($bg, 0, 0, 0, 127);
        imagefill($bg, 0, 0, $transparent);

        imagecopyresampled($bg, $src, 0, 0, 0, 0, 300, 200, $bgWidth, $bgHeight);
        imagedestroy($src);

        $x = rand($this->imgWidth, 300 - $this->imgWidth - 20);
        $y = rand(0, 200 - $this->imgHeight - 20);

        $puzzle = imagecreatetruecolor($this->imgWidth, $this->imgHeight);
        imagesavealpha($puzzle, true);
        $transparentPuzzle = imagecolorallocatealpha($puzzle, 0, 0, 0, 127);
        imagefill($puzzle, 0, 0, $transparentPuzzle);

        // 从原图中复制出拼图块
        imagecopy($puzzle, $bg, 0, 0, $x, $y, $this->imgWidth, $this->imgHeight);

        // 抠图背景（半透明白）
        $whiteAlpha = imagecolorallocatealpha($bg, 255, 255, 255, 90);
        for ($i = 0; $i < $this->imgWidth; $i++) {
            for ($j = 0; $j < $this->imgHeight; $j++) {
                imagesetpixel($bg, $x + $i, $y + $j, $whiteAlpha);
            }
        }

        // 在抠图区域画一个边框（白色不透明）
        $borderColor = imagecolorallocatealpha($bg, 255, 255, 255, 0);
        imagerectangle($bg, $x, $y, $x + $this->imgWidth - 1, $y + $this->imgHeight - 1, $borderColor);

        $token = '';
        if ($this->isToken) {
            session_start();
            $token            = uniqid('captcha_', true);
            $_SESSION[$token] = [
                'offset'     => $x,
                'expires_at' => time() + 300, // 有效期5分钟
            ];
        }
        // 返回图片和拼图数据（Base64），以及位置信息,token
        return [
            'bg'     => imageToBase64($bg),
            'puzzle' => imageToBase64($puzzle),
            'offset' => $x,
            'token'  => $token,
            'width'  => $this->imgWidth,
            'height' => $this->imgHeight,
            'top'    => $y
        ];
    }

    /**
     * 根据不同的图片加载不同的函数
     * @param $path
     * @return bool|false|resource
     */
    public function loadImage($path)
    {
        if (!file_exists($path)) {
            return false;
        }
        $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        switch ($ext) {
            case 'png':
                return @imagecreatefrompng($path);
            case 'jpg':
            case 'jpeg':
                return @imagecreatefromjpeg($path);
            case 'gif':
                return @imagecreatefromgif($path);
            case 'webp':
                return function_exists('imagecreatefromwebp') ? @imagecreatefromwebp($path) : false;
            default:
                return false;
        }
    }


}
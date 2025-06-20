<?php

namespace Chenjiangbin\CaptchaPuzzle;


trait AttrData
{

    // 抠图宽度、高度
    protected int  $imgWidth  = 50;
    protected int  $imgHeight = 50;
    protected bool $isToken   = false;
    // 验证码图片
    protected array $captchaImages
        = [
            '../public/images/yzm_01.png',
            '../public/images/yzm_02.png',
            '../public/images/yzm_03.png',
            '../public/images/yzm_04.png',
            '../public/images/yzm_05.png',
            '../public/images/yzm_06.png',
            '../public/images/yzm_07.png'
        ];
    // 验证码图片路径
    protected string $src;

    // 设定图像大小
    protected int $targetWidth  = 300;
    protected int $targetHeight = 200;

    /**
     * 设置抠图宽度
     * @param int $value
     * @return $this
     */
    public function setImgWidth(int $value)
    {
        $this->imgWidth = $value;
        return $this;
    }

    /**
     * 设置抠图高度
     * @param int $value
     * @return $this
     */
    public function setImgHeight(int $value)
    {
        $this->imgHeight = $value;
        return $this;
    }

    /**
     * 设置图像宽度
     * @param int $value
     * @return $this
     */
    public function setTargetWidth(int $value)
    {
        $this->targetWidth = $value;
        return $this;
    }

    /**
     * 设置图像高度
     * @param int $value
     * @return $this
     */
    public function setTargetHeight(int $value)
    {
        $this->targetHeight = $value;
        return $this;
    }

    /**
     * 设置验证码图片，可以传单个字符串路径或者数组，随机选择一张
     * @param $value
     * @return $this
     */
    public function setCaptchaImages($value)
    {
        if (is_string($value)) {
            $this->src = $value;
        } else {
            $this->src = $value[array_rand($value)] ?? $this->captchaImages[array_rand($this->captchaImages)];
        }
        return $this;
    }

    /**
     * 获取当前背景图片路径
     * @return string
     */
    public function getSrc(): string
    {
        return $this->src;
    }

    /**
     * 是否返回token
     * @param bool $value
     * @return $this
     */
    public function setIsToken(bool $value)
    {
        $this->isToken = $value;
        return $this;
    }

}
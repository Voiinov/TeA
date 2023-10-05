<?php

namespace App\Helper;

class Designer
{

    /**
     * @param string $social
     * @param string $lnk
     * @param bool $showIcon
     * @return string
     */
    public function socialLink(string $social, string $lnk, bool $showIcon = false): string
    {
        $social = mb_strtolower($social);

        switch ($social) {
            case("linkedin"):
                $ico = "fab fa-linkedin";
                $url = "https://www.linkedin.com/in/";
                $text = "LinkedIn";
                break;
            case("github"):
                $ico = "fab fa-github";
                $url = "https://github.com";
                $text = "GitHub";
                break;
            case("instagram"):
                $ico = "fab fa-instagram";
                $url = "https://instagram.com";
                $text = "Instagram";
                break;
            case("fb"):
                $ico = "fab fa-facebook";
                $url = "https://facebook.com";
                $text = "Facebook";
                break;
            default:
                $ico = "fas fa-link";
                $text = $social;
                $url = $social;
        }
        if ($showIcon)
            $text = "<i class='{$ico}'></i>&nbsp;{$text}";

        return $this->linkConstruct($url . $lnk, $text);
    }

    /**
     * @param string $url
     * @param string $text
     * @param string $target
     * @return string
     */
    public function linkConstruct(string $url, string $text, string $target = "_blank"): string
    {
        return "<a href='{$url}' target='{$target}'>{$text}</a>";
    }

}
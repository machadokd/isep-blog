<?php

namespace App\Support;

class PostTitle
{
    /**
     * Drop a leading challenge name ("Desafio 1 - Semana 2" becomes "Semana 2"),
     * for places where the challenge is already shown next to the post.
     */
    public static function withoutChallenge(string $title, ?string $challengeName): string
    {
        if (blank($challengeName)) {
            return $title;
        }

        $shortened = preg_replace('/^\s*'.preg_quote($challengeName, '/').'\s*[-–—:|]\s*/iu', '', $title);

        return filled($shortened) ? $shortened : $title;
    }
}

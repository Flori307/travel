<?php
// app/Services/ProfanityFilterService.php

namespace App\Services;

class ProfanityFilterService
{
    protected $badWords = [
        'дурак', 'идиот', 'дебил', 'козел', 'сволочь', 'гад', 'тварь',
        'сука', 'бля', 'хуй', 'пизда', 'ебать', 'залупа', 'мудак',
        'редиска', 'олух', 'чмо', 'урод', 'кретин', 'даун', 'шиза',
        'fuck', 'shit', 'damn', 'bitch', 'asshole', 'bastard'
    ];

    protected $replacements = [
        'дурак' => '****',
        'идиот' => '*****',
        'дебил' => '*****',
        'fuck' => '****',
        'shit' => '****',
    ];

    public function containsProfanity(string $text): bool
    {
        $lowerText = mb_strtolower($text);
        
        foreach ($this->badWords as $badWord) {
            if (mb_strpos($lowerText, mb_strtolower($badWord)) !== false) {
                return true;
            }
        }
        
        return false;
    }

    public function filterProfanity(string $text): string
    {
        $filtered = $text;
        
        foreach ($this->badWords as $badWord) {
            $pattern = '/\b' . preg_quote($badWord, '/') . '\b/iu';
            $filtered = preg_replace($pattern, '***', $filtered);
        }
        
        return $filtered;
    }

    public function getBadWordsFound(string $text): array
    {
        $found = [];
        $lowerText = mb_strtolower($text);
        
        foreach ($this->badWords as $badWord) {
            if (mb_strpos($lowerText, mb_strtolower($badWord)) !== false) {
                $found[] = $badWord;
            }
        }
        
        return $found;
    }
}
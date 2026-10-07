<?php

namespace Tests\Unit;

use App\Support\PostTitle;
use PHPUnit\Framework\TestCase;

class PostTitleTest extends TestCase
{
    public function test_it_drops_the_leading_challenge_name(): void
    {
        $this->assertSame('Semana 2', PostTitle::withoutChallenge('Desafio 1 - Semana 2', 'Desafio 1'));
        $this->assertSame('Regras do perfil', PostTitle::withoutChallenge('desafio 1: Regras do perfil', 'Desafio 1'));
    }

    public function test_it_keeps_titles_that_do_not_start_with_the_challenge_name(): void
    {
        $this->assertSame('Regras do perfil de risco', PostTitle::withoutChallenge('Regras do perfil de risco', 'Desafio 1'));
        $this->assertSame('Desafio 1', PostTitle::withoutChallenge('Desafio 1', 'Desafio 1'));
        $this->assertSame('Desafio 1 - Semana 2', PostTitle::withoutChallenge('Desafio 1 - Semana 2', null));
    }
}

<?php

namespace Tests\Feature;

use App\Models\User;
use Canvas\Models\Post;
use Canvas\Models\Topic;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class CanvasUiPagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_homepage_features_latest_post_and_lists_challenges_in_order(): void
    {
        $firstChallenge = $this->createTopic('Deteção de fraude');
        $currentChallenge = $this->createTopic('Perfil de investidor');
        $this->createPost($firstChallenge, 'Kick-off da fraude', now()->subWeeks(5));
        $this->createPost($currentChallenge, 'Requisitos do perfil', now()->subWeeks(2));
        $this->createPost($currentChallenge, 'Regras do perfil de risco', now()->subDay());

        $this->get(route('canvas-ui.index'))
            ->assertOk()
            ->assertSeeInOrder(['01', 'Deteção de fraude', '1 semana', '02', 'Perfil de investidor', 'Em curso', 'Semana 1', 'Semana 2'])
            ->assertSeeInOrder(['Última atualização', 'Semana 2', 'Regras do perfil de risco'])
            ->assertSee('Kick-off da fraude');
    }

    public function test_homepage_filters_posts_by_challenge(): void
    {
        $fraud = $this->createTopic('Deteção de fraude');
        $profile = $this->createTopic('Perfil de investidor');
        $this->createPost($fraud, 'Post de fraude', now()->subWeek());
        $this->createPost($profile, 'Post de perfil', now()->subDay());

        $this->get(route('canvas-ui.index', ['desafio' => 'detecao-de-fraude']))
            ->assertOk()
            ->assertSee('Post de fraude')
            ->assertDontSee('Post de perfil')
            ->assertDontSee('Última atualização');
    }

    public function test_post_page_shows_week_number_and_neighbouring_weeks(): void
    {
        $topic = $this->createTopic('Perfil de investidor');
        $this->createPost($topic, 'Primeira semana', now()->subWeeks(3));
        $middle = $this->createPost($topic, 'Segunda semana', now()->subWeeks(2));
        $this->createPost($topic, 'Terceira semana', now()->subWeek());

        $this->get(route('canvas-ui.show', $middle->slug))
            ->assertOk()
            ->assertSeeInOrder(['Perfil de investidor', 'Segunda semana', 'Semana 2'])
            ->assertSeeInOrder(['Semana 1', 'Primeira semana', 'Semana 3', 'Terceira semana']);
    }

    public function test_week_badge_is_hidden_when_title_already_names_the_week(): void
    {
        $topic = $this->createTopic('Desafio 1');
        $post = $this->createPost($topic, 'Desafio 1 - Semana 1', now()->subDay());

        $this->get(route('canvas-ui.show', $post->slug))
            ->assertOk()
            ->assertSee('Desafio 1 - Semana 1')
            ->assertDontSee('>Semana 1</span>', false);
    }

    public function test_topic_page_lists_weeks_oldest_first(): void
    {
        $topic = $this->createTopic('Perfil de investidor');
        $this->createPost($topic, 'Segunda semana', now()->subWeek());
        $this->createPost($topic, 'Primeira semana', now()->subWeeks(2));

        $this->get(route('canvas-ui.topic', $topic->slug))
            ->assertOk()
            ->assertSeeInOrder(['Perfil de investidor', 'Em curso', 'Semana 1', 'Primeira semana', 'Semana 2', 'Segunda semana']);
    }

    public function test_challenges_page_lists_challenges_in_sequence_with_status(): void
    {
        $past = $this->createTopic('Deteção de fraude');
        $current = $this->createTopic('Perfil de investidor');
        $this->createTopic('Risco de crédito');
        $this->createPost($past, 'Conclusões da fraude', now()->subWeeks(4));
        $this->createPost($current, 'Regras do perfil', now()->subDay());

        $this->get(route('canvas-ui.topics'))
            ->assertOk()
            ->assertSeeInOrder([
                'Deteção de fraude', 'Concluído', '1 semana publicada', 'Conclusões da fraude',
                'Perfil de investidor', 'Em curso', '1 semana publicada', 'Regras do perfil',
                'Risco de crédito', 'Em breve', '0 semanas publicadas',
            ]);
    }

    public function test_about_page_renders(): void
    {
        $this->get(route('canvas-ui.about'))
            ->assertOk()
            ->assertSee('Sobre nós')
            ->assertSee('Equipa');
    }

    public function test_old_team_url_redirects_to_about(): void
    {
        $this->get('/equipa')->assertRedirect('/sobre-nos')->assertStatus(301);
    }

    private function createTopic(string $name): Topic
    {
        return Topic::query()->forceCreate([
            'id' => (string) Str::uuid(),
            'slug' => Str::slug($name),
            'name' => $name,
            'user_id' => User::factory()->create()->id,
        ]);
    }

    private function createPost(Topic $topic, string $title, \DateTimeInterface $publishedAt): Post
    {
        return Post::query()->forceCreate([
            'id' => (string) Str::uuid(),
            'slug' => Str::slug($title),
            'title' => $title,
            'body' => 'Conteúdo',
            'published_at' => $publishedAt,
            'topic_id' => $topic->id,
            'user_id' => $topic->user_id,
        ]);
    }
}

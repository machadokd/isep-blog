<?php

namespace App\Http\Controllers\Canvas;

use Canvas\Events\PostViewed;
use Canvas\Models\CanvasUser;
use Canvas\Models\Post;
use Canvas\Models\Tag;
use Canvas\Models\Topic;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Collection;

class CanvasUiController extends Controller
{
    public function index(Request $request): View
    {
        $challenges = $this->challenges();
        $selectedChallenge = $challenges->firstWhere('slug', $request->query('desafio'));

        $featuredPost = $selectedChallenge === null
            ? Post::published()->with(['user', 'topic'])->latest('published_at')->first()
            : null;

        $posts = Post::published()
            ->with(['user', 'topic'])
            ->when($selectedChallenge, fn ($query) => $query->where('topic_id', $selectedChallenge->id))
            ->when($featuredPost, fn ($query) => $query->whereKeyNot($featuredPost->id))
            ->latest('published_at')
            ->paginate(9)
            ->withQueryString();

        $postsOnPage = collect($posts->items())->push($featuredPost)->filter();

        $this->attachCanvasUsersToPosts($postsOnPage);
        $this->attachWeekNumbers($postsOnPage);

        $currentChallenge = $challenges->firstWhere('status', 'current');
        $currentChallengeWeeks = $currentChallenge
            ? $currentChallenge->posts()->published()->orderBy('published_at')->get(['id', 'title', 'published_at'])
            : collect();

        return view('canvas::ui.index', compact('posts', 'featuredPost', 'challenges', 'selectedChallenge', 'currentChallengeWeeks'));
    }

    public function feed(): Response
    {
        $posts = Post::published()
            ->latest()
            ->limit(20)
            ->get();

        $channelTitle = config('app.name');
        $channelLink = route('canvas-ui.index');
        $channelDescription = 'Posts from '.$channelTitle.'.';
        $channelLanguage = str_replace('_', '-', app()->getLocale());

        return response()
            ->view('canvas::ui.feed', compact(
                'posts',
                'channelTitle',
                'channelLink',
                'channelDescription',
                'channelLanguage',
            ))
            ->header('Content-Type', 'application/rss+xml; charset=UTF-8');
    }

    public function show(string $slug): View
    {
        $post = Post::published()
            ->with(['user', 'tags', 'topic'])
            ->firstWhere('slug', $slug);

        if (! $post) {
            abort(404);
        }

        $this->attachCanvasUsersToPosts(collect([$post]));

        $weeks = $post->topic
            ? $post->topic->posts()->published()->orderBy('published_at')->get(['id', 'slug', 'title', 'topic_id'])->values()
            : collect([$post]);
        $position = (int) $weeks->search(fn (Post $week): bool => $week->id === $post->id);

        $post->setAttribute('week_number', $position + 1);
        $previousWeek = $weeks->get($position - 1)?->setAttribute('week_number', $position);
        $nextWeek = $weeks->get($position + 1)?->setAttribute('week_number', $position + 2);

        event(new PostViewed(
            post: $post,
            ip: request()->ip(),
            agent: request()->userAgent(),
            referer: request()->header('referer'),
        ));

        return view('canvas::ui.show', compact('post', 'weeks', 'previousWeek', 'nextWeek'));
    }

    public function author(string $username): View
    {
        $canvasUser = CanvasUser::query()
            ->where('username', $username)
            ->first();

        if ($canvasUser === null) {
            abort(404);
        }

        /** @var class-string<Model> $userModel */
        $userModel = config('canvas.user_model');

        $user = $userModel::query()->find($canvasUser->user_id);

        if ($user === null) {
            abort(404);
        }

        $user->setRelation('canvasUser', $canvasUser);

        $posts = Post::query()
            ->where('user_id', $canvasUser->user_id)
            ->published()
            ->with('topic')
            ->latest()
            ->paginate();

        return view('canvas::ui.author', compact('user', 'posts'));
    }

    public function tags(): View
    {
        $tags = Tag::query()
            ->withCount(['posts' => fn ($query) => $query->published()])
            ->orderBy('name')
            ->paginate();

        return view('canvas::ui.tags', compact('tags'));
    }

    public function tag(string $slug): View
    {
        $tag = Tag::firstWhere('slug', $slug);

        if (! $tag) {
            abort(404);
        }

        $posts = $tag->posts()
            ->published()
            ->with(['user', 'topic'])
            ->latest()
            ->paginate();

        $this->attachCanvasUsersToPosts($posts);

        return view('canvas::ui.tag', compact('tag', 'posts'));
    }

    public function topics(): View
    {
        $topics = $this->challenges();
        $weeksByChallenge = $this->weeksByChallenge();

        return view('canvas::ui.topics', compact('topics', 'weeksByChallenge'));
    }

    public function topic(string $slug): View
    {
        $topic = $this->challenges()->firstWhere('slug', $slug);

        if (! $topic) {
            abort(404);
        }

        $posts = $topic->posts()
            ->published()
            ->with(['user', 'tags', 'topic'])
            ->oldest('published_at')
            ->paginate(24);

        $this->attachCanvasUsersToPosts($posts);
        $this->attachWeekNumbers($posts);

        return view('canvas::ui.topic', compact('topic', 'posts'));
    }

    public function about(): View
    {
        return view('canvas::ui.about');
    }

    /**
     * Challenges run one at a time, so their order is the date of their first published post.
     * The challenge with the most recent post is the current one; challenges without posts are upcoming.
     *
     * @return Collection<int, Topic>
     */
    private function challenges(): Collection
    {
        $publishedPosts = fn ($query) => $query->published();

        $topics = Topic::query()
            ->withCount(['posts' => $publishedPosts])
            ->withMin(['posts' => $publishedPosts], 'published_at')
            ->withMax(['posts' => $publishedPosts], 'published_at')
            ->get();

        [$started, $upcoming] = $topics->partition(fn (Topic $topic): bool => $topic->posts_count > 0);

        $currentTopicId = $started->sortByDesc('posts_max_published_at')->first()?->id;

        return $started->sortBy('posts_min_published_at')
            ->concat($upcoming->sortBy('created_at'))
            ->values()
            ->each(function (Topic $topic, int $index) use ($currentTopicId): void {
                $topic->setAttribute('sequence', $index + 1);
                $topic->setAttribute('status', match (true) {
                    $topic->id === $currentTopicId => 'current',
                    $topic->posts_count > 0 => 'done',
                    default => 'upcoming',
                });
            });
    }

    /**
     * Published posts of every challenge in publication order, keyed by topic id.
     *
     * @return Collection<string, Collection<int, Post>>
     */
    private function weeksByChallenge(): Collection
    {
        return Post::published()
            ->whereNotNull('topic_id')
            ->orderBy('published_at')
            ->get(['id', 'slug', 'title', 'topic_id'])
            ->groupBy('topic_id');
    }

    /**
     * Each published post in a challenge is one week, numbered in publication order.
     */
    private function attachWeekNumbers(Paginator|Collection $posts): void
    {
        /** @var Collection<int, Post> $items */
        $items = $posts instanceof Paginator
            ? collect($posts->items())
            : collect($posts);

        $topicIds = $items->pluck('topic_id')->filter()->unique()->values();

        if ($topicIds->isEmpty()) {
            return;
        }

        /** @var array<string, int> $weekNumbers */
        $weekNumbers = [];

        Post::published()
            ->whereIn('topic_id', $topicIds)
            ->orderBy('published_at')
            ->get(['id', 'topic_id'])
            ->groupBy('topic_id')
            ->each(function (Collection $topicPosts) use (&$weekNumbers): void {
                foreach ($topicPosts->values() as $index => $topicPost) {
                    $weekNumbers[$topicPost->id] = $index + 1;
                }
            });

        $items->each(fn (Post $post) => $post->setAttribute('week_number', $weekNumbers[$post->id] ?? null));
    }

    private function attachCanvasUsersToPosts(Paginator|Collection $posts): void
    {
        /** @var Collection<int, Post> $items */
        $items = $posts instanceof Paginator
            ? collect($posts->items())
            : collect($posts);

        $userIds = $items
            ->pluck('user_id')
            ->filter()
            ->unique()
            ->values();

        if ($userIds->isEmpty()) {
            return;
        }

        $canvasUsers = CanvasUser::query()
            ->whereIn('user_id', $userIds)
            ->get()
            ->keyBy('user_id');

        $items->each(function (Post $post) use ($canvasUsers): void {
            $user = $post->user;

            if ($user === null) {
                return;
            }

            $canvasUser = $canvasUsers->get($post->user_id);

            if ($canvasUser !== null) {
                $user->setRelation('canvasUser', $canvasUser);
            }
        });
    }
}

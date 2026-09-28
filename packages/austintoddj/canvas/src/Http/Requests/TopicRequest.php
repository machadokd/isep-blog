<?php

declare(strict_types=1);

namespace Canvas\Http\Requests;

use Canvas\Support\MediaUrl;
use Illuminate\Validation\Rule;

class TopicRequest extends FormRequest
{
    /**
     * @return bool
     */
    public function authorize()
    {
        return $this->user(config('canvas.guard'))->can('manage-taxonomy');
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('featured_image')) {
            $this->merge([
                'featured_image' => MediaUrl::toStoredMediaReference(
                    is_string($this->input('featured_image')) ? $this->input('featured_image') : null,
                ),
            ]);
        }
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name' => 'required',
            'slug' => [
                'required',
                'alpha_dash',
                Rule::unique('canvas_topics')->where(function ($query) {
                    return $query->where('slug', request('slug'))->where('user_id', request()->user(config('canvas.guard'))->id);
                })->ignore(request('id'))->whereNull('deleted_at'),
            ],
            'featured_image' => 'nullable|string',
            'featured_image_caption' => 'nullable|string',
        ];
    }
}

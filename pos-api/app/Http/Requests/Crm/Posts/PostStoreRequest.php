<?php

namespace App\Http\Requests\Crm\Posts;

use App\Models\Post;
use App\Models\PostCategory;
use App\Models\PostType;
use App\Models\Tag;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PostStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => [
                'required',
                'max:120',
                Rule::unique('posts', 'title')
            ],
            'slug' => 'required',
            'content' => 'required',
            'post_type' => ['required', Rule::exists('post_types', 'uuid')],
            'files' => 'nullable',
            'files.*' => ['nullable', 'mimes:jpg,png'],
            'tags' => 'nullable',
            'post_category' => 'required',
            'post_category.*' => Rule::exists('post_category', 'uuid'),
            'featured' => 'required|boolean'
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $decodedPostType = json_decode($this->post_type, true);
        $decodedPostCategory = json_decode($this->post_category, true);

        $decodedTags = array_map(function ($tag) {
            $decodedTag = json_decode($tag, true);
            return $decodedTag['value'] ?? $tag;
        }, $this->tags ?? []);

        $this->replace([
            ...$this->all(),
            'featured' => $this->featured === "true",
            'post_type' => $decodedPostType['value'] ?? null,
            'post_category' => $decodedPostCategory['value'] ?? null,
            'tags' => $decodedTags,
            'slug' => Post::createSlug(
                $this->slug,
                PostType::whereUuid($decodedPostType['value'] ?? null)->first()
            )
        ]);
    }



    public function passedValidation()
    {
        $this->replace([
            ...$this->all(),
            'tags' => array_map(function ($tag) {
                return Tag::whereUuid($tag)->first()->name ?? $tag;
            }, $this->tags)
        ]);

        $this->merge([
            'post_type_id' => PostType::whereUuid($this->post_type)->first()->id,
            'category_id' => PostCategory::whereUuid($this->post_category)->first()->id,
        ]);
    }
}

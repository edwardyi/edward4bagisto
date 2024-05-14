<?php

namespace Webkul\MagicAI\Services;

use Gemini\Data\GenerationConfig;
use Gemini\Laravel\Facades\Gemini as GeminiBaseModel;

class Gemini
{
    /**
     * New service instance.
     */
    public function __construct(
        protected string $model,
        protected string $prompt,
        protected float $temperature,
        protected bool $stream = false
    ) {
        $this->setConfig();
    }

    /**
     * Sets OpenAI credentials.
     */
    public function setConfig(): void
    {
        config([
            'openai.api_key'      => core()->getConfigData('general.magic_ai.settings.api_key'),
            'openai.organization' => core()->getConfigData('general.magic_ai.settings.organization'),
        ]);
    }

    /**
     * Set LLM prompt text.
     */
    public function ask(): string
    {
        $generationConfig = new GenerationConfig(
            stopSequences: [
                'Title',
            ],
            maxOutputTokens: 800,
            temperature: $this->temperature,
            topP: 0.8,
            topK: 10
        );
        // https://github.com/google-gemini-php/laravel
        // https://github.com/google-gemini-php/laravel?tab=readme-ov-file#configuration
        $result = GeminiBaseModel::geminiPro()
            ->withGenerationConfig($generationConfig)
            ->generateContent($this->prompt);

        return $result->text();
    }

    /**
     * Generate image.
     */
    public function images(array $options): array
    {
        // @see https://deepmind.google/technologies/imagen-2/
        // $result = BaseOpenAI::images()->create([
        //     'model'           => $this->model,
        //     'prompt'          => $this->prompt,
        //     'n'               => intval($options['n'] ?? 1),
        //     'size'            => $options['size'],
        //     'quality'         => $options['quality'] ?? 'standard',
        //     'response_format' => 'b64_json',
        // ]);

        // $images = [];

        // foreach ($result->data as $image) {
        //     $images[]['url'] = 'data:image/png;base64,'.$image->b64_json;
        // }

        // return $images;
    }
}

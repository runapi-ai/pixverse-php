<?php

declare(strict_types=1);

namespace RunApi\Pixverse\Resources;

use RunApi\Core\Http\HttpClient;
use RunApi\Core\Models\TaskCreateResponse;
use RunApi\Core\RequestOptions;
use RunApi\Core\Resources\TypedConfiguredResource;
use RunApi\Pixverse\Models\CompletedVideoTaskResponse;
use RunApi\Pixverse\Models\VideoTaskResponse;
use RunApi\Pixverse\Types;

/** Image to video operations for PixVerse. */
readonly class ImageToVideo extends TypedConfiguredResource
{
    /**
     * Create a image to video task and return immediately with a task id.
     *
     * @param array{
     *   duration_seconds: int,
     *   first_frame_image_url: string,
     *   model: string,
     *   output_resolution: string,
     *   prompt: string,
     *   callback_url?: string,
     *   enable_audio?: bool,
     *   enable_multi_clip?: bool,
     *   seed?: int
     * } $params
     */
    public function create(array $params, ?RequestOptions $options = null): TaskCreateResponse
    {
        return parent::create($params, $options);
    }

    /** Fetch the current status of a image to video task. */
    public function get(string $id, ?RequestOptions $options = null): VideoTaskResponse
    {
        $response = parent::get($id, $options);

        /** @var VideoTaskResponse $response */
        return $response;
    }

    /**
     * Create a image to video task and poll until it completes.
     *
     * @param array{
     *   duration_seconds: int,
     *   first_frame_image_url: string,
     *   model: string,
     *   output_resolution: string,
     *   prompt: string,
     *   callback_url?: string,
     *   enable_audio?: bool,
     *   enable_multi_clip?: bool,
     *   seed?: int
     * } $params
     */
    public function run(array $params, ?RequestOptions $options = null): CompletedVideoTaskResponse
    {
        $response = parent::run($params, $options);

        /** @var CompletedVideoTaskResponse $response */
        return $response;
    }

    /** Create the resource using the shared RunAPI HTTP transport. */
    public static function fromHttp(HttpClient $http): self
    {
        return new self(
            $http,
            '/api/v1/pixverse/image_to_video',
            'pixverse/image-to-video',
            VideoTaskResponse::class,
            CompletedVideoTaskResponse::class,
            Types::IMAGE_TO_VIDEO_MODELS,
            'image-to-video',
            VideoTaskResponse::class,
            CompletedVideoTaskResponse::class,
        );
    }
}

<?php

declare(strict_types=1);

namespace RunApi\Pixverse\Resources;

use RunApi\Core\Http\HttpClient;
use RunApi\Core\Models\TaskCreateResponse;
use RunApi\Core\RequestOptions;
use RunApi\Core\Resources\TypedConfiguredResource;
use RunApi\Pixverse\Models\CompletedVideoTaskResponse;
use RunApi\Pixverse\Models\VideoTaskResponse;

/** Extend video operations for PixVerse. */
readonly class ExtendVideo extends TypedConfiguredResource
{
    /**
     * Create a extend video task and return immediately with a task id.
     *
     * @param array{
     *   duration_seconds: int,
     *   model: string,
     *   output_resolution: string,
     *   prompt: string,
     *   source_task_id: string,
     *   callback_url?: string,
     *   enable_audio?: bool,
     *   seed?: int
     * } $params
     */
    public function create(array $params, ?RequestOptions $options = null): TaskCreateResponse
    {
        return parent::create($params, $options);
    }

    /** Fetch the current status of a extend video task. */
    public function get(string $id, ?RequestOptions $options = null): VideoTaskResponse
    {
        $response = parent::get($id, $options);

        /** @var VideoTaskResponse $response */
        return $response;
    }

    /**
     * Create a extend video task and poll until it completes.
     *
     * @param array{
     *   duration_seconds: int,
     *   model: string,
     *   output_resolution: string,
     *   prompt: string,
     *   source_task_id: string,
     *   callback_url?: string,
     *   enable_audio?: bool,
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
            '/api/v1/pixverse/extend_video',
            VideoTaskResponse::class,
            CompletedVideoTaskResponse::class,
            'extend-video',
            VideoTaskResponse::class,
            CompletedVideoTaskResponse::class,
        );
    }
}

<?php

declare(strict_types=1);

namespace RunApi\Pixverse\Tests\Unit;

use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use RunApi\Core\ClientOptions;
use RunApi\Core\Errors\ValidationException;
use RunApi\Core\Resources\Pricing;
use RunApi\Core\Tests\Fixtures\QueueHttpClient;
use RunApi\Pixverse\Models\CompletedVideoTaskResponse;
use RunApi\Pixverse\PixverseClient;
use RunApi\Pixverse\Resources\EditVideo;
use RunApi\Pixverse\Resources\ExtendVideo;
use RunApi\Pixverse\Resources\ImageToVideo;
use RunApi\Pixverse\Resources\TextToVideo;
use RunApi\Pixverse\Resources\TransitionVideo;

final class PixverseClientTest extends TestCase
{
    public function testExposesTypedResources(): void
    {
        $client = new PixverseClient(new ClientOptions(apiKey: 'k', httpClient: new QueueHttpClient([]), maxRetries: 0));

        self::assertInstanceOf(TextToVideo::class, $client->textToVideo);
        self::assertInstanceOf(ImageToVideo::class, $client->imageToVideo);
        self::assertInstanceOf(EditVideo::class, $client->editVideo);
        self::assertInstanceOf(ExtendVideo::class, $client->extendVideo);
        self::assertInstanceOf(TransitionVideo::class, $client->transitionVideo);
        self::assertInstanceOf(Pricing::class, $client->pricing);
    }

    public function testCreatePostsCompactedBodyToCorrectPath(): void
    {
        $transport = new QueueHttpClient([
            new Response(200, [], '{"id":"task_1"}')]);
        $client = new PixverseClient(new ClientOptions(apiKey: 'k', httpClient: $transport, maxRetries: 0));

        $task = $client->textToVideo->create([
            'model' => 'pixverse-v6',
            'prompt' => 'A product render',
            'output_resolution' => '360p',
            'duration_seconds' => 1,
            'enable_audio' => true,
            'aspect_ratio' => '16:9',
            'enable_multi_clip' => true,
            'callback_url' => '']);

        $body = json_decode((string) $transport->requests[0]->getBody(), true, flags: JSON_THROW_ON_ERROR);

        self::assertSame('task_1', $task->id);
        self::assertSame('/api/v1/pixverse/text_to_video', $transport->requests[0]->getUri()->getPath());
        self::assertSame('pixverse-v6', $body['model']);
        self::assertArrayNotHasKey('callback_url', $body);
        self::assertArrayNotHasKey('seed', $body);
    }

    public function testRunReturnsTypedCompletedResponseAndPreservesUnknownFields(): void
    {
        $transport = new QueueHttpClient([
            new Response(200, [], '{"id":"task_1"}'),
            new Response(200, [], '{"id":"task_1","status":"completed","videos":[{"url":"https://file.runapi.ai/result"}],"generation_stage":"all_audios_ready","extra_field":"kept","usage":{"cost":0.05}}')]);
        $client = new PixverseClient(new ClientOptions(apiKey: 'k', httpClient: $transport, maxRetries: 0));

        $result = $client->textToVideo->run([
            'model' => 'pixverse-v6',
            'prompt' => 'A product render',
            'output_resolution' => '360p',
            'duration_seconds' => 1,
            'enable_audio' => true,
            'seed' => 1,
            'aspect_ratio' => '16:9',
            'enable_multi_clip' => true]);

        self::assertInstanceOf(CompletedVideoTaskResponse::class, $result);
        self::assertSame('https://file.runapi.ai/result', $result->videos[0]->url);
        self::assertSame('kept', $result->toArray()['extra_field']);
        self::assertSame('/api/v1/pixverse/text_to_video/task_1', $transport->requests[1]->getUri()->getPath());
    }

    public function testCompletedResponseRequiresResultFiles(): void
    {
        $transport = new QueueHttpClient([
            new Response(200, [], '{"id":"task_1"}'),
            new Response(200, [], '{"id":"task_1","status":"completed","usage":{"cost":0.05}}')]);
        $client = new PixverseClient(new ClientOptions(apiKey: 'k', httpClient: $transport, maxRetries: 0));

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('videos is required');

        $client->textToVideo->run([
            'model' => 'pixverse-v6',
            'prompt' => 'A product render',
            'output_resolution' => '360p',
            'duration_seconds' => 1,
            'enable_audio' => true,
            'seed' => 1,
            'aspect_ratio' => '16:9',
            'enable_multi_clip' => true]);
    }




    public function testSecondaryResourceUsesItsOwnPath(): void
    {
        $transport = new QueueHttpClient([
            new Response(200, [], '{"id":"task_2"}')]);
        $client = new PixverseClient(new ClientOptions(apiKey: 'k', httpClient: $transport, maxRetries: 0));

        $client->imageToVideo->create([
            'model' => 'pixverse-v6',
            'prompt' => 'A product render',
            'output_resolution' => '360p',
            'duration_seconds' => 1,
            'enable_audio' => true,
            'seed' => 1,
            'first_frame_image_url' => 'https://cdn.runapi.ai/public/samples/image.jpg',
            'enable_multi_clip' => true]);

        self::assertSame('/api/v1/pixverse/image_to_video', $transport->requests[0]->getUri()->getPath());
    }
}

<?php

declare(strict_types=1);

namespace RunApi\Pixverse;

use RunApi\Core\BaseClient;
use RunApi\Core\ClientOptions;
use RunApi\Pixverse\Resources\EditVideo;
use RunApi\Pixverse\Resources\ExtendVideo;
use RunApi\Pixverse\Resources\ImageToVideo;
use RunApi\Pixverse\Resources\TextToVideo;
use RunApi\Pixverse\Resources\TransitionVideo;

/**
 * PixVerse RunAPI PHP client.
 *
 * The client exposes typed model resources plus the universal `files` and
 * `account` resources.
 */
final class PixverseClient extends BaseClient
{
    /** Text to video operations for PixVerse. */
    public readonly TextToVideo $textToVideo;
    /** Image to video operations for PixVerse. */
    public readonly ImageToVideo $imageToVideo;
    /** Edit video operations for PixVerse. */
    public readonly EditVideo $editVideo;
    /** Extend video operations for PixVerse. */
    public readonly ExtendVideo $extendVideo;
    /** Transition video operations for PixVerse. */
    public readonly TransitionVideo $transitionVideo;

    /** Create a PixVerse client with optional API key, base URL, and transport overrides. */
    public function __construct(ClientOptions $options = new ClientOptions())
    {
        parent::__construct($options);
        $this->textToVideo = TextToVideo::fromHttp($this->http);
        $this->imageToVideo = ImageToVideo::fromHttp($this->http);
        $this->editVideo = EditVideo::fromHttp($this->http);
        $this->extendVideo = ExtendVideo::fromHttp($this->http);
        $this->transitionVideo = TransitionVideo::fromHttp($this->http);
    }
}

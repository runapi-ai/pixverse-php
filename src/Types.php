<?php

declare(strict_types=1);

namespace RunApi\Pixverse;

final class Types
{
    /**
     * Allowed model slugs for text to video requests.
     *
     * @var list<string>
     */
    public const TEXT_TO_VIDEO_MODELS = ['pixverse-v6'];

    /**
     * Allowed model slugs for image to video requests.
     *
     * @var list<string>
     */
    public const IMAGE_TO_VIDEO_MODELS = ['pixverse-v6'];

    /**
     * Allowed model slugs for edit video requests.
     *
     * @var list<string>
     */
    public const EDIT_VIDEO_MODELS = ['pixverse-v6'];

    /**
     * Allowed model slugs for extend video requests.
     *
     * @var list<string>
     */
    public const EXTEND_VIDEO_MODELS = ['pixverse-v6'];

    /**
     * Allowed model slugs for transition video requests.
     *
     * @var list<string>
     */
    public const TRANSITION_VIDEO_MODELS = ['pixverse-v6'];

    private function __construct()
    {
    }
}

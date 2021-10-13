<?php

declare(strict_types=1);

namespace Woda\WordPress\BlockEditor\AllowedBlockTypes;

final class ConfigProvider
{
    /**
     * @return array<mixed>
     */
    public function __invoke(): array
    {
        return [
            'block_editor' => [
                'allowed_block_types' => [
                    /**
                     * Example config group targeting posts and pages.
                     */
                    // [
                    //     'post_types' => [
                    //         'post',
                    //         'page',
                    //     ],
                    //     'allow' => [],
                    //     'disallow' => [],
                    // ],
                ],
            ],
            'dependencies' => [
                'aliases' => [],
                'factories' => [
                    Filter::class => FilterFactory::class,
                ],
            ],
        ];
    }
}

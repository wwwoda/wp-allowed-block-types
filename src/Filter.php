<?php

declare(strict_types=1);

namespace Woda\WordPress\BlockEditor\AllowedBlockTypes;

use Woda\WordPress\Hook\HookCallbackProviderInterface;
use WP_Block_Editor_Context;
use WP_Post;

use function array_diff;
use function array_key_exists;
use function array_unique;
use function count;
use function in_array;
use function is_array;

/**
 * @phpstan-type AllowedBlockTypesConfig list<array{
 *   post_types?: list<string>,
 *   allow?: list<string>,
 *   disallow?: list<string>
 * }>
 */
final class Filter implements HookCallbackProviderInterface
{
    /** @var AllowedBlockTypesConfig */
    private array $blockTypeConfigs;

    /**
     * @param AllowedBlockTypesConfig $blockTypeConfigs
     */
    public function __construct(array $blockTypeConfigs)
    {
        $this->blockTypeConfigs = $blockTypeConfigs;
    }

    public function registerCallbacks(): void
    {
        add_filter('allowed_block_types_all', [$this, 'updateAllowedBlockTypes'], 10, 2);
    }

    /**
     * @param array<mixed>|bool $allowedBlockTypes
     * @return array<mixed>|bool
     */
    public function updateAllowedBlockTypes($allowedBlockTypes, WP_Block_Editor_Context $context)
    {
        if (!is_array($allowedBlockTypes) || !$context->post instanceof WP_Post) {
            return $allowedBlockTypes;
        }
        $allowedBlockTypesConfig = [];
        $disallowedBlockTypesConfig = [];
        foreach ($this->blockTypeConfigs as $config) {
            if (
                array_key_exists('post_types', $config)
                && !in_array($context->post->post_type, $config['post_types'], true)
            ) {
                continue;
            }
            if (
                array_key_exists('allow', $config)
                && count($config['allow']) > 0
            ) {
                $allowedBlockTypesConfig = [...$allowedBlockTypesConfig, ...$config['allow']];
            }
            if (
                !array_key_exists('disallow', $config)
                || count($config['disallow']) <= 0
            ) {
                continue;
            }

            $disallowedBlockTypesConfig = [...$disallowedBlockTypesConfig, ...$config['disallow']];
        }
        $newAllowedBlockTypesConfig = count($allowedBlockTypesConfig) < 1
            ? $allowedBlockTypes
            : array_unique($allowedBlockTypesConfig);
        if (count($disallowedBlockTypesConfig) > 0) {
            $newAllowedBlockTypesConfig = [...array_diff(
                $newAllowedBlockTypesConfig,
                array_unique($disallowedBlockTypesConfig)
            ),
            ];
        }
        return $newAllowedBlockTypesConfig;
    }
}

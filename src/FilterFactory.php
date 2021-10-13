<?php

declare(strict_types=1);

namespace Woda\WordPress\BlockEditor\AllowedBlockTypes;

use Psr\Container\ContainerInterface;
use Woda\WordPress\Config\Config;

final class FilterFactory
{
    public function __invoke(ContainerInterface $container): Filter
    {
        return new Filter(
            Config::get($container)->array('block_editor/allowed_block_types')
        );
    }
}

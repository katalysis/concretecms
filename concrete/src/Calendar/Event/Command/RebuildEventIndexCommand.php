<?php

namespace Concrete\Core\Calendar\Event\Command;

use Concrete\Core\Attribute\Category\CategoryInterface;
use Concrete\Core\Attribute\Category\EventCategory;
use Concrete\Core\Attribute\Command\RebuildIndexCommandHandler;
use Concrete\Core\Page\Command\AbstractRebuildIndexCommand;

class RebuildEventIndexCommand extends AbstractRebuildIndexCommand
{
    public function getAttributeKeyCategory(): CategoryInterface
    {
        return app(EventCategory::class);
    }

    public static function getHandler(): string
    {
        return RebuildIndexCommandHandler::class;
    }

    public function getIndexName()
    {
        return tc('IndexName', 'Events');
    }
}

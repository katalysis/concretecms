<?php

namespace Concrete\Core\Calendar\Event\Command;

use Concrete\Core\Attribute\Category\EventCategory;
use Concrete\Core\Calendar\Event\EventService;
use Concrete\Core\Command\Task\Output\OutputAwareInterface;
use Concrete\Core\Command\Task\Output\OutputAwareTrait;

class ReindexEventTaskCommandHandler implements OutputAwareInterface
{
    use OutputAwareTrait;

    /**
     * @var EventCategory
     */
    protected $eventCategory;

    /**
     * @var EventService
     */
    protected $eventService;

    public function __construct(EventCategory $eventCategory, EventService $eventService)
    {
        $this->eventCategory = $eventCategory;
        $this->eventService = $eventService;
    }

    /**
     * @param ReindexEventTaskCommand $command
     */
    public function __invoke($command)
    {
        $this->output->write(t('Reindexing event ID: %s', $command->getEventID()));

        $event = $this->eventService->getByID($command->getEventID(), EventService::EVENT_VERSION_APPROVED);
        
        if ($event) {
            $version = $event->getApprovedVersion();
            if ($version) {
                // Reindex event attributes
                $indexer = $this->eventCategory->getSearchIndexer();
                $values = $this->eventCategory->getAttributeValues($version);
                foreach ($values as $value) {
                    $indexer->indexEntry($this->eventCategory, $value, $version);
                }
            }
        }
    }
}

<?php

namespace Concrete\Core\Calendar\Event\Command;

use Concrete\Core\Attribute\Category\EventCategory;
use Concrete\Core\Command\Task\Output\OutputAwareInterface;
use Concrete\Core\Command\Task\Output\OutputAwareTrait;
use Concrete\Core\Database\Connection\Connection;

class ClearEventIndexCommandHandler implements OutputAwareInterface
{
    use OutputAwareTrait;

    /**
     * @var Connection
     */
    protected $connection;

    /**
     * @var EventCategory
     */
    protected $eventCategory;

    public function __construct(Connection $connection, EventCategory $eventCategory)
    {
        $this->connection = $connection;
        $this->eventCategory = $eventCategory;
    }

    public function __invoke(ClearEventIndexCommand $command)
    {
        $this->output->write(t('Clearing event index...'));
        
        $table = $this->eventCategory->getIndexedSearchTable();
        if ($this->connection->tableExists($table)) {
            $this->connection->executeUpdate('TRUNCATE TABLE ' . $table);
        }
    }
}

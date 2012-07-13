<?php 
namespace CdliUserProfile\Integration;

use Zend\EventManager\EventInterface;

interface IntegrationInterface
{
    /**
     * Accept user input, validate and persist
     * 
     * @return bool (true = success, false = failure)
     */
    public function save(EventInterface $e);

    public function addFormSection(EventInterface $e);
}

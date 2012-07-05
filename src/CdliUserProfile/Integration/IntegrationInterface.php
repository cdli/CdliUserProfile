<?php 
namespace CdliUserProfile\Integration;

use Zend\EventManager\EventInterface;

interface IntegrationInterface
{
    public function save(EventInterface $e);
    public function addFormSection(EventInterface $e);
}

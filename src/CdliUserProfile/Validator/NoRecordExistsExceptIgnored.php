<?php
namespace CdliUserProfile\Validator;

use ZfcUser\Validator\AbstractRecord;

class NoRecordExistsExceptIgnored extends AbstractRecord
{
    protected $ids = array(); 

    public function __construct(array $options)
    {
        $this->ids = (array)$options['ignored_record_ids'];
        parent::__construct($options);
    }

    public function isValid($value)
    {
        $valid = true;
        $this->setValue($value);

        $result = $this->query($value);
        if ($result && !in_array($result->getId(), $this->ids)) {
            $valid = false;
            $this->error(self::ERROR_RECORD_FOUND);
        }

        return $valid;
    }
}

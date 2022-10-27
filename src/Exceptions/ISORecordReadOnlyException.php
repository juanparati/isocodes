<?php

namespace Juanparati\ISOCodes\Exceptions;

class ISORecordReadOnlyException extends \Exception
{
    protected $message = 'Record is read-only';
}
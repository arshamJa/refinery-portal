<?php

namespace App;


enum MeetingStatus: string
{
    case IS_CANCELLED = 'true';
    case NOT_CANCELLED = 'false';

    public function isCancelled(): bool
    {
        return $this === self::IS_CANCELLED;
    }

    public function notCancelled(): bool
    {
        return $this === self::NOT_CANCELLED;
    }
}

<?php

namespace App\Models\Traits;

trait WithDateFormatterAccessorsTrait
{
    /**
     * Get the formatted created at attribute.
     *
     * @return string
     */
    public function getDateCreatedAttribute()
    {
        return formatDate(setTimeZone($this->created_at));
    }

    /**
     * Get the formatted updated at attribute.
     *
     * @return string
     */
    public function getDateUpdatedAttribute()
    {
        return formatDate(setTimeZone($this->updated_at));
    }

    /**
     * Get the formatted deleted at attribute.
     *
     * @return string
     */
    public function getDateDeletedAttribute()
    {
        return formatDate(setTimeZone($this->deleted_at));
    }

    /**
     * Get the human readable format created at attribute.
     *
     * @return string
     */
    public function getCreatedSinceAttribute()
    {
        return timeSince(setTimeZone($this->created_at));
    }

    /**
     * Get the human readable format updated at attribute.
     *
     * @return string
     */
    public function getUpdatedSinceAttribute()
    {
        return timeSince(setTimeZone($this->updated_at));
    }

    /**
     * Get the human readable format deleted at attribute.
     *
     * @return string
     */
    public function getDeletedSinceAttribute()
    {
        return timeSince(setTimeZone($this->deleted_at));
    }
}

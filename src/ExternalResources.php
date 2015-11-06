<?php

namespace App;


/**
 * Describes an instance in shop application which uses some external resources
 * beyond shop application that must be released immediately after their usage.
 */
interface ExternalResources
{
    /**
     * Releases any external resources associated with current instance.
     */
    public function closeAll();
}

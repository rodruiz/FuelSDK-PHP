<?php

namespace FuelSdk;

/**
 * A person subscribed to receive email or SMS communication.
 */
class ET_Subscriber extends ET_CUDWithUpsertSupport
{
    /**
    * Initializes a new instance of the class and sets the obj property of parent.
    */
    public function __construct()
    {
        $this->obj = 'Subscriber';
    }
}

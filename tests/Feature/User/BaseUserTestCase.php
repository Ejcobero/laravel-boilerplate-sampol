<?php

namespace Tests\Feature\User;

use Tests\Feature\BaseTestCase;

class BaseUserTestCase extends BaseTestCase
{
    const userJsonStructure = [
        'first_name',
        'middle_name',
        'last_name',
        'email',
        'username',
        'role',
        'created_at',
        'updated_at',
        'date_created',
        'date_updated',
        'date_deleted',
        'created_since',
        'updated_since',
        'deleted_since'
    ];
}

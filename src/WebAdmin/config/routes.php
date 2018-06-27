<?php

$app->get('/admin', FTC\WebAdmin\Handler\HomePage::class, 'admin.home');
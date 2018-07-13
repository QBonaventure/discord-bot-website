<?php

$app->get('/admin', FTC\WebAdmin\Handler\HomePage::class, 'admin.home');
$app->get('/admin/roles[/{roleId:\d+}]', FTC\WebAdmin\Handler\RolesManagement::class, 'admin.roles');
$app->get('/admin/members[/{memberId:\d+}]', FTC\WebAdmin\Handler\MembersManagement::class, 'admin.members');
$app->get('/admin/channels[/{channelId:\d+}]', FTC\WebAdmin\Handler\ChannelsManagement::class, 'admin.channels');
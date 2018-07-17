<?php

$app->get('/admin', FTC\WebAdmin\Handler\HomePage::class, 'admin.home');
$app->get('/admin/roles[/{roleId:\d+}]', FTC\WebAdmin\Handler\RolesManagement::class, 'admin.roles');
$app->get('/admin/members[/{memberId:\d+}]', FTC\WebAdmin\Handler\MembersManagement::class, 'admin.members');
$app->get('/admin/channels[/{channelId:\d+}]', FTC\WebAdmin\Handler\ChannelsManagement::class, 'admin.channels');

$app->get('/admin/rbac', FTC\WebAdmin\Handler\RbacManagement::class, 'admin.rbac');
$app->get('/admin/rbac/route/{routeName}/roles/{roleId:\d+}/remove', FTC\WebAdmin\Handler\RbacManagement::class, 'admin.rbac.remove');
$app->get('/admin/rbac/route/{routeName}/roles/{roleId:\d+}/add', FTC\WebAdmin\Handler\RbacManagement::class, 'admin.rbac.add');
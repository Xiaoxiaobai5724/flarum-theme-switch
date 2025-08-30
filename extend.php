<?php

use Flarum\Extend;
use Flarum\Api\Serializer\UserSerializer;
use Flarum\User\User;
use Illuminate\Contracts\View\Factory;

return [
    // 1. 给 UserSerializer 加字段 theme，方便前端读取
    (new Extend\ApiSerializer(UserSerializer::class))
        ->attribute('theme', fn ($serializer, User $user) => $user->getPreference('theme', 'default')),

    // 2. 允许前端修改 theme
    (new Extend\User())
        ->registerPreference('theme', 'strval', 'default'),

    // 3. 根据主题注入 CSS
    (new Extend\Frontend('forum'))
        ->css(function (Factory $view, User $user) {
            $theme = $user->getPreference('theme', 'default');
            // 只注入存在的文件
            $path = "theme-switcher/less/{$theme}.less";
            if (file_exists(__DIR__ . "/resources/less/{$theme}.less")) {
                return $view->make('flarum.forum::frontend.content.css', [
                    'css' => [$path]
                ]);
            }
            return null;
        }),

    // 4. 设置页组件挂载
    (new Extend\Frontend('forum'))
        ->js(__DIR__ . '/js/dist/forum.js')
        ->css(__DIR__ . '/resources/less/app.less'), // 公共样式（可选）
];

<?php

use Flarum\Extend;
use Flarum\Api\Serializer\UserSerializer;
use Flarum\User\User;
use Illuminate\Contracts\View\Factory;

return [
    // 把 theme 字段暴露给前端
    (new Extend\ApiSerializer(UserSerializer::class))
        ->attribute('theme', fn ($serializer, User $user) => $user->getPreference('theme', 'hubui')),

    // 允许前端修改 theme 偏好
    (new Extend\User())
        ->registerPreference('theme', 'strval', 'hubui'),

    // 根据当前主题注入对应的 less/css
    (new Extend\Frontend('forum'))
        ->css(function (Factory $view, User $user) {
            $theme = $user->getPreference('theme', 'hubui');

            // 只注入存在的文件；文件名与 $theme 同名
            $file = __DIR__ . "/resources/less/{$theme}.less";
            if (file_exists($file)) {
                // 返回相对路径，Flarum 会自动编译
                return $view->make('flarum.forum::frontend.content.css', [
                    'css' => ["theme-switcher/less/{$theme}.less"]
                ]);
            }
            return null;
        }),

    // 引入论坛 JS
    (new Extend\Frontend('forum'))
        ->js(__DIR__ . '/js/dist/forum.js'),
];

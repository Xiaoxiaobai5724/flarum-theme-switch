<?php

use Flarum\Extend;
use Flarum\Api\Serializer\UserSerializer;
use Flarum\User\User;
use Flarum\Frontend\Document;
use Illuminate\Support\Str;

return [

    /* 1. 暴露字段给前端 */
    (new Extend\ApiSerializer(UserSerializer::class))
        ->attribute('theme', fn ($serializer, User $user) => $user->getPreference('theme', 'hubui')),

    /* 2. 允许用户修改 theme 偏好 */
    (new Extend\User())
        ->registerPreference('theme', 'strval', 'hubui'),

    /* 3. 把 4 个主题注册成可访问的资源（可选编译） */
    (new Extend\Frontend('forum'))
        ->css(__DIR__ . '/resources/less/hubui.less',   'theme-hubui')
        ->css(__DIR__ . '/resources/less/wanxi.less',   'theme-wanxi')
        ->css(__DIR__ . '/resources/less/fluent.less',  'theme-fluent')
        ->css(__DIR__ . '/resources/less/moderno.less', 'theme-moderno'),

    /* 4. 根据当前用户 theme 把对应 <link rel="stylesheet"> 插入 <head> */
    (new Extend\Frontend('forum'))
        ->content(function (Document $document, User $user) {
            $theme = $user->getPreference('theme', 'hubui');

            // 只接受白名单，防止任意文件名
            if (!in_array($theme, ['hubui', 'wanxi', 'fluent', 'moderno'])) {
                $theme = 'hubui';
            }

            $cssUrl = app('flarum.assets.forum')->getAssetUrl("theme-{$theme}.css");
            if ($cssUrl) {
                $document->head[] = '<link rel="stylesheet" href="' . e($cssUrl) . '">';
            }
        }),

    /* 5. 论坛 JS（设置页组件） */
    (new Extend\Frontend('forum'))
        ->js(__DIR__ . '/js/dist/forum.js'),
];

<?php

/**
 * This file has been auto-generated
 * by the Symfony Routing Component.
 */

return [
    false, // $matchHost
    [ // $staticRoutes
        '/admin' => [[['_route' => 'admin', '_controller' => 'App\\Controller\\Admin\\DashboardController::index'], null, null, null, false, false, null]],
        '/playlist/new' => [[['_route' => 'app_playlist', '_controller' => 'App\\Controller\\PlaylistController::index'], null, null, null, false, false, null]],
        '/playlist-song/new' => [[['_route' => 'app_playlist_song_new', '_controller' => 'App\\Controller\\PlaylistSongController::createPlaylistWithSong'], null, null, null, false, false, null]],
        '/profile/new' => [[['_route' => 'app_profile', '_controller' => 'App\\Controller\\ProfileController::index'], null, null, null, false, false, null]],
        '/song/new' => [[['_route' => 'app_song', '_controller' => 'App\\Controller\\SongController::index'], null, null, null, false, false, null]],
        '/style/new' => [[['_route' => 'app_style', '_controller' => 'App\\Controller\\StyleController::index'], null, null, null, false, false, null]],
        '/user/new' => [[['_route' => 'app_user', '_controller' => 'App\\Controller\\UserController::index'], null, null, null, false, false, null]],
        '/user/playlist/new' => [[['_route' => 'app_user_playlist', '_controller' => 'App\\Controller\\UserPlaylistController::index'], null, null, null, false, false, null]],
    ],
    [ // $regexpList
        0 => '{^(?'
                .'|/_error/(\\d+)(?:\\.([^/]++))?(*:35)'
            .')/?$}sDu',
    ],
    [ // $dynamicRoutes
        35 => [
            [['_route' => '_preview_error', '_controller' => 'error_controller::preview', '_format' => 'html'], ['code', '_format'], null, null, false, true, null],
            [null, null, null, null, false, false, 0],
        ],
    ],
    null, // $checkCondition
];

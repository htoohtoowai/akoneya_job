<?php

return [
    'enablePublishStatus' => 1,
    'enableDeleteStatus' => 0,
    'disableDeletedStatus' => 0,
    'disableBannedStatus' => 0,
    'privacy' => [
    	'public' => 1,
    	'friend' => 2,
    	'only_me' => 3
    ],
    'privacyName' => [
        1 => 'public',
        2 => 'friend',
        3 => 'only_me'
    ],
    'mediaType' => [
    	'image' => 1,
    	'video' => 2
    ],
    'mediaTypeName' => [
        1 => 'image',
        2 => 'video'
    ],
    'likeStatus' => true,
    'unLikeStatus' => false,
    'followedStatus' => true,
    'unFollowedStatus' => false,
    'perPage' => 10,
    'reportType' => [
    	'profile' => 1,
    	'newsfeed' => 2
    ],
];

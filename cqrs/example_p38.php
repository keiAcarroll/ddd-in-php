<?php

use Infrastructure\Projection\Elasticsearch\PostWasCreatedProjection;
use Infrastructure\Projection\Elasticsearch\PostWasPublishedProjection;
use Infrastructure\Projection\Elasticsearch\PostWasCategorizedProjection;
use Infrastructure\Projection\Elasticsearch\PostContentWasChangedProjection;
use Infrastructure\Projection\Elasticsearch\PostTitleWasChangedProjection;

$client = new ElasticsearchClientBuilder::create()->build();

$projector = new Projector();

// projector に projectionを登録
$projector->register([
    new PostWasCreatedProjection($client),
    new PostWasPublishedProjection($client),
    new PostWasCategorizedProjection($client),
    new PostContentWasChangedProjection($client),
    new PostTitleWasChangedProjection($client),
]);

$events = [
    new PostWasCreated(/* ... */),
    new PostWasPublished(/* ... */),
    new PostWasCategorized(/* ... */),
    new PostContentWasChanged(/* ... */),
    new PostTitleWasChanged(/* ... */),
];

// イベントを
$projector->project($events);

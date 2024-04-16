<?php

use Rector\Caching\ValueObject\Storage\FileCacheStorage;
use Rector\CodeQuality\Rector\Array_\CallableThisArrayToAnonymousFunctionRector;
use Rector\CodingStyle\Rector\ArrowFunction\StaticArrowFunctionRector;
use Rector\CodingStyle\Rector\Closure\StaticClosureRector;
use Rector\CodingStyle\Rector\FuncCall\ArraySpreadInsteadOfArrayMergeRector;
use Rector\CodingStyle\Rector\PostInc\PostIncDecToPreIncDecRector;
use Rector\Config\RectorConfig;
use Rector\Php70\Rector\StaticCall\StaticCallOnNonStaticToInstanceCallRector;
use Rector\Php74\Rector\Closure\ClosureToArrowFunctionRector;
use Rector\Php81\Rector\Array_\FirstClassCallableRector;
use Rector\Php81\Rector\FuncCall\NullToStrictStringFuncCallArgRector;
use Rector\Set\ValueObject\SetList;
use Rector\ValueObject\PhpVersion;
use RectorLaravel\Rector\FuncCall\RemoveDumpDataDeadCodeRector;
use RectorLaravel\Rector\PropertyFetch\OptionalToNullsafeOperatorRector;
use RectorLaravel\Set\LaravelSetList;

return static function (RectorConfig $rectorConfig): void {
    // register paths
    //----------------------------------------------------------
    $rectorConfig->paths([
        __DIR__.'/src',
    ]);

    $rectorConfig->parallel(
        processTimeout: 360,
        maxNumberOfProcess: 16,
        jobSize: 20
    );

    $rectorConfig->phpVersion(PhpVersion::PHP_80);

    $rectorConfig->importNames();

    // cache settings
    //----------------------------------------------------------

    // Ensure file system caching is used instead of in-memory.
    $rectorConfig->cacheClass(FileCacheStorage::class);

    // Specify a path that works locally as well as on CI job runners.
    $rectorConfig->cacheDirectory(__DIR__.'/.rector_cache');

    // skip paths and/or rules
    //----------------------------------------------------------
    $rectorConfig->skip([
        // Rector transforme $foo++ en ++$foo et derrière Pint transforme ++$foo en $foo++
        // du coup je désactive, laissant pour le moment la priorité à Pint
        // @todo : voir qu'est-ce qui est le mieux
        PostIncDecToPreIncDecRector::class,

        // Transforme des faux-positifs, je préfère désactiver ça (PHP 8.1)
        NullToStrictStringFuncCallArgRector::class,

        // Je trouve la lecture plus difficile avec cette syntaxe, donc je désactive
        ArraySpreadInsteadOfArrayMergeRector::class,

        // Ne pas changer les closure et Arrow Function en Static
        StaticClosureRector::class,
        StaticArrowFunctionRector::class,

        // Pas de ça dans les routes car transforme :
        // [Controller::class, 'method'] en (new Controller)->method(...)
        StaticCallOnNonStaticToInstanceCallRector::class => [__DIR__.'/routes'],
        FirstClassCallableRector::class => [__DIR__.'/routes'],
        // Également, si SetList::CODE_QUALITY, éviter de transformer dans les routes
        // [GlideController::class, 'images'] en fn($path, Request $request) => (new GlideController())->images($path, $request)
        CallableThisArrayToAnonymousFunctionRector::class => [__DIR__.'/routes'],
        ClosureToArrowFunctionRector::class => [__DIR__.'/routes'],
    ]);

    $rectorConfig->rules([
        OptionalToNullsafeOperatorRector::class,
        RemoveDumpDataDeadCodeRector::class,
    ]);

    $rectorConfig->sets([
        LaravelSetList::LARAVEL_FACADE_ALIASES_TO_FULL_NAMES,
        SetList::PHP_80,
        SetList::DEAD_CODE,
        SetList::CODE_QUALITY,
        SetList::CODING_STYLE,
        //SetList::NAMING,
        SetList::TYPE_DECLARATION,
        //SetList::PRIVATIZATION,
        SetList::EARLY_RETURN,
        SetList::INSTANCEOF,
    ]);
};

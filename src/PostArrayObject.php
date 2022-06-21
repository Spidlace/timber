<?php

namespace Timber;

use JsonSerializable;

/**
 * PostArrayObject class for dealing with arbitrary collections of Posts
 * (typically not wrapping a `WP_Query` directly, which is what `Timber\PostQuery` does).
 *
 * @api
 */
class PostArrayObject extends \ArrayObject implements PostCollectionInterface, JsonSerializable
{
    use AccessesPostsLazily;

    /**
     * Takes an arbitrary array of WP_Posts to wrap and (lazily) translate to
     * Timber\Post instances.
     *
     * @api
     * @param \WP_Post[] $posts an array of WP_Post objects
     */
    public function __construct(array $posts)
    {
        parent::__construct($posts, 0, PostsIterator::class);
    }

    /**
     * @inheritdoc
     */
    public function pagination(array $options = [])
    {
        return null;
    }

    /**
     * Override data printed by var_dump() and similar. Realizes the collection before
     * returning. Due to a PHP bug, this only works in PHP >= 7.4.
     *
     * @see https://bugs.php.net/bug.php?id=69264
     * @internal
     */
    public function __debugInfo(): array
    {
        return [
            'info' => sprintf(
                '
********************************************************************************

    This output is generated by %s().

    The properties you see here are not actual properties, but only debug
    output. If you want to access the actual instances of Timber\Posts, loop
        over the collection or get all posts through $query->to_array().

        More info: https://timber.github.io/docs/v2/guides/posts/#debugging-post-collections

********************************************************************************',
                __METHOD__
            ),
            'posts' => $this->getArrayCopy(),
            'factory' => $this->factory,
            'iterator' => $this->getIterator(),
        ];
    }

    /**
     * Returns realized (eagerly instantiated) Timber\Post data to serialize to JSON.
     *
     * @internal
     */
    #[\ReturnTypeWillChange]
    public function jsonSerialize()
    {
        return $this->getArrayCopy();
    }
}

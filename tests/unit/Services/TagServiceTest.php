<?php

namespace Isern\Test\Services;

use Isern\Services\TagService;
use Isern\Storage\Tag\TagEntity;
use Illuminate\Support\Collection;
use Mockery as m;

class TagServiceTest extends \PHPUnit_Framework_TestCase
{

    public function tearDown()
    {
        m::close();
    }

    public function testAllMethod()
    {
        $repo = m::mock('\Isern\Storage\Tag\TagRepositoryInterface')
            ->shouldReceive('getAll')->once()->andReturn(new Collection)
            ->getMock();

        $service = new TagService($repo);
        $tags = $service->all();

        $this->assertInstanceOf('\Illuminate\Support\Collection', $tags);
    }

    public function testFindBySlugMethod()
    {
        $entity = (new TagEntity)->setRawAttributes(['id' => 1, 'name' => 'Isern Palaus', 'slug' => 'isern-palaus']);

        $repo = m::mock('\Isern\Storage\Tag\TagRepositoryInterface')
            ->shouldReceive('getBySlug')->once()->andReturn($entity)
            ->getMock();

        $service = new TagService($repo);
        $tag = $service->findBySlug('isern-palaus');

        $this->assertInstanceOf('\Isern\Storage\Tag\TagEntity', $tag);
        $this->assertEquals('Isern Palaus', $tag->name);
        $this->assertEquals('isern-palaus', $tag->slug);
    }

    public function testNonExistenOnFindBySlugMethod()
    {
        $repo = m::mock('\Isern\Storage\Tag\TagRepositoryInterface')
            ->shouldReceive('getBySlug')->once()->andReturn(null)
            ->getMock();

        $service = new TagService($repo);
        $tag = $service->findBySlug('isern');
        $errors = $service->getErrors();

        $this->assertFalse($tag);
        $this->assertEquals(['type' => 'not_found', 'bag' => null], $errors);
    }

}

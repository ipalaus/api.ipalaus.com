<?php

namespace Isern\Test\Services;

use Isern\Services\CategoryService;
use Isern\Storage\Category\CategoryEntity;
use Illuminate\Support\Collection;
use Mockery as m;

class CategoryServiceTest extends \PHPUnit_Framework_TestCase
{

    public function tearDown()
    {
        m::close();
    }

    public function testAllMethod()
    {
        $repo = m::mock('\Isern\Storage\Category\CategoryRepositoryInterface')
            ->shouldReceive('getAll')->once()->andReturn(new Collection)
            ->getMock();

        $service = new CategoryService($repo);
        $categories = $service->all();

        $this->assertInstanceOf('\Illuminate\Support\Collection', $categories);
    }

    public function testFindBySlugMethod()
    {
        $entity = (new CategoryEntity)->setRawAttributes(['id' => 1, 'name' => 'Web Development', 'slug' => 'web-development']);

        $repo = m::mock('\Isern\Storage\Category\CategoryRepositoryInterface')
            ->shouldReceive('getBySlug')->once()->andReturn($entity)
            ->getMock();

        $service = new CategoryService($repo);
        $category = $service->findBySlug('web-development');

        $this->assertInstanceOf('\Isern\Storage\Category\CategoryEntity', $category);
        $this->assertEquals('Web Development', $category->name);
        $this->assertEquals('web-development', $category->slug);
    }

    public function testNonExistenOnFindBySlugMethod()
    {
        $repo = m::mock('\Isern\Storage\Category\CategoryRepositoryInterface')
            ->shouldReceive('getBySlug')->once()->andReturn(null)
            ->getMock();

        $service = new CategoryService($repo);
        $category = $service->findBySlug('isern');
        $errors = $service->getErrors();

        $this->assertFalse($category);
        $this->assertEquals(['type' => 'not_found', 'bag' => null], $errors);
    }

}

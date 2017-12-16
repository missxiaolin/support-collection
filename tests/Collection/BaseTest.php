<?php
// +----------------------------------------------------------------------
// | BaseTest.php [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016-2017 xiaolin All rights reserved.
// +----------------------------------------------------------------------
// | Author: xiaolin <462441355@qq.com> <https://github.com/missxiaolin>
// +----------------------------------------------------------------------
namespace Tests\Collection;

use PHPUnit\Framework\TestCase;
use Xiao\Support\Collection;

class BaseTest extends TestCase
{
    protected $arr = [
        'name' => 'xiaolin',
        'sex' => 1,
        'tel' => ['mobile' => '18678017520', 'phone' => '8653623']
    ];

    protected $list = [
        ['id' => 1, 'name' => 'xiaolin', 'sex' => 'boy'],
        ['id' => 2, 'name' => 'Agnes', 'sex' => 'girl'],
        ['id' => 3, 'name' => 'xl', 'sex' => 'boy'],
    ];

    public function testInit()
    {
        $arr = ['name' => 'xiaolin', 'sex' => 1];
        $collection = new Collection($arr);
        $this->assertEquals('xiaolin', $collection->name);
    }

    public function testGet()
    {
        $collection = new Collection($this->arr);
        $this->assertEquals('xiaolin', $collection->name);
        $this->assertEquals('18678017520', $collection->tel['mobile']);
        $this->assertEquals('18678017520', $collection->get('tel.mobile'));
    }

    public function testAll()
    {
        $collection = new Collection($this->arr);
        $this->assertEquals($this->arr, $collection->all());
    }

    public function testOnly()
    {
        $collection = new Collection($this->arr);
        $this->assertArrayNotHasKey('tel', $collection->only(['name', 'sex']));
    }

    public function testExcept()
    {
        $collection = new Collection($this->arr);
        $this->assertArrayNotHasKey('name', $collection->except(['name']));
    }

    public function testMerge()
    {
        $collection = new Collection($this->arr);
        $new = $collection->merge(['name2' => 'xl']);
        $this->assertArrayHasKey('name', $new);
        $this->assertArrayHasKey('name2', $new);
    }

    public function testHas()
    {
        $collection = new Collection($this->arr);
        $this->assertTrue($collection->has('name'));
    }

    public function testFirst()
    {
        $collection = new Collection($this->list);
        $this->assertEquals($this->list[0], $collection->first());
    }

    public function testLast()
    {
        $collection = new Collection($this->list);
        $this->assertEquals($this->list[2], $collection->last());
    }

    public function testAdd()
    {
        $collection = new Collection($this->arr);
        $arr = $this->arr;

        $collection->add('name2', 'Agnes');
        $arr['name2'] = 'Agnes';
        $this->assertEquals($arr, $collection->all());
    }

    public function testSet()
    {
        $collection = new Collection($this->arr);
        $arr = $this->arr;

        $collection->set('name', 'Agnes');
        $arr['name'] = 'Agnes';
        $this->assertEquals($arr, $collection->all());
    }

    public function testForget()
    {
        $collection = new Collection($this->arr);
        $arr = $this->arr;

        $collection->forget('name');
        unset($arr['name']);
        $this->assertEquals($arr, $collection->all());
    }

    public function testFilter()
    {
        $collection = new Collection($this->arr);

        $res = $collection->filter(function ($item) {
            return $item == 'xiaolin';
        });

        $this->assertEquals(['name' => 'xiaolin'], $res->all());
    }

    public function testWhere()
    {
        $collection = new Collection($this->list);

        $res = $collection->where('id', 1)->first();
        $this->assertEquals(['id' => 1, 'name' => 'xiaolin', 'sex' => 'boy'], $res);
    }

    public function testGroupBy()
    {
        $collection = new Collection($this->list);

        $res = $collection->groupBy('sex');

        $this->assertEquals([
            ['id' => 1, 'name' => 'xiaolin', 'sex' => 'boy'],
            ['id' => 3, 'name' => 'xl', 'sex' => 'boy'],
        ], $res->get('boy')->all());

        $this->assertEquals([
            ['id' => 2, 'name' => 'Agnes', 'sex' => 'girl'],
        ], $res->get('girl')->all());
    }
}
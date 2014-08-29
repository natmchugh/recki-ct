<?php
/**
 * Copyright 2014 Google Inc. All rights reserved.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * @copyright 2014 Google Inc. All rights reserved
 * @license http://www.apache.org/licenses/LICENSE-2.0.txt Apache-2.0
 * @category Tests
 * @package Analyzer
 * @subpackage OptimizerRule
 */

namespace ReckiCT\Analyzer\OptimizerRule;
use Gliph\Graph\DirectedAdjacencyList;
use PHPUnit_Framework_TestCase as TestCase;

use ReckiCT\Type;
use ReckiCT\Graph\Vertex;
use ReckiCT\Graph\Variable;
use ReckiCT\Graph\Vertex\BitwiseNot as JitBitwiseNot;

/**
 * @coversDefaultClass \ReckiCT\Analyzer\OptimizerRule\BitwiseNot
 */
class BitwiseNotTest extends TestCase
{
    /**
     * @covers ::process
     */
    public function testNoChange()
    {
        $rule = new Assign();
        $this->assertFalse($rule->process($this->getMock(Vertex::class), $this->getMock(DirectedAdjacencyList::class)));
    }

    /**
     * @covers ::process
     */
    public function testChange()
    {
        $rule = new BitwiseNot();
        $vertex = new JitBitwiseNot($a = new Variable(new Type(Type::TYPE_LONG)), $r = new Variable());
        $this->assertTrue($rule->process($vertex, $this->getMock(DirectedAdjacencyList::class)));

        $this->assertEquals(Type::TYPE_LONG, $r->getType()->getType());
    }

    /**
     * @covers ::process
     */
    public function testChangeString()
    {
        $rule = new BitwiseNot();
        $vertex = new JitBitwiseNot($a = new Variable(new Type(Type::TYPE_STRING)), $r = new Variable());
        $this->assertTrue($rule->process($vertex, $this->getMock(DirectedAdjacencyList::class)));

        $this->assertEquals(Type::TYPE_STRING, $r->getType()->getType());
    }

    /**
     * @covers ::process
     */
    public function testWithoutChange()
    {
        $rule = new BitwiseNot();
        $vertex = new JitBitwiseNot($a = new Variable(new Type(Type::TYPE_LONG)), $r = new Variable(new Type(Type::TYPE_DOUBLE)));
        $this->assertFalse($rule->process($vertex, $this->getMock(DirectedAdjacencyList::class)));

        $this->assertEquals(Type::TYPE_DOUBLE, $r->getType()->getType());
    }
}
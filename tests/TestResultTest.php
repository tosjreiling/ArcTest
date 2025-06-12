<?php

namespace Tests;

use ArcTest\Core\TestCase;
use ArcTest\Core\TestResult;
use ArcTest\Core\TestResultCollection;
use ArcTest\Enum\RecordKey;
use ArcTest\Enum\TestOutcome;
use ArcTest\Exceptions\AssertionFailedException;

class TestResultTest extends TestCase {
    public function testCanStoreAndRetrieveResults(): void {
        $collection = new TestResultCollection();
        $result = new TestResult("testClass", "testMethod", TestOutcome::PASSED, "All good", null, 0.012);

        $collection->add($result);

        $this->assertCount(1, $collection->all());
        $this->assertSame("testClass", $collection->all()[0]->className);
    }

    public function testToArrayExceptionsAssertions(): void {
        $exception = new AssertionFailedException(expected: 1, actual: 2, message: "Fail");
        $result = new TestResult("failTest", "testFailure", TestOutcome::FAILED, "Mismatch", $exception, 0.01);

        $array = $result->toArray();

        $this->assertEquals("failTest", $array[RecordKey::CLASSNAME->value]);
        $this->assertEquals("testFailure", $array[RecordKey::METHOD->value]);
        $this->assertEquals(TestOutcome::FAILED->value, $array[RecordKey::OUTCOME->value]);
        $this->assertEquals(1, $array[RecordKey::EXPECTED->value]);
        $this->assertEquals(2, $array[RecordKey::ACTUAL->value]);
        $this->assertEquals("Fail", $array[RecordKey::MESSAGE->value]);
    }
}
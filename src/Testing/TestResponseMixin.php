<?php

namespace Nuwave\Lighthouse\Testing;

use Closure;
use PHPUnit\Framework\Assert;

/**
 * @mixin \Illuminate\Testing\TestResponse
 */
class TestResponseMixin
{
    public function assertGraphQLValidationError(): Closure
    {
        return function (string $key, ?string $message) {
            $this->assertJson([
                'errors' => [
                    [
                        'extensions' => [
                            'validation' => [
                                $key => [
                                    $message,
                                ],
                            ],
                        ],
                    ],
                ],
            ]);

            return $this;
        };
    }

    public function assertGraphQLValidationKeys(): Closure
    {
        return function (array $keys) {
            $validation = TestResponseUtils::extractValidationErrors($this);

            Assert::assertSame(
                $keys,
                array_keys($validation['extensions']['validation']),
                'Expected the query to return validation errors for specific fields.'
            );

            return $this;
        };
    }

    public function assertGraphQLValidationPasses(): Closure
    {
        return function () {
            $validation = TestResponseUtils::extractValidationErrors($this);

            Assert::assertNull(
                $validation,
                'Expected the query to have no validation errors.'
            );

            return $this;
        };
    }

    public function assertGraphQLErrorCategory(): Closure
    {
        return function (string $category) {
            $this->assertJson([
                'errors' => [
                    [
                        'extensions' => [
                            'category' => $category,
                        ],
                    ],
                ],
            ]);

            return $this;
        };
    }

    public function jsonGet(): Closure
    {
        return function (string $key = null) {
            return data_get($this->decodeResponseJson(), $key);
        };
    }
}

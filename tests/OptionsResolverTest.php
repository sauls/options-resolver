<?php
/**
 * This file is part of the sauls/options-resolver package.
 *
 * @author    Saulius Vaičeliūnas <vaiceliunas@inbox.lt>
 * @link      http://saulius.vaiceliunas.lt
 * @copyright 2018
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sauls\Component\OptionsResolver;

use PHPUnit\Framework\TestCase;


use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;
use Symfony\Component\OptionsResolver\Exception\MissingOptionsException;
use Symfony\Component\OptionsResolver\Exception\UndefinedOptionsException;
use Symfony\Component\OptionsResolver\Options;

class OptionsResolverTest extends TestCase
{
    protected  $resolver;

    protected function setUp()
    {
        $this->resolver = new OptionsResolver();
    }

    /**
     * @test
     */
    public function should_resolve_associative_array(): void
    {
        $this->resolver
            ->setDefaults([
                'test' => 1,
                'a' => 2,
                'x' => [
                    'y' => 100,
                ],
            ]);

        $this->assertSame([
            'test' => 1,
            'a' => 2,
            'x' => [
                'y' => 25,
            ]
        ], $this->resolver->resolve([
            'x' => [
                'y' => 25,
            ]
        ]));
    }

    /**
     * @test
     */
    public function should_throw_exception_on_missing_nested_value(): void
    {
        $this->expectException(MissingOptionsException::class);
        $this->expectExceptionMessage('The required option "nested.value" is missing.');
        $this->resolver->setRequired(['nested.name', 'nested.value']);

        $array = $this->resolver->resolve([
            'nested' => [
                'name' => 'test',
            ]
        ]);
    }

    /**
     * @test
     */
    public function should_throw_exception_on_wrong_nested_type(): void
    {
        $this->expectException(InvalidOptionsException::class);
        $this->expectExceptionMessage('The option "nested.value" with value "wrong" is expected to be of type "int", but is of type "string"');
        $this->resolver
            ->setDefined(['nested.name', 'nested.value'])
            ->addAllowedTypes('nested.value', ['int']);
        $this->resolver->resolve([
            'nested' => [
                'name' => 'test',
                'value' => 'wrong',
            ]
        ]);
    }

    /**
     * @test
     */
    public function should_throw_exception_on_wrong_type_given(): void
    {
        $this->expectException(InvalidOptionsException::class);
        $this->expectExceptionMessage('The option "nested.type" with value "four" is invalid. Accepted values are: "one", "two", "three"');
        $this->resolver
            ->setDefined(['nested.name', 'nested.value', 'nested.type'])
            ->addAllowedTypes('nested.value', ['int'])
            ->addAllowedValues('nested.type', ['one', 'two', 'three'])
            ->setDefaults([
                'nested' => [
                    'name' => 'test options',
                    'value' => 25
                ]
            ]);

        $this->resolver->resolve([
            'nested' => [
                'type' => 'four',
            ]
        ]);
    }

    /**
     * @test
     */
    public function should_normalize_nested_options_value(): void
    {
        $this->resolver
            ->setDefined(['nested.name', 'nested.value', 'nested.type'])
            ->addAllowedTypes('nested.value', ['int'])
            ->setDefaults([
                'nested' => [
                    'name' => 'test options',
                    'value' => 25
                ]
            ])
            ->setNormalizer('nested.type', function(Options $options, $value) {
                $valueMap = ['zero', 'one', 'two', 'three'];

                return \array_key_exists($value, $valueMap) ? $valueMap[$value] : 'unknown';
            })
        ;

        $resolvedOptions = $this->resolver->resolve([
            'nested' => [
                'type' => 2,
            ]
        ]);

        $this->assertSame([
            'nested' => [
                'name' => 'test options',
                'value' => 25,
                'type' => 'two',
            ]
        ], $resolvedOptions);
    }

    /**
     * @test
     */
    public function should_set_defaults_with_dot_notation_keys(): void
    {
        $this->resolver
            ->setDefaults([
                'nested.name' => 'test options',
                'nested.value' => 25,
            ]);

        $this->assertSame([
            'nested' => [
                'name' => 'test options',
                'value' => 25
            ],
        ], $this->resolver->resolve([]));

        $this->assertSame(
            [
                'nested' => [
                    'value' => 25,
                    'name' => 'tada!',
                ]
        ], $this->resolver->resolve(
            [
                'nested' => [
                    'name' => 'tada!'
                ]
            ]
        ));

        $this->assertSame(
            [
                'nested' => [
                    'name' => 'works!',
                    'value' => 100,
                ]
            ], $this->resolver->resolve(
            [
                'nested.name' => 'works!',
                'nested.value' => 100,
            ]
        ));
    }

    /**
     * @test
     */
    public function should_throw_exception_or_unsupported_key(): void
    {
        $this->expectException(UndefinedOptionsException::class);
        $this->expectExceptionMessage('The option "nested.deep.nmae" does not exist. Defined options are: "nested.deep.name", "nested.name", "nested.type", "nested.value", "text", "type"');

        $this->resolver
            ->setDefined(['type', 'text', 'nested.name', 'nested.value', 'nested.type', 'nested.deep.name'])
            ->addAllowedTypes('nested.value', ['int'])
            ->addAllowedValues('nested.type', ['one', 'two', 'three'])
            ->setDefaults([
                'nested' => [
                    'name' => 'test options',
                    'value' => 25
                ]
            ]);

        $this->resolver->resolve([
            'nested' => [
                'deep' => [
                    'nmae' => 'Hello!',
                ],
            ]
        ]);
    }
}

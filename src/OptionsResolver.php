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

use function Sauls\Component\Helper\array_key_assoc_with_value;
use Sauls\Component\Collection\ArrayCollection;
use Symfony\Component\OptionsResolver\OptionsResolver as SymfonyOptionsResolver;

class OptionsResolver extends SymfonyOptionsResolver
{
    /**
     * @throws \Symfony\Component\OptionsResolver\Exception\AccessException
     */
    public function setDefaults(array $defaults)
    {
        return parent::setDefaults(array_key_assoc_with_value($defaults));
    }

    /**
     * @throws \Symfony\Component\OptionsResolver\Exception\UndefinedOptionsException
     * @throws \Symfony\Component\OptionsResolver\Exception\OptionDefinitionException
     * @throws \Symfony\Component\OptionsResolver\Exception\NoSuchOptionException
     * @throws \Symfony\Component\OptionsResolver\Exception\MissingOptionsException
     * @throws \Symfony\Component\OptionsResolver\Exception\InvalidOptionsException
     * @throws \Symfony\Component\OptionsResolver\Exception\AccessException
     */
    public function resolve(array $options = []): array
    {
        return (new ArrayCollection(
            parent::resolve(
                array_key_assoc_with_value($options)
            )
        ))->all();
    }

}

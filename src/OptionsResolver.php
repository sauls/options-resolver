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

use function Sauls\Component\Helper\array_keys_with_value;
use Symfony\Component\OptionsResolver\OptionsResolver as SymfonyOptionsResolver;
use Sauls\Component\Collection\ArrayCollection;

class OptionsResolver extends SymfonyOptionsResolver
{
    /**
     * @throws \Symfony\Component\OptionsResolver\Exception\AccessException
     * @throws \Exception
     */
    public function setDefaults(array $defaults)
    {
        return parent::setDefaults(array_keys_with_value($defaults));
    }

    /**
     * @throws \Exception
     */
    public function resolve(array $options = []): array
    {
        return (new ArrayCollection(
            parent::resolve(
                array_keys_with_value($options)
            )
        ))->all();
    }

}

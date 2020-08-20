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

use Exception;
use Sauls\Component\Collection\ArrayCollection;
use Symfony\Component\OptionsResolver\Exception\AccessException;
use Symfony\Component\OptionsResolver\OptionsResolver as SymfonyOptionsResolver;

use function Sauls\Component\Helper\array_key_childs_exist;
use function Sauls\Component\Helper\array_keys_with_value;
use function Sauls\Component\Helper\array_remove_key;

class OptionsResolver extends SymfonyOptionsResolver
{
    /**
     * @throws AccessException
     * @throws Exception
     */
    public function setDefaults(array $defaults)
    {
        return parent::setDefaults(array_keys_with_value($defaults));
    }

    /**
     * @throws Exception
     */
    public function resolve(array $options = []): array
    {
        return (new ArrayCollection(
            $this->processDotNotatedValues(
                parent::resolve(array_keys_with_value($options))
            )
        ))->all();
    }

    private function processDotNotatedValues(array $resolvedValues): array
    {
        foreach ($resolvedValues as $key => $value) {
            if (array_key_childs_exist($key, $resolvedValues)) {
                array_remove_key($resolvedValues, $key);
            }
        }

        return $resolvedValues;
    }
}

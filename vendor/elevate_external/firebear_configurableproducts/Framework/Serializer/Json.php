<?php
declare(strict_types=1);
/**
 * Json
 *
 * @copyright Copyright © 2020 Firebear Studio. All rights reserved.
 * @author    fbeardev@gmail.com
 */

namespace Firebear\ConfigurableProducts\Framework\Serializer;

use Magento\Framework\Serialize\SerializerInterface;

/**
 * @inheritdoc
 */
class Json implements SerializerInterface
{
    /**
     * @var SerializerInterface
     */
    protected $serializer;

    /**
     * Json constructor.
     * @param SerializerInterface $serializer
     */
    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * @param $valueToEncode
     * @return bool|string
     */
    public function jsonEncode($valueToEncode)
    {
        return $this->serialize($valueToEncode);
    }

    /**
     * @param array|bool|float|int|string|null $data
     * @return bool|string
     */
    public function serialize($data)
    {
        return $this->serializer->serialize($data);
    }

    /**
     * @param $encodedValue
     * @return array|bool|float|int|string|null
     */
    public function jsonDecode($encodedValue)
    {
        return $this->unserialize($encodedValue);
    }

    /**
     * @param string $string
     * @return array|bool|float|int|string|null
     */
    public function unserialize($string)
    {
        return $this->serializer->unserialize($string);
    }
}

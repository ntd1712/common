<?php

namespace Chaos\Foundation\Traits;

/**
 * Trait JsonAwareTrait
 * @author ntd1712
 */
trait JsonAwareTrait
{
    /**
     * {@inheritdoc} Required by interface IJson.
     *
     * @return  string A <i>JSON</i> encoded string on success or <b>FALSE</b> on failure, see {@link json_encode()}.
     * @throws  \RuntimeException
     */
    public function toJson()
    {
        $args = func_get_args();

        if (!isset($args[0]) || !is_object($args[0])) {
            array_unshift($args, $this);
        }

        if (/*function_exists(CHAOS_JSON_ENCODER) && */CHAOS_JSON_USE_INTERNAL) {
            $encoded = @call_user_func_array(CHAOS_JSON_ENCODER, $args);

            if (JSON_ERROR_NONE !== json_last_error()) {
                throw new \RuntimeException('JSON encoding failed: ' . json_last_error_msg());
            }
        } else if (class_exists(CHAOS_JSON_ENCODER)) {
            $encoded = forward_static_call_array([CHAOS_JSON_ENCODER, 'encode'], $args);
        } else {
            $encoded = false;
        }

        return $encoded;
    }

    /**
     * {@inheritdoc} Required by interface IJson.
     *
     * @param   string $json The <i>JSON</i> string being decoded.
     * @return  mixed The value encoded in <i>JSON</i> in appropriate PHP type, for details see {@link json_decode()}.
     * @throws  \RuntimeException
     */
    public function fromJson($json)
    {
        $args = func_get_args();

        if (/*function_exists(CHAOS_JSON_DECODER) && */CHAOS_JSON_USE_INTERNAL) {
            $decoded = @call_user_func_array(CHAOS_JSON_DECODER, $args);

            if (JSON_ERROR_NONE !== json_last_error()) {
                throw new \RuntimeException('JSON decoding failed: ' . json_last_error_msg());
            }
        } else if (class_exists(CHAOS_JSON_DECODER)) {
            $decoded = forward_static_call_array([CHAOS_JSON_DECODER, 'decode'], func_get_args());
        } else {
            $decoded = $json;
        }

        if (is_object($decoded)) {
            foreach ($this as $property => $value) {
                if (property_exists($decoded, $property)) {
                    $this->$property = $decoded->$property;
                }
            }

            return $this;
        } else if (is_array($decoded)) {
            foreach ($this as $property => $value) {
                if (array_key_exists($property, $decoded)) {
                    $this->$property = $decoded[$property];
                }
            }

            return $this;
        }

        return $decoded;
    }
}

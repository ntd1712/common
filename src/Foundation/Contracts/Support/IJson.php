<?php

namespace Chaos\Foundation\Contracts\Support;

/**
 * Interface IJson
 * @author ntd1712
 */
interface IJson
{
    /**
     * Converts the object instance to its JSON representation.
     *
     * @return  string A <i>JSON</i> encoded string on success or <b>FALSE</b> on failure, see {@link json_encode()}.
     * @throws  \RuntimeException
     */
    public function toJson();

    /**
     * De-serializing a JSON string into the object instance.
     *
     * @param   string $json The <i>JSON</i> string being decoded.
     * @return  mixed The value encoded in <i>JSON</i> in appropriate PHP type, for details see {@link json_decode()}.
     * @throws  \RuntimeException
     */
    public function fromJson($json);
}

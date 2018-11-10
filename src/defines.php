<?php

// common: service, repository & entity
define('CHAOS_ANNOTATION_IGNORE', 'Exclude()');
define('CHAOS_ANNOTATION_IGNORE_DATA', '@ignore');
define('CHAOS_JSON_USE_INTERNAL', true);
define('CHAOS_MAX_QUERY', 10);
define('CHAOS_MAX_RECURSION_DEPTH', 14);

define('CHAOS_JSON_DECODER', 'json_decode'); // Zend\Json\Decoder, Symfony\Component\Serializer\Encoder\JsonDecode
define('CHAOS_JSON_ENCODER', 'json_encode'); // Zend\Json\Encoder, Symfony\Component\Serializer\Encoder\JsonEncoder
define('CHAOS_BASE_OBJECT_COLLECTION_INTERFACE', 'Chaos\Foundation\Contracts\IBaseObjectCollection');
define('CHAOS_BASE_OBJECT_ITEM_INTERFACE', 'Chaos\Foundation\Contracts\IBaseObjectItem');
define('CHAOS_READ_EVENT_ARGS', 'Chaos\Foundation\Events\ReadEventArgs');
define('DOCTRINE_ARRAY_COLLECTION', 'Doctrine\Common\Collections\ArrayCollection');
define('DOCTRINE_PERSISTENT_COLLECTION', 'Doctrine\ORM\PersistentCollection');
define('DOCTRINE_ENTITY_MANAGER', 'Doctrine\ORM\EntityManager');
define('DOCTRINE_PROXY', 'Doctrine\ORM\Proxy\Proxy');
define('ZEND_STATIC_FILTER', 'Zend\Filter\StaticFilter');
define('ZEND_STATIC_VALIDATOR', 'Zend\Validator\StaticValidator');

// common: regular expressions
define('CHAOS_MATCH_DATE', '#\d{1,4}([-\/.])\d{1,2}\1\d{1,4}#x');
define('CHAOS_MATCH_ASC_DESC', '#([^\s]+)\s*(asc|desc)?\s*(.*)#i');
define('CHAOS_MATCH_COLUMN',
    '#column\(.*(?:type\s*=\s*["\']([^"\'\s]+)["\']|columndefinition\s*=\s*["\']([^\s\(]+)(?:\([^\)]+\))?[\w\s]*["\']).*\)#i');
define('CHAOS_MATCH_ONE_MANY', '#((?:one|many)to(?:one|many))\(.*targetentity\s*=\s*["\']\\\?([^"\'\s]+)["\'].*\)#i');
define('CHAOS_MATCH_RULE_ITEM', '#\[(\w+)\s*(\([^\)]+\))?\]#');
define('CHAOS_MATCH_TYPE',
    '#type\("\\\?([\w\\\]+)(?:<\'?\\\?([^\',]+)\'?(?:\s*,\s*\'?\\\?([^\',]+)\'?)?(?:\s*,\s*\'?\\\?([^\',]+)\'?)?>)?"\)#i');
define('CHAOS_MATCH_VAR', '#@var\s+\\\?([\w\\\]+)(?:[(\[<]\\\?([\w\\\]*)[>\])])?#i');
define('CHAOS_REPLACE_COMMA_SEPARATOR', '#\s*,\s*#');
define('CHAOS_REPLACE_SPACE_SEPARATOR', '#\s+#');

// TODO: remove
define('CHAOS_ANNOTATION_IGNORE_RULES', '@ignore rules');
define('CHAOS_MATCH_RULES', '#\[\s*(.+)\s*\]#');

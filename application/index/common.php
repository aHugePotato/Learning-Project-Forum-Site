<?php

/**
 * function hashGen
 * 用md5生成哈希
 *
 * @param mixed $arg
 *
 * @return mixed
 */
function hashGen()
{
    return md5(microtime());
}

<?php

function id_baru(Type $var = null)
{
  return md5(microtime());
}
<?php

/**
 * @file
 * Some older mysql client libs are missing this constant.
 */

if (!defined('MYSQLI_BINARY_FLAG')) {
  define('MYSQLI_BINARY_FLAG', 128);
}


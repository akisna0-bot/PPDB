<?php

return [
    'max_connections' => 300,
    'innodb_buffer_pool_size' => '256M',
    'query_cache_size' => '64M',
    'tmp_table_size' => '64M',
    'max_heap_table_size' => '64M',
    'key_buffer_size' => '32M',
    'innodb_log_file_size' => '64M',
    'innodb_log_buffer_size' => '16M',
    'innodb_flush_log_at_trx_commit' => 2,
    'innodb_file_per_table' => 1,
];
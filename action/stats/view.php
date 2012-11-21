<?php
require_once 'libcc/stats.php';

$stats = new Stats(array(Stats::USER, Stats::PROBLEMSET, Stats::COURSE, Stats::EXERCISE));
$env->setData('title', 'Statistics');
$t['stats'] = $stats->fetch()->get();
$env->setLayout('stats/summary');
?>
